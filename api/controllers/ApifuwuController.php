<?php

namespace api\controllers;

use api\models\WpIschoolGonggao;
use api\models\WpIschoolInbox;
use api\models\WpIschoolNews;
use api\models\WpIschoolOutbox;
use api\models\WpIschoolPastudent;
use api\models\WpIschoolSchool;
use api\models\ZfCardInfo;
use api\models\ZfCardInfowater;
use api\models\ZfDealDetail;
use api\models\ZfDealDetailwater;
use Yii;
use api\models\WpIschoolUser;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\widgets\LinkPager;
use api\models\WpIschoolTeaclass;

class ApifuwuController extends BaseActiveController
{

    /**
    *服务部分接口
     */
//学校地点配置获取
    public function config($sid, $pos_no) {
/*        $config = [
            '56758' => [
                '241' => "超市消费",
                '242'=>"超市消费",
                '251' => "医务室消费",
            ],
            '56650' => [
                '71' => "超市消费",
            ],
        ];*/
        $config = \yii::$app->params['config.xiaofei'];
        if (isset($config[$sid]) && isset($config[$sid][$pos_no])) {
            return $config[$sid][$pos_no];
        } else {
            return "餐厅刷卡";
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
        $str5 = 'href="/'; $str6 = 'href="'.URL_PATH.'/';
        $str3 = 'one#/'; $str4 = URL_PATH.'/';
        Yii::trace($data);
        foreach ($data as $k=>$v){
            $data[$k]['content'] = str_replace($str5,$str6,str_replace($str1,$str2,$v['content']));
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

    //家校沟通发布信息获取联系人姓名
    public function actionGerlxr(){
        $cid = $this->post['cid'];
        $data =[];
        $data = !empty($cid)?$this->Allteacher($cid):"";     //班主任信息
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
        $touid=explode(',',$in_uid);
        $ctime = time();
        $type = 0;
        $sql = "INSERT INTO `wp_ischool_outbox` ( `content` , `outopenid`,`out_uid`, `ctime` , `title` , `type` ) VALUES( :content, :outopenid,:out_uid, :ctime,:title, 0)";

        $sql2 = "INSERT INTO `wp_ischool_inbox` ( `content` ,`outopenid`,`inopenid`, `out_uid` , `in_uid` , `ctime` , `title` , `type` ) VALUES( :content,:outopenid, :inopenid,:out_uid,:in_uid, :ctime,:title,0)";
        $transaction = \Yii::$app->db->beginTransaction();       //事务开始
        try{
            $outbox = \Yii::$app->db->createCommand($sql,[':content'=>$content,':outopenid'=>$this->getOpenid($out_uid),':out_uid'=>$out_uid,':ctime'=>$ctime,':title'=>$title])->execute();
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

    //餐卡服务消费记录模块
    public function actionRecords(){
        $stuid = $this->post['stuid'];
        $stuinfo = $this->stuinfo($stuid);
        if (empty($stuinfo)){
            return $this->errorHandler("1021");
        }
        $stuno2 = $stuinfo[0]['stuno2'];
        $sid = $stuinfo[0]['sid'];
        $payinfo = WpIschoolSchool::find()->select('ckpass')->where(['id'=>$sid])->asArray()->one();
        if ($payinfo['ckpass'] =="n"){
            return $this->errorHandler("1048");
        }
        if(time()>$stuinfo[0]['enddateck']){
            return $this->errorHandler("1022");
        }
        $zfend_school=\yii::$app->params['zfend.school'];
        if(in_array($sid,$zfend_school)){
            if(preg_match('/[a-zA-Z]/',$stuno2)){
                $sid = substr($stuno2, 1, 5);
                $stuno2=substr($stuno2,6);
            }else{
                $sid = '56651';
                $stuno2=substr($stuno2,2);
            }
        }
        // if(preg_match('/[a-zA-Z]/',$stuno2)){
        //     $sid=substr($stuno2,1,5);
        //     $stuno2=substr($stuno2,6);
        // }else{
        //     $stuno2=substr($stuno2,2);
        // }
        if(!isset($this->post['shijian']) || $this->post['shijian'] =="null"){
            $begintmmt = $this->getBeginTimestamp("month");       //获得本月的开始时间戳
            $endtmmt = time();                                      //获得当前时间戳
        }else{
            $shijian =$this->post['shijian'];
            $begintmmt = strtotime(date($shijian));       //获得某一天开始的时间戳
            $endtmmt = $begintmmt+86400;              //获得某一天结束的时间戳
        }
        $cardinfo = ZfCardInfo::find()->where(['user_no'=>$stuno2,"school_id"=>$sid])->select('card_no')->asArray()->all();
        if ($cardinfo){
            $card_no = $cardinfo[0]['card_no']; //card表中学生学号user_no
            $models = ZfDealDetail::find()->where(['card_no'=>$card_no,"school_id"=>$sid])->andFilterWhere(['between','created',$begintmmt,$endtmmt])->orderBy("created desc")->asArray()->all();
            $data = [];
            foreach ($models as $k =>$v){
                $data[$k]['time'] = date("Y-m-d H:i:s",$v['created']);
                $data[$k]['didian'] = self::config($v['school_id'],$v['pos_sn']);
//                $data[$k]['didian'] = "水卡消费";
                $data[$k]['jine'] = $v['amount'];
                $data[$k]['yue'] = $v['balance'];
            }
            return $this->formatAsjson($data);
        }else{
            return $this->errorHandler("1038");
        }

    }

//水卡服务消费记录模块
public function actionRecordsk(){
        $stuid = $this->post['stuid'];
        $stuinfo = $this->stuinfo($stuid);
        if (empty($stuinfo)){
            return $this->errorHandler("1021");
        }
        $stuno2 = $stuinfo[0]['stuno2'];
        $sid = $stuinfo[0]['sid'];
        $payinfo = WpIschoolSchool::find()->select('ckpass')->where(['id'=>$sid])->asArray()->one();
        if ($payinfo['ckpass'] =="n"){
            return $this->errorHandler("1049");
        }
        if(time()>$stuinfo[0]['enddateck']){
            return $this->errorHandler("1022");
        }

        /*        if(preg_match('/[a-zA-Z]/',$stuno2)){
                    $stuno2=substr($stuno2,6);
                }else{
                    $stuno2=substr($stuno2,2);
                }*/
        if(!isset($this->post['shijian']) || $this->post['shijian'] =="null"){
            $begintmmt = $this->getBeginTimestamp("month");       //获得本月的开始时间戳
            $endtmmt = time();                                      //获得当前时间戳
        }else{
            $shijian =$this->post['shijian'];
            $begintmmt = strtotime(date($shijian));       //获得某一天开始的时间戳
            $endtmmt = $begintmmt+86400;              //获得某一天结束的时间戳
        }

        $cardinfo = ZfCardInfowater::find()->where(['user_no'=>$stuno2,"school_id"=>$sid])->select('card_no')->asArray()->all();
        if ($cardinfo){
            $card_no = $cardinfo[0]['card_no']; //card表中学生学号user_no
            $models = ZfDealDetailwater::find()->where(['card_no'=>$card_no,"school_id"=>$sid])->andFilterWhere(['between','created',$begintmmt,$endtmmt])->orderBy("created desc")->asArray()->all();
            $data = [];
            foreach ($models as $k =>$v){
                $data[$k]['time'] = date("Y-m-d H:i:s",$v['created']);
//                $data[$k]['didian'] = self::config($v['school_id'],$v['pos_sn']);
                $data[$k]['didian'] = "水卡消费";
                $data[$k]['jine'] = $v['amount'];
                $data[$k]['yue'] = $v['balance'];
            }
            return $this->formatAsjson($data);
        }else{
            return $this->errorHandler("1038");
        }

    }
    
    //水卡服务消费记录模块
//     public function actionRecordsk(){
//         $stuid = $this->post['stuid'];
//         $stuinfo = $this->stuinfo($stuid);
//         if (empty($stuinfo)){
//             return $this->errorHandler("1021");
//         }
//         $stuno2 = $stuinfo[0]['stuno2'];
//         $sid = $stuinfo[0]['sid'];
//         $payinfo = WpIschoolSchool::find()->select('ckpass')->where(['id'=>$sid])->asArray()->one();
//         if ($payinfo['ckpass'] =="n"){
//             return $this->errorHandler("1049");
//         }
//         if(time()>$stuinfo[0]['enddateck']){
//             return $this->errorHandler("1022");
//         }
//         $begintmmt = $this->getBeginTimestamp("month");       //获得本月的时间戳
//         $cardinfo = ZfCardInfowater::find()->where(['user_no'=>$stuno2,"school_id"=>$sid])->select('card_no')->asArray()->all();
//         if ($cardinfo){
//             $card_no = $cardinfo[0]['card_no']; //card表中学生学号user_no
//             $models = ZfDealDetailwater::find()->where(['card_no'=>$card_no,"school_id"=>$sid])->andFilterWhere(['>','created',$begintmmt])->orderBy("created desc")->asArray()->all();
//             $data = [];
//             foreach ($models as $k =>$v){
//                 $data[$k]['time'] = date("Y-m-d H:i:s",$v['created']);
// //                $data[$k]['didian'] = self::config($v['school_id'],$v['pos_sn']);
//                 $data[$k]['didian'] = "水卡消费";
//                 $data[$k]['jine'] = $v['amount'];
//                 $data[$k]['yue'] = $v['balance'];
//             }
//             return $this->formatAsjson($data);
//         }else{
//             return $this->errorHandler("1038");
//         }

//     }


    //平安通知模块
    public function actionSecurity(){
        $stuid = $this->post['stuid'];
        $shijian = ($this->post['shijian'] !=="null")?$this->post['shijian']:"month";
        $stuinfo = $this->stuinfo($stuid);
        if (empty($stuinfo)){
            return $this->errorHandler("1021");
        }
        if(time()>$stuinfo[0]['enddatepa']){
            return $this->errorHandler("1023");
        }

        if ($shijian =="month"){
            $begintmmt = $this->getBeginTimestamp($shijian);       //获得本月的时间戳
            $models = $this->getdklist($stuid,$begintmmt);
        }else{
            $begintm = strtotime(date($shijian));       //获得某一天开始的时间戳
            $endtime = $begintm+86400;              //获得某一天结束的时间戳
            $models = $this->getDaylist($stuid,$begintm,$endtime);
        }

        $data = [];
        foreach ($models as $k =>$v){
            $data[$k]['time'] = date("Y-m-d H:i:s",$v['ctime']);
            $data[$k]['infos'] = $v['info'];
        }
        return $this->formatAsjson($data);
    }

    //亲情电话模块列表显示
    public function actionChild(){
        $stu_id = $this->post['stu_id'];
        $data = WpIschoolPastudent::find()->select("Relation,tel,id")->where(['stu_id'=>$stu_id,'isqqtel'=>1,'ispass'=>'y'])->all();
        return $this->formatAsjson($data);
    }

    //添加亲情号码
    public function actionAddqqtel(){
        $uid = $this->post['uid'];
        $stu_id = $this->post['stu_id'];
        $Relation = $this->post['Relation'];
        $tel = $this->post['tel'];
        $cid = $this->post['cid'];
        $class = $this->post['class'];
        $stuname = $this->post['student'];
        if(!preg_match("/^1[34578]\d{9}$/", $tel)){
            return $this->errorHandler("1004");
        }
        $res = WpIschoolPastudent::find()->where(['stu_id'=>$stu_id,'ispass'=>'y','isqqtel'=>1])->asArray()->all();
        if(count($res) >=5){
            return $this->errorHandler("1025");
        }
        $model = new WpIschoolPastudent();
        $model->name= $this->getusername($uid);
        $model->ctime= time();
        $model->stu_id= $stu_id;
        $model->cid= $cid;
        $model->class= $class;
        $model->tel= $tel;
        $model->stu_name= $stuname;
        $model->ispass= "y";
        $sinfo = $this->getSchoolidbystuid($stu_id);
        $model->sid = empty($sinfo[0]['sid'])?1:$sinfo[0]['sid'];
        $model->school= empty($sinfo[0]['school'])?1:$sinfo[0]['school'];
        $model->Relation= $Relation;
        $model->isqqtel= 1;
        $model->uid = $uid;

        if ($model->save(false)){
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1026");
        }
    }

    //修改亲情号码
    public function actionUpqqh()
    {
        $id = $this->post['id'];
        $Relation = $this->post['Relation'];
        $tel = $this->post['tel'];
        if(!preg_match("/^1[34578]\d{9}$/", $tel)){
            return $this->errorHandler("1004");
        }
        $model = WpIschoolPastudent::findOne($id);
        $model->tel = $tel;
        $model->Relation = $Relation;
        $res = $model->save(false);
        if($res){
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1027");
        }
    }

    //删除亲情号码
    public  function actionDelqqh(){
        $id = $this->post['id'];
        $model = WpIschoolPastudent::findOne($id);
        $res = $model->delete();
        if($res){
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1028");
        }
    }


    public function actionSendmessages(){
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
        $stuid = $this->post['stu_id'];
        $touid=explode(',',$in_uid);
        $stuinfo = $this->stuinfo($stuid);
        if (empty($stuinfo)){
            return $this->errorHandler("1021");
        }
        if(time()>$stuinfo[0]['enddatejx']){
            return $this->errorHandler("1050");
        }

        $ctime = time();
        $type = 0;
        $sql = "INSERT INTO `wp_ischool_outbox` ( `content` , `outopenid`,`out_uid`, `ctime` , `title` , `type` ) VALUES( :content, :outopenid,:out_uid, :ctime,:title, 0)";

        $sql2 = "INSERT INTO `wp_ischool_inbox` ( `content` ,`outopenid`,`inopenid`, `out_uid` , `in_uid` , `ctime` , `title` , `type` ) VALUES( :content,:outopenid, :inopenid,:out_uid,:in_uid, :ctime,:title,0)";
        $transaction = \Yii::$app->db->beginTransaction();       //事务开始
        try{
            $outbox = \Yii::$app->db->createCommand($sql,[':content'=>$content,':outopenid'=>$this->getOpenid($out_uid),':out_uid'=>$out_uid,':ctime'=>$ctime,':title'=>$title])->execute();
            Yii::trace($touid);
            foreach($touid as $in_uid){
                $inbox = \Yii::$app->db->createCommand($sql2,[':content'=>$content,':outopenid'=>$this->getOpenid($out_uid),':inopenid'=>$this->getOpenid($in_uid),':out_uid'=>$out_uid,':in_uid'=>$in_uid,':ctime'=>$ctime,':title'=>$title])->execute();
                require_once "/data/lib/push.php";
                \Jpush::push($in_uid,"您有一条信息，请及时查看！","id");
            }
            $transaction->commit();
            return $this->formatAsjson("success");
        }catch(Exception $e){     // 如果有一条查询失败，则会抛出异常
            $transaction->rollBack();
            return $this->errorHandler("1024");
        }
    }


        //与学生对应的老师信息
    public function actionStuteas(){
        // $stuid = $this->post['stu_id'];
        $cid  = $this->post['cid'];
        $uid = $this->uid;
        Yii::trace($uid);
        $tea_uid = WpIschoolTeaclass::find()->select("tname,tel")->where("cid =:cid and uid != :uid and tel is not null")->addParams([':cid'=>$cid,':uid'=>$uid])->groupBy(['tname'])->asArray()->all();
        $data = [];
        foreach ($tea_uid as $key => $value) {
            $data[$key]['tname'] = $value['tname'];
            $data[$key]['pwd'] = $value['tel'];
            $data[$key]['jid'] = $value['tel'].'@im.henanzhengfan.com';
            $data[$key]['user_img'] = WpIschoolUser::findOne(["tel"=>$value['tel']])['user_img']?:"";
        }
        Yii::trace($data);
        return $this->formatAsjson($data);
    }

        //点击家校沟通根据学生ID判断家校沟通当前学生的有效期是否过期
    public function actionEndjx(){
        $stuid = $this->post['stu_id'];
        $endjx = $this->stuinfo($stuid)[0]['enddatejx'];
        $data['ispass_jx'] = "y";
        if (time()>$endjx) {
           $data['ispass_jx'] = "n";
        }
        return $this->formatAsjson($data);
    }

    //学生四大项业务开通截止日期
   public function actionEnddate(){
        $stuid = $this->post['stu_id'];
        $stajx = $this->stuinfo($stuid)[0]['upendtimejx'];
        $staqq = $this->stuinfo($stuid)[0]['upendtimeqq'];
        $stack = $this->stuinfo($stuid)[0]['upendtimeck'];
        $stapa = $this->stuinfo($stuid)[0]['upendtimepa'];
        $endjx = $this->stuinfo($stuid)[0]['enddatejx'];
        $endqq = $this->stuinfo($stuid)[0]['enddateqq'];
        $endck = $this->stuinfo($stuid)[0]['enddateck'];
        $endpa = $this->stuinfo($stuid)[0]['enddatepa'];
        $data['jxgt_stime'] = date("Y-m-d H:i:s",$stajx);       //学生家校沟通有效期是否过期
        $data['jxgt_etime'] = $endjx>time()?date("Y-m-d H:i:s",$endjx):"没开通";
        $data['qqdh_stime'] = date("Y-m-d H:i:s",$staqq);
        $data['qqdh_etime'] = $endqq>time()?date("Y-m-d H:i:s",$endqq):"没开通";
        $data['xyxf_stime'] = date("Y-m-d H:i:s",$stack);
        $data['xyxf_etime'] = $endck>time()?date("Y-m-d H:i:s",$endck):"没开通";
        $data['patz_stime'] = date("Y-m-d H:i:s",$stapa);
        $data['patz_etime'] = $endpa>time()?date("Y-m-d H:i:s",$endpa):"没开通";
        return $this->formatAsjson($data);
    }

}
