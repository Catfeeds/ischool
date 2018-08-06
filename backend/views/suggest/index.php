<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '意见反馈';
?>
<div class="wp-ischool-suggest-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'title',
            'sid',
        	'ctime:datetime',
            [
            		'class' => 'yii\grid\ActionColumn',
            		'template' => '{view} {update}',
    		],
        ],
    ]); ?>
</div>
