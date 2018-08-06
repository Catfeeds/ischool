<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\controllers\UtilsController;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolSchool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-ischool-school-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	
    <?= $model->isNewRecord?$form->field($model, 'pro')->dropDownList(UtilsController::getProvs(),['id'=>"pro-id","prompt"=>"Select ..."]):$form->field($model, 'pro')->textInput(["readonly"=>"readonly"]); ?>

    <?= $model->isNewRecord?$form->field($model, 'city')->widget(DepDrop::classname(), [
     		'options' => ['id'=>'city-id','prompt'=>'Select...'],
     		'pluginOptions'=>[
         		'depends'=>['pro-id'],
         		'url' => Url::to(['/utils/citys'])
     	]
 	]):$form->field($model, 'city')->textInput(["readonly"=>"readonly"]); ?>

    <?= $model->isNewRecord?$form->field($model, 'county')->widget(DepDrop::classname(), [
     		'options' => ['id'=>'country-id','prompt'=>'Select...'],
     		'pluginOptions'=>[
         		'depends'=>['city-id'],
         		'url' => Url::to(['/utils/country'])
     	]
 	]):$form->field($model, 'county')->textInput(["readonly"=>"readonly"]);; 
    ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'schtype')->dropDownList(UtilsController::getSchoolTypes()) ?>

	<?= $form->field($model, 'rmoney')->textInput()->label("补卡金额") ?>
	<?= $form->field($model, 'rmoney_note')->textarea(['maxlength' => true])->label("补卡事项") ?>

	<?= $form->field($model, 'half_qinqing_money')->textInput(['maxlength' => true])->label("33套餐一学期价格") ?>
	<?= $form->field($model, 'half_pingan_money')->textInput(['maxlength' => true])->label("35套餐一学期价格") ?>
	<?= $form->field($model, 'half_jiaxiao_money')->textInput(['maxlength' => true])->label("335(普通)套餐一学期价格") ?>
    <?= $form->field($model, 'half_jiaxiao_money_ck')->textInput(['maxlength' => true])->label("335（餐卡）套餐一学期价格") ?>

	<?= $form->field($model, 'half_canka_money')->textInput(['maxlength' => true])->label("3355套餐一学期价格") ?>
	<?= $form->field($model, 'half_sss_money')->textInput(['maxlength' => true])->label("355套餐一学期价格") ?>
	<?= $form->field($model, 'half_ww_money')->textInput(['maxlength' => true])->label("55套餐一学期价格") ?>
	<?= $form->field($model, 'one_qinqing_money')->textInput(['maxlength' => true])->label("33套餐一学年价格") ?>
	<?= $form->field($model, 'one_pingan_money')->textInput(['maxlength' => true])->label("35套餐一学年价格") ?>
	<?= $form->field($model, 'one_jiaxiao_money')->textInput(['maxlength' => true])->label("335（普通）套餐一学年价格") ?>
    <?= $form->field($model, 'one_jiaxiao_money_ck')->textInput(['maxlength' => true])->label("335（餐卡）套餐一学年价格") ?>
	<?= $form->field($model, 'one_canka_money')->textInput(['maxlength' => true])->label("3355套餐一学年价格") ?>
	<?=$form->field($model, 'one_sss_money')->textInput(['maxlength' => true])->label("355套餐一学年价格") ?>
	<?= $form->field($model, 'one_ww_money')->textInput(['maxlength' => true])->label("55套餐一学年价格") ?>
	<?= $form->field($model, 'papass')->textInput(['maxlength' => true])->label("平安通知是否开通，n代表没开通，y代表开通") ?>
	<?= $form->field($model, 'jxpass')->textInput(['maxlength' => true])->label("家校沟通是否开通，n代表没开通，y代表开通") ?>
	<?= $form->field($model, 'qqpass')->textInput(['maxlength' => true])->label("亲情电话是否开通，n代表没开通，y代表开通") ?>
	<?= $form->field($model, 'ckpass')->textInput(['maxlength' => true])->label("餐卡是否开通，n代表没开通，y代表开通") ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
