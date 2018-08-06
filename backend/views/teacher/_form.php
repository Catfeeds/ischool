<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\controllers\UtilsController;
use backend\models\WpIschoolTeaclass;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolTeacher */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-teacher-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'school')->textInput(['maxlength' => true,"readonly"=>"readonly"]) ?>

    <?= $form->field($model, 'tname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model,'cid')->dropDownList(UtilsController::getClasses($model->sid)) ?>
<!--    $form->field($model->classes?:new WpIschoolTeaclass(), 'cid')->dropDownList(UtilsController::getClasses($model->sid))-->
    <?= $form->field($model, 'ispass')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
