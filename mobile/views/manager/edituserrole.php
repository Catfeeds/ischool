
<div class="container-fluid register-user-margin">

<form method="post" action="/manager/doadd" id="userr" >

<input type="hidden" value="<?php echo $openid?>" name="openid">
<input type="hidden" value="<?php echo $xqid?>" name="xqid"/>

<div class="row register-user-container">

<div class="col-xs-12 user-root-title">
<span class="badge">用户角色分配</span>
</div>

<div class="col-xs-12 register-user-center-margin">
<div class="row examine-user-list" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)">
<div class="col-xs-3">用户名</div>
<div class="col-xs-8" style="top:-7px">

<select class="form-control" id="user" onchange="changeUser(this.value)">
<option class="select-option" value="">请选择</option>
<?php foreach ($list_teacher as $key=>$vo) {?>
<option class="select-option" value="<?php echo $vo['openid']?>"><?php echo $vo['tname']?></option>
<?php } ?>
</select>

</div>

</div>
</div>

</div>

<div class="row register-user-container">

<div class="col-xs-12 register-user-center-margin">

<div class="row checkbox-root-Paging">

<?php foreach ($list_role as $key=>$vo) {?>
<div onclick="user_checkbox(this,event)">
<div class="col-xs-9"><?php echo $vo['name']?></div>
<div class="col-xs-3">
<div class="checkbox">
<input type="checkbox" value="<?php echo $vo['id']?>" id="r<?php echo $vo['id']?>" class="js"/>
<label for="r<?php echo $vo['id']?>"></label>
</div>
</div>
</div>

<?php }?>
</div>

</div>

<div class="col-xs-4 col-xs-offset-4 text-center" style="margin-bottom: 20px;">
<div class="data-card-op" id="selectAll_button_edit" onclick="selectAll_button_edit()">全选</div>
</div>
 
<div class="col-xs-4 text-center">
<div class="data-card-del" id="save_button" onclick="save_button()">保存</div>
</div>

</div>
</form>
</div> <!-- container-fluid -->

<script>
function save_button(){
	if($("#user").val()==""){
		alertDialog("请选中一个有效的用户！");
	}else{
		var jss = $(".js");
		var roles = "";
		for (var i = 0; i < jss.length; i++) {
			if (jss[i].checked) {
				roles = roles + jss[i].value + "-";
			}
		}
		doGet({uname:$("#user option:selected").text(),uid:$("#user").val(),roleids:roles,sid:$("#hidden_sid").val(),school:$("#hidden_school").val()},$(this));
	}
}

function selectAll_button_edit(){
	var qx = $(".js");
	var value=$("#selectAll_button_edit").text();
	if(value=="全选"){
		for (var i = 0; i < qx.length; i++) {
			qx[i].checked=true;
		}

		$("#selectAll_button_edit").text("全不选");
	}

	if(value=="全不选"){
		for (var i = 0; i < qx.length; i++) {
			qx[i].checked=false;
		}
		$("#selectAll_button_edit").text("全选");
	}

}

function clearSaveDis(){
	$("#save_button").removeAttr("disabled");
}

function doGet(para,ths){
	ths.attr("disabled","disabled");
	setTimeout(clearSaveDis,2000);
	$.getJSON( '/manager/saveuserrole',para,function (daat){
		if(daat=='success'){
			alertDialog("保存成功！");
		}else{
			alertDialog("操作失败，请重试！");
		}

	})
}


function changeUser(userid){
	$('.js').each(function () {
		$(this).attr('checked',false);
	});

		if(userid!=""){
			$.getJSON('/manager/getuserrole',{uid:userid,sid:$("#hidden_sid").val()}, function (data){
				if(data.result=='success'){
					if(data.data!=null){
						var res = data.data;
						var i = 0;
						for(i;i<res.length;i++){

							$("#r"+res[i].rid)[0].checked=true;
							 
						}
					}
				}else{
					alertDialog("获取该用户对应角色失败，请重试！");
				}

			});
		}
}


function user_checkbox(ths){

	var che=$(ths).find("input[type='checkbox']");

	if(che[0].checked){
		che[0].checked=false;
	}else{
		che[0].checked=true;
	};
}

</script>
