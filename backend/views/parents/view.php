<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolPastudent */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Wp Ischool Pastudents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-ischool-pastudent-view">

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
            'openid',
            'ctime:datetime',
            'stu_id',
            'school',
            'cid',
            'class',
            'tel',
            'stu_name',
            'ispass',
            'email:email',
            'sid',
            'Relation',
            'isqqtel',
        ],
    ]) ?>

</div>
