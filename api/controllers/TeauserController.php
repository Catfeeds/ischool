<?php

namespace api\controllers;

use api\models\WpIschoolGonggao;
use api\models\WpIschoolNews;
use api\models\WpIschoolPastudent;
use api\models\WpIschoolTeaclass;
use Yii;
use api\models\WpIschoolUser;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\widgets\LinkPager;


class TeauserController extends BaseActiveController
{
    //登录接口
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
        if(empty($teaclass_cid)){
            $teaclass_cid = WpIschoolTeaclass::find()->select('cid,sid,role,ispass')->where(['and','uid ='.$res['id'],'cid !=0'])->orderby('ctime desc')->asArray()->one();
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
                'expire'=>time()+3600 * 24 *7
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

    //     public function actionLogin(){
    //     $tel = $this->post['tel'];
    //     $pwd = md5($this->post['pwd']);
    //     $push_id = $this->post['push_id'];
    //     $models = new WpIschoolUser();
    //     $res = $models->findOne(['tel'=>$tel,'pwd'=>$pwd]);
    //     if (empty($res)){
    //         return $this->errorHandler("1003");
    //     }
    //     if (!empty($res)){
    //         $teaclass_cid = WpIschoolTeaclass::find()->select('cid,sid')->where(['uid'=>$res['id'],'role'=>"班主任","ispass"=>"y"])->orderby('ctime desc')->asArray()->one();
    //         if (empty($teaclass_cid['cid'])){
    //             Yii::trace($teaclass_cid);
    //             return $this->errorHandler("1053");
    //         }
    //     }

    //     if ($res){
    //         $cookies = Yii::$app->response->cookies;
    //         $user_id = $res->id;
    //         Yii::trace($user_id);
    //         $cookies->add(new \yii\web\Cookie([
    //             'name' => "user_id",
    //             'value' => $user_id,
    //             'expire'=>time()+3600 * 24 *30
    //         ]));
    //         Yii::trace($res->id);
    //         $data['uid'] = strval($res['id']);
    //         $data['last_sid'] = strval($teaclass_cid['sid']);
    //         $data['last_cid'] = strval($teaclass_cid['cid']);
    //         $data['name'] = strval($res['name']);
    //         $data['tel'] = $tel;
    //         $data['schoolname'] = $teaclass_cid['sid']?$this->getSchoolname($teaclass_cid['sid']):"暂无学校";       //学校名称
    //         $data['last_classname'] = $this->getClassnamebystuid($teaclass_cid['cid'])['name'];
    //         $models = WpIschoolUser::findOne(['tel'=>$tel]);
    //         $models->tel = $tel;
    //         $models->login_time =time();
    //         if ($push_id !=="null"){
    //             $models->push_id = $push_id;
    //         }
    //         $models->last_cid = $teaclass_cid['cid'];
    //         $models->last_sid = $teaclass_cid['sid'];
    //         $models->update(false);
    //         $res = $this->formatAsjson($data);
    //     }
    //     return $res;
    // }

//短信接口
    public function actionDuanxin(){
        $tel = $this->post['tel'];
        $type = $this->post['type'];
        $res = WpIschoolUser::findOne(['tel'=>$tel]);
        if(!empty($res) && $type=="zhuce"){
            return $this->errorHandler("1011");
        }
        if(empty($res) && $type=="wjmm"){
            return $this->errorHandler("1015");
        }
        //获取短信验证码
        return $this->getDxyzm($tel);
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
            $model->shenfen = "tea";
            $result = $model->save(false);
            if ($result){
                $data = WpIschoolUser::findOne(['tel'=>$tel]);
                unset($data['pwd']);
                $cookies = Yii::$app->response->cookies;
                $user_id = $data['id'];
                Yii::trace($user_id);
                $cookies->add(new \yii\web\Cookie([
                    'name' => "user_id",
                    'value' => $user_id,
                    'expire'=>time()+3600 * 24 *7
                ]));
                $redis->delete($tel);
                return $this->formatAsjson($data);
            }
        }else{
            return $this->errorHandler("1014");
        }
    }

    //忘记密码接口
    public function actionForgetpwd(){
        $tel = $this->post['tel'];
        $yzm = $this->post['yzm'];
        $pwd = $this->post['pwd'];
        $pwdt = $this->post['pwdt'];
        $res = WpIschoolUser::findOne(['tel'=>$tel]);
        if(empty($res)){
            return $this->errorHandler("1015");
        }
        if ($pwd !== $pwdt){
            return $this->errorHandler("1010");
        }
        $redis = BaseActiveController::getRedis();
        try {
            $redis->ping();
        } catch (Exception $e) {
            $redis = BaseActiveController::getRedis();
        }
        $fwqyzm = $redis->get($tel);
        if(!isset($fwqyzm)){
            return $this->errorHandler("1012");
        }
        if ($fwqyzm !== $yzm){
            return $this->errorHandler("1013");
        }
        $res->pwd = md5($pwd);
        $ret = $res->save(false);
        if ($ret){
            $redis->delete($tel);
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1016");
        }
    }

    //绑定班级
    public function actionAddclass(){
        $uid = $this->post['uid'];
        $openid = $this->openid;
        $schoolname = $this->post['school'];
        $sid = $this->post['sid'];
        $class = $this->post['class'];
        $cid = $this->post['cid'];
        $role = $this->post['role'];
        $model = WpIschoolTeaclass::find()->where(['and',['cid'=>$cid],['role'=>$role]])->all();
        if(!empty($model)){
            return $this->errorHandler("1044");
        }
        $models = new WpIschoolTeaclass();
        $models->tname = $this->users['name'];
        $models->openid = $this->openid;
        $models->school = $schoolname;
        $models->sid = $sid;
        $models->class = $class;
        $models->cid = $cid;
        $models->role = $role;
        $models->ctime = time();
        $models->ispass = "n";
        $models->tel = $this->users['tel'];
        $models->uid = $uid;

        $modelss = WpIschoolUser::findOne($uid);
        $modelss->last_sid = $sid;
        $modelss->last_cid = $cid;


        $transaction = \yii::$app->db->beginTransaction();
        try{
            if($models->save(false) && $modelss->save(false))
            {
                $transaction->commit();
                $userinfos  = \Yii::$app->db->createCommand("select * from wp_ischool_user  where  id =:uid",[':uid'=>$uid] )->queryAll();//获取用户信息
                $data['uid'] = strval($userinfos[0]['id']);
                $data['last_sid'] = strval($userinfos[0]['last_sid']);
                $data['last_cid'] = strval($userinfos[0]['last_cid']);
                $data['name'] = strval($userinfos[0]['name']);
                $data['tel'] = $userinfos[0]['tel'];
                $data['schoolname'] = $this->getSchoolname($userinfos[0]['last_sid']);       //学校名称
                $data['last_classname'] = $this->getClassnamebystuid($userinfos[0]['last_cid'])['name'];
                return $this->formatAsjson($data);
            }else {
                $res = 1;
                $transaction->rollBack();
                return $this->errorHandler("1044");
            }
        }catch (Exception $e)
        {
            $transaction->rollBack();
        }
    }

    //首页部分接口
    public function actionIndex(){
        $sid = $this->post['sid'];
        $issid = $this->issetSid($sid);
        if ($issid){
            $data['head']['schoolname'] = $this->getSchoolname($sid);       //学校名称
            $sql = "select * from wp_ischool_hpage_lunbo where sid =:sid order by id DESC";
            $models = Yii::$app->db->createCommand($sql,[":sid"=>$sid])->queryAll();
            if ($models){
                Yii::trace($models[0]['id']);
                foreach ($models as $k =>$v){
                    $data['head']['hdp'][$k]['hdpid'] = strval($v['id']);             //幻灯片ID
                    $data['head']['hdp'][$k]['hdpsid'] = strval($v['sid']);            //幻灯片学校ID
                    $data['head']['hdp'][$k]['hdppicurl'] = URL_PATH.$v['picurl'];     //幻灯片图片链接地址
                }
            }else{
                $data['head']['hdp'] = [];
            }
            $data['head']['gglburl'] = URL_PATH."/apiuser/notice";      //公告跳转链接
            $data['head']['dtlburl'] = URL_PATH."/apiuser/dynamics";      //动态跳转链接
            //首页头条公告
            $topgonggao = WpIschoolGonggao::find()->where(['sid'=>$sid])->orderBy("ctime desc")->limit("1")->all();
            $data['body']['topggid'] = isset($topgonggao[0]['id'])?strval($topgonggao[0]['id']):"null";    //首页头条公告跳转ID
            $data['body']['topggtitle'] = isset($topgonggao[0]['title'])?$topgonggao[0]['title']:"null"; //首页头条公告标题
            //首页班级动态
            $topdongtai = WpIschoolNews::find()->where(['sid'=>$sid])->orderBy("ctime desc")->limit("1")->all();
            $data['body']['topdtid'] = isset($topdongtai[0]['id'])?strval($topdongtai[0]['id']):"null";    //首页头条动态跳转ID
            $data['body']['topdtitle'] = isset($topdongtai[0]['title'])?$topdongtai[0]['title']:"null"; //首页头条动态标题
            /**
             * 校园风采接口
             */
            $sql2 = "select t3.* from(SELECT t2.id,t2.title,t2.toppicture,t2.content,t2.sketch,t2.sid,t.id as cid,t.name from wp_ischool_hpage_colname t LEFT JOIN wp_ischool_hpage_colcontent t2 on t.id=t2.cid where t.sid=:sid ORDER BY t2.id desc) t3 GROUP BY t3.name order by cid asc";
            $fcmodel = \yii::$app->db->createCommand($sql2,[":sid"=>$sid])->queryAll();
            yii::trace($fcmodel);
            if ($fcmodel){
                foreach ($fcmodel as $k=>$v){
                    $data['foot'][$k]['fcid'] = !empty($v['content'])?strval($v['id']):"null";     //校园风采ID
                    $data['foot'][$k]['fcpicurl'] = $v['toppicture']?URL_PATH.$v['toppicture']:"null";     //校园风采图片地址
                    $data['foot'][$k]['fctytitle'] = $v['name']?:"null";     //校园风采板块标题
                    $data['foot'][$k]['fcwztitle'] = $v['title']?:"null";     //校园风采文章标题
                    $data['foot'][$k]['fcwzjianjie'] = $v['sketch']?:"null";     //校园风采文章简介
                    $data['foot'][$k]['fcsid'] = strval($v['sid'])?:"null";     //校园风采学校ID
                }
            }else{
                $data['foot'] = [];
            }
            return $this->formatAsjson($data);
        }else{
            return $this->errorHandler("1007");
        }
    }


//首页头条公告内容页
    public function actionTopnotice(){
        $sid = $this->post['topggid'];
        $topgonggao = WpIschoolGonggao::find()->where(['id'=>$sid])->orderBy("ctime desc")->limit("1")->all();
        $data['topggtitle'] = isset($topgonggao[0]['title'])?$topgonggao[0]['title']:"null"; //首页头条公告标题
        if (!empty($topgonggao[0]['content'])){
            $topggconten = $topgonggao[0]['content'];
            $str1 = 'src="'; $str2 = 'src="'.URL_PATH;
            $content = str_replace($str1,$str2,$topggconten);  //首页头条公告内容
            $data['topggcontent']  = "<div style='color:red;'>".$content."</div>";
        }else{
            $data['topggcontent'] = "null";
        }

        $data['topggctime'] = isset($topgonggao[0]['ctime'])?date("Y-m-d H:i:s",$topgonggao[0]['ctime']):"null";//首页头条公告发布时间
        return $this->formatAsjson($data);
    }

    //首页头条动态内容页
    public function actionTopnews(){
        $sid = $this->post['topdtid'];
        $topnews = WpIschoolNews::find()->where(['id' => $sid])->orderBy("ctime desc")->limit("1")->all();
        $data['topdttitle'] = isset($topnews[0]['title']) ? $topnews[0]['title'] : "null"; //首页头条公告标题
        if (!empty($topnews[0]['content'])){
            $topdtcontent = $topnews[0]['content'];
            $str1 = 'src="'; $str2 = 'src="'.URL_PATH;
            $data['topdtcontent'] = str_replace($str1,$str2,$topdtcontent);  //首页头条公告内容
        }else{
            $data['topdtcontent'] = "null";
        }
        $data['topgdtctime'] = isset($topnews[0]['ctime']) ? date("Y-m-d H:i:s", $topnews[0]['ctime']) : "null";//首页头条公告发布时间
        return $this->formatAsjson($data);
    }

    //首页校园风采内容详情页接口
    public function actionFccontent(){
        $id = $this->post['fcid'];
        $sql = "select * from wp_ischool_hpage_colcontent where id =:id order by id DESC limit 4";
        $res = Yii::$app->db->createCommand($sql,[':id'=>$id])->queryAll();
        yii::trace($res);
        $data['title'] = isset($res[0]['title']) ? $res[0]['title'] : "null"; //首页校园风采标题
        $data['content'] = null;
        if (!empty($res[0]['content'])){
            $content = $res[0]['content'];
            $str1 = 'src="'; $str2 = 'src="'.URL_PATH;
            $data['content'] = str_replace($str1,$str2,$content);  //首页校园风采内容
        }
        return $this->formatAsjson($data);
    }

    //校内公告列表接口
    public function actionNotice(){
        $sid = $this->post['sid'];
        $issid = $this->issetSid($sid);
        if ($issid) {
            $models = WpIschoolGonggao::find()->where(['sid' => $sid])->orderBy('ctime DESC')->all();
            $data = [];
            foreach ($models as $k => $v) {
                $data[$k]['ggid'] = strval($v['id']);
                $data[$k]['ggtitle'] = strval($v['title']);
                $data[$k]['ggctime'] = date("Y-m-d H:i:s", $v['ctime']);
                $data[$k]['ggzuozhe'] = $v['name'];
            }
            $res = $this->formatAsjson($data);
            return $res;
        }else{
            return $this->errorHandler("1007");
        }
    }

    //班级动态列表接口
    public function actionDynamics(){
        $sid = $this->post['sid'];
        $issid = $this->issetSid($sid);
        if ($issid) {
            $models = WpIschoolNews::find()->where(['sid'=>$sid])->orderBy('ctime DESC')->all();
            $data = [];
            foreach ($models as $k =>$v){
                $data[$k]['dtid'] = strval($v['id']);
                $data[$k]['dttitle'] = strval($v['title']);
//                $data[$k]['dtcontent'] = $v['content'];
                $data[$k]['dtctime'] = date("Y-m-d H:i:s",$v['ctime']);
                $data[$k]['dtzuozhe'] = $v['name'];
            }
            $res = $this->formatAsjson($data);
            return $res;
        }else{
            return $this->errorHandler("1007");
        }
    }
}