<?php
\yii\base\Event::on(
		\dektrium\user\controllers\SecurityController::className(),
		\dektrium\user\controllers\SecurityController::EVENT_AFTER_LOGIN,
		function () {
			
			if(\yii::$app->user->getIdentity()['flags']!= 0) 
 			{
 				\yii::$app->user->logout();
 			}
		}
);
