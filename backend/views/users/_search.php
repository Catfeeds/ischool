<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'tel') ?>

    <?= $form->field($model, 'openid') ?>

    <?= $form->field($model, 'last_sid') ?>

    <?php // echo $form->field($model, 'pwd') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'score') ?>

    <?php // echo $form->field($model, 'level') ?>

    <?php // echo $form->field($model, 'login_ip') ?>

    <?php // echo $form->field($model, 'last_login_ip') ?>

    <?php // echo $form->field($model, 'last_login_time') ?>

    <?php // echo $form->field($model, 'shenfen') ?>

    <?php // echo $form->field($model, 'last_stuid') ?>

    <?php // echo $form->field($model, 'last_cid') ?>

    <?php // echo $form->field($model, 'login_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
