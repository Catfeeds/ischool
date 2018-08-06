<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'openid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_sid')->textInput() ?>

    <?= $form->field($model, 'pwd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ctime')->textInput() ?>


<!--    $form->field($model, 'score')->textInput()-->
<!--    $form->field($model, 'level')->textInput()-->

    <?= $form->field($model, 'login_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_login_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_login_time')->textInput() ?>

    <?= $form->field($model, 'shenfen')->dropDownList([ 'xiaozhang' => 'Xiaozhang', 'guanli' => 'Guanli', 'jiazhang' => 'Jiazhang', 'tea' => 'Tea', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'last_stuid')->textInput() ?>

    <?= $form->field($model, 'last_cid')->textInput() ?>

    <?= $form->field($model, 'login_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
