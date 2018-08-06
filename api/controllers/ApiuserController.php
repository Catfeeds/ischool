<?php

namespace api\controllers;

use api\models\WpIschoolGonggao;
use api\models\WpIschoolNews;
use api\models\WpIschoolPastudent;
use Yii;
use api\models\WpIschoolUser;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\widgets\LinkPager;
use common\models\Communals;

class ApiuserController extends BaseActiveController
{
    public function actionTest()
    {
        if($this->post['info']==1)
            return	$this->formatAsjson("success");
        else return $this->errorHandler(0);
    }

    //登录接口
    public function actionLogin(){
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
//                 		'name' => "tel",
//                 		'value' => $tel,
//                 		'expire'=>time()+3600 * 24 *30
//                 ]));
                $user_id = $res->id;
                Yii::trace($user_id);
                $cookies->add(new \yii\web\Cookie([
                    'name' => "user_id",
                    'value' => $user_id,
                    'expire'=>time()+3600 * 24 *7
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
                $data['last_classname'] = $this->getClassnamebystuid($data['last_cid'])['name'];
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
                $res = $this->formatAsjson($data);
            }else{
                $res = $this->errorHandler("1003");
            }
        return $res;
    }

//短信接口
    public function actionDuanxin(){
        $tel = $this->post['tel'];
        $type = $this->post['type'];
        $res = WpIschoolUser::findOne(['tel'=>$tel]);
        if (empty($tel)){
            return $this->errorHandler("1004");
        }
        if(!empty($res) && $type=="zhuce"){
            return $this->errorHandler("1011");
        }
        if(empty($res) && $type=="wjmm"){
            return $this->errorHandler("1015");
        }
        //获取短信验证码
        Yii::trace(1111);
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

    //首页部分接口
    public function actionIndex(){
//        $sid = 56758;
//        $uid = $this->post['uid'];
        $sid = $this->post['sid'];
        $issid = $this->issetSid($sid);
        if ($issid){
            $data['head']['schoolname'] = $this->getSchoolname($sid);       //学校名称
            // $sql = "select * from wp_ischool_hpage_lunbo where sid =:sid order by id DESC limit 4";
            $sql = "select * from wp_ischool_hpage_lunbo where sid =:sid";
            $models = Yii::$app->db->createCommand($sql,[":sid"=>$sid])->queryAll();
            if ($models){
                Yii::trace($models[0]['id']);
                foreach ($models as $k =>$v){
                    $data['head']['hdp'][$k]['hdpid'] = strval($v['id']);             //幻灯片ID
                    $data['head']['hdp'][$k]['hdpsid'] = strval($v['sid']);            //幻灯片学校ID
                    $data['head']['hdp'][$k]['hdppicurl'] = URL_PATH.$v['picurl'];     //幻灯片图片链接地址
                }
    //            $res = $this->formatAsjson($data);
            }else{
                $data['head']['hdp'] = [];
    //            $res = $this->formatAsjson($data);
            }
            $data['head']['gglburl'] = URL_PATH."/apiuser/notice";      //公告跳转链接
            $data['head']['dtlburl'] = URL_PATH."/apiuser/dynamics";      //动态跳转链接
            //首页头条公告
            $topgonggao = WpIschoolGonggao::find()->where(['sid'=>$sid])->orderBy("ctime desc")->limit("1")->all();
            $data['body']['topggid'] = isset($topgonggao[0]['id'])?strval($topgonggao[0]['id']):"null";    //首页头条公告跳转ID
            $data['body']['topggtitle'] = isset($topgonggao[0]['title'])?$topgonggao[0]['title']:"null"; //首页头条公告标题
    //        $data['body']['topggcontent'] = isset($topgonggao[0]['content'])?$topgonggao[0]['content']:null; //首页头条公告内容
    //        $data['body']['topggctime'] = isset($topgonggao[0]['ctime'])?date("Y-m-d H:i:s",$topgonggao[0]['ctime']):null;//首页头条公告发布时间
            //首页班级动态
            $topdongtai = WpIschoolNews::find()->where(['sid'=>$sid])->orderBy("ctime desc")->limit("1")->all();
            $data['body']['topdtid'] = isset($topdongtai[0]['id'])?strval($topdongtai[0]['id']):"null";    //首页头条动态跳转ID
            $data['body']['topdtitle'] = isset($topdongtai[0]['title'])?$topdongtai[0]['title']:"null"; //首页头条动态标题
    //        $data['body']['topdcontent'] = isset($topdongtai[0]['content'])?$topdongtai[0]['content']:null; //首页头条动态内容
    //        $data['body']['topdctime'] = isset($topdongtai[0]['ctime'])?date("Y-m-d H:i:s",$topdongtai[0]['ctime']):null;//首页头条动态发布时间
            /**
             * 校园风采接口
            */
            // $sql2 = "select t3.* from(SELECT t2.id,t2.title,t2.toppicture,t2.content,t2.sketch,t2.sid,t.id as cid,t.name from wp_ischool_hpage_colname t LEFT JOIN wp_ischool_hpage_colcontent t2 on t.id=t2.cid where t.sid=:sid ORDER BY t2.id desc) t3 GROUP BY t3.name limit 5";
            $sql2="select t3.* from(SELECT t2.id,t2.title,t2.toppicture,t2.content,t2.sketch,t2.sid,t.id as cid,t.name from wp_ischool_hpage_colname t LEFT JOIN wp_ischool_hpage_colcontent t2 on t.id=t2.cid where t.sid=:sid ORDER BY t2.id desc) t3 GROUP BY t3.name order by cid asc ";
            $fcmodel = \yii::$app->db->createCommand($sql2,[":sid"=>$sid])->queryAll();
            yii::trace($fcmodel);
            if ($fcmodel){
                foreach ($fcmodel as $k=>$v){
                    $data['foot'][$k]['fcid'] = !empty($v['content'])?strval($v['id']):"null";     //校园风采ID
                    $data['foot'][$k]['fcpicurl'] = $v['toppicture']?URL_PATH.$v['toppicture']:"null";     //校园风采图片地址
    //                $data['foot'][$k]['fccontent'] = $v['content'];     //校园风采内容
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
//                $str1 = 'src="'; $str2 = 'src="'.URL_PATH;
                $str1 = 'src="/'; $str2 = 'src="'.URL_PATH.'/';
                $content = str_replace($str1,$str2,$topggconten);  //首页头条公告内容
                $data['topggcontent']  = $content;
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
//                $str1 = 'src="'; $str2 = 'src="'.URL_PATH;
                $str1 = 'src="/'; $str2 = 'src="'.URL_PATH.'/';
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
//            $str1 = 'src="'; $str2 = 'src="'.URL_PATH;
            $str1 = 'src="/'; $str2 = 'src="'.URL_PATH.'/';
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
//                $data[$k]['ggcontent'] = $v['content'];
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

    //添加学生接口
    public function actionAddchild(){
        $uid = $this->post['uid'];
        $openid = $this->openid;
//        $tel = $this->post['tel'];
        $schoolname = $this->post['school'];
        $sid = $this->post['sid'];
        $class = $this->post['class'];
        $cid = $this->post['cid'];
        $student = $this->post['student'];        //学生名字
        $res = $this->isHasStudent($cid,$student);      //检查班级有无此人
        if (empty($res)){
            return $this->errorHandler("1018");
        }
        $stuid = $res[0]['id'];         //学生id
        $painfo  = $this->getPainfo($uid,$stuid);     //检查家长表中是否已经有手机号码和学生ID的信息(是否已经关注此学生)
        if ($painfo){
            return $this->errorHandler("1019");
        }
        $userinfo  = \Yii::$app->db->createCommand("select * from wp_ischool_user  where  id =:uid",[':uid'=>$uid] )->queryAll();//获取用户信息
        $pname = (!empty($userinfo))?$userinfo[0]['name']:"用户名暂时为空";      //用户名
        $uppastu = new WpIschoolPastudent();
        $uppastu->name = $pname;
        $uppastu->ctime = time();
        $uppastu->stu_id = $stuid;
        $uppastu->school = $schoolname;
        $uppastu->cid = $cid;
        $uppastu->class = $class;
        $uppastu->tel = $this->users['tel'];
        $uppastu->stu_name = $student;
        $uppastu->ispass = "y";
        $uppastu->sid = $sid;
        $uppastu->isqqtel = 0;
        $uppastu->uid = $uid;
        $uppastu->openid = $openid;

        $modelss = WpIschoolUser::findOne($uid);
        $modelss->last_sid = $sid;
        $modelss->last_stuid = $stuid;
        $modelss->last_cid = $cid;


        $transaction = \yii::$app->db->beginTransaction();
        try{
            if($uppastu->save(false) && $modelss->save(false))
            {
                $transaction->commit();

                $userinfos  = \Yii::$app->db->createCommand("select * from wp_ischool_user  where  id =:uid",[':uid'=>$uid] )->queryAll();//获取用户信息
                $data['uid'] = strval($userinfos[0]['id']);
                $data['last_stuid'] = strval($userinfos[0]['last_stuid']);
                $data['last_sid'] = strval($userinfos[0]['last_sid']);
                $data['last_cid'] = strval($userinfos[0]['last_cid']);
                $data['name'] = strval($userinfos[0]['name']);
                $data['tel'] = $userinfos[0]['tel'];
                $data['schoolname'] = $this->getSchoolname($userinfos[0]['last_sid']);       //学校名称
                $data['last_stuname'] = $this->getStunamebystuid($userinfos[0]['last_stuid'])['name'];
                $data['last_classname'] = $this->getClassnamebystuid($userinfos[0]['last_cid'])['name'];
                return $this->formatAsjson($data);
            }else {
                $res = 1;
                $transaction->rollBack();
                return $this->errorHandler("1020");
            }
        }catch (Exception $e)
        {
            $transaction->rollBack();
        }
    }
//登录测试接口
    public function actionLogincs(){
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
//                 		'name' => "tel",
//                 		'value' => $tel,
//                 		'expire'=>time()+3600 * 24 *30
//                 ]));
            $user_id = $res->id;
            Yii::trace($user_id);
            $cookies->add(new \yii\web\Cookie([
                'name' => "user_id",
                'value' => $user_id,
                'expire'=>time()+3600 * 24 *7
            ]));
            Yii::trace($res->id);
            $user_img = URL_PATH.$res['user_img'];
            $data['uid'] = strval($res['id']);
            $data['last_stuid'] = strval($res['last_stuid']);
            $data['last_sid'] = strval($res['last_sid']);
            $data['last_cid'] = strval($res['last_cid']);
            if (!empty($data['last_stuid'])) {
                    if(empty($data['last_sid']) || empty($data['last_cid']) || $data['last_sid'] ==1 || $data['last_cid'] ==1){
                    return $this->errorHandler("1055");
                    }
            }
            $data['name'] = strval($res['name']);
            $data['tel'] = $tel;
            $data['schoolname'] = $res['last_sid']?$this->getSchoolname($res['last_sid']):"暂无学校";       //学校名称
            $data['last_stuname'] = $this->getStunamebystuid($res['last_stuid'])['name'];
            $data['last_classname'] = $this->getClassnamebystuid($res['last_cid'])['name'];
//                $models = new WpIschoolUser();
            $models = WpIschoolUser::findOne(['tel'=>$tel]);
            $models->tel = $tel;
            $models->login_time =time();
            if ($push_id !=="null"){
                $models->push_id = $push_id;
            }
            $models->update(false);
            $res = $this->formatAsjson($data);
        }else{
            $res = $this->errorHandler("1003");
        }
        return $res;
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

	public function actionImguploads(){
        Yii::trace(111111);
        // Yii::trace($_FILES);
        // Yii::trace($_FILES['img']);
        $upimg = !isset($_FILES['img'])?null:$_FILES['img'];   //上传图片
        Yii::trace($upimg);
        // $streamData =file_get_contents('php://input');
        // Yii::trace($streamData);
        // return $this->Uploadfiles($upimg);
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
             $imgurl =  $url.$imgurl;
            $data['imgurl'] = $imgurl;
            // $res = $this->Uploadfiles($upimg)
            return $this->formatAsjson($data);
    }
}
