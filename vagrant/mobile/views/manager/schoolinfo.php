<div class="container-fluid register-user-margin">

<div class="row register-user-container">
<div class="col-xs-12 register-user-center-margin">
	
<!-- 学校名称 -->
<div class="row class-root-Paging" style="display:block" id="display-2">

<div class="col-xs-4" style="top:6px">
归属省
</div>
<div class="col-xs-7">
<select id="scpro" class="form-control" onchange="changePro(this.value)">

<?php foreach ($ry as $vo) {?>
<option value="<?php echo $vo['code']?>">
<?php echo $vo['name']?>
</option>
<?php }?>
</select>
</div>

</div>

<div class="row class-root-Paging" style="display:block" id="display-3">

<div class="col-xs-4" style="top:6px">
市级
</div>
<div class="col-xs-7">
<select class="form-control" id="sccity" onchange="changeCity(this.value)">

<?php foreach ($rl as $vo) {?>
<option value="<?php echo $vo['code']?>">
<?php echo $vo['name']?>
</option>
<?php }?>
</select>
</div>

</div>

<div class="row class-root-Paging" style="display:block" id="display-4">

<div class="col-xs-4" style="top:6px">
区县
</div>
<div class="col-xs-7">
<select class="form-control" id="scarea">

<?php foreach ($ra as $vo) {?>
<option value="<?php echo $vo['name']?>">
<?php echo $vo['name']?>
</option>
<?php }?>

</select>
</div>

</div>
	
<div class="row class-root-Paging" style="display:block" id="display-1">
<div class="col-xs-4" style="top:6px">
学校类型
</div>
<div class="col-xs-8">
<select class="form-control" onchange="" id="sctype">

<?php foreach ($type as $vo ){?>
<option value="<?php echo $vo['name']?>">
<?php echo $vo['name']?>
</option>
<?php }?>
</select>
</div>
</div>
<div class="row class-root-Paging" style="display:block" id="display-6">
<div class="col-xs-4" style="top:6px">
学校名称
</div>
<div class="col-xs-8">
<input type="text" value="<?php echo $schname?>" class="form-control" name="scname" id="scname">
</div>
</div>
	
</div>
</div>
<div class="row register-user-container times-display">
<div class="col-xs-12 register-user-center-margin">

<div class="row examine-user-list">
 
<div class="col-xs-5 col-xs-offset-1" onclick="upl()" style="display:block" id="display-end">
<span class="data-card-add">完成</span>
</div>
</div>

</div>
</div>
</div>

<script>

function changePro(pro){
	$("#city").html("<option value=''>数据加载中...</option>");
	$("#area").html("<option value=''>请选择</option>");
	$("#type").html("<option value=''>请选择</option>");
	$("#school").html("<option value=''>请选择</option>");
	$("#class").html("<option value=''>请选择</option>");
	var url= "/manager/getcitybyprovince";
	var para={code:pro};
	doGetReturnRes(url,para,function(data){
		var htmls="<option value=''>请选择</option>"

				for (var i = 0; i < data.length; i++) {
					htmls = htmls + "<option value="+data[i].code+">"+data[i].name+"</option>";
				}
				$("#sccity").html(htmls);
	});
}

function changeCity(city){
	var url="/manager/getcountrybycity";
	var para={code:city};
	doGetReturnRes(url,para,function(data){
		var htmls="<option value=''>请选择</option>"
				for (var i = 0; i < data.length; i++) {
					htmls = htmls + "<option value="+data[i].name+">"+data[i].name+"</option>";
				}
				$("#scarea").html(htmls);
	});
}

</script>

