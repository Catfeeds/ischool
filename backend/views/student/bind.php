<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolSchool */

$this->title = '学生绑定' ;
$this->params['breadcrumbs'][] = ['label' => '学生信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = '学生绑定';
?>
<style>
.mr10{margin-top:10px;margin-bottom:10px}
</style>
<div class="wp-ischool-school-update">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="wp-ischool-school-form">


	<?= html::activeTextInput($model, "name",['readonly'=>"readonly","class"=>"form-control"]) ?>

	
	<div class="container "  id="row_container" >
	<?php foreach ($parents as $row) {?>
	<div class="row mr10" data-id="<?php echo $row['id']?>" data-stuid="<?php echo $model->id?>">
	<div class="col-md-1">身份：</div>
	<div class="col-md-2"><input name="relation" value="<?php echo $row['Relation']?>" ></div>
	<div class="col-md-1">姓名：</div>
	<div class="col-md-2"><input name="name" value="<?php echo $row['name']?>" ></div>
	<div class="col-md-1">电话：</div>
	<div class="col-md-2"><input name="tel" value="<?php echo $row['tel']?>"  ></div>
	<div class="col-md-3">
	<input type="button" value="保存" class="save_button">
	<input type="button" value="删除" class="delete_button">
	</div>
	</div>
	<?php }?>
	</div>
	
	<div>
	<button class="btn btn-success" id="addRow">添加</button>
	</div>


</div>

</div>
<div id="clone_parent" style="display:none">
	<div class="row mr10" data-id="-1" data-stuid="<?php echo $model->id?>">
	<div class="col-md-1">身份：</div>
	<div class="col-md-2"><input name="relation"  ></div>
	<div class="col-md-1">姓名：</div>
	<div class="col-md-2"><input name="name"  ></div>
	<div class="col-md-1">电话：</div>
	<div class="col-md-2"><input name="tel"  ></div>
	<div class="col-md-3">
	<input type="button" value="保存" class="save_button">
	<input type="button" value="删除" class="delete_button">
	</div>
	</div>
</div>

<script type="text/javascript">
<!--
$(function(){
	$("#addRow").click(function(){
		var row = $("#clone_parent").html();
		$("#row_container").append(row)
	})
	$(document).on("click",".delete_button",function(){
		var row_parent = $(this).parents(".row");
		var id = row_parent.data("id");
		if(id > 0)
		{
			$.post("/parents/ajaxdelete?id="+id).done(function(data){
				if(data.status == 1) row_parent.remove();
			})
		}
		else row_parent.remove();
	})
	$(document).on("click",".save_button",function(){
		var row_parent = $(this).parents(".row");
		var post = {};
		post.parent_id = row_parent.data("id");
		post.stu_id = row_parent.data("stuid");
		post.parent_relation = row_parent.find("[name='relation']").val();
		post.parent_name = row_parent.find("[name='name']").val();
		post.parent_tel = row_parent.find("[name='tel']").val();
		if(!post.parent_relation || !post.parent_name || !post.parent_tel)
		{
			alert("数据不能为空"); return false;
		}
        var re = /^1[3-9]\d{9}$/;
        if (!re.test(post.parent_tel)) {
                alert("请输入正确的手机号");
         }
		$.post("/parents/ajaxsave",post).done(function(data){
			if(data.status == 1) window.location.reload();
		})
		
	})
})
//-->
	</script>