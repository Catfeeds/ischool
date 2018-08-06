<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\controllers\UtilsController;
use kartik\depdrop\DepDrop;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolStudent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-student-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stuno2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cardid')->textInput(['maxlength' => true]) ?>
    <?= $form->field($card_model, 'card_no')->textInput(['maxlength' => true]) ?>
	<?php if ($model->isNewRecord) {?>
    <?= $form->field($model, 'school')->textInput(['maxlength' => true,"class"=>"hidden","id"=>"school_name"])->label(false) ?>

    <?= $form->field($model, 'sid')->dropDownList(UtilsController::getSchools(),['id'=>"sid",'prompt'=>'Select...'])  ?>
    <?php } else {?>
    <?= $form->field($model, 'school')->textInput(['maxlength' => true,"id"=>"school_name","readonly"=>"readonly"])?>
	<?php }?>
	<?= $form->field($model, 'sex')->radioList(["女"=>"女","男"=>"男"])->label('性别') ?>

	<?php if ($model->isNewRecord) {?>
    <?= $form->field($model, 'cid')->widget(DepDrop::classname(), [
     		'options' => ['prompt'=>'Select...',"id"=>"cid"],
     		'pluginOptions'=>[
         		'depends'=>['sid'],
         		'url' => Url::to(['/utils/classes'])
     	]
 	])->label("班级")?>
	<?php } else { ?>
	<?= $form->field($model, 'cid')->dropDownList(UtilsController::getClasses($model->sid),['id'=>"cid"])->label("班级") ?>
	<?= $form->field($model, 'bupdate')->checkbox(['value'=>1,'label'=>"批量"]) ?>
	<?php }?>
    <?= $form->field($model, 'class')->textInput(['maxlength' => true,"class"=>"hidden","id"=>"class_name"])->label(false) ?>
	<?php $model->enddatepa = date("Y-m-d",$model->enddatepa)?>
	<?= $form->field($model, 'enddatepa')->widget(DatePicker::className(),[
			'type' => DatePicker::TYPE_INPUT,
			'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'yyyy-mm-dd'
			]
	])->label('平安通知截止时间') ?>
	<?php $model->enddateqq = date("Y-m-d",$model->enddateqq)?>
	<?= $form->field($model, 'enddateqq')->widget(DatePicker::className(),[
		'type' => DatePicker::TYPE_INPUT,
		'pluginOptions' => [
			'autoclose'=>true,
			'format' => 'yyyy-mm-dd'
		]
	])->label('亲情电话截止时间') ?>
	<?php $model->enddatejx = date("Y-m-d",$model->enddatejx)?>
	<?= $form->field($model, 'enddatejx')->widget(DatePicker::className(),[
		'type' => DatePicker::TYPE_INPUT,
		'pluginOptions' => [
			'autoclose'=>true,
			'format' => 'yyyy-mm-dd'
		]
	])->label('家校沟通截止时间') ?>
	<?php $model->enddateck = date("Y-m-d",$model->enddateck)?>
	<?= $form->field($model, 'enddateck')->widget(DatePicker::className(),[
		'type' => DatePicker::TYPE_INPUT,
		'pluginOptions' => [
			'autoclose'=>true,
			'format' => 'yyyy-mm-dd'
		]
	])->label('餐卡充值截止时间') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<script>
$(function(){
	$("#sid").change(function(){
		if(!$(this).val())  $("#school_name").val("");
		else $("#school_name").val($(this).find("option:selected").text())
	})
	$("#cid").change(function(){
		if(!$(this).val()) $("#class_name").val("");
		else $("#class_name").val($(this).find("option:selected").text())
	})
})
</script>
</div>
