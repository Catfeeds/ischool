<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolSafecard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-safecard-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'stuid')->textInput() ?>

    <?= $form->field($model, 'info')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'yearmonth')->textInput() ?>

    <?= $form->field($model, 'yearweek')->textInput() ?>

    <?= $form->field($model, 'weekday')->textInput() ?>

    <?= $form->field($model, 'receivetime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
