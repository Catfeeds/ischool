<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

}
