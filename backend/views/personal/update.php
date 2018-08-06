<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolPastudent */

$this->title = 'Update Wp Ischool Pastudent: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Wp Ischool Pastudents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wp-ischool-pastudent-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
