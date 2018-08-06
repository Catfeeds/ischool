<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'openid') ?>

    <?= $form->field($model, 'money') ?>

    <?= $form->field($model, 'trade_no') ?>

    <?= $form->field($model, 'trade_name') ?>

    <?php // echo $form->field($model, 'paytype') ?>

    <?php // echo $form->field($model, 'ispass') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'utime') ?>

    <?php // echo $form->field($model, 'zfopenid') ?>

    <?php // echo $form->field($model, 'stuid') ?>

    <?php // echo $form->field($model, 'trans_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
