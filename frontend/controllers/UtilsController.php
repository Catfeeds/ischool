<?php

namespace frontend\controllers;


class UtilsController extends \yii\web\Controller
{
	public $enableCsrfValidation = false;
    public function actionIndex()
    {
        return $this->render('index');
    }
	public function actionUploadimg()
	{
		$childDir = date("y/m/d");
		$base_path = \yii::$app->basePath."/web/";
		$upload= $base_path."/ischool/upload/picture/".$childDir;
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
		$a="./ischool/upload/picture/".$childDir.$file_name;
		$a=ltrim($a,".");
		$a = $a;
		\Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
		return array('msg'=>"success",'file_path'=>$a,'success'=>true);
	}
}
