<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolPastudent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-pastudent-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'openid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'stu_id')->textInput() ?>

    <?= $form->field($model, 'school')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cid')->textInput() ?>

    <?= $form->field($model, 'class')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stu_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ispass')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sid')->textInput() ?>

    <?= $form->field($model, 'Relation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isqqtel')->textInput() ?>

    <?= $form->field($model, 'is_deleted')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
