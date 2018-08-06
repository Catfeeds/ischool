<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'openid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'money')->textInput() ?>

    <?= $form->field($model, 'trade_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'trade_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paytype')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ispass')->textInput() ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'utime')->textInput() ?>

    <?= $form->field($model, 'zfopenid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stuid')->textInput() ?>

    <?= $form->field($model, 'trans_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
