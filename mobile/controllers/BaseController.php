<?php
namespace mobile\controllers;

use mobile\models\WpIschoolPicschool;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use mobile\models\WpIschoolRolePurview;
use mobile\models\WpIschoolPurview;
use mobile\models\WpIschoolUserRole;
use mobile\models\WpIschoolAccessToken;
use mobile\models\WpIschoolTeacher;
use mobile\models\WpIschoolPastudent;
use mobile\models\WpIschoolUser;
use mobile\controllers\InformationController;
use yii\helpers\Url;
/**
 * Base controller
 */
class BaseController extends Controller {
	public $openid,$sid;
	public function init()
	{
		
		$openid = \yii::$app->request->get("openid");
		$sid = \yii::$app->request->get("sid");
		if($openid && !$sid) {
			$ret = WpIschoolUser::findOne(['openid'=>$openid]);
			if(!$ret) $sid = 1;
			else 	$sid = $ret['last_sid'];
		}
		if($openid && $sid)
		{
			$sid = intval($sid);
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
					'name' => 'openid',
					'value' => $openid,
					'expire'=>time()+3600 * 24 *30
			]));
			$cookies->add(new \yii\web\Cookie([
					'name' => 'sid',
					'value' => $sid,
					'expire'=>time()+3600 * 24 *30
			]));
		}
		if(!$openid || !$sid)
		{
	         $openid = \yii::$app->request->cookies->get("openid")->value;
	         $sid = \yii::$app->request->cookies->get("sid")->value;
			 $sid = intval($sid);
		}


// 		if(!$openid || !$sid){
// 			 $urls = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
// 			 // http://mobile.jxqwt.cn/information/dosmaddchild?stuno2=T567585582005&code=061VP8cH1KMlT50uglcH16tecH1VP8cp&state=123
// 			 preg_match_all("/(?:code=)(.*)(?:&)/i",$urls, $matches); 
// var_dump($urls);exit;
// 			$code=$matches[1];  
// 	        $stuno2=\yii::$app->request->get("stuno2");
// 	        $sid = \yii::$app->request->get("sid"); //sid信息
//             $stuno2=\yii::$app->request->get("stuno2");

//             var_dump($urls);
//             // $url = Url::toRoute(['information/smjzurl','stuno2'=>$stuno2]);
//             if (empty($code)) {
//             	return $this->redirect('http://mobile.jxqwt.cn/information/smjzurl?stuno2='.$stuno2);
//             }else{
// 	   		 	return $this->redirect('http://mobile.jxqwt.cn/information/smjzurl?stuno2='.$stuno2.'&sid='.$sid.'&code='.$code);
//             // exit;                 
//             }
//         }


		// if(!$openid || !$sid)  exit("参数错误");
		
		$this->openid = $openid;
		$this->sid = $sid;
		\yii::$app->view->params['openid'] = $openid;
		\yii::$app->view->params['sid'] = $sid;
		\yii::$app->view->params['baseparams'] = "openid=".$openid."&sid=".$sid;
		\yii::$app->view->params['homepage'] = "智慧校园";
	}
	public function beforeAction($action)
	{
		//if (Yii::$app->user->isGuest) return $this->redirect("/user/login")->send();
		return true;
		if (parent::beforeAction($action)) {
			$permission = \yii::$app->controller->route;
			if (\Yii::$app->user->can($permission)) {
				return true;
			}
			else
				throw new ForbiddenHttpException();
		} else {
			throw new  ForbiddenHttpException();
		}
	}
	static function saveAccessList($authId=null,$sid){
		return   $_SESSION['_ACCESS_LIST']=self::getAccessList($authId,$sid);

	}
	/**
	 * 取得当前认证号的所有权限列表
	 */
	static public function getAccessList($openid,$sid) {
		// Db方式权限数据
		$sql="SELECT name FROM wp_ischool_purview WHERE id IN (SELECT pid from wp_ischool_role_purview where rid in (select rid from wp_ischool_user_role where openid='".$openid."' and sid='".$sid."'))";
		$access=WpIschoolRolePurview::findBySql($sql)->asArray()->all();
		$a=array();
		foreach($access as $k=>$v)
		{
			$a[$v['name']]=$v['name'];
		}
		$access=$a;
		return $access;
		 
	}
	//检查当前操作是否需要认证
	function checkAccess($fun) {
		//如果项目要求认证，并且当前模块需要认证，则进行权限认证
		if(!isset($_SESSION['_ACCESS_LIST'])) self::saveAccessList($this->openid,$this->sid);
		if(empty($_SESSION['_ACCESS_LIST'][$fun]))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	protected function getUid($type=null){
		if($type==null){
			$openid=\yii::$app->view->params['openid'];
		}else{
			$openid=$type;
		}		
		$res=WpIschoolUser::findOne(['openid'=>$openid]);
		return $res['id'];
	}
	//微信验证信息
	function Jssdk(){
		$APPID = APPID;
		$APPSECRET = APPSECRET;
		/*
		$sqls="select * from wp_ischool_access_token limit 0,1";
		$my_token=WpIschoolAccessToken::findBySql($sqls)->asArray()->all();

		$now = time();
		$ACC_TOKEN="";
		
		if(($now-$my_token[0]['last_time'])<1800){  //微信客户端认证access_token是否超过两小时，是就重新去微信抓去
			$ACC_TOKEN=$my_token[0]['access_token'];
		}else{
			$TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;
			$json=file_get_contents($TOKEN_URL);
			$result=json_decode($json);
			$ACC_TOKEN=$result->access_token;
			$d= WpIschoolAccessToken::findOne($my_token[0]['id']);
			$d->access_token=$ACC_TOKEN;
			$d->last_time=time();
			$res=$d->save(false);

	 	}*/
		$TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;
		$redis = new \redis();
                $result = $redis->connect('127.0.0.1', 6379);
                if ($result) {
                        $acc_token = $redis->get("my_access_token");
                        if (!$acc_token) {
                                $json = file_get_contents($TOKEN_URL);
                                $result = json_decode($json);
                                $acc_token = $result->access_token;
                                $redis->set("my_access_token", $acc_token, 7100);
                        }

                } else {
                        $json = file_get_contents($token_url);
                        $result = json_decode($json);
                        $acc_token = $result->access_token;
                }

                $ACC_TOKEN =  $acc_token;


		$data = json_decode(file_get_contents("./json/jsapi_ticket.json"));
		if ($data->expire_time < time()) {
			// 如果是企业号用以下 URL 获取 ticket
			// $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$ACC_TOKEN."&type=jsapi";
			$res = json_decode($this->httpGet($url));
			$ticket = $res->ticket;
			if ($ticket) {
				$data->expire_time = time() + 7000;
				$data->jsapi_ticket = $ticket;
				$fp = fopen("./json/jsapi_ticket.json", "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		} else {
			$ticket = $data->jsapi_ticket;
		}

		$timestamp = time();
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$urll = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$nonceStr = $this->createNonceStr();
		$string = "jsapi_ticket=".$ticket."&noncestr=".$nonceStr."&timestamp=".$timestamp."&url=".$urll."";
		$signature = sha1($string);

		$signPackage = array(
				"appId"     => $APPID,
				"nonceStr"  => $nonceStr,
				"timestamp" => $timestamp,
				"url"       => $urll,
				"signature" => $signature,
				"rawString" => $string
		);
		return $signPackage;
	}
	function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);

		$res = curl_exec($curl);
		curl_close($curl);

		return $res;
	}
         /**
        * @param $url
        * @param $data
        * @return int
        * 做post请求后立即返回避免等待
        */
       function asynBroad($url,$data){
           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, 1);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
           curl_setopt($ch, CURLOPT_TIMEOUT, 1);//1秒后立即执行
           curl_exec($ch);
           curl_close($ch);

       }
       /**
	 * Ajax方式返回数据到客户端
	 *
	 * @access protected
	 * @param mixed $data
	 *        	要返回的数据
	 * @param String $type
	 *        	AJAX返回数据格式
	 * @return void
	 */
	protected function ajaxReturn($data, $type = '') {
            if (empty ( $type ))
                    $type = C ( 'DEFAULT_AJAX_RETURN' );
            switch (strtoupper ( $type )) {
                    case 'JSON' :
                            // 返回JSON数据格式到客户端 包含状态信息
                            header ( 'Content-Type:application/json; charset=utf-8' );
                            exit ( json_encode ( $data ) );
                    case 'XML' :
                            // 返回xml格式数据
                            header ( 'Content-Type:text/xml; charset=utf-8' );
                            exit ( xml_encode ( $data ) );
                    case 'JSONP' :
                            // 返回JSON数据格式到客户端 包含状态信息
                            header ( 'Content-Type:application/json; charset=utf-8' );
                            $handler = isset ( $_GET [C ( 'VAR_JSONP_HANDLER' )] ) ? $_GET [C ( 'VAR_JSONP_HANDLER' )] : C ( 'DEFAULT_JSONP_HANDLER' );
                            exit ( $handler . '(' . json_encode ( $data ) . ');' );
                    case 'EVAL' :
                            // 返回可执行的js脚本
                            header ( 'Content-Type:text/html; charset=utf-8' );
                            exit ( $data );
                    default :
                            header ( 'Content-Type:application/json; charset=utf-8' );
                            exit ( json_encode ( $data ) );
            }
	}
	function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
        /**
        * @param $sid
        * return 所有用户
        */
        function getAllUser($sid){
           $allUserArr=array();
           $this->getAllTeacher($sid,$allUserArr);
           $this->getAllParents($sid,$allUserArr);
           return $allUserArr;
        }
        
        /**
        * @param $sid
        * return 所有老师
        */
        function getAllTeacher($sid,&$teaArr){
           $sql="select distinct openid from wp_ischool_teacher where sid=".$sid;
           $teachers = WpIschoolTeacher::findBySql($sql);           
           foreach($teachers as $v){
               $teaArr[] = $v['openid'];
           }
           return 0;
        }
          /**
        * @param $sid
        * return 所有家长
        */
        function getAllParents($sid,&$parArr){
           $sql="select distinct openid from wp_ischool_pastudent where sid=".$sid;
           $parents = WpIschoolPastudent::findBySql($sql);
           foreach($parents as $v){
               $parArr[] = $v['openid'];
           }
           return 0;
        }


	//获取学校图片信息
	public function getSchoolPics($sid){
		$toppic =WpIschoolPicschool::find()->select('toppic')->where(['schoolid'=> $sid])->asArray()->all();
		if($toppic){
			return $toppic[0]['toppic'];
		}else{
			return URL_PATH."/upload/syspic/msg.jpg";
		}
	}
	    //文档下载
    public function actionUpload(){
          header("Content-type:text/html;charset=utf-8");
        $time=$_GET['time'];
        if(time()-$time>30){
            exit();
        }
        $file_name = $_GET['url'];
          // var_dump($file_name);die;
        $name = $_GET['name'];

        //$newname = $name.".".pathinfo($file_name,PATHINFO_EXTENSION);
         $newname = substr($file_name, strrpos($file_name, '/')+1);

        //        $file_path=$file_sub_path.$file_name;
        $file_path='/data/web/ischool/frontend/web'.$file_name;
         // var_dump($file_path);die;
        //首先要判断给定的文件存在与否
        if(!file_exists($file_path)){
            echo "没有该文件文件";
            return ;
        }
        $fp=fopen($file_path,"r");
        $file_size=filesize($file_path);
        //下载文件需要用到的头
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length:".$file_size);
        Header("Content-Disposition: attachment; filename=".$newname);
        $buffer=1024;
        $file_count=0;
        //向浏览器返回数据
        while(!feof($fp) && $file_count<$file_size){
            $file_con=fread($fp,$buffer);
            $file_count+=$buffer;
            echo $file_con;
        }
        fclose($fp);
    }
    protected function PostCurl($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置header
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($data)));
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

    protected function getPostInfo($arrInfo){
        $url="http://60.205.148.191:8080/yqsh_third/api/onecard/message";
        //申请商户的时候生成的key
        $key="44EC6C5C17BA74D1759AB0AEB782E4EE";
        //第三方代码
        $thirdCode='510102180306020203';
        $arrInfo['thirdCode']=$thirdCode;
        $new_array=$arrInfo;
        if($new_array['name']!=''){
            unset($new_array['name']);
        }
        $mac=strtoupper(md5(implode('',$new_array).$key));          
        $arrInfo['mac']=$mac;       
        $data=json_encode($arrInfo , JSON_UNESCAPED_UNICODE);
        $result=$this->PostCurl($url,$data);
        return $result;
    }
}



