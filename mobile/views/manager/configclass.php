<div class="container-fluid register-user-margin">
<div class="row register-user-container">
<div class="col-xs-12 user-root-title">
<div onclick="loadHtmlByUrl('/manager/gotoallclass')">
<i style="color: white" class="fa fa-reply"></i>
<span style="margin-left: 20px;" class="badge">班级配置</span>

</div>
</div>

<div class="col-xs-12 register-user-center-margin">
<div class="row examine-user-list" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)">
<div class="col-xs-3">班级</div>
<div class="col-xs-7" style="top:-7px">

<select id="list_cid" class="form-control">
<option class="select-option" value="">请选择</option>
<?php foreach ($list_class as $key=>$vo) {?>
<option class="select-option" value="<?php echo $vo['id']?>" id="<?php echo $vo['name']?>"><?php echo $vo['name']?></option>
<?php }?>
</select>

</div>
</div>
</div>

<div class="col-xs-12 register-user-center-margin">
<div class="row examine-user-list" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)">
<div class="col-xs-3">角色</div>
<div class="col-xs-7" style="top:-7px">

<select id="role" class="form-control" onchange="showZdy(this)">
<option class="select-option" value="班主任">班主任</option>
<option class="select-option" value="语文老师">语文老师</option>
<option class="select-option" value="数学老师">数学老师</option>
<option class="select-option" value="英语老师">英语老师</option>
<option class="select-option" value="体育老师">体育老师</option>
<option class="select-option" value="音乐老师">音乐老师</option>
<option class="select-option" value="物理老师">物理老师</option>
<option class="select-option" value="化学老师">化学老师</option>
<option class="select-option" value="生物老师">生物老师</option>
<option class="select-option" value="历史老师">历史老师</option>
<option class="select-option" value="地理老师">地理老师</option>
<option class="select-option" value="计算机老师">计算机老师</option>
<option class="select-option" value="自定义">自定义</option>
</select>

</div>
</div>
</div>
 
<div class="col-xs-12 register-user-center-margin" style="display:none;" id="zdy">
<div class="row examine-user-list" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)">
<div class="col-xs-3">自定义角色名</div>
<div class="col-xs-7" style="top:-7px">
<input id="userrole" class="form-control">
</div>
</div>
</div>

<div class="col-xs-12 register-user-center-margin">
<div class="row examine-user-list" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)">
<div class="col-xs-3">老师</div>
<div class="col-xs-7" style="top:-7px">

<select id="list_teacher" class="form-control">
<option class="select-option" value="">请选择</option>
<?php foreach ($list_teacher as $key=>$vo) {?>
<option class="select-option" value="<?php echo $vo['openid']?>"><?php echo $vo['tname']?></option>
<?php }?>
</select>

</div>
</div>
</div>

<div class="col-xs-12 register-user-center-margin" onclick="add_span()">
<div class="row class-root-Paging">
<div class="col-xs-8">
为这个班级安排一位老师
</div>
<div class="col-xs-4">
<span class="data-card-add">确定</span>
</div>
</div>
</div>
</div>

<div class="row register-user-container">
<div class="col-xs-12 register-user-center-margin" id="list_tc">

<div class="row class-root-Paging" id="zwxx">
<div class="col-xs-8 text-omit">
暂无信息
</div>
<div class="col-xs-4">
<span class="data-card-add">删除</span>
</div>
</div>

</div>
</div>

</div> <!-- container-fluid -->

<!-- container-fluid DIV -->
<script type="text/javascript" src="/js/bootstrap-confirmation.js"></script>
<script>

$("#save_button").on("click",function(){
	if($.trim($("#list_cid").val())==""){
		alertDialog("请选择班级！");
	}else{
		var tids = "";
		$("#list_teaclass option").each(function(){
			tids = tids + $(this).val()+";";
		});

			doGet({cid:$("#list_cid").val(),classname:$.trim($("#list_cid option:selected").text()),tid:tids,role:$("#role").val(),sid:$("#hidden_sid").val(),school:$("#hidden_school").val()});
	}
	 
});

	function doGet(para){
		$.getJSON("/manager/saveconfigclass",para,function(data){
			if(data.flag=="success"){
				$("#zwxx").hide();
				$("#list_tc").append(getHtml(data.data));
			}else{
				if(data.flag=="fail")
				{
					alertDialog("配置保存失败，请重试！");
				}
				else
				{
					alertDialog("不能重复绑定请重新选择");
				}

			}
		});
	}

	function getHtml(obj){
		var str = "<div class='row class-root-Paging tc'>"
				+  "<div class='col-xs-8 text-omit'>"
						+  obj.tname  +"  " + obj.role
						+  "</div>"
								+  "<div class='col-xs-4'>"
										+  "<span class='data-card-add' onclick='deleteTeaClass("+obj.id+",this)'>删除</span>"
												+  "</div>"
														+  "</div>";
														return str;
	}

	 
	$("#list_cid").on("change",function(){
		var url =  "/manager/getteaclass";
		var para = {cid:$(this).val()};
		$.getJSON(url,para,function (data){
			if(data.result=='success'){
				var htmls = "";
				if(data.data!=null){
					$("#zwxx").hide();
					var res2 = data.data;
					for (var i = 0; i < res2.length; i++) {
						htmls = htmls + getHtml(res2[i]);
					}
				}else{
					//htmls=getHtml({id:"",tname:"暂无信息",role:""});
				}
				$("#list_tc").html(htmls);

			}else{
				alertDialog('获取当前班级老师失败，请重试');
			}
		});
	})
	 

	function add_span(){
		if($("#list_cid").val()==""){
			alertDialog("请选择一个班级！");
		}else if($("#list_teacher").val()==""){
			alertDialog("请选择一位老师！");
		}else{
			var role = "";
			if($("#zdy").is(":hidden")){
				role = $("#role").val();
			}else{
				role = $.trim($("#userrole").val());
				if(role == ""){
					alertDialog('请输入自定义角色名称');
					return 0;
				}
			}
			doGet({cid:$("#list_cid").val(),classname:$.trim($("#list_cid option:selected").text()),tname:$("#list_teacher option:selected").text(),sid:$("#hidden_sid").val(),openid:$("#list_teacher").val(),school:$("#hidden_school").val(),role:role});
		}
	}


	function deleteTeaClass(tcid,ths){
		var url =  "/manager/deleteconfigclass";
		var para = {tcid:tcid};
		$.getJSON(url,para,function(data){
			if(data.result=='success'){
				$(ths).parents(".tc").hide().remove();
				if($("#list_tc .tc").length==0){
					$("#zwxx").show();
				}
			}else{
				alertDialog('移除失败，请重试');
			}
		});
	}

	function showZdy(ths){
		$("#userrole").val('');
		if($(ths).val() =='自定义'){
			$("#zdy").show();
		}else{
			$("#zdy").hide();
		}
	}
	 
	</script>
