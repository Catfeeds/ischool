<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpIschoolOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单查询';
?>
<div class="wp-ischool-order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
    <?= Html::a('导出', [strpos(\yii::$app->request->url,"?")>-1? \yii::$app->request->url.'&type=export':\yii::$app->request->url.'?type=export'], ['class' => 'btn btn-danger pull-right','target'=>"_blank"]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $arrayColumns,
    ]); ?>
</div>
