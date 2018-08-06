<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolKaku */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-kaku-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'stuno2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'epc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telid')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
