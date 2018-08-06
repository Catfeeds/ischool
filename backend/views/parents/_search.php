<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolPastudentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-pastudent-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'openid') ?>

    <?= $form->field($model, 'ctime') ?>

    <?= $form->field($model, 'stu_id') ?>

    <?php // echo $form->field($model, 'school') ?>

    <?php // echo $form->field($model, 'cid') ?>

    <?php // echo $form->field($model, 'class') ?>

    <?php // echo $form->field($model, 'tel') ?>

    <?php // echo $form->field($model, 'stu_name') ?>

    <?php // echo $form->field($model, 'ispass') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'sid') ?>

    <?php // echo $form->field($model, 'Relation') ?>

    <?php // echo $form->field($model, 'isqqtel') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
