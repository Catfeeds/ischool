<?php
namespace frontend\controllers;
use app\models\paUser;
use app\models\User;
use app\models\WpIschoolStudent;
use app\models\WpIschoolStuLeave;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use dosamigos\qrcode\QrCode;

require_once 'yuyinapi/AipSpeech.php';
/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout="mainsite.php";
    public $enableCsrfValidation = false;
    public $urls;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

//    public function actionQrcode($url)
//    {
//        return QrCode::png($url);    //调用二维码生成方法
//    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }
            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displaylays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
//查询openid是否存在
    public function actionCheckopid()
    {
        $post = yii::$app->request->post();
        $id = $post['userid'];
        $sql = "select * from wp_ischool_user WHERE id = :id";
        $res = \yii::$app->db->createCommand($sql,[':id'=>$id])->queryAll();
        $isopenid = $res[0]['openid'];
        if(!empty($isopenid)){
            $session = Yii::$app->session;
            $session['openid'] =$isopenid;
            $at = 0;
        }else{
            $at = 1;
        }
        return $at;
    }


//登录
    public function actionDenglu()
    {
        $session = Yii::$app->session;
        if(isset($session['name']) && $session['lifetime'] > time() && !empty($session['openid'])){
            $url = $session->get('url');
            echo "<script>window.location='$url';</script>";
        }
        if(\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model = new paUser();
//            $res = $model->findOne(['tel'=>$post['telephone'],'pwd'=>md5($post['password']),'shenfen'=>$post['role']]);
            $res = $model->findOne(['tel'=>$post['telephone'],'pwd'=>md5($post['password'])]);
            if(!empty($res)){
                $openid = $res['openid'];
                $userid = $res['id'];
                $sid = $res['last_sid'];
                $shenfen = $res['shenfen'];
                $model = paUser::findOne($userid);
                if($shenfen =="tea"){
                    $url = "/teacher/index";
                }
                if($shenfen =="guanli"){
                    $url = "/guanli/index";
                }
                if($shenfen =="jiazhang"){
                    $url = "/pastudent/index";
                }
                $model->last_login_ip =!empty($res['login_ip'])?$res['login_ip']:'127.0.0.1';
                $model->login_ip = $_SERVER["REMOTE_ADDR"];
                $model->last_login_time = !empty($res['login_time'])?$res['login_time']:time();
                $model->login_time = time();
                $result = $model->save(false);
                if($result>0 || $result===0){
                    $session['name'] = $res['name'];
                    $session['tel'] = $res['tel'];
                    $session['url'] = $url;
                    $session['lifetime'] = time()+3600;
//                    $session['role'] = $post['role'];
                    if(empty($openid)){
                        $url = \yii::$app->request->hostInfo;
                        $urls = $url."/user_id=$userid";
                        $this->urls = $urls;
//                    $info = self::actionQrcode($urls);
                        return $this->render('erweima',[
                            'userid'=>$userid,
                            'sid' =>$sid
                        ]);
                    }else{
                        $session['openid'] = $openid;
                    }
                    // echo "<script>alert('登录成功！');window.location='$url';</script>";
                    echo "<script>window.location='$url';</script>";
                }
            }else
            {
                echo "<script>alert('用户名或密码不正确！');history.go(-1);</script>";
            }
        }
        $connection  = \Yii::$app->db;
        $command = $connection->createCommand('select distinct pro from wp_ischool_school ORDER BY convert(pro USING gbk)');
        $info['pro'] = $command->queryAll();
        return $this->render('denglu',['info'=>$info]);
    }

    public function actionDenglut()
    {
        $session = Yii::$app->session;
        if(isset($session['name']) && $session['lifetime'] > time() && !empty($session['openid'])){
            $url = $session->get('url');
            echo "<script>window.location='$url';</script>";
        }
        if(\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model = new paUser();
//            $res = $model->findOne(['tel'=>$post['telephone'],'pwd'=>md5($post['password']),'shenfen'=>$post['role']]);
            $res = $model->findOne(['tel'=>$post['telephone'],'pwd'=>md5($post['password'])]);
            if(!empty($res)){
                $openid = $res['openid'];
                $userid = $res['id'];
                $sid = $res['last_sid'];
                $shenfen = $res['shenfen'];
                $model = paUser::findOne($userid);
                if($shenfen =="tea"){
                    $url = "/teacher/index";
                }
                if($shenfen =="guanli"){
                    $url = "/guanli/index";
                }
                if($shenfen =="jiazhang"){
                    $url = "/pastudent/index";
                }
                $model->last_login_ip =!empty($res['login_ip'])?$res['login_ip']:'127.0.0.1';
                $model->login_ip = $_SERVER["REMOTE_ADDR"];
                $model->last_login_time = !empty($res['login_time'])?$res['login_time']:time();
                $model->login_time = time();
                $result = $model->save(false);
                if($result>0 || $result===0){
                    $session['name'] = $res['name'];
                    $session['tel'] = $res['tel'];
                    $session['url'] = $url;
                    $session['lifetime'] = time()+3600;
//                    $session['role'] = $post['role'];
                    if(empty($openid)){
                        $url = \yii::$app->request->hostInfo;
                        $urls = $url."/user_id=$userid";
                        $this->urls = $urls;
//                    $info = self::actionQrcode($urls);
                        return $this->render('erweima',[
                            'userid'=>$userid,
                            'sid' =>$sid
                        ]);
                    }else{
                        $session['openid'] = $openid;
                    }
                    echo "<script>alert('登录成功！');window.location='$url';</script>";
                }
            }else
            {
                echo "<script>alert('用户名或密码不正确！');history.go(-1);</script>";
            }
        }
        $connection  = \Yii::$app->db;
        $command = $connection->createCommand('select distinct pro from wp_ischool_school ORDER BY convert(pro USING gbk)');
        $info['pro'] = $command->queryAll();
        return $this->render('denglut',['info'=>$info]);
    }


    public function actionGetcode()
    {
//        $appid = \yii::$app->params["APP_ID"];
//        $secret =\yii::$app->params['APP_SECRET'];

        $userid = \yii::$app->request->get("user_id");
        $sid = \yii::$app->request->get("sid");
        $appid = APPID;
        $secret = APPSECRET;
        if($sid == 56650){
            $appid = SGAPPID;
            $secret = SGAPPSECRET;
        }
//        $ur = \yii::$app->request->hostInfo;
        $ur = "http://mobile.jxqwt.cn/";
        $url = $ur."/utils/upopenid?sid=".$sid;
        $urls = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$url&response_type=code&scope=snsapi_userinfo&state=".$userid."#wechat_redirect";
        $this->redirect($urls);
    }

    public function actionWjewm(){
        $model = new paUser();
        $post['telephone'] = $_POST['userPhonwj'];
        $res = $model->findOne(['tel'=>$post['telephone']]);
        if(!empty($res)) {
            $openid = $res['openid'];
            $userid = $res['id'];
            if (substr($openid,0,6) == 'okr7Gv'){
                $sid = 56650;
            }else{
                $sid = $res['last_sid'];
            }
            return $this->render('wjewm', [
                'userid' => $userid,
                'sid' => $sid,
                'oldopid' => $openid
            ]);
        }else{
            echo "<script>alert('该手机号码尚未注册！');window.location='/site/denglu';</script>";
        }
    }

    public function actionGetwjcode()
    {
//        $appid = \yii::$app->params["APP_ID"];
//        $secret =\yii::$app->params['APP_SECRET'];

        $userid = \yii::$app->request->get("user_id");
        $sid = \yii::$app->request->get("sid");
        $oldopid = \yii::$app->request->get("oldopid");
        $appid = APPID;
        $secret = APPSECRET;
        if($sid == 56650){
            $appid = SGAPPID;
            $secret = SGAPPSECRET;
        }
//        $ur = \yii::$app->request->hostInfo;
        $ur = "http://mobile.jxqwt.cn/";
        $url = $ur."/utils/upopenid?type=".$sid.'/'.$oldopid;
        $urls = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$url&response_type=code&scope=snsapi_userinfo&state=".$userid."#wechat_redirect";
        \yii::trace($urls);
        $this->redirect($urls);
    }

    public function actionGetopenid()
    {
        $code = \yii::$app->request->get("code");
        $sid = \yii::$app->request->get("sid");
        $appid = APPID;
        $secret = APPSECRET;
        if($sid == 56650){
            $appid = SGAPPID;
            $secret = SGAPPSECRET;
        }
        $data = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code');
        $data = json_decode($data);
        $openid = $data->openid;
        \yii::trace($code);\yii::trace($sid);\yii::trace($openid);
        $userid =  \yii::$app->request->get("state");
        $isopenid = WpIschoolUser::findOne(['openid'=>$openid]);
        if ($isopenid) {
            $message = "该微信号已经注册过，请勿重复注册！";
            return $this->render("message",['message'=>$message]);
        }
        $model = WpIschoolUser::findOne($userid);
        if($model)
        {
            $model->openid = $openid;
            $model->save(false);
            $message = "绑定成功";
        }
        else $message = "绑定失败";
        return $this->render("message",['message'=>$message]);

    }


    /*    public function actionGetopenid()
        {
            $code = $_GET["code"];
            $appid = \yii::$app->params["APP_ID"];
            $secret =\yii::$app->params['APP_SECRET'];
            $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
            $oauth2 = $this->getJson($oauth2Url);
            $openid = $oauth2['openid'];
            return $openid;
        }*/
    function getJson($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }
//忘记密码生成二维码
    public function actionWjqrcode()
    {
        $user_id = \yii::$app->request->get("userid");
        $sid = \yii::$app->request->get("sid");
        $oldopid = \yii::$app->request->get("oldopid");
        $ur = \yii::$app->request->hostInfo;
        $url = $ur."/site/getwjcode?user_id=".$user_id."&sid=".$sid."&oldopid=".$oldopid;
        \yii::trace($url);
        return QrCode::png($url);    //调用二维码生成方法
    }
//注册后第一次登录生成二维码
    public function actionQrcode()
    {
        $user_id = \yii::$app->request->get("userid");
        $sid = \yii::$app->request->get("sid");
        $ur = \yii::$app->request->hostInfo;
        $url = $ur."/site/getcode?user_id=".$user_id."&sid=".$sid;
        \yii::trace($url);
        return QrCode::png($url);    //调用二维码生成方法
    }
    //未登录绑定学生自动生成二维码
    public function actionBdqrcode()
    {
        $stuno2 = \yii::$app->request->get("stuno2");
        $url = $urls = 'http://mobile.jxqwt.cn/information/smjzurl?stuno2='.$stuno2;
        \yii::trace($url);
        return QrCode::png($url);    //调用二维码生成方法
    }
    //注册
    public function actionZhuce()
    {
        $session = Yii::$app->session;
        if(isset($session['name']) && $session['lifetime'] > time()){
            $url = $session->get('url');
            echo "<script>window.location='$url';</script>";
        }
        if(\Yii::$app->request->isPost)
        {
            $post = \Yii::$app->request->post();
            $model = new paUser();
            $res = $model->findOne(['tel'=>$post['telephone']]);
            if(!empty($res)){
                echo "<script>alert('该手机号码已经被注册！');window.location ='/site/denglu';</script>";
            }else{
                $model = new paUser();
                $model->name = $post['telephone'];
                $model->tel = $post['telephone'];
                $model->pwd = md5($post['password']);
                $model->ctime = time();
                $model->last_sid = $post['School'];
                $model->shenfen = $post['role'];
                $result = $model->save(false);
                if($result>0 || $result===0){
                    echo "<script>alert('注册成功,请登录！');window.location='/site/denglu';</script>";
                }else{
                    echo "<script>alert('注册失败！');history.go(-1);</script>";
                }
            }
        }
    }

    //退出
    public function actionLoginout(){
        $session = Yii::$app->session;
        unset($session['name']);
        unset($session['tel']);
        unset($session['url']);
        unset($session['openid']);
//        unset($session['role']);
//        $path = Yii::$app->request->hostInfo;
        $path = "http://pc.jxqwt.cn/";
        // echo "<script>alert('退出成功！');window.location = '$path';</script>";
        echo "<script>window.location = '$path';</script>";
    }

    /**从数据库获取城市信息*/
    public function actionGetcity(){
        $pname = \yii::$app->request->post('pro');
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select distinct city from wp_ischool_school where pro='".$pname."' ORDER BY convert(city USING gbk)");
        $post =  json_encode($command->queryAll());
        return $post;
    }
    /**从数据库获取乡镇信息*/
    public function actionGetcounty(){
        $pname = \yii::$app->request->post('city');
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select distinct county from wp_ischool_school where city='".$pname."' ORDER BY convert(county USING gbk)");
        $post =  json_encode($command->queryAll());
        return $post;
    }
//学校类型 高中初中小学
    public function actionGettype(){
        $pname = \yii::$app->request->post('county');
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select distinct schtype from wp_ischool_school where county='".$pname."' ORDER BY convert(schtype USING gbk)");
        $post =  json_encode($command->queryAll());
        return $post;
    }

    //学校名字
    public function actionGetschool(){
        $area = \yii::$app->request->post('county');
        $cname = \yii::$app->request->post('schtype');
        $connection  = Yii::$app->db;
//        $command = $connection->createCommand("select id,name from wp_ischool_school where schtype='".$cname."' and county='".$area."' ORDER BY convert(name USING gbk)");
        $command = $connection->createCommand("select id,name from wp_ischool_school where county='".$area."' ORDER BY convert(name USING gbk)");
        $post =  json_encode($command->queryAll());
        return $post;
    }

    public function actionGetclass(){
        $sid = \yii::$app->request->post('sid');
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select id,name from wp_ischool_class where sid='".$sid."' ORDER BY convert(name USING gbk)");
        $post =  json_encode($command->queryAll());
        return $post;
    }
//二维码绑定学生
    public function actionBdxs(){
        $connection  = \Yii::$app->db;
        $command = $connection->createCommand('select distinct pro from wp_ischool_school ORDER BY convert(pro USING gbk)');
        $info['pro'] = $command->queryAll();
        return $this->renderPartial('bdxs',[
            'info'=>$info
        ]);
    }

    /**家长关注孩子*/
    public function actionAddchild(){
        $post =  \YII::$app->request->post();
        $cid  = $post['cid'];               //班级ID
        $openid = $post['openid'];          //家长openid
        $student = $post['student'];        //学生名字

        $res = $this->isHasStudent($cid,$student);      //检查班级有无此人
        $at ="";
        if(!empty($res)){
            $at = $res[0]['stuno2'];
        }else{
            $at = 1;
        }
        return $at;
    }

    /*  检测某班级是否存在某学生 */
    public function isHasStudent($cid,$name){
        $connection  = \Yii::$app->db;
        $command = $connection->createCommand("select id,stuno2 from wp_ischool_student where cid='".$cid."' and name ='".$name."' ORDER BY convert(name USING gbk)");
        $res =  $command->queryAll();
        return $res;
    }

    public function actionLeave(){
        // header('Content-Type: application/octet-stream;'); //设置内容类型
        // header('Content-Type: application/json;'); //设置内容类型
        // header('Content-Disposition: attachment;method => POST');
        //http://pc.jxqwt.cn/site/leave?stuno=e20051203711021616606b25  http://pc.jxqwt.cn/site/leave?School=XXXXXX;Address=YY;ID=4058810109
          //模拟网址 http://mobile.jxqwt.cn/upload/photos/%E6%AD%A3%E6%A2%B5%E9%AB%98%E7%BA%A7%E4%B8%AD%E5%AD%A6/%E4%BF%A1%E6%81%AF%E9%83%A8/%E8%B4%B9%E9%87%91%E9%87%91.jpg
        $streamData =file_get_contents('php://input');
//        $streamData = "stuno=e20051203711021616606b25";School=XXXXXX&Address=YY&ID=4058810109
//        $streamData = "School=XXXXXX;Address=YY;ID=4058810109";
        $arr = explode("=",$streamData);
        // $streamData = $_POST;
        // if(empty($streamData)) {
        //     $streamData = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
        // }elseif(empty($streamData)){
        //     $streamData = file_get_contents('php://input');
        // }
//        $card_no = substr($streamData,6);
        $str =  $arr[3];
        $newstr = "";
        for($i=1;$i<=strlen($str)/2;$i++){
            $newstr.=substr($str, -$i*2, 2);
        }
        $newstr = hexdec($newstr);
        $card_no = str_pad($newstr,10,"0",STR_PAD_LEFT);
        $arrsid = explode(";", $arr[1]);
        $sid = intval($arrsid[0]);
        Yii::trace($arr);
        Yii::trace($card_no);
        Yii::trace($sid);
        // $ret = new \stdclass();
        // $ret->name = "liming";
        // $ret->img = "[img]";
        // $info = json_encode($ret);
        // $info = str_replace("[img]", file_get_contents('http://mobile.jxqwt.cn/upload/photos/%E6%AD%A3%E6%A2%B5%E9%AB%98%E7%BA%A7%E4%B8%AD%E5%AD%A6/%E4%BF%A1%E6%81%AF%E9%83%A8/%E8%B4%B9%E9%87%91%E9%87%91.jpg'), $info);
        // var_dump($ret);

//        $img = 'http://mobile.jxqwt.cn/upload/photos/%E6%AD%A3%E6%A2%B5%E9%AB%98%E7%BA%A7%E4%B8%AD%E5%AD%A6/%E4%BF%A1%E6%81%AF%E9%83%A8/%E8%B4%B9%E9%87%91%E9%87%91.jpg';
//        $imageData = base64_encode(file_get_contents($img));
//        $image_info = getimagesize($img);
//        $base64_image_content = "data:{$image_info['mime']};base64," . chunk_split($imageData);
//        echo $base64_image_content;
//        echo "\n\n\n\t\t\t";
//        exit;
        if(!empty($card_no)) {
//            $sql = "select stu_id from wp_ischool_student_card where card_no = '$card_no'  ORDER BY ctime DESC limit 1";
            $sql = "select stu_id,s.is_zoudu,s.school,s.class,s.name from wp_ischool_student_card c LEFT JOIN wp_ischool_student s ON c.stu_id=s.id where c.card_no = '$card_no' AND s.sid = $sid  ORDER BY c.ctime DESC limit 1";
            $res = Yii::$app->db->createCommand($sql)->queryOne();
            Yii::trace($res);
            if(!empty($res)){
                Yii::trace($res['stu_id']);
                $model = WpIschoolStudent::findOne($res['stu_id']);
                if ($model){
                    $avatar = "http://pc.jxqwt.cn/upload/photos/".$model['school']."/".$model['class']."/".$model['name'].".jpg";
                    $imgurl = "http://pc.jxqwt.cn/img/zwtp.jpg";    //暂无图片模版
                    $img = !empty($avatar)?$avatar:$imgurl;
                    $imageData = base64_encode(file_get_contents($img));
                    // $image_info = getimagesize($img);
                    // $base64_image_content = "data:{$image_info['mime']};base64," . chunk_split($imageData);
                    // echo $base64_image_content;
                    // echo "\n\n\n\r\r\r";
                    $models = WpIschoolStuLeave::find()->where(['stu_id'=>$model['id'],'flag'=>1])->orderBy('id desc')->one();
                    $ret = new \stdclass();
                    // 你的 APPID AK SK
                    define('APP_ID', '10618424');
                    define('API_KEY', 'n8Gzc9hCIry4T3hro8j1argO');
                    define('SECRET_KEY', 'IPE8NbpwBjeQ0dxR4veyAsud81m4ixyH');
                    $aipSpeech = new \AipSpeech(APP_ID, API_KEY, SECRET_KEY);
                    Yii::trace($models);
                    if (!empty($models) && $models['stop_time']>time()) {
                        // echo $model['name'];
                        // echo "\n\n\n\r\r\r";
                        // echo date('Y-m-d H:i:s',$models['ctime']);
                        //  echo "\n\n\n\r\r\r";
                        // echo date('Y-m-d H:i:s',$models['begin_time']);
                        //  echo "\n\n\n\r\r\r";
                        // echo date('Y-m-d H:i:s',$models['stop_time']);
                        //  echo "\n\n\n\r\r\r";
                        // echo $models['reason'];
                        //  echo "\n\n\n\r\r\r";
                        // echo date('Y-m-d H:i:s',$models['oktime']);
                        //  echo "\n\n\n\r\r\r";
                        $yuyin = $model['name'] . "学生请假";
                        $okopenid = $models['okopenid'];
                        $teacher = $this->getTeacher($okopenid);
                        Yii::trace($teacher);

                        // echo $result;
                        // echo "\n\n\n\r\r\r";
    /*                    $a = mt_rand(1, 4);
                        switch ($a) {
                            case 1:
                                $flag = 10;         //走读生
                                break;
                            case 2:
                                $flag = 11;         //走读请假
                                break;
                            case 3:
                                $flag = 20;         //在校生
                                break;
                            case 4:
                                $flag = 21;         //在校生请假
                                break;
                        }*/
                        if ($res['is_zoudu'] == "20"){
                            $flag = "21";         //在校生请假
                        }elseif ($res['is_zoudu'] == "10"){
                            $flag = "11";         //走读请假
                        }
                        $ret->ctime = date('Y-m-d H:i:s', $models['ctime']);
                        $ret->begin_time = date('Y-m-d H:i:s', $models['begin_time']);
                        $ret->stop_time = date('Y-m-d H:i:s', $models['stop_time']);
                        $ret->reason = $models['reason'];
                        $ret->teacher = $teacher;
                        // $ret->yuyin = "[yuyin]";
                        // $info = str_replace("[img]", file_get_contents($img), $info);
                        // $info = str_replace("[yuyin]", $result, $info);
                        //exit;
                    }else{
                        if ($res['is_zoudu'] == "20"){
                            $sy = "在校生";         //在校生请假
                        }elseif ($res['is_zoudu'] == "10"){
                            $sy = "走读生";         //走读请假
                        }
                        $flag = $res['is_zoudu'];         //在校生或走读生
                        $yuyin = $model['name'] .$sy;
                    }
                    $result = $aipSpeech->synthesis($yuyin, 'zh', 1, array(
                        'vol' => 5,
                    ));
                    $result = base64_encode($result);
                    $ret->flag = $flag;
                    $ret->school = $model['school'];
                    $ret->class = $model['class'];
                    $ret->name = $model['name'];
                    $ret->img = $imageData;
                    $ret->yuyin = $result;
                    $info = json_encode($ret);
                    Yii::trace($info);
                    return $info;
                }
            }
        }
    }

    public function base64EncodeImage($image_file) {
        $base64_image = '';
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'rwx'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }

    //根据openid 获取用户姓名
    private function getTeacher($openid){
        $model = paUser::findOne(['openid'=>$openid]);
        return $model['name'];
    }


}
?>
