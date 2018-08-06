<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\controllers\UtilsController;

use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolStudent */

$this->title = "批量发送";
$this->params['breadcrumbs'][] = ['label' => "教师管理", 'url' => ['index']];
$this->params['breadcrumbs'][] = '批量发送';
?>
<div class="wp-ischool-student-update">

<div class="student-gsend">

    	<?php $form = ActiveForm::begin([
    			"method"=>"post",
    			"action"=>"/teacher/groupsend"
    	]); ?>
        <?= $form->field($model, 'sid')->widget(Select2::className(),[
        		'name' => "first",
        		'data' => UtilsController::getSchools(),
        		'options' => ['placeholder' => '请选择学校', 'multiple' => true,"id"=>"sid"],
        ])->label("学校");
        ?>
        <?= 
        $form->field($model, 'cid')->widget(Select2::classname(), [
     		'options' => ['prompt'=>'Select...',"id"=>"cid",'multiple' => "multiple","id"=>"cid"],
        	'data' => [],
        	]
     	
 	)->label("班级") ?>
        <?= $form->field($model, 'title')->label("标题") ?>
        <?= $form->field($model, 'content')->textarea()->label("内容") ?>
        <div class="form-group">
            <?= Html::submitButton('发送', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>

</div>

<script type="text/javascript">
<!--
$("#sid").change(function(){
	var sids = new Array();
	$("#sid option:selected").each(function(){
		sids.push($(this).val());
	})
	$.post("/utils/multiclasses",{"sids":sids}).done(function(result){
		result =  $.parseJSON(result);
	    $('#cid').empty().select2({'data' : result });
	})
})
//-->
</script>


