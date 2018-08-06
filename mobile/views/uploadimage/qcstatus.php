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

<!-- <form action="/uploadimage/up" method="post" enctype="multipart/form-data" target="hidden_frame"> -->
<form id="search" class="form-horizontal" action="/uploadimage/qcstatus" method="post">
<!-- <label for="file">文件名:</label>
<input type="file" name="ImportData[upload]" id="file" />  -->
<div style="max-width:1000px;margin:20px auto;" class="form-group">
    <label class="col-sm-2 control-label" for="trade_no">商户单号:</label>
    <div class="col-sm-6">
      <input class="form-control" id="trade_no" type="text" name="trade_no" placeholder="例：2017091506316416001" onfocus="myFunction()" onblur="myfunction_one()"/>
    </div>
    <br />
    <div class="clearfix"></div>
    <div class="clear" style="padding-left:10%; margin:15px 0px;color:red;">
    	提示：请根据查询的结果结合消费记录进行妥善处理。
    </div>
    <div class="clear" style="padding-left:10%; margin:15px 0px;">
    	<input type="button"  class="btn btn-primary float-left" name="name" onclick="search_result()" value="查询" />
    </div>
   <input type="hidden" name="danhao" id="danhao" value="<?php echo $result['trade_no']?>">
   <input type="hidden" name="danhao" id="card_no" value="<?php echo $result['card_no']?>">
   <input type="hidden" name="danhao" id="school_id" value="<?php echo $result['school_id']?>">
</div>
</form>
<!-- <iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe> --> 
<table class="text-center table table-bordered" width="100%" style="max-width:1000px;margin:0 auto;">
    <tr>
		<td style="border-color:#aaa;" colspan="8">微信充值订单</td>
	
	</tr>	
	<tr>
		<td style="border-color:#aaa;">学校</td>
		<td style="border-color:#aaa;">姓名</td>
		<td style="border-color:#aaa;">学号</td>
		
		<td style="border-color:#aaa;">金额</td>
		<td style="border-color:#aaa;">圈存状态</td>
		<td style="border-color:#aaa;">充值时间</td>
		<td style="border-color:#aaa;">圈存时间</td>		
		<td style="border-color:#aaa;">商户单号</td>
	</tr>
	<?php if($result!=0 && $result!=null){ ?>
	<tr>
		<td style="border-color:#aaa;"><?= $result['school_name']?></td>
		<td style="border-color:#aaa;"><?= $result['user_name']?></td>
		<td style="border-color:#aaa;"><?= $result['user_no']?></td>

		<td style="border-color:#aaa;"><?= $result['credit']?></td>

		<?php if($result['is_active']==0){ ?>
		<td style="border-color:#aaa;color:red;" ><span class="glyphicon glyphicon-remove"></span>未圈存</td>
		<?php }else{ ?>
		<td style="border-color:#aaa;color:green;"  ><span class="glyphicon glyphicon-ok"></span>已圈存
    	<input type="button"  class="btn btn-success btn-xs" name="status" onclick="change()" value="变更" />   		
		</td>
		<?php }?>
		<td style="border-color:#aaa;"><?= date('Y-m-d H:i:s',$result['time'])?></td>

		<td style="border-color:#aaa;"><?= $result['qctime']?date('Y-m-d H:i:s',$result['qctime']):'未圈存';?></td>		
		<td style="border-color:#aaa;"><?= $result['trade_no']?></td>
	</tr>
	<?php }else if($result==0){ ?>
	<tr>
		<td style="border-color:#aaa;" colspan="8">没有相关结果！</td>
	
	</tr>
	<?php }?>
</table>
<br/>
<div style="max-width:1000px;margin:20px auto;" class="form-group">

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
				url:'/uploadimage/editqc',
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
				url:'/uploadimage/search-consume',
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