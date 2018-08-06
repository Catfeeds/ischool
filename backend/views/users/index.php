<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpIschoolUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户信息';
?>
<div class="wp-ischool-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'tel',
            'openid',
            'last_sid',
            // 'pwd',
            // 'ctime:datetime',
            // 'score',
            // 'level',
            // 'login_ip',
            // 'last_login_ip',
            // 'last_login_time:datetime',
            // 'shenfen',
            // 'last_stuid',
            // 'last_cid',
            // 'login_time:datetime',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            		'header' => "操作",
            ],
        ],
    ]); ?>
</div>
