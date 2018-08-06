<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolStudentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-student-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'stuno2') ?>

    <?= $form->field($model, 'sex') ?>

    <?= $form->field($model, 'school') ?>

    <?php // echo $form->field($model, 'class') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'cid') ?>

    <?php // echo $form->field($model, 'cardid') ?>

    <?php // echo $form->field($model, 'stuno') ?>

    <?php // echo $form->field($model, 'outType') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'carCode') ?>

    <?php // echo $form->field($model, 'sid') ?>

    <?php // echo $form->field($model, 'LastTime') ?>

    <?php // echo $form->field($model, 'LastStatus') ?>

    <?php // echo $form->field($model, 'enddate') ?>

    <?php // echo $form->field($model, 'upendtime') ?>

    <?php // echo $form->field($model, 'enddatejx') ?>

    <?php // echo $form->field($model, 'upendtimejx') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
