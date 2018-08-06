<?php

namespace api\controllers;

use api\models\WpIschoolSchool;
use api\models\WpIschoolGonggao;
use api\models\WpIschoolNews;
use api\models\WpIschoolPastudent;
use api\models\WpIschoolTeaclass;
use api\models\WpIschoolStudent;
use Yii;
use api\models\WpIschoolUser;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\widgets\LinkPager;
use common\models\Communals;
use api\models\ZfCardInfo;
use api\models\ZfCardInfowater;
use api\models\ZfDealDetail;
use api\models\ZfDealDetailwater;

class CeshiController extends BaseActiveController
{
    public $enableCsrfValidation = false;
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
        if(preg_match('/[a-zA-Z]/',$stuno2)){
            $sid=substr($stuno2,1,5);
            $stuno2=substr($stuno2,6);
        }else{
            $stuno2=substr($stuno2,2);
        }
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

//学生打卡信息统计
    public function actionSafecardhz(){
        $cid = $this->post['cid'];
        $shijian = $this->post['shijian'];
        if($shijian == "null"){
            $begintm = strtotime(date('Y-m-d',time()));
            $endtime = time();
        }else{
          $begintm = strtotime(date($shijian));       //获得某一天开始的时间戳
          $endtime = $begintm+86400;              //获得某一天结束的时间戳  
        }

        $sql = "select count(DISTINCT stuid) dkrs from wp_ischool_safecard a left JOIN wp_ischool_student  b ON a.stuid=b.id WHERE b.cid=:cid and a.ctime BETWEEN :begintm AND :endtime and a.info LIKE '%进校%'";
        $sql2 = "SELECT a.name,'未打卡' as dktime FROM wp_ischool_student a WHERE a.cid=:cid and a.id not IN(SELECT DISTINCT stuid from wp_ischool_safecard c LEFT JOIN wp_ischool_student b ON c.stuid=b.id where b.cid=:cid and a.ctime BETWEEN :begintm AND :endtime and c.info LIKE '%进校%')";
        $sql3 = "SELECT a.`name`,FROM_UNIXTIME(b.ctime) dktime FROM wp_ischool_safecard b LEFT JOIN wp_ischool_student a on a.id=b.stuid WHERE a.cid = :cid and b.ctime BETWEEN :begintm  AND :endtime and b.info LIKE '%进校%' GROUP BY b.stuid";
        $data['tongji'] = \Yii::$app->db->createCommand($sql,[":cid"=>$cid,":begintm"=>$begintm,":endtime"=>$endtime])->queryOne();
        $data['tongji']['total'] = WpIschoolStudent::find()->select("id")->where(["cid"=>$cid])->count();
        $data['tongji']['wdkrs'] = strval($data['tongji']['total']-$data['tongji']['dkrs']);
        $data['tongji']['daoxiaolv'] = round($data['tongji']['dkrs']/$data['tongji']['total']*100,2)."％";
        $data1 = \Yii::$app->db->createCommand($sql2,[":cid"=>$cid,":cid"=>$cid,":begintm"=>$begintm,":endtime"=>$endtime])->queryAll();
        $data2 = \Yii::$app->db->createCommand($sql3,[":cid"=>$cid,":begintm"=>$begintm,":endtime"=>$endtime])->queryAll();
        $data['allstu'] = array_merge($data1,$data2);
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

public function actionLogins(){
//            $tel = yii::$app->request->post("tel");
//            $pwd = yii::$app->request->post("pwd");
            $tel = $this->post['tel'];
            $pwd = md5($this->post['pwd']);
            $push_id = $this->post['push_id'];
            $models = new WpIschoolUser();
            $res = $models->findOne(['tel'=>$tel,'pwd'=>$pwd]);
            if ($res){
                 $cookies = Yii::$app->response->cookies;
//                 $cookies->add(new \yii\web\Cookie([
//                      'name' => "tel",
//                      'value' => $tel,
//                      'expire'=>time()+3600 * 24 *30
//                 ]));
                $user_id = $res->id;
                Yii::trace($user_id);
                $cookies->add(new \yii\web\Cookie([
                    'name' => "user_id",
                    'value' => $user_id,
                    'expire'=>time()+3600 * 24 *30
                ]));
                 Yii::trace($res->id);
                $data['uid'] = strval($res['id']);
                $data['last_stuid'] = strval($res['last_stuid']);
                $data['last_sid'] = strval($res['last_sid']);
                // $data['last_cid'] = strval($res['last_cid']);
                $data['last_cid'] = !empty($res['last_stuid'])?strval($this->stuinfo($res['last_stuid'])[0]['cid']):null;

                /*if (empty($data['last_stuid']) || empty($data['last_sid']) || empty($data['last_cid']) || $data['last_sid'] ==1 || $data['last_cid'] ==1){
                    return $this->errorHandler("1055");
                }*/
/*                if (!empty($data['last_stuid']) {
                    if(empty($data['last_sid']) || empty($data['last_cid']) || $data['last_sid'] ==1 || $data['last_cid'] ==1){
                    return $this->errorHandler("1055");
                    }
                } */
                $data['name'] = strval($res['name']);
                $data['tel'] = $tel;
                $data['schoolname'] = $res['last_sid']?$this->getSchoolname($res['last_sid']):"暂无学校";       //学校名称
                $data['last_stuname'] = $this->getStunamebystuid($res['last_stuid'])['name'];
                $data['last_classname'] = $this->getClassnamebystuid($res['last_cid'])['name'];
                $data['jid'] = $tel."@im.henanzhengfan.com";
                $data['user_img'] = isset($res['user_img'])?\Yii::$app->params['pcurl_path'].$res['user_img']:null;
//                $models = new WpIschoolUser();
                $models = WpIschoolUser::findOne(['tel'=>$tel]);
                $models->tel = $tel;
                $models->login_time =time();
                if ($push_id !=="null"){
                    $models->push_id = $push_id;
                }
                $models->update(false);
                \Yii::trace($data);
                $res = $this->formatAsjson($data);
            }else{
                $res = $this->errorHandler("1003");
            }
        return $res;
    }

    //教师版登录接口
    public function actionLogin(){
        $tel = $this->post['tel'];
        $pwd = md5($this->post['pwd']);
        $push_id = $this->post['push_id'];
        $model = new WpIschoolUser();
        $res = $model->findOne(['tel'=>$tel,'pwd'=>$pwd]);
        if (empty($res)){
            return $this->errorHandler("1003");
        }
        $data =[];
        $models = WpIschoolUser::findOne(['tel'=>$tel]);
        if(empty($res['last_cid'])){
            $teaclass_cid = WpIschoolTeaclass::find()->select('cid,sid,role,ispass')->where(['and','uid ='.$res['id'],'cid !=0'])->orderby('ctime desc')->asArray()->one();
        }else{
            $teaclass_cid = WpIschoolTeaclass::find()->select('cid,sid,role,ispass')->where(['uid' =>$res['id'],'cid' =>$res['last_cid']])->orderby('ctime desc')->asArray()->one();
        }
        $error = $this->ispass_tea($teaclass_cid);
        if($error != "0"){
            return $this->errorHandler($error);
        }
        if ($res){
            $cookies = Yii::$app->response->cookies;
            $user_id = $res->id;
            Yii::trace($user_id);
            $cookies->add(new \yii\web\Cookie([
                'name' => "user_id",
                'value' => $user_id,
                'expire'=>time()+3600 * 24 *30
            ]));
            Yii::trace($res->id);
            if($teaclass_cid['role'] =="班主任"){
                $data['is_bzr'] ='y';
            }else{
                $data['is_bzr'] ='n';
            }
            $data['uid'] = strval($res['id']);
            $data['last_sid'] = strval($teaclass_cid['sid']);
            $data['last_cid'] = strval($teaclass_cid['cid']);
            $data['name'] = strval($res['name']);
            $data['tel'] = $tel;
            $data['schoolname'] = $teaclass_cid['sid']?$this->getSchoolname($teaclass_cid['sid']):"暂无学校";       //学校名称
            $data['last_classname'] = $this->getClassnamebystuid($teaclass_cid['cid'])['name'];
            $models->last_cid = $teaclass_cid['cid'];
            $models->last_sid = $teaclass_cid['sid'];
            $models->tel = $tel;
            $models->login_time =time();
            if ($push_id !=="null"){
                $models->push_id = $push_id;
            }
            $models->update(false);
            $res = $this->formatAsjson($data);
        }
        return $res;
    }


    public function ispass_tea($teaclass_cid){
        \Yii::trace($teaclass_cid);
        if(!$teaclass_cid){
            $error = "1054";
        }else if($teaclass_cid['ispass'] !='y'){
            $error ="1053";
        }else{
            $error ="0";
        }
        return $error;
    }
   
// select distinct name,id from wp_ischool_subject ORDER BY id asc
// 教师身份种类
    public function actionSubject(){
        $sql = "select id,name from wp_ischool_subject ORDER BY id asc";
        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        return $this->formatAsjson($res);
    }



        //个人中信界面投诉建议提交接口
    public function actionSuggest(){

        $content = $this->post['content'];
        $sid = $this->post['sid'];
        $title = $this->post['title'];
        $school = $this->post['schoolname'];
        $uid = $this->post['uid'];
        $openid = $this->openid;
        if(isset($_FILES['img'])){
            $res = $this->Uploadimgs($_FILES);
            Yii::trace($res);
            $content.= $res;
        }
        if (empty($title)){
            return $this->errorHandler("1047");
        }
        $sql = "insert into wp_ischool_suggest (`content`,`outopenid`,`sid`,`ctime`,`title`,`school`,`uid`) values (:content,:outopenid,:sid,:ctime,:title,:school,:uid)";
        $res = \Yii::$app->db->createCommand($sql,[":content"=>$content,":outopenid"=>$openid,":sid"=>$sid,":ctime"=>time(),":title"=>$title,":school"=>$school,":uid"=>$uid,])->execute();
        if ($res){
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1032");
        }
    }

    //根据学生ID判断家校沟通当前学生的有效期是否过期
    public function actionEndjx(){
        $stuid = $this->post['stu_id'];
        $endjx = $this->stuinfo($stuid)[0]['enddatejx'];
        $data['ispass_jx'] = "y";
        if (time()>$endjx) {
           $data['ispass_jx'] = "n";
        }
        return $this->formatAsjson($data);
    }
    //根据学生ID获取对应的班主任信息
    public function Banzhuren(){
        $stuid = 127718;
        $cid = $this->stuinfo($stuid)[0]['cid'];
        $banzhuren_id = $this->getHeadmaster($cid);
        Yii::trace($banzhuren_id);
        return $banzhuren_id;
        // \Jpush::push($zfuid,"支付成功！","id");
    }

    //学生补卡点击接口
    public function actionBuka(){
        $sid = $this->post['sid'];
        $payinfo = WpIschoolSchool::find()->select('bkpass')->where(['id'=>$sid])->asArray()->one();
        return $this->formatAsjson($payinfo);
    }
            //功能支付页面接口
    public function actionRecharge(){
        $sid = $this->post['sid'];
        $payinfo = WpIschoolSchool::find()->select('papass,jxpass,qqpass,ckpass,is_youhui')->where(['id'=>$sid])->asArray()->all();
        $payinfo[0]['youhui'] = \Yii::$app->params['youhui'];
        $payinfo[0]['show_jxhd'] = 'n';
        $payinfo[0]['show_jxgt'] = 'n';    //功能支付 家校沟通是否默认选中
        $payinfo[0]['show_patz'] = $payinfo[0]['papass']=="y"?'y':'n'; //功能支付 平安通知是否默认选中
        $payinfo[0]['show_qqdh'] = $payinfo[0]['qqpass']=="y"?'y':'n'; //功能支付 亲情电话是否默认选中
        $payinfo[0]['show_xyfw'] = $payinfo[0]['ckpass']=="y"?'y':'n'; //功能支付 校园服务是否默认选中
        if ($payinfo[0]['show_patz']=='n' || $payinfo[0]['show_qqdh'] =='n' || $payinfo[0]['show_xyfw'] =='n') {
            $payinfo[0]['show_ykt'] = 'n'; //功能支付 一卡通业务是否默认选中
        }else{
            $payinfo[0]['show_ykt'] = 'y';
        }
        $data['sid'] = $sid;
        $data['jxgt'] =$payinfo[0]['show_jxgt'];
        $data['patz'] =$payinfo[0]['show_patz'];
        $data['qqdh'] =$payinfo[0]['show_qqdh'];
        $data['ckfw'] =$payinfo[0]['show_xyfw'];
        $data['shijian'] ="yxn";
        $payinfo[0]['money'] = strval($this->actionTotaljrre($data)); //默认选中显示的价格
                \Yii::trace($payinfo);
        return $this->formatAsjson($payinfo);
    }

    //总金额计算
    public function actionTotaljrre($data){
        $sid = $data['sid'];
        $patz = ($data['patz']!=="n"?$data['patz']:"");
        $jxgt = ($data['jxgt']!=="n"?$data['jxgt']:"");
        $qqdh = ($data['qqdh']!=="n"?$data['qqdh']:"");
        $ckfw = ($data['ckfw']!=="n"?$data['ckfw']:"");
        $shijian = $data['shijian'];
        if (empty($shijian)){
            return $this->errorHandler("1039");
        }
        $a = "";
        if ($shijian == "yxn"){
            $a = "year";
            $aa = "12";
        }elseif($shijian == "yxq"){
            $a = "half";
            $aa = "6";
        }elseif($shijian == "ygy"){
            $a = "month";
            $aa = "1";
        }

        $money1 = !empty($jxgt)?3*$aa:0;
        $zl['patz'] =  "s";
        $zl['qqdh'] =  "w";
        $zl['ckfw'] =  "wc";
        \Yii::trace($patz);
        \Yii::trace($qqdh);
        
        $strs = "";
        $strs = ($patz=='y'?$zl["patz"]:"");
        $strs.= $qqdh=="y"?$zl["qqdh"]:"";
        $strs.= $ckfw=="y"?$zl["ckfw"]:"";
        \Yii::trace($strs);
        $tczh = $strs;
        $money2 =0;
        \Yii::trace($tczh);
        if (!empty($tczh)){
            //套餐种类分类组合
            $tc['swwc'] = "swwc";
            $tc['sw'] = "sw";
            $tc['swc'] = "swc";
            $tc['wwc'] = "wwc";
            $tc['s'] = "s";
            $tc['w'] = "w";
            $tc['wc'] = "wc";
            $b= $tc[$tczh];
            $money2 = $this->Pricecalculation($sid,$a,$b);
        }
        $data['money'] = $money1+$money2;
        return $data['money'];
    }

    //套餐组合价格计算
    public function Pricecalculation($sid,$a,$b){
//        $sid = $this->post['sid'];
//        $sid = "56623";
        $payinfo = WpIschoolSchool::find()->select('apphalf_money,appone_money,appmonth_money')->where(['id'=>$sid])->asArray()->all();
        $res = array("half"=>json_decode($payinfo[0]['apphalf_money']),"year"=>json_decode($payinfo[0]['appone_money']),"month"=>json_decode($payinfo[0]['appmonth_money']));
        \Yii::trace($a);
        \Yii::trace($b);
        \Yii::trace($res);
        return $res[$a]->$b;    //返回套餐价格
//        return $this->formatAsjson($payinfo);
    }


        //个人中心首页
    public function actionIndex(){
        $uid = $this->post['uid'];
//        $uid = $this->uid;
        $stu_id = $this->post['stu_id'];    //当前学生ID last_stuid
        $sinfo = $this->getSchoolidbystuid($stu_id);
        $data['stuid'] = $stu_id;
        $data['stuname'] =$this->getStunamebystuid($stu_id)['name'];
        $data['school'] =empty($sinfo[0]['school'])?1:$sinfo[0]['school'];
        $model = new WpIschoolPastudent();
        $res = $model->getPastudent($uid);
        $data['stuinfo'] = [];
        foreach ($res as $k=>$v){
            $data['stuinfo'][$k]['stu_id'] = !empty($v['stu_id'])?$v['stu_id']:null;
            $data['stuinfo'][$k]['schoolname'] = !empty($v['school'])?$v['school']:null;
            $data['stuinfo'][$k]['school_id'] = !empty($v['sid'])?$v['sid']:null;
            $data['stuinfo'][$k]['stu_name'] = !empty($this->getStunamebystuid($v['stu_id'])['name'])?$this->getStunamebystuid($v['stu_id'])['name']:null;
            $data['stuinfo'][$k]['class'] = !empty($v['class'])?$v['class']:null;
            $data['stuinfo'][$k]['cid'] = !empty($v['cid'])?$v['cid']:null;
        }
        return $this->formatAsjson($data);
    }


        //通过手机号码获取用户名
    public function getName($tel){
        $data = [];
        $model = WpIschoolUser::findOne(['tel'=>$tel]);
        $name = !empty($model['name'])?$model['name']:"暂无姓名";
       return $model['name'];
    }
    public function actionGetnames(){
    	$tels = $this->post['tels'];
    	Yii::trace($tels);
        eval("\$tels = ".$tels.';');
        $data = [];
        Yii::trace($tels);
        // var_dump($tels);exit;
        // $tels = [15225054532,13526566308,15639275420];
		// [15225054532,13526566308,15639275420]
        foreach ($tels as $key => $value) {
            $data[$key]= $this->getName($value);
        }
        return $this->formatAsjson($data);
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




    //班级管理开通人数统计接口
    public function actionTotalstu(){
        $cid = $this->post['cid'];
        $data = [];
        $a = 0; $b = 0; $c = 0; $d = 0;
       $model = WpIschoolStudent::find()->select('enddateqq,enddatejx,enddateck,enddatepa')->where(['cid'=>$cid])->orderBy('name asc')->asArray()->all();
       foreach ($model as $k=>$v){
            if($v['enddateqq']>time()){
                $a++;
            }
            if($v['enddatejx']>time()){
                $b++;
            }
            if($v['enddateck']>time()){
                $c++;
            }
            if($v['enddatepa']>time()){
                $d++;
            }
        }
        $data['tj']['total_qq'] = strval($a);
        $data['tj']['total_jx'] = strval($b);
        $data['tj']['total_ck'] = strval($c);
        $data['tj']['total_pa'] = strval($d);
        $data['tj']['total'] = strval(count($model));
        \Yii::trace($data);
        return $this->formatAsjson($data);
      }

    //班级管理学生列表接口
    public function actionStulist(){
        $cid = $this->post['cid'];
        $name = $this->post['stu_name'];
        if ($name == "null"){
            $model = $this->getAllstuinf($cid);
        }else{
            $model = $this->getAllstuinfos($cid,$name);
        }
        $data = [];
        $a = 0; $b = 0; $c = 0; $d = 0;
        foreach ($model as $k=>$v){
            $data[$k]['stu_name'] = $v['name'];
            $data[$k]['stu_id'] = $v['id'];
            $data[$k]['stuno2'] = $v['stuno2'];
            $data[$k]['is_try'] = $v['is_try'];
            $data[$k]['is_qq'] = ($v['enddateqq']>time())?"y":"n";
            $data[$k]['is_jx'] = ($v['enddatejx']>time())?"y":"n";
            $data[$k]['is_ck'] = ($v['enddateck']>time())?"y":"n";
            $data[$k]['is_pa'] = ($v['enddatepa']>time())?"y":"n";
            if($data[$k]['is_qq'] == 'y'){
                $data['tj']['total_qq'] = strval(++$a);
            }
            if($data[$k]['is_jx'] == 'y'){
                $data['tj']['total_jx'] = strval(++$b);
            }
            if($data[$k]['is_ck'] == 'y'){
                $data['tj']['total_ck'] = strval(++$c);
            }
            if($data[$k]['is_pa'] == 'y'){
                $data['tj']['total_pa'] = strval(++$d);
            }
        }
        $data['tj']['total'] = strval(count($model));
        return $this->formatAsjson($data);
    }

    //通过手机号码获取用户名
    public function actionGetname(){
        $tel = $this->post['tel'];
        $data = [];
        $model = WpIschoolUser::findOne(['tel'=>$tel]);
        $data['username'] = $model['name'];
        $data['user_img'] = $model['user_img'];
        return $this->formatAsjson($data);
    }

        //根据用户手机号获取用户姓名
    public function getusername($uid){
        $model = WpIschoolUser::findOne($uid);
        return $model['name'];
    }
//注册接口
    public function actionRegister(){
        $name = isset($this->post['name'])?$this->post['name']:null;
        $tel = isset($this->post['tel'])?$this->post['tel']:null;
        $yzm = isset($this->post['yzm'])?$this->post['yzm']:null;
        $pwd = isset($this->post['pwd'])?$this->post['pwd']:null;
        $pwdt = isset($this->post['pwdt'])?$this->post['pwdt']:null;
        if (isset($name) && isset($tel) && isset($yzm) && isset($pwd) && isset($pwdt)){
            if(!preg_match("/^1[34578]\d{9}$/", $tel)){
                return $this->errorHandler("1004");
            }
            if (strlen($pwd)<6){
                return $this->errorHandler("1009");
            }
            if ($pwd !== $pwdt){
                return $this->errorHandler("1010");
            }
            $res = WpIschoolUser::findOne(['tel'=>$tel]);
            if(!empty($res)){
                return $this->errorHandler("1011");
            }
            $redis = BaseActiveController::getRedis();
            try {
                $redis->ping();
            } catch (Exception $e) {
                $redis = BaseActiveController::getRedis();
            }
            $fwqyzm = $redis->get($tel);
            Yii::trace($fwqyzm);
            if(!isset($fwqyzm)){
                return $this->errorHandler("1012");
            }
            if ($fwqyzm !== $yzm){
                return $this->errorHandler("1013");
            }
            $model = new WpIschoolUser();
            $model->name = $name;
            $model->tel = $tel;
            $model->pwd = md5($pwd);
            $model->ctime = time();
            $model->shenfen = "jiazhang";
            $result = $model->save(false);
            if ($result){
                $commond = new Communals();
                $commond->register_im($tel,$tel);
                $redis->delete($tel);
                return $this->formatAsjson("success");
            }
        }else{
            return $this->errorHandler("1014");
        }
    }



public function register_im($tel, $password) {
    if (empty($tel) || empty($password) || !preg_match("/^1\d{10}$/", $tel)) {
        return false;
    }
    $posturl = "http://im.henanzhengfan.com:5281/api/register";
    $postData = [
        "user" => $tel,
        "host" => "im.henanzhengfan.com",
        "password" => $password,
    ];
    $postData = json_encode($postData);
    $user = \Yii::$app->params['IM_ADMIN_USER'];
    $pass = \Yii::$app->params['IM_ADMIN_PASSWORD'];;
    $ch = curl_init(); //初始化curl
    curl_setopt($ch, CURLOPT_URL, $posturl); //抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_USERPWD, "{$user}:{$pass}");
    $data = curl_exec($ch); //运行curl
    if (strpos('$data', 'successfully registered')) {
        return true;
    } else {
        return false;
    }

}

    //与学生对应的老师信息
    public function actionStuteas(){
        // $stuid = $this->post['stu_id'];
        $cid  = $this->post['cid'];
        $uid = $this->uid;
        $tea_uid = WpIschoolTeaclass::find()->select("tname,tel")->where("cid =:cid and uid != :uid and tel is not null")->addParams([':cid'=>$cid,':uid'=>$uid])->groupBy(['tname'])->asArray()->all();
        $data = [];
        foreach ($tea_uid as $key => $value) {
            $data[$key]['tname'] = $value['tname'];
            $data[$key]['pwd'] = '123456';
            $data[$key]['jid'] = $value['tel'].'@im.henanzhengfan.com';
            $data[$key]['user_img'] = WpIschoolUser::findOne(["tel"=>$value['tel']])['user_img'];
        }
        return $this->formatAsjson($data);
        Yii::trace($data);
    }
   
//图片上传接口
    public function actionImgupload(){
        $res = $this->Uploadimg($_FILES);
            // $res = $this->Uploadfiles($upimg)
        if (!isset($res['status'])) {
            $url= "http://pc.jxqwt.cn";
            $imgurl =  $url.$res;
            $data['imgurl'] = $imgurl;
            return $this->formatAsjson($data);
        }else{
            return $res;
        }
    }

    public function actionUserimg(){
        $id = $this->post['uid'];
        $res = $this->Uploadimg($_FILES);
        \yii::trace($res);
        if (!isset($res['status'])) {
            $model = WpIschoolUser::findOne($id);
            $model->user_img = $res;
            if ($model->save(false)) {
                return $this->formatAsjson("success");
            }
            else{
                return $this->errorHandler("1058");
            }
        }else{
                return $res;
        }

        Yii::trace($res['status']);
    }



       //上传文件
    public function Uploadfiles($upimg){
        //设置上传文件大小限制(单位b)
        $max_size=1048576;
        //设置上传文件的文件格式限制
        $format=array("image/jpeg","image/gif","image/png");
        //文件上传目录
//        $dir=dirname(__FILE__) ."/upload/";
        $dir = UPLOAD_PATH.date('y/m/d/',time());
        $dir2 = "/ischool/upload/picture/".date('y/m/d/',time());
//        $dir = "uploads/jxgt/".date('y/m/d',time());
        Yii::trace($dir);
        //判断上传目录，不存在就创建
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
//            mkdir($dir,true);
        }

        $str = "";
        // Yii::trace("333333");
        //被上传文件的名称
            $name=$upimg["name"];
            //被上传文件的类型
            $type=$upimg["type"];
            //被上传文件的大小，以字节计
            $size=$upimg["size"];
            //存储在服务器的文件的临时副本的名称
            $tmp_name=$upimg["tmp_name"];
            //由文件上传导致的错误代码
            $error=$upimg["error"];

            //判断文件大小
            if($size>$max_size){
                return $this->errorHandler("1056");
//                exit("");
            }
            //判断文件格式
            if(!in_array($type,$format)){
                return $this->errorHandler("1057");
//                exit("无效的文件格式");
            }

            //生成文件名
            date_default_timezone_set("PRC");
            $file_name=time().mt_rand(1111, 999999);
            //获取文件格式
            $ext=substr($type, strpos($type, "/")+1);

            if($error>0){
                exit($error);
            }else{
                if(move_uploaded_file($tmp_name, $dir.$file_name.".".$ext)){
                    Yii::trace("oooooo");
                    $imgurl = $dir2.$file_name.".".$ext;
                    $str.= "<p><img src='".$imgurl."'><br></p>";
                    //exit("上传成功");
                }
            }
             Yii::trace("121212");
             $url= "http://pc.jxqwt.cn";
             return $url.$imgurl;
    }

    //批量上传图片
    public function Uploadimgs($upimg){
        Yii::trace(111111);
        // Yii::trace($_FILES);
        // Yii::trace($_FILES['img']);
        $upimg = !isset($upimg['img'])?null:$upimg['img'];   //上传图片
        Yii::trace($upimg);
        // $streamData =file_get_contents('php://input');
        // Yii::trace($streamData);
        // return $this->Uploadfiles($upimg);
        //设置上传文件大小限制(单位b)
        $max_size=1048576;
        //设置上传文件的文件格式限制
        $format=array("image/jpeg","image/jpg","image/gif","image/png");
        //文件上传目录
//        $dir=dirname(__FILE__) ."/upload/";
        $dir = UPLOAD_PATH.date('y/m/d/',time());
        $dir2 = "/ischool/upload/picture/".date('y/m/d/',time());
//        $dir = "uploads/jxgt/".date('y/m/d',time());
        Yii::trace($dir);
        //判断上传目录，不存在就创建
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
//            mkdir($dir,true);
        }

        $str = "";
        // Yii::trace("333333");
        for($i=0,$j=count($upimg["name"]);$i<$j;$i++){
         //被上传文件的名称
            $name=$upimg["name"][$i];
            //被上传文件的类型
            $type=$upimg["type"][$i];
            //被上传文件的大小，以字节计
            $size=$upimg["size"][$i];
            //存储在服务器的文件的临时副本的名称
            $tmp_name=$upimg["tmp_name"][$i];
            //由文件上传导致的错误代码
            $error=$upimg["error"][$i];

            //判断文件大小
//             if($size>$max_size){
//                 return $this->errorHandler("1056");
// //                exit("");
//             }
            //判断文件格式
            if(!in_array($type,$format)){
                return $this->errorHandler("1057");
//                exit("无效的文件格式");
            }

            //生成文件名
            date_default_timezone_set("PRC");
            $file_name=time().mt_rand(1111, 999999);
            //获取文件格式
            $ext=substr($type, strpos($type, "/")+1);
            $url_path = "http://pc.jxqwt.cn";
            if($error>0){
                exit($error);
            }else{
                if(move_uploaded_file($tmp_name, $dir.$file_name.".".$ext)){
                    Yii::trace("oooooo");
                    $imgurl = $url_path.$dir2.$file_name.".".$ext;
                    $str.= "<p><img src='".$imgurl."'><br></p>";
                    //exit("上传成功");
                }
            }
        }
             Yii::trace("121212");
             return $str;
    }


    //根据学生ID判断学生综合有效期是否过期	
    public function actionEndtotal(){
        $stuid = $this->post['stu_id'];
        $sid = $this->getSchoolidbystuid($stuid)[0]['sid'];
        $data = WpIschoolSchool::find()->select('jxpass,qqpass,qqpass,ckpass,bkpass,ispass,skpass,xfpass,ckczzfb,ckczwx')->where(['id'=>$sid])->asArray()->one();
        yii::trace($data);
        $endjx = $this->stuinfo($stuid)[0]['enddatejx'];
        $endqq = $this->stuinfo($stuid)[0]['enddateqq'];
        $endck = $this->stuinfo($stuid)[0]['enddateck'];
        $endpa = $this->stuinfo($stuid)[0]['enddatepa'];
        $data['ispass_jx'] = (time()>$endjx)?"n":"y";		//学生家校沟通有效期是否过期
        $data['ispass_qq'] = (time()>$endqq)?"n":"y";		//学生亲情电话有效期是否过期
        $data['ispass_ck'] = (time()>$endck)?"n":"y";		//学生餐卡服务有效期是否过期
        $data['ispass_pa'] = (time()>$endpa)?"n":"y";		//学生平安通知有效期是否过期
        return $this->formatAsjson($data);
    }

    public function actionGettoken(){
        $request = Yii::$app->request;
        //array( 'Content-Type: application/x-www-form-urlencoded; charset=utf-8','Content-Length: ' . strlen($data))
        $this->ImLogs(json_encode($request->post()));
        $appSecret = 'wsiDh7C2rC'; // 开发者平台分配的 App Secret。
        $nonce = rand(); // 获取随机数。
        $timestamp = time()*1000; // 获取时间戳（毫秒）。
        $signature = sha1($appSecret.$nonce.$timestamp);
        $header = array();
        $header[] = 'App-Key:e0x9wycfe4czq';
        $header[] = 'Timestamp:'.$timestamp;
        $header[] = 'Nonce:'.$nonce;
        $header[] = 'Signature:'.$signature;
        $userId= $request->post('userId');
        $name= $request->post('name');
        $portraitUri= $request->post('portraitUri');
        $ret = [];
        // TODO: 此处添加业务逻辑。
        $url="http://api.cn.ronghub.com/user/getToken.json";
        $data="userId=".$userId."&name=".$name."&portraitUri=".$portraitUri;
        $this->ImLogs($data);
        $header[] = 'Content-Length:'.strlen($data);
        $result=$this->PostCurl($url,$data,$header);
        $this->ImLogs(json_encode($result));
        if(isset($result) && $result['code']=='200'){  
                          
            $ret['userId'] = $result['userId'];
            $ret['token'] = $result['token'];
            $ret['status'] = '0';
            $ret['info'] = '操作成功';
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $ret;
        }else{
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $ret['status'] = $result['code'];
            $ret['info'] = '获取token失败';
            return $ret;
        }
    }

    protected function PostCurl($url,$data,$header="Content-Type: application/x-www-form-urlencoded")
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置header
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return array("errcode"=>-1,"errmsg"=>'发送错误号'.curl_errno($curl).'错误信息'.curl_error($curl));
        }
        curl_close($curl);
        return json_decode($result, true);
    }
    protected function ImLogs($info){
        $date=date("Y-m-d H:i:s",time());
        file_put_contents(Yii::getAlias('@api').'/runtime/logs/IM.txt',$date." ".$info."\r\n", FILE_APPEND);
    }


}
