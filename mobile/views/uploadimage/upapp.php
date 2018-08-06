<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
</head>
<body>

<!-- <form action="/uploadimage/up" method="post" enctype="multipart/form-data" target="hidden_frame"> -->
<form id="check" class="form-horizontal" action="/uploadimage/upapp" method="post" enctype="multipart/form-data">
<!-- <label for="file">文件名:</label>
<input type="file" name="ImportData[upload]" id="file" />  -->
<div style="max-width:1000px;margin:20px auto;" class="form-group">
    <label class="col-sm-1 control-label" for="file">文件名</label>
    <div class="col-sm-4">
      <input class="form-control" id="file" type="file" name="ImportData[upload]" onchange="checkgeshi(this.value)">
    </div>
    <br />
    <div class="clearfix"></div>
    <div class="clear" style="padding-left:10%; margin:15px 0px;color:red;">
    	提示：请上传您从商户平台导出的流水数据，上传前请先将文档保存为xls格式。
    </div>
    <div class="clear" style="padding-left:10%; margin:15px 0px;">
    	<input type="button"  class="btn btn-primary float-left" name="name" onclick="checkinfo()" value="确认" />
    </div>
   <!--  <div class="clear" style="padding-left:10%; margin:15px 0px;">
    <button type="button" class="btn btn-success" onclick="haode()">导出</button>
    </div> -->
    <input type="hidden"  value="<?= implode('',array(0=>array('credit'=>1,'trade_no'=>'02017121512114763369'),1=>array('credit'=>2,'trade_no'=>'02017121512353922572')));?>" id='stuinfo'  >
</div>
</form>
<!-- <iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe> --> 
<table class="text-center table table-bordered" width="100%" style="max-width:1000px;margin:0 auto;">
	<?php if($stu){?>
	<tr>
		<?php if($status==1){ ?>
		<td style="border-color:#aaa;" colspan="9">
		<?php }else{ ?>
		<td style="border-color:#aaa;" colspan="8">
		<?php }?>
			以下信息总条数为：<?php echo $count;?>
		</td>
	</tr>
	<?php }?>
	<tr><td style="border-color:#aaa;">序号</td>
		<td style="border-color:#aaa;">学校</td>
		<td style="border-color:#aaa;">类别</td>
		<td style="border-color:#aaa;">交易时间</td>
		<td style="border-color:#aaa;">班级</td>
		<td style="border-color:#aaa;">姓名</td>
		<td style="border-color:#aaa;">交易金额</td>		
		<td style="border-color:#aaa;">商户订单号</td>
		<?php if($status==1){ ?>
		<td style="border-color:#aaa;">校区</td>
		<?php }?>
	</tr>
	<?php foreach($stu as $k=>$v){?>

	<tr>
		<td style="border-color:#aaa;"><?php echo $k-1;?></td>
		<td style="border-color:#aaa;"><?php echo $v['school']?></td>
		<td style="border-color:#aaa;"><?php echo $v['type']?></td>
		<td style="border-color:#aaa;"><?php echo $v['time']?></td>
		<td style="border-color:#aaa;"><?php echo $v['class']?></td>
		<td style="border-color:#aaa;"><?php echo $v['name']?></td>
		<td style="border-color:#aaa;"><?php echo $v['total']?></td>		
		<td style="border-color:#aaa;"><?php echo $v['trade_no_yuan']?></td>
		<?php if($status==1){ ?>
		<td style="border-color:#aaa;"><?php echo $v['xiaoqu']?></td>
		<?php }?>
	</tr>
	<?php } ?>
</table>	
</body>
</html>
<script>
	function checkinfo(){
		var file=$('#file').val();
		if(file==''){
			alert('请选择上传文件！');
			return false;
		}
		 		 
		 //取出上传文件的扩展名	 
	    $('#check').submit();			
	}
	function haode(){
		window.location="/uploadimage/export";
		// var data=$('#stuinfo').val();
		// $.ajax({
		// 	url:'/uploadimage/export',
		// 	data:'stu='+data,
		// 	dataType:'json',
		// 	type:'post',
		// 	success:function(msg){					
		// 		if(msg){
		// 			alert('操作成功！');
		// 		}else{
		// 			alert('操作失败！');
		// 		}

		// 	}
		// })
		// alert(data);
	}
	function checkgeshi(filename){
		var arr = ["xls"];
		var index = filename.lastIndexOf(".");
		var ext = filename.substr(index+1);
		//循环比较
		if( ext!= arr[0]){
		 	alert('请上传.xls格式文本！');
		 	$('#file').val("");
		 	return false;
		}
	}
</script>