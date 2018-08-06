<?php 
use yii\widgets\LinkPager;
use yii\grid\GridView;
 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
<style type="text/css">
	input::-webkit-input-placeholder {          
         font-size: 12px;
     }
</style>
</head>
<body>
<form id="consum" class="form-horizontal" action="/customer/consum" method="post">
<div style="max-width:1000px;margin:20px auto;" class="form-group">
	<label class="col-sm-2 control-label " for="starttime" style="text-align:right;margin-top:6px;">开始时间:</label>
         <div class="col-sm-6">
           <input type="date" id="starttime" class="form-control">
		 </div>
		 <label class="col-sm-2 control-label " for="starttime" style="text-align:right;margin-top:6px;">开始时间:</label>
         <div class="col-sm-6">
           <input type="date" id="starttime" class="form-control">
		 </div>

         <label class="col-sm-2 control-label " for="starttime" style="text-align:right;margin-top:6px;">开始时间:</label>
         <div class="col-sm-6">
           <input type="date" id="starttime" class="form-control">
		 </div>

		  <div class="clearfix"></div>
		  <br/>
  	    <label class="col-sm-2 control-label" for="endtime" style="text-align:right;margin-top:6px;">结束时间:</label>
         <div class="col-sm-6">
           <input type="date" id="endtime" class="form-control">
		 </div>
		 <div class="clearfix"></div>
		 <br/>
	     <div class="clear" style="padding-left:10%; margin:15px 0px;">
    	     <input type="button"  class="btn btn-primary float-left" name="consume" onclick="search_consume()" value="查询" />
        </div>
      
</div>
</form>
<table class="text-center table table-bordered " width="100%" style="max-width:1000px;margin:0 auto;" id="consume">
	<tr id="ckcode" >
		<td style="border-color:#aaa;" colspan="5">消费记录</td>
		
	</tr>
	<tr>
		
		<td style="border-color:#aaa;">卡号</td>
		<td style="border-color:#aaa;">学校</td>
		<td style="border-color:#aaa;">消费金额</td>
		<td style="border-color:#aaa;">余额</td>
		<td style="border-color:#aaa;">创建时间</td>
		
	</tr>
</table>	
</body>
</html>
<script>
	function myFunction(){
		$("#trade_no").removeAttr("placeholder");
	}
	function myfunction_one(){
		$("#trade_no").attr("placeholder","例：2017091506316416001");
	}
	function search_result(){
		var trade=$("#trade_no").val();
		if(trade==''){
			alert("请输入商户单号！");
			return false;
		}
		$("#search").submit();
	}
	function change(){
		var danhao=$("#danhao").val();
		if(window.confirm("你确定执行该操作？")){
			$.ajax({
				url:'/customer/editqc',
				data:'danhao='+danhao,
				dataType:'json',
				type:'post',
				success:function(msg){					
					if(msg){
						alert('操作成功！');
						$("#trade_no").val(msg);
						search_result();
					}else{
						alert('操作失败！');
					}

				}
			})
		}
	}
	//查询消费记录
	function search_consume(){
		var starttime =$("#starttime").val();
		var endtime =$("#endtime").val();
		var card_no=$("#card_no").val();
		var school_id=$("#school_id").val();
		if(starttime==""){
			alert("请选择开始日期");
			return false;
		}
		if(endtime==""){
			alert("请选择结束日期");
			return false;
		}
		if(card_no==""){
			alert("请输入商户单号");
			return false;
		}
		$.ajax({
				url:'/customer/search-consume',
				data:'starttime='+starttime+'&endtime='+endtime+'&card_no='+card_no+'&school_id='+school_id,
				dataType:'json',
				type:'post',
				success:function(data){					
					if(data.flag==0){					                       
					    $.each(data.ckshuju,function(i,info){
						var tr = "<tr><td style='border-color:#aaa;'>"+info.card_no+"</td><td style='border-color:#aaa;'>"+info.school_name+"</td><td style='border-color:#aaa;'>"+info.amount+"</td><td style='border-color:#aaa;'>"+info.balance+"</td><td style='border-color:#aaa;'>"+info.time+"</td></tr>";	
						$("#consume").append(tr);
					    })
				
					}else{
		                alert('没有相关记录');
		  			
					}

				}
		})
	}
</script>