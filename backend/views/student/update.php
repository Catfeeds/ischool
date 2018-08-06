<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolStudent */

$this->title = "更新学生";
$this->params['breadcrumbs'][] = ['label' => "学生管理", 'url' => ['index']];
$this->params['breadcrumbs'][] = '更新学生';
?>
<div class="wp-ischool-student-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'card_model'=>$card_model
    ]) ?>

</div>
