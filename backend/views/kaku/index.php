<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpIschoolKakuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'EPC电话卡号查询';
?>
<div class="wp-ischool-kaku-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>-->
<!--        Html::a('Create Wp Ischool Kaku', ['create'], ['class' => 'btn btn-success'])-->
<!--        --><?//=?>
<!--    </p>-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'stuno2',
            'epc',
            'telid',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'header' => "操作",],
        ],
    ]); ?>
</div>
