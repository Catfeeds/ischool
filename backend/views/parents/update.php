<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolPastudent */

$this->title = '更新家长: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '家长管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wp-ischool-pastudent-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
