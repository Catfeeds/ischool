<?php

namespace api\controllers;

use api\models\WpIschoolGonggao;
use api\models\WpIschoolInbox;
use api\models\WpIschoolNews;
use api\models\WpIschoolOutbox;
use api\models\WpIschoolPastudent;
use api\models\WpIschoolStudent;
use api\models\ZfCardInfo;
use api\models\ZfDealDetail;
use Yii;
use api\models\WpIschoolUser;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\widgets\LinkPager;

class TeafuwuController extends BaseActiveController
{

    /**
    *家校沟通接口
     */
    //家校沟通发布信息获取联系人姓名
    public function actionGerlxr(){
        $stu_name = ($this->post['stu_name'] !=="null")?$this->post['stu_name']:"noparam";
        $cid = $this->post['cid'];
        $data =[];
        if ($stu_name =="noparam"){
            $sql = "select a.name as stuname,b.uid,case WHEN a.enddateqq>unix_timestamp(now()) THEN 0 ELSE 1 end as is_pay from wp_ischool_student a left JOIN wp_ischool_pastudent b ON a.id = b.stu_id and b.isqqtel=0 and b.uid is not null WHERE a.cid = :cid  GROUP BY b.uid";
            $data = Yii::$app->db->createCommand($sql,[':cid'=>$cid])->queryAll();
        }else{
            $sql = "select a.name as stuname,b.uid,case WHEN a.enddateqq>unix_timestamp(now()) THEN 0 ELSE 1 end as is_pay from wp_ischool_student a left JOIN wp_ischool_pastudent b ON a.id = b.stu_id and b.isqqtel=0 and b.uid is not null WHERE a.cid = :cid AND a.name like :name GROUP BY b.uid";
            $data = Yii::$app->db->createCommand($sql,[':cid'=>$cid,':name'=>'%'.$stu_name.'%'])->queryAll();
        }
        return  $this->formatAsjson($data);
    }

    //家校沟通发布信息保存信息
    public function actionSendmessage(){
//        $base64 = $this->post['picurl'];
//        $img = base64_decode($base64);
        /*$imgs = $_FILES["file"];
        if(!file_exists('uploads/jxgt/'.date('y/m/d',time()))){
            mkdir('uploads/jxgt/'.date('y/m/d',time()),0777,true);
        }
        $url = 'uploads/jxgt/'.date('y/m/d',time());
        $picname = $this->create_random_string(13);
        $imgurl = $url.'/'.$picname.'.jpg';
        //将图片保存到本地 保存成功返回的是字节数
        $a = file_put_contents($imgurl, $img);
        $imgstring = "<img src = '".$imgurl."'/>";*/
        $content = $this->post['content'];
        $title = $this->post['title'];
        $out_uid = $this->post['out_uid'];
        $in_uid = $this->post['in_uid'];
        $upimg = !isset($_FILES['equipImg'])?null:$_FILES['equipImg'];   //上传图片
        $content = "<p>".$content."</p>";
        Yii::trace($upimg);
        if (!empty($upimg)){
            $imgurl = $this->Uploadfiles($upimg);
            if ($imgurl == "1051"){
                return $this->errorHandler("1051");
            }
            if ($imgurl == "1052"){
                return $this->errorHandler("1052");
            }
            $content.=$imgurl;
        }
        $touid=explode(',',$in_uid);
        $ctime = time();
        $type = 0;
        $sql = "INSERT INTO `wp_ischool_outbox` ( `content` , `outopenid`,`out_uid`, `ctime` , `title` , `type` ) VALUES( :content, :outopenid,:out_uid, :ctime,:title, 0)";

        $sql2 = "INSERT INTO `wp_ischool_inbox` ( `content` ,`outopenid`,`inopenid`, `out_uid` , `in_uid` , `ctime` , `title` , `type` ) VALUES( :content,:outopenid, :inopenid,:out_uid,:in_uid, :ctime,:title,0)";
        $transaction = \Yii::$app->db->beginTransaction();       //事务开始
        try{
            $outbox = \Yii::$app->db->createCommand($sql,[':content'=>$content,':outopenid'=>$this->getOpenid($out_uid),':out_uid'=>$out_uid,':ctime'=>$ctime,':title'=>$title])->execute();
            foreach($touid as $k=> $in_uid){
                if (empty($in_uid)){
                    unset($touid[$k]);
                }
            }
            Yii::trace($touid);
            foreach($touid as $in_uid){
                $inbox = \Yii::$app->db->createCommand($sql2,[':content'=>$content,':outopenid'=>$this->getOpenid($out_uid),':inopenid'=>$this->getOpenid($in_uid),':out_uid'=>$out_uid,':in_uid'=>$in_uid,':ctime'=>$ctime,':title'=>$title])->execute();
                require_once "/data/lib/push.php";
                \Jpush::push($in_uid,"您有一条家校沟通信息，请及时查看！","id");
            }
            $transaction->commit();
            return $this->formatAsjson("success");
        }catch(Exception $e){     // 如果有一条查询失败，则会抛出异常
            $transaction->rollBack();
            return $this->errorHandler("1024");
        }
    }

    //家校沟通收件箱列表接口
    public function actionInbox(){
        $uid = $this->post['uid'];
        $type = $this->post['type'];
        $data = $this->getinboxlist($uid,$type);
        return  $this->formatAsjson($data);
    }

    //家校沟通收件箱内容接口
    public function actionInboxcont(){
        $id = $this->post['id'];        //文章ID
        $data = WpIschoolInbox::find()->select('id,out_uid,in_uid,content,ctime,title,fujian')->where(['id'=>$id])->asArray()->all();

        $str1 = 'src="/'; $str2 = 'src="'.URL_PATH.'/';
        $str3 = 'one#/'; $str4 = URL_PATH.'/';
        Yii::trace($data);
        foreach ($data as $k=>$v){
            $content=$v["content"];
            $sta=substr($content,0,3);
            if($sta=="<p>")
            {
                $star=strrpos($content,"</p>");
                $end=$star+4;
                $v["content"]=substr($content,0,$end);
            }
            $data[$k]['content'] = str_replace($str1,$str2,$v['content']);
            $data[$k]['fujian'] = str_replace($str3,$str4,$v['fujian']);
            $data[$k]['fujian'] = "";
            $data[$k]['fajianren'] = $this->getusername($data[$k]['out_uid']);
            $data1= substr($v['fujian'],0,4);
            $data2 =substr($v["fujian"], 4);
            $fujian = explode('#',$data2);
            if (!empty($data1) && $data1 == "one#") {
                Yii::trace($fujian);
                foreach ($fujian as $key => $val) {
                    $data[$k]['fujian'].= "#".URL_PATH.$val;
                }
                $data[$k]['fujians'] = explode("#",$data[$k]['fujian']);
                unset($data[$k]['fujians'][0]);
            }
            $data[$k]['ctime'] = isset($data[0]['ctime'])?date("Y-m-d H:i:s",$data[0]['ctime']):null;;
            unset($data[$k]['fujian']);
        }
        return  $this->formatAsjson($data);
    }

    //家校沟通发件箱列表接口
    public function actionOutbox(){
        $uid = $this->post['uid'];
        $type = $this->post['type'];
        $data = $this->getoutboxlist($uid,$type);
        return  $this->formatAsjson($data);
    }

    //家校沟通发件箱内容接口
    public function actionOutboxcont(){
        $id = $this->post['id'];        //文章ID
        $data = WpIschoolOutbox::find()->select('id,out_uid,content,ctime,title,fujian')->where(['id'=>$id])->asArray()->all();
        $str1 = 'src="/'; $str2 = 'src="'.URL_PATH.'/';
        $str3 = 'one#/'; $str4 = URL_PATH.'/';
        Yii::trace($data);
        foreach ($data as $k=>$v){
            $content=$v["content"];
            $sta=substr($content,0,3);
            if($sta=="<p>")
            {
                $star=strrpos($content,"</p>");
                $end=$star+4;
                $v["content"]=substr($content,0,$end);
            }
            $data[$k]['content'] = str_replace($str1,$str2,$v['content']);
            $data[$k]['fujian'] = str_replace($str3,$str4,$v['fujian']);
            $data[$k]['fujian'] = "";
            $data[$k]['fajianren'] = $this->getusername($data[$k]['out_uid']);
            $data1= substr($v['fujian'],0,4);
            $data2 =substr($v["fujian"], 4);
            $fujian = explode('#',$data2);
            if (!empty($data1) && $data1 == "one#") {
                Yii::trace($fujian);
                foreach ($fujian as $key => $val) {
                    $data[$k]['fujian'].= "#".URL_PATH.$val;
                }
                $data[$k]['fujians'] = explode("#",$data[$k]['fujian']);
                unset($data[$k]['fujians'][0]);
            }
            $data[$k]['ctime'] = isset($data[0]['ctime'])?date("Y-m-d H:i:s",$data[0]['ctime']):null;
            unset($data[$k]['fujian']);
        }

        return  $this->formatAsjson($data);
    }

    public function actionImgupload(){
        $file = $_FILES['equipImg'];  //得到传输的数据,以数组的形式
        Yii::trace($file);
        //print_r($file);exit;
     }

    //家校沟通发布信息获取家长联系人姓名
    public function actionStulxr(){
        $stu_id = $this->post['stu_id'];
        $uid = $this->uid;
        $datas =array();
        $data = WpIschoolStudent::findOne($stu_id);
        // $sql = "SELECT t.name AS stu_name,t.class,t.stuno2,p.`name` AS pname,p.tel as jid FROM wp_ischool_pastudent p LEFT JOIN wp_ischool_student t ON p.stu_id =t.id WHERE stu_id=:stu_id and p.tel is NOT NULL and p.isqqtel=0 GROUP BY tel";
        $res = WpIschoolPastudent::find(['stu_id'=>$stu_id])->select('name as pname,tel as jid')->where("stu_id=:stu_id and tel is NOT NULL and isqqtel=0 and uid != :uid")->addParams([':stu_id'=>$stu_id,':uid'=>$uid])->groupby("tel")->asArray()->all();
        $datas['stuinfo']['stu_name'] = $data['name'];
        $datas['stuinfo']['class'] = $data['class'];
        $datas['stuinfo']['stuno2'] = $data['stuno2'];
        $datas['painfo'] = [];
        foreach ($res as $key => $value) {
            $datas['painfo'][$key]['pname'] = $value['pname'];
            $datas['painfo'][$key]['jid'] = $value['jid']."@im.henanzhengfan.com";
            $datas['painfo'][$key]['user_img'] = WpIschoolUser::findOne(["tel"=>$value['jid']])['user_img'];
        }
        return  $this->formatAsjson($datas);
    }

        //教师版获取学生列表接口
    public function actionStulist(){
        $cid = $this->post['cid'];
        $name = $this->post['stu_name'];
        if ($name == "null"){
            $model = $this->getAllstuinf($cid);
        }else{
            $model = $this->getAllstuinfos($cid,$name);
        }
        $data = [];
        foreach ($model as $k=>$v){
            $data[$k]['stu_name'] = $v['name'];
            $data[$k]['stu_id'] = $v['id'];
        }
        return $this->formatAsjson($data);
    }

}
