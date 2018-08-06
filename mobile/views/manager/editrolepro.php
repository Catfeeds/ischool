
<div class="container-fluid register-user-margin">


<div class="row register-user-container">

<div class="col-xs-12 user-root-title">
<span class="badge">角色权限编辑</span>
</div>

<div class="col-xs-12 register-user-center-margin">
<div class="row examine-user-list" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)">
<div class="col-xs-3 col-sm-2">角色名</div>
<div class="col-xs-5 col-sm-8" style="top:-7px">

<select class="form-control" id="role" onchange="changeRole(this.value)">
<option class="select-option" value="">请选择</option>
<?php foreach ($list_role as $key=>$vo) {?>
<option class="select-option" value="<?php echo $vo['id']?>"><?php echo $vo['name']?></option>
<?php }?>
</select>


</div>
<div class="col-xs-4 col-sm-2"><span class="dist-Jurisdiction" data-toggle="modal" data-target="#myModal">添加</span><span onclick="delete_span()">删除</span></div>
</div>
</div>

</div>


<div class="row register-user-container">

<div class="col-xs-12 register-user-center-margin">

<div class="row checkbox-root-Paging">

<?php foreach ($list_purview as $key=>$vo) {?>
<div onclick="user_checkbox(this,event)">
<div class="col-xs-9"><?php echo $vo['description']?></div>
<div class="col-xs-3">
<div class="checkbox">
<input type="checkbox" class='qx' value="<?php echo $vo['id']?>" id="p<?php echo $vo['id']?>" name="funcid[]"/>
<label for="p<?php echo $vo['id']?>"></label>
</div>
</div>
</div>
<?php }?>
</div>

</div>

<div class="col-xs-4 col-xs-offset-4 text-center" style="margin-bottom: 20px;">
<div class="data-card-op" id="selectAll_button" onclick="selectAll_button()">全选</div>
</div>
 
<div class="col-xs-4 text-center">
<div class="data-card-del" id="save_button" onclick="save_button()">保存</div>
</div>

</div>

 
</div> <!-- container-fluid -->

<script>

function save_button(){
	if($("#role").val()==""){
		alertDialog("请新建一个角色并选中！");
	}else{
		var qxs = $(".qx");
		var pids = "";
		for (var i = 0; i < qxs.length; i++) {
			if (qxs[i].checked) {
				pids = pids + qxs[i].value + "-";
			}
		}

		doGet({role:$("#role").val(),purview:pids,sid:$("#hidden_sid").val(),school:$("#hidden_school").val(),openid:$("#hidden_openid").val()},$(this));

	}
}


function selectAll_button(){

	var qx = $(".qx");
	var value=$("#selectAll_button").text();
	if(value=="全选"){
		for (var i = 0; i < qx.length; i++) {
			qx[i].checked=true;
		}

		$("#selectAll_button").text("全不选");
	}

	if(value=="全不选"){
		for (var i = 0; i < qx.length; i++) {
			qx[i].checked=false;
		}
		$("#selectAll_button").text("全选");
	}
}




function doGet(para,ths){
	ths.attr("disabled","disabled");
	setTimeout(clearSaveDis,2000);
	$.getJSON('/manager/saverolepro',para,function (data){
		if(data=='success'){
			alertDialog("保存成功！");
		}else{
			alertDialog("操作失败，请重试！");
		}

	})
}


function delete_span(){
	var para = {rid:$("#role").val()};
	var url=  '/manager/deleterole';
	 
	var ths = $(this);
	var d = dialog({
		title: '提示',
		content: '您确定要删除吗?',
		okValue: '确定',

		ok: function () {
			$.post(url,para,function (data){
				if(data=='success'){$("#role option:selected").remove();}
			});
		},

		cancelValue: '取消',
		cancel: function () {

		}

	});

		d.showModal();
}





function clearSaveDis(){
	$("#save_button").removeAttr("disabled");
}




function changeRole(roleid){
	$('.qx').each(function () {
		$(this).attr('checked',false);
	});

		if(roleid!=""){
			$.getJSON( '/manager/getrolepro',{rid:roleid}, function (data){
				if(data.result=='success'){
					if(data.data!=null){
						var res = data.data;
						var i = 0;
						for(i;i<res.length;i++){
							$("#p"+res[i].pid).prop("checked",true);
							 
						}
					}
				}else{
					alertDialog("获取该角色对应权限失败，请重试！");
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
