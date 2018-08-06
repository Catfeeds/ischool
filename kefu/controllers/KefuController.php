<?php
namespace kefu\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use kefu\models\Im;
/**
 * Site controller
 */
class KefuController extends Controller {

	public $enableCsrfValidation = false;
	/**
	 * @inheritdoc
	 */
	public function actionIndex() {
		$result=Im::getKefuInfo();
		return $this->render('index',[
			'token'=>$result['token'],
			'appkey'=>$result['appkey'],
		]);
	}

	//发送消息数据保存
	public function actionSavesendmessage(){
		$post=Yii::$app->request->post();
		$result=Im::saveMes($post);
		return json_encode($result);
	}
	//接收消息数据保存
	public function actionSaverecmessage(){
		$post=Yii::$app->request->post();
		$result=Im::saveMesRec($post);
		return json_encode($result);
	}
	//获取历史消息
	public function actionGethismes(){
		$post=Yii::$app->request->post();
		$result=Im::getHisMes($post);
		return json_encode($result);
	}
	//获取会话列表
	public function actionGetcoverlist(){
		$post=Yii::$app->request->post();
	    $result=Im::getCoveList($post);
		return json_encode($result);
	}

}
