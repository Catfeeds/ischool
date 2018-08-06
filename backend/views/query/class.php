<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\controllers\UtilsController;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '学校信息';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-ischool-class-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?= Html::a('导出', [strpos(\yii::$app->request->url,"?")>-1? \yii::$app->request->url.'&type=export':\yii::$app->request->url.'?type=export'], ['class' => 'btn btn-danger pull-right','target'=>"_blank"]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $array_columns
    		
    ])
    ?>
</div>