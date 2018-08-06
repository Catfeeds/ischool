<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolOrderck */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-orderck-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'sfbk')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
