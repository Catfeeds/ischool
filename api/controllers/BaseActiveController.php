<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-03-09
 * Time: 10:08
 */
namespace api\controllers;

/*
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\filters\RateLimiter;
*/
use api\models\WpIschoolGroupMessage;
use api\models\WpIschoolInbox;
use api\models\WpIschoolOutbox;
use api\models\WpIschoolPastudent;
use api\models\WpIschoolSafecard;
use api\models\WpIschoolSchool;
use api\models\WpIschoolStudent;
use Yii;
use yii\web\Controller;
use api\models\WpIschoolUser;

class BaseActiveController extends Controller
{
    public $modelClass = 'common\models\apiuser';

    public $post = null;
    public $get = null;
    public $user = null;
    public $userId = null;
    public $cookies = null;
    public $uid = null;         // 用户ID
    public $users = null;         // 用户信息
    public $openid = null;
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
        if(isset(\yii::$app->request->cookies->get("user_id")->value)){
            $user_id = \yii::$app->request->cookies->get("user_id")->value;
            $res = WpIschoolUser::findOne($user_id);
            $this->uid = $res['id'];
            $this->openid = !empty($res['openid'])?$res['openid']:null;
            Yii::trace($this->uid);
            $this->users = $res;
        }
        $url=Yii::$app->request->url;
        Yii::trace($url);
//        Yii::trace($user_id);
	 // 调整url为数组或者配置文件
        if ($url != "/pay/wxpay" &&  $url != "/apiuser/login" && $url != "/apiuser/register" && $url != "/apiuser/forgetpwd" && $url != "/apiuser/duanxin" && $url != "/teauser/login" && $url != "/teauser/register" && $url != "/teauser/forgetpwd" && $url != "/teauser/duanxin" && $url != "/pay/wxpaynotify" && $url != "/pay/alipaynotifyck" && $url != "/pay/alipaynotifywt" && $url != "/pay/alipaynotify"  && $url != "/pay/wxpaynotifyrbk" && $url != "/pay/alipaynotifyrbk" && $url != "/ceshipay/alipaynotifyck" && $url != "/ceshipay/wxpaynotify" && $url != "/ceshipay/alipaynotifywt" && $url != "/ceshipay/alipaynotify" && $url != "/ceshipay/alipayrbk" && $url != "/ceshipay/alipaynotifyrbk")
        {
            if(!isset($user_id))
            {
                \Yii::$app->response->data = $this->errorHandler("1041");
                \Yii::$app->response->send();
		exit();
            }
        }
    }

    /*
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                //     HttpBasicAuth::className(),
                //     HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
        ];


        //  数据返回类型设置
        //$behaviors['contentNegotiator']['formats']['application/json'] = 'json';
        //$behaviors['contentNegotiator']['formats']['application/xml'] = 'json';

        return $behaviors;
    }
    */

    // 对数据进行校验
    public function beforeAction($action)
    {
        parent::beforeAction($action);

        $this->post = Yii::$app->request->post();
        $this->get = Yii::$app->request->get();
        $this->user = Yii::$app->user->identity;
        $this->userId = Yii::$app->user->id;
        $this->cookies = Yii::$app->request->cookies;
        return $action;
    }
    public function errorHandler($status)
    {
        $errors = [
            "1001"=>"参数错误!",
            "1002"=>"用户未登录!",
            "1003"=>"用户名或密码错误!",
            "1004"=>"手机号码不合法!",
            "1005"=>"密码不能少于6位!",
            "1006"=>"幻灯片不存在!",
            "1007"=>"学校信息不存在!",
            "1008"=>"用户ID不存在!",
            "1009"=>"密码少于6位!",
            "1010"=>"两次密码不一致!",
            "1011"=>"手机号码已经注册!",
            "1012"=>"短信验证码已经过期!",
            "1013"=>"短信验证码输入错误!",
            "1014"=>"信息填写不完整!",
            "1015"=>"该手机号码未注册!",
            "1016"=>"密码保存失败!",
            "1018"=>"该班级没有此学生!",
            "1019"=>"家长已绑定过该学生!",
            "1020"=>"学生信息绑定保存失败!",
            "1021"=>"学生信息不存在!",
            "1022"=>"学生校园消费服务已经欠费！",
            "1023"=>"学生平安通知服务已经欠费！",
            "1024"=>"家校沟通发布信息保存失败！",
            "1025"=>"亲情号码个数超过限制！",
            "1026"=>"亲情号码数据保存失败！",
            "1027"=>"亲情号码数据修改失败！",
            "1028"=>"亲情号码数据删除失败！",
            "1029"=>"切换学生信息失败！",
            "1030"=>"该学生信息不存在！",
            "1031"=>"删除学生信息失败！",
            "1032"=>"学生申请请假失败！",
            "1033"=>"投诉建议提交失败！",
            "1034"=>"用户名修改失败！",
            "1035"=>"该手机号码已经注册！",
            "1036"=>"手机号码修改失败！",
            "1037"=>"原始密码输入错误！",
            "1038"=>"学生卡号异常，请联系管理员！",
            "1039"=>"退出登录失败！",
            "1040"=>"用户未登录！",
            "1041"=>"接口异常请重新登录！",
            "1042"=>"网络异常，请重新提交订单！",
            "1043"=>"短信发送次数超出上限！",
            "1044"=>"改班级已绑定班主任！",
            "1045"=>"操作失败！",
            "1046"=>"支付类型错误！",
            "1047"=>"标题不能为空！",
            "1048"=>"学校未开通校园消费业务！",
            "1049"=>"学校未开通水卡消费业务！",
            "1050"=>"学生家校沟通服务已经欠费！",
            "1051"=>"文件大小超出限制，不能超过1MB！",
            "1052"=>"无效的文件格式!",
            "1053"=>"教师身份审核中，请耐心等待!",
            "1054"=>"用户身份错误，请核实后登录!",
            "1055"=>"用户资料信息不全，请联系管理员!",
            "1056"=>"图片大小超出限制，不能超过10mb!",
            "1057"=>"图片文件格式不正确!",
            "1058"=>"图片文件保存失败!",
            "1059"=>"该用户手机号码不存在!",
            "1060"=>"您还不是班主任，请联系管理员确认身份后登录!",
            "1061"=>"缺少必要的参数!",
            "1062"=>"票的数量必须选择!",
            "1063"=>"选择票的日期不能小于今天!",
            "1064"=>"选择票的日期不能大于2018年8月31号!",
        ];
        $ret = [];
        $ret['status'] = $status;
//        $ret['info']['msg'] = $errors[$status]?:"error";
        $ret['msg'] = $errors[$status]?:"error";
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $ret;
    }

    public function errorDuanxin($info){
        $ret = [];
        $ret['status'] = "1017";
        $ret['msg'] = $info;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $ret;
    }
    public function formatAsjson($data)
    {
        $ret = [];
        $ret['status'] = 0;
        $ret['info'] = $data;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $ret;
    }
//根据学校ID获取学校名字
    public function getSchoolname($sid){
        $models = WpIschoolSchool::findOne($sid);
        return $models['name'];
    }

    //验证学校ID是否存在
    public function issetSid($sid){
        $models = WpIschoolSchool::findOne($sid);
        return $models;
    }

    /**
    连接redis
     */
    public static function getRedis(){
        $redis = new \redis();
        $redis->connect('127.0.0.1',6379,5); //本机6379端口，5秒超时
        $redis->select(2);      //2库
        return $redis;
    }


    /*  根据班级ID学生姓名检测某班级是否存在某学生 */
    public function isHasStudent($cid,$name){
        $connection  = \Yii::$app->db;
        $command = $connection->createCommand("select id from wp_ischool_student where cid=:cid and name =:name ORDER BY convert(name USING gbk)",[":cid"=>$cid,":name"=>$name]);
        $res =  $command->queryAll();
        return $res;
    }

    /**通过学生ID和手机号码检查家长有没有绑定孩子**/
    public function getPainfo($uid,$stuid){
        $connection  = \Yii::$app->db;
        $command = $connection->createCommand("select * from wp_ischool_pastudent where uid=:uid and stu_id=:stuid and isqqtel='0' ORDER BY convert(name USING gbk)",[":uid"=>$uid,":stuid"=>$stuid]);
        $req = $command->queryAll();
        return $req;
    }

    //获取收件箱内容列表
    public function getinboxlist($uid,$type){
        $model = WpIschoolInbox::find()->select('id,out_uid,in_uid,ctime,title,fujian')->where(['in_uid'=>$uid,'type'=>$type])->limit("30")->orderBy('id DESC')->asArray()->all();
//        Yii::trace($model[0]['content']);
        foreach ($model as $k=>$v){
            $model[$k]['ctime'] = isset($model[0]['ctime'])?date("Y-m-d H:i:s",$v['ctime']):null;
        }
        return $model;
    }
    //获取发件箱内容列表
    public function getoutboxlist($uid,$type){
        $model = WpIschoolOutbox::find()->select('id,out_uid,ctime,title,fujian')->where(['out_uid'=>$uid,'type'=>$type])->limit("30")->orderBy('id DESC')->asArray()->all();
        foreach ($model as $k=>$v){
            $model[$k]['ctime'] = isset($model[0]['ctime'])?date("Y-m-d H:i:s",$v['ctime']):null;
        }
        return $model;
    }

    /**根据学生ID获取学生表对应的信息*/
    public function stuinfo($id){
        $connection  = \Yii::$app->db;
        $command = $connection->createCommand("select * from wp_ischool_student where id=:id",[':id'=>$id]);
        $res =  $command->queryAll();
        return $res;
    }

    /*  计算时间戳，本月头month，本周头week，本日头today时间戳作为查询起点 */
    public function getBeginTimestamp($type){
        $beginTs="";
        if($type=='today') {
            $beginTs=strtotime(date('Y-m-d'));
        }else if($type=='week'){
            $date = date("Y-m-d");
            $first=1;                                // 1 表示每周星期一为开始时间，0表示每周日为开始时间
            $w = date("w", strtotime($date));       //获取当前是本周的第几天，周日是 0，周一 到周六是 1 -6
            $d = $w ? $w - $first : 6;              //如果是周日 -6天
            $now_start = date("Y-m-d", strtotime("$date -".$d." days")); //本周开始时间：
            $beginTs =  strtotime($now_start);     //本周起始时间戳
        }else if($type=='month'){
            $beginTs=strtotime(date('Y-m'));
        }

        return $beginTs;
    }
//获取某一天打卡列表信息
    public function getDaylist($stuid,$begintm,$endtime){
        $models = WpIschoolSafecard::find()->where(['and',['in', 'stuid', $stuid],['between','ctime',$begintm,$endtime],['<>','info','未到']])->orderBy('ctime desc')->asArray()->all();
        return $models;
    }

    //获取打卡列表信息
    public function getdklist($stuid,$begintm){
        $models = WpIschoolSafecard::find()->where(['and',['in', 'stuid', $stuid],['>','ctime',$begintm],['<>','info','未到']])->orderBy('ctime desc')->asArray()->all();
        return $models;
    }

    //根据用户ID获取用户姓名
    public function getusername($uid){
        $model = WpIschoolUser::findOne($uid);
        return $model['name'];
    }

    /**根据班级ID获取该班级所有老师信息*/
    public  function  Allteacher($id){
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select tname,uid from wp_ischool_teaclass where cid=:id and ispass='y' group by uid",[':id'=>$id]);
        $res =  $command->queryAll();
        return $res;
    }

    //根据班级ID获取改班级所有学生信息
    public function getAllstuinf($cid)
    {
        return WpIschoolStudent::find()->where(['cid'=>$cid])->orderBy('name asc')->asArray()->all();
    }

    public function getAllstuinfo($cid)
    {
        return WpIschoolStudent::find()->where(['cid'=>$cid])->orderBy('name asc')->asArray()->all();
    }
    //根据班级ID和学生姓名获取改班级所有学生信息
    public function getAllstuinfos($cid,$name)
    {
        return WpIschoolStudent::find()->where(['and',['cid'=>$cid],['like','name',$name]])->orderBy('name asc')->asArray()->all();
    }

    //生成指定长度的字符串
    function create_random_string($random_length) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $random_string = '';
        for ($i = 0; $i < $random_length; $i++) {
            $random_string .= $chars [mt_rand(0, strlen($chars) - 1)];
        }
        return $random_string;
    }

    //根据学生ID获取学校ID
    public function getSchoolidbystuid($stuid)
    {
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select sid,school,cid from wp_ischool_student where id=:id ORDER BY convert(name USING gbk)",[':id'=>$stuid])->queryAll();
        return $command;
    }

    //根据学生ID获取学生姓名
    public function getStunamebystuid($stuid)
    {
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select name from wp_ischool_student where id=:id",[':id'=>$stuid])->queryOne();
        return $command;
    }

    //根据班级ID获取班级名
    public function getClassnamebystuid($cid)
    {
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select name from wp_ischool_class where id=:id",[':id'=>$cid])->queryOne();
        return $command;
    }

    /**根据班级ID获取班主任信息*/
    public  function  getHeadmaster($id){
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select * from wp_ischool_teaclass where cid=:id and role ='班主任'",[':id'=>$id]);
        $res =  $command->queryAll();
        return $res;
    }

    /**从数据库获取城市信息*/
    public function actionCity(){
        $command = Yii::$app->db->createCommand("select distinct city from wp_ischool_school where city is not NULL  ORDER BY convert(city USING gbk)")->queryAll();
        return $this->formatAsjson($command);
    }

    //根据省市获取学校名字
    public function actionGetschool(){
        $city = $this->post['city'];
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select id,name from wp_ischool_school where city=:city ORDER BY convert(name USING gbk)",[':city'=>$city])->queryAll();
        return $this->formatAsjson($command);
    }

    //根据学校ID获取班级列表
    public function actionGetclass(){
        $sid = $this->post['sid'];
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select id,name from wp_ischool_class where sid=:sid ORDER BY level,class asc",[':sid'=>$sid])->queryAll();
        return $this->formatAsjson($command);
    }



    /***    修改用户名     */
    public function Upname($name,$id)
    {
        $upuser = WpIschoolUser::findOne($id);
        $upuser->name = $name;
        $upname = $upuser->save(false);
        if($upname){
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1034");
        }
    }

    /***    修改用户电话号码     */
    public function Uptel($tel,$id)
    {
        $upuser = WpIschoolUser::findOne($id);
        $upuser->tel = $tel;
        $istel = WpIschoolUser::findOne(['tel'=>$tel]);    //查询手机号是否存在
        if($istel){
            return $this->errorHandler("1035");
        }
        $uptel = $upuser->save(false);
        if($uptel){
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1036");
        }
    }

    //验证码验证接口
    public function Dxyanzheng($tel,$yzm){
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
        $redis->delete($tel);
        return true;
    }

    //修改密码
    public function actionUpdatepwd(){
        $uid = $this->post['uid'];
        $oldpasswd = $this->post['oldpwd'];  //老密码
        $newpasswd = $this->post['newPwd'];    //新密码
        $pwdt = $this->post['pwdt'];  //重复密码
        if ($newpasswd !== $pwdt){
            return $this->errorHandler("1010");
        }
        if(md5($oldpasswd) == $this->users['pwd']){
            $model = WpIschoolUser::findOne($uid);
            $model->pwd = md5($newpasswd);
            $res = $model->save(false);
            if($res>0 || $res === 0){
                return $this->formatAsjson("success");
            }else{
                return $this->errorHandler("1016");
            }
        }else{
            return $this->errorHandler("1037");
        }
    }

    //退出登录
    public  function actionLoginout(){
        if (isset(\Yii::$app->request->cookies->get('user_id')->value)){
            $user_id = \Yii::$app->request->cookies->get('user_id')->value;
            Yii::trace($user_id);
//            $res = \Yii::$app->response->getCookies()->remove($user_id);
            Yii::$app->response->cookies->remove("user_id");
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1040");
        }
    }

    //获取短信验证码
    public function getDxyzm($tel){
        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/smssdk/SmsSenderUtil.php";
        require_once $vendorpath."/lee/smssdk/SmsSingleSender.php";
        // 短信应用SDK AppID
        $appid = \Yii::$app->params['sms-app-id']; // 1400开头
        // 短信应用SDK AppKey
        $appkey = \Yii::$app->params['sms-app-key'];
        // 需要发送短信的手机号码
//        $phoneNumbers = ["21212313123", "12345678902", "12345678903"];
        // 短信模板ID，需要在短信应用中申请
        $templateId = \Yii::$app->params['sms-app-templateId'];  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请
        // 签名
        $smsSign = \Yii::$app->params['sms-app-smsSign']; // NOTE:
        // 单发短信
        try {
            $ssender = new \Qcloud\Sms\SmsSingleSender($appid, $appkey);
            //设置发送短信内容(必填)
            $param = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);
            $params = [$param];
            $result = $ssender->sendWithParam("86", $tel, $templateId,
                $params, "", "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
            $rsp = json_decode($result);
            // echo $result;
            // yii::trace($rsp->result);
            if ($rsp->result ==0) {
                $redis = self::getRedis();
                try {
                    $redis->ping();
                } catch (Exception $e) {
                    $redis = self::getRedis();
                }
                $redis->set($tel,$param,30000);
                return $this->formatAsjson("success");
            }else{
                return $this->errorHandler("1043");
            }

        } catch(\Exception $e) {
            echo var_dump($e);
        }
    }
    //根据用户ID获取用户openid
    public function getOpenid($id){
        $res = WpIschoolUser::findOne($id);
        $re = !empty($res->openid)?$res->openid:null;
        return $re;
    }





    //系统信息
    public function actionSystem(){
        $uid = $this->post['uid'];
        $model = WpIschoolGroupMessage::find()->select('title,id,content,created')->where(['in_uid'=>$uid])->orderBy("created desc")->asArray()->all();
        Yii::trace($model);
        return $this->formatAsjson($model);
    }

    //系统信息
    public function actionSystemcont(){
        $id = $this->post['id'];
        $model = WpIschoolGroupMessage::find()->select('title,content,created')->where(['id'=>$id])->asArray()->one();;
        Yii::trace($model);
        return $this->formatAsjson($model);
    }


           //上传图片
    public function Uploadimg($upimg){
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
             return $imgurl;
    }

    //上传文件
    public function Uploadfiles($upimg){
        //设置上传文件大小限制(单位b)
        $max_size=1024*10*1024;
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

        //通过手机号码获取用户名
    public function actionGetname(){
        $tel = $this->post['tel'];
        $data = [];
        $model = WpIschoolUser::findOne(['tel'=>$tel]);
        if (isset($model)) {
            $data['username'] = $model['name'];
            $data['user_img'] = $model['user_img'];
            return $this->formatAsjson($data);
        }else{
            return $this->errorHandler("1059");
        }
        
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

    protected function Formatjson($data){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
    protected function PostCurl($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $appSecret = \yii::$app->params['IM_APPSECRET']; // 开发者平台分配的 App Secret。       
        $timestamp = time()*1000; // 获取时间戳（毫秒）。
        $nonce = rand(); // 获取随机数。
        $signature = sha1($appSecret.$nonce.$timestamp); 
        //设置header
        $header = array();
        $header[] = 'App-Key:'.\yii::$app->params['IM_APPKEY'];;
        $header[] = 'Timestamp:'.$timestamp;
        $header[] = 'Nonce:'.$nonce;
        $header[] = 'Signature:'.$signature;
        $header[] = 'Content-Length:'.strlen($data);
        $header[] = 'Content-Type: application/x-www-form-urlencoded';
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
    protected function second_array_unique_bykey($arr, $key){
        $tmp_arr = array();
        $return_arr=array();
        foreach($arr as $k => $v){
            if(in_array($v[$key], $tmp_arr)){
              unset($arr[$k]); 
            }else{
              $tmp_arr[$k] = $v[$key];
              $return_arr[]= $v;
            }
        }
        return $return_arr;  
    }

    // 教师身份种类
    public function actionSubject(){
        $sql = "select id,name from wp_ischool_subject ORDER BY id asc";
        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        return $this->formatAsjson($res);
    }
}
