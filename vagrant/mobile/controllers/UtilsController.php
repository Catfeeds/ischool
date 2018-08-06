<?php

namespace mobile\controllers;
use mobile\models\WpIschoolUser;


class UtilsController extends \yii\web\Controller
{
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
                $data = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx8c6755d40004036d&secret=bb0e9a8a2a7cb366b57d2db1b66e24fc&code='.$code.'&grant_type=authorization_code');
                $data = json_decode($data);
                $openid = $data->openid;
		$userid =  \yii::$app->request->get("state");
		$model = WpIschoolUser::findOne($userid);
		if($model)
		{
			$model->openid = $openid;
			$model->save(false);
			$message = "绑定成功";
			//return $this->redirect("http://pc.jxqwt.cn/site/denglu");
		}
		else $message = "绑定失败";
		return $this->render("message",['message'=>$message]);

	}
	
	public function actionGetopenid()
	{
		$code = \yii::$app->request->get("code");
		$data = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx8c6755d40004036d&secret=bb0e9a8a2a7cb366b57d2db1b66e24fc&code='.$code.'&grant_type=authorization_code');
		$data = json_decode($data);
		$openid = $data->openid;
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
}
