
<div class="container-fluid register-user-margin">

<div class="row register-user-container">

<div class="col-xs-12 user-root-title">
<span class="badge">批量新建班级</span>
</div>

<div class="col-xs-12 register-user-center-margin">
<div class="row examine-user-list" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)">
<div class="col-xs-3">年级</div>
<div class="col-xs-8" style="top:-7px">

<?php if ($type == "幼教") {?>
<select class="form-control txt" id="grade">
<option class="select-option" value="1">小班</option>
<option class="select-option" value="2">中班</option>
<option class="select-option" value="3">大班</option>
</select>
<?php }else {?>
<select class="form-control txt" id="grade">
<option class="select-option" value="1">一年级</option>
<option class="select-option" value="2">二年级</option>
<option class="select-option" value="3">三年级</option>
<option class="select-option" value="4">四年级</option>
<option class="select-option" value="5">五年级</option>
<option class="select-option" value="6">六年级</option>
<option class="select-option" value="7">七年级</option>
<option class="select-option" value="8">八年级</option>
<option class="select-option" value="9">九年级</option>
</select>

<?php }?>

</div>

</div>
</div>

</div>


<div class="row register-user-container">

<div class="add-class-name">
<div class="col-xs-3 text-left">
班级总数
</div>
<div class="col-xs-8">
<select class="form-control txt" id="classname">
<option class="select-option" value="1">1</option>
<option class="select-option" value="2">2</option>
<option class="select-option" value="3">3</option>
<option class="select-option" value="4">4</option>
<option class="select-option" value="5">5</option>
<option class="select-option" value="6">6</option>
<option class="select-option" value="7">7</option>
<option class="select-option" value="8">8</option>
<option class="select-option" value="9">9</option>
<option class="select-option" value="10">10</option>
<option class="select-option" value="11">11</option>
<option class="select-option" value="12">12</option>
<option class="select-option" value="13">13</option>
<option class="select-option" value="14">14</option>
<option class="select-option" value="15">15</option>
<option class="select-option" value="16">16</option>
<option class="select-option" value="17">17</option>
<option class="select-option" value="18">18</option>
<option class="select-option" value="19">19</option>
<option class="select-option" value="20">20</option>
<option class="select-option" value="21">21</option>
<option class="select-option" value="22">22</option>
<option class="select-option" value="23">23</option>
<option class="select-option" value="24">24</option>
<option class="select-option" value="25">25</option>
<option class="select-option" value="26">26</option>
<option class="select-option" value="27">27</option>
<option class="select-option" value="28">28</option>
<option class="select-option" value="29">29</option>
<option class="select-option" value="30">30</option>
<option class="select-option" value="31">31</option>
<option class="select-option" value="32">32</option>
<option class="select-option" value="33">33</option>
<option class="select-option" value="34">34</option>
<option class="select-option" value="35">35</option>
<option class="select-option" value="36">36</option>
<option class="select-option" value="37">37</option>
<option class="select-option" value="38">38</option>
<option class="select-option" value="39">39</option>
<option class="select-option" value="40">40</option>
</select>
</div>
</div>



<div class="col-xs-4 col-xs-offset-7 text-center" style="margin-bottom: 20px;margin-top: 20px;">
<div class="data-card-del" onclick="save_button()">保存</div>
</div>

</div>
<div class="row register-user-container">

<div class="col-xs-12 user-root-title">
<span class="badge">自定义新建班级/学校内部群组</span>
</div>

<div class="col-xs-12 register-user-center-margin">
<div class="row examine-user-list" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)">
<div class="col-xs-3">年级/组别</div>
<div class="col-xs-8" style="top:-7px">

<?php if ($type == "幼教") {?>
<select class="form-control txt2" id="grade2">
<option class="select-option" value="1">小班</option>
<option class="select-option" value="2">中班</option>
<option class="select-option" value="3">大班</option>
<option class="select-option" value="0">学校内部群组</option>
</select>
<?php }else {?>

<select class="form-control txt2" id="grade2">
<option class="select-option" value="1">一年级</option>
<option class="select-option" value="2">二年级</option>
<option class="select-option" value="3">三年级</option>
<option class="select-option" value="4">四年级</option>
<option class="select-option" value="5">五年级</option>
<option class="select-option" value="6">六年级</option>
<option class="select-option" value="7">七年级</option>
<option class="select-option" value="8">八年级</option>
<option class="select-option" value="9">九年级</option>
<option class="select-option" value="0">学校内部群组</option>
</select>

<?php }?>


</div>

</div>
</div>

</div>


<div class="row register-user-container">

<div class="add-class-name">
<div class="col-xs-3 text-left">
班级/组名称
</div>
<div class="col-xs-8">
<input class="form-control txt2" id="classname2" type="text" />
</div>
</div>

<div class="col-xs-4 col-xs-offset-7 text-center" style="margin-bottom: 20px;margin-top: 20px;">
<div class="data-card-del" onclick="save_class()">保存</div>
</div>

</div>
<input type="hidden" id="path" value="{$path}">
</div> <!-- container-fluid -->

<script type="text/javascript">

function save_button(){

	if($.trim($("#classname").val())==""){
		alertDialog("请输入班级名称！");
	}else{
		doGet({classname:$.trim($("#classname").val()),grade:$.trim($("#grade").val()),
		other:$.trim($("#other").val()),sid:$("#hidden_sid").val(),school:$("#hidden_school").val(),openid:$("#hidden_openid").val()});
	}
}

function doGet(para){
	 
	$.getJSON('/manager/doaddclass',para,function (data){
		if(data==0){
			alertDialog("保存成功！");
			$(".txt").val("");
		}else{
			if(data==2)
			{
				alertDialog("保存失败，请重试");
			}
			else
			{
				alertDialog("班级名字重复请重新输入");
			}

		}

	})
}

function save_class(){
	if($.trim($("#classname2").val())==""){
		alertDialog("请输入班级/组名称！");
	}else{
		doGet2({classname:$.trim($("#classname2").val()),grade:$.trim($("#grade2").val()),
		other:$.trim($("#other").val()),sid:$("#hidden_sid").val(),school:$("#hidden_school").val(),openid:$("#hidden_openid").val()});
	}
}

function doGet2(para){
	 
	$.getJSON('/manager/doaddclasscustomer',para,function (data){
		if(data==0){
			alertDialog("保存成功！");
		}else{
			if(data==2)
			{
				alertDialog("保存失败，请重试");
			}
			else
			{
				alertDialog("班级/组名字重复请重新输入");
			}
		}
	})
}

</script>
