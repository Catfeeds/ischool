<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolStudent */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Wp Ischool Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-ischool-student-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'stuno2',
            'sex',
            'school',
            'class',
            'address',
            'ctime:datetime',
            'cid',
            'cardid',
            'stuno',
            'outType',
            'type',
            'carCode',
            'sid',
            'LastTime:datetime',
            'LastStatus',
            'enddatepa',
            'upendtimepa:datetime',
            'enddatejx',
            'upendtimejx:datetime',
            'enddateqq',
            'upendtimeqq:datetime',
            'enddateck',
            'upendtimeck:datetime',
        ],
    ]) ?>

</div>
