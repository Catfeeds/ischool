<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolKaku */

//$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '卡库信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = "信息详情";
?>
<div class="wp-ischool-kaku-view">

    <h1><?= Html::encode($this->title) ?></h1>
<!--
    <p>
        <?/*= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) */?>
        <?/*= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'stuno2',
            'epc',
            'telid',
        ],
    ]) ?>

</div>
