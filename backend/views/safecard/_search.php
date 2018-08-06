<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolSafecardSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-safecard-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'stuid') ?>

    <?= $form->field($model, 'info') ?>

    <?= $form->field($model, 'ctime') ?>

    <?= $form->field($model, 'yearmonth') ?>

    <?php // echo $form->field($model, 'yearweek') ?>

    <?php // echo $form->field($model, 'weekday') ?>

    <?php // echo $form->field($model, 'receivetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
