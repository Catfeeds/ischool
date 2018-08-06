<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\controllers\UtilsController;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolClass */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-class-form">

    <?php $form = ActiveForm::begin(); ?>

	<?php if($model->isNewRecord) {?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	
    <?= $form->field($model, 'school')->textInput(['class'=>"hidden",'id'=>'sid'])->label(false) ?>

    <?= $form->field($model, 'sid')->dropDownList(UtilsController::getSchools(),['id'=>"school"])->label("学校") ?>
	<?php }else {?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	
    <?= $form->field($model, 'school')->textInput(['readonly'=>"readonly",'id'=>'sid'])->label("学校") ?>

    <?= $form->field($model, 'sid')->dropDownList(UtilsController::getSchools(),['id'=>"school","class"=>"hidden"])->label("学校")->label(false) ?>

	<?php } ?>
    <?= $form->field($model, 'level')->dropDownList(UtilsController::getClassLevel())->label("年级") ?>

    <?= $form->field($model, 'class')->dropDownList(UtilsController::getClassNumber())->label("班级") ?>

	   
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
<!--
$(function(){
	var school_value = $("#school").find('option:selected').text();
	$("#sid").val(school_value)
	$("#school").change(function(){
		var school_value = $("#school").find('option:selected').text();
		$("#sid").val(school_value)
	})
})
//-->
</script>
