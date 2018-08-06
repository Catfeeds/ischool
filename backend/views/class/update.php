<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolClass */

$this->title = '更新班级';
$this->params['breadcrumbs'][] = ['label' => '班级信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = '更新班级';
?>
<div class="wp-ischool-class-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
