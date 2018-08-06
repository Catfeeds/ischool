<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\controllers\UtilsController;

use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolStudent */

$this->title = "批量发送";
$this->params['breadcrumbs'][] = ['label' => "家长管理", 'url' => ['index']];
$this->params['breadcrumbs'][] = '批量发送';
?>
<div class="wp-ischool-student-update">

<div class="student-gsend">

    	<?php $form = ActiveForm::begin([
    			"method"=>"post",
    			"action"=>"/parents/groupsend"
    	]); ?>
        <?= $form->field($model, 'sid')->widget(Select2::className(),[
        		'name' => "first",
        		'data' => UtilsController::getSchools(),
        		'options' => ['placeholder' => '请选择学校', 'multiple' => true,"id"=>"sid"],
        ])->label('学校');
        ?>
        <?= $form->field($model, 'cid')->widget(Select2::classname(), [
     		'options' => ['prompt'=>'Select...',"id"=>"cid",'multiple' => "multiple","id"=>"cid"],
        	'data' => [],
        	])->label("班级")
		?>
 		<?= $form->field($model, 'fenzu')->widget(Select2::className(),[
		'name' => "first",
//            'data' => UtilsController::getfenzu(),
		'data' => ['已缴费' =>['csxx'=>'测试推送','ckyjf' => '餐卡已缴费','payjf'=>'平安通知已缴费','qqyjf'=>'亲情号码已缴费','jxyjf'=>'家校沟通已缴费'],'未缴费'=>['ckwjf'=>'餐卡未缴费','pawjf'=>'平安通知未缴费','qqwjf'=>'亲情号码未缴费','jxwjf'=>'家校沟通未缴费']],
		'options' => ['placeholder' => '请选择分组', "id"=>"fenzu"],
	])->label('分组');
	?>
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


