<?php

namespace mobile\controllers;
use mobile\models\WpIschoolUser;
use Yii;

class UtilsController extends \yii\web\Controller
{
	public static $parms_utis;
	public $enableCsrfValidation = false;
        public function actionIndex(){
                return $this->render('index');
        }
	public function actionUploadimg()
	{
		$childDir = date("y/m/d");
		$base_path = \yii::$app->basePath."/web/";
		$upload= $base_path."/upload/picture/".$childDir;
		if(!file_exists($upload))
		{
			mkdir($upload,0755,true);
		}
		$file_name = "/".uniqid().".jpeg";
		$path = $upload.$file_name;
		$base64_str = \yii::$app->request->post("data");
		$ifp = fopen( $path, "wb" );
		fwrite( $ifp, base64_decode( $base64_str) );
		fclose( $ifp );
		$a="./upload/picture/".$childDir.$file_name;
		$a=ltrim($a,".");
		$a = $a;
		\Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
		return array('msg'=>"success",'file_path'=>$a,'success'=>true);
	}
	
	public function actionUpopenid()
	{
		$code = \yii::$app->request->get("code");
		$sid = \yii::$app->request->get("sid");
		$type = \yii::$app->request->get("type");
        if (!empty($type)){
            $res = explode("/",$type);
            $sid = $res[0];
            $oldopid = $res[1];
        }
		$appid = APPID;
		$secret = APPSECRET;
		if($sid == 56650){
			$appid = SGAPPID;
			$secret = SGAPPSECRET;
		}
        $data = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code');
        $data = json_decode($data);
        $openid = $data->openid;
        if (!empty($oldopid)){
        	if($oldopid == $openid){
	            $message = "您的身份信息验证成功，请重置您的密码信息！";
	            return $this->render("czmm",[
	                'message'=>$message,
	                'openid'=> $openid
	            ]);
        	}else{
                $message = "您的身份信息没有验证成功，请认真核实您的手机号信息！";
                return $this->render("message",['message'=>$message]);
        	}
        }
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
	
	public function actionGetopenid()
	{
		$sid=\yii::$app->request->get("sid");
		$APPID = APPID;
		$APPSECRET = APPSECRET;
		$session = \Yii::$app->session;
		if($sid == 56650){
			$APPID = SGAPPID;
			$APPSECRET = SGAPPSECRET;
			 // session_start();			
            $session['school_id']=$sid;
			 // self::$parms_utis = $sid;
		}else{
			if(!empty($session['school_id'])){
				unset($session['school_id']);
			}
			
		}
		$code = \yii::$app->request->get("code");
		$data = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$APPID.'&secret='.$APPSECRET.'&code='.$code.'&grant_type=authorization_code');
		$data = json_decode($data);
		$openid = $data->openid;
//		var_dump($data);exit();
		$cookies = \Yii::$app->response->cookies;
		$cookies->add(new \yii\web\Cookie([
				'name' => 'openid',
				'value' => $openid,
				'expire'=>time()+3600 * 24 *30
		]));
		$ret = WpIschoolUser::findOne(['openid'=>$openid]);
		if(!$ret) $sid = 1;
		else 
		{
			$sid = $ret['last_sid'];
		}
		//$access_token = $data->access_token;
		$cookies->add(new \yii\web\Cookie([
				'name' => 'sid',
				'value' => $sid,
				'expire'=>time()+3600 * 24 *30
		]));
		$state = \yii::$app->request->get("state");
		$url_path = str_replace("FF", "/", $state);
		return $this->redirect($url_path);
	}
        
	public function actionChecksangao(){
		$tel = \yii::$app->request->post("phyid");
		$telephone = sprintf("%'010s", $tel);
		$result = \yii::$app->db->createCommand("select b.* from wp_ischool_student_card as a,wp_ischool_student as b where a.stu_id = b.id and a.card_no = :card_no",[":card_no"=>$telephone])->queryOne(); 
		\Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
		if($result)
		{
			$number = \yii::$app->db->createCommand("select count(1) from huanleyuan where phyid = :card_no",[":card_no"=>$telephone])->queryScalar();
			\yii::$app->db->createCommand()->insert('huanleyuan', [
    				'phyid' => $telephone,
			])->execute();			
			
			$info = [];
			$info['school'] = $result['school'];
			$info['class'] = $result['class'];
			$info['name'] = $result['name'];
			$info['number'] = intval($number);
			return ['Status'=>0,'Info'=>$info];
		}else 
		{
			return ['Status'=>1001,'Message'=>'卡片信息有误，请核对后重新刷卡！'];
		}

		
	}
        public function actionUploadclassimgs()
	{       $sid=\yii::$app->request->get("sid");
                $cid=\yii::$app->request->get("cid");
		$childDir = date("y/m/d");
		$base_path = \yii::$app->basePath."/web/";
		$upload= $base_path."/upload/photos/".$sid."/".$cid."/".$childDir;
		if(!file_exists($upload))
		{
			mkdir($upload,0755,true);
		}
		$file_name = "/".uniqid().".jpeg";
		$path = $upload.$file_name;
		$base64_str = \yii::$app->request->post("data");
		$ifp = fopen( $path, "wb" );
		fwrite( $ifp, base64_decode( $base64_str) );
		fclose( $ifp ); 
		$a="./upload/photos/".$sid."/".$cid."/".$childDir.$file_name;
		$a=ltrim($a,".");
		$a = $a;
		\Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
		return array('msg'=>"success",'file_path'=>$a,'success'=>true);
	}

	public function actionCzmm(){
	    if (\yii::$app->request->isPost){
            $newpasswd = \yii::$app->request->post('newPwd');    //新密码
            $openid = \yii::$app->request->post('openid');
            $model = WpIschoolUser::findOne(['openid'=>$openid]);
            $model->pwd = md5($newpasswd);
            $res = $model->save(false);
            if($res>0 || $res === 0){
                $message = "密码重置成功，请返回电脑端登录！";
                return $this->render("message",['message'=>$message]);
            }else{
                $message = "密码重置失败！";
                return $this->render("message",['message'=>$message]);
            }
        }
	    return $this->render('czmm');
    }

}
