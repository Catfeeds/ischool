<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolClass */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Wp Ischool Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-ischool-class-view">

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
            'sid',
            'school',
            'ctime:datetime',
            'flag',
            'level',
            'class',
        ],
    ]) ?>

</div>
