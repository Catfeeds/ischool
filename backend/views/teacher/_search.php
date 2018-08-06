<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolTeacherSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-teacher-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tname') ?>

    <?= $form->field($model, 'sid') ?>

    <?= $form->field($model, 'school') ?>

    <?= $form->field($model, 'tel') ?>

    <?php // echo $form->field($model, 'openid') ?>

    <?php // echo $form->field($model, 'ispass') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'epc') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
