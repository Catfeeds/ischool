<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\controllers\UtilsController;
use kartik\depdrop\DepDrop;
/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolSchoolSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-school-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'pro')->dropDownList(UtilsController::getProvs(),['id'=>"pro-id"]) ?>

    <?= $form->field($model, 'city')->widget(DepDrop::classname(), [
     		'options' => ['id'=>'city-id'],
     		'pluginOptions'=>[
         		'depends'=>['pro-id'],
         		//'placeholder' => '城市',
         		'url' => Url::to(['/utils/citys'])
     	]
 	]);  ?>

    <?= $form->field($model, 'county')->widget(DepDrop::classname(), [
     		'options' => ['id'=>'country-id'],
     		'pluginOptions'=>[
         		'depends'=>['city-id'],
         		//'placeholder' => '县区',
         		'url' => Url::to(['/utils/country'])
     	]
 	]);  ?>


    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
