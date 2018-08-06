<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolSuggest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-suggest-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true,'readonly'=>'readonly']) ?>
    <?= $form->field($model, 'content')->textarea(['rows' => 6,'readonly'=>'readonly']) ?>

   <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
