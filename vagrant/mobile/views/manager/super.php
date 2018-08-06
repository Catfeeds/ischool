
<div style="margin-top:20px;">

<div class="col-sm-12 " style="margin-bottom:10px;">
<span class="badge">学校管理员配置</span>
</div>

<div class="col-xs-12 register-user-container">
<div class="register-user-center-margin" id="user">

</div>

<div style="display:block;height:50px">
</div>


</div>

</div>
<!-- JS代码区 -->
<script>


function changeSchool(sid,openid){
		var url="/supermanage/getteabyschool";
		var para={sid:sid,openid:openid};
		var str="";
		doGetReturnRes(url,para,function(data){
			if(data!=null){
				str="<div class='row register-user-center-title'><div class='col-sm-4 col-xs-4'>用户名</div><div class='col-sm-4 col-xs-4'>状态</div>"
						str+="<div class='col-sm-4 col-xs-4'>操作</div></div>";
						for(i=0;i<data.length;i++){
							str+="<div class='row examine-user-list text-center'>"
							str+="<div class='col-xs-4 col-sm-4 text-omit'>"+data[i].tname+"</div>"
							str+="<div class='col-sm-4 col-xs-4'>"
							str+="<span class='badge'  id='"+data[i].id+"t' style='background-color:#00CC66 ; margin-top:2px; font-weight:normal;'>"
							str+=""+getRole(data[i].rid)+"</span>"
							str+="</div>"
							str+="<div class='col-sm-4 col-xs-4'>"
							str+="<span class='badge' style='background-color:#FF6666 ; margin-top:2px; font-weight:normal;' onclick='setManager(this,"+data[i].id+")' value='"+data[i].tname+";"+data[i].openid+";"+data[i].sid+";"+data[i].school+"'>改变身份"
							str+="</span>"
							str+="</div></div>"
						}
			}
			else
			{
				str="<div class='row register-user-center-title'>暂无相关信息</div>"
			}
			$("#user").html(str);
		});
	}

	function getRole($type){
		if($type==1){
			return "超管";
		}else{
			return "非超管";
		}
	}

	function setManager(ths,tid){
		var zt = $("#"+tid+"t").text();
		var cg = "";
		if(zt!="超管"){
			cg=1;
		}else{
			cg=0;
		}
		var url= "/supermanage/setmanager";
		var para={para:$(ths).attr('value'),cg:cg};
		doGetReturnRes(url,para,function(data){
			if(data=='success'){
				if(cg==1){
					$("#"+tid+"t").text("超管");
				}else{
					$("#"+tid+"t").text("非超管");
				}
			}
		});
	}

	$(document).ready(function(){
		var openid=$("#openid").val();
		var sid=$("#sid").val();
		changeSchool(sid,openid);
	});
	 
	</script>
