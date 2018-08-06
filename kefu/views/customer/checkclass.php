<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
</head>
<body>

<!-- <form action="/uploadimage/up" method="post" enctype="multipart/form-data" target="hidden_frame"> -->
<form id="check" class="form-horizontal" action="/uploadimage/checkclass" method="post" enctype="multipart/form-data">
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
    	提示：上传三高东校区数据。
    </div>
    <div class="clear" style="padding-left:10%; margin:15px 0px;">
    	<input type="button"  class="btn btn-primary float-left" name="name" onclick="checkinfo()" value="确认" />
    </div>
</div>
</form>
<!-- <iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe> --> 
<table class="text-center table table-bordered" width="100%" style="max-width:1000px;margin:0 auto;">
	<?php if($arr){?>
	<tr>
		<td style="border-color:#aaa;" colspan="8">		
			匹配信息条数：<?php echo $y;?>
			不匹配信息条数：<?php echo $n;?>
		</td>
	</tr>
	<?php }?>
	<tr>
		<td style="border-color:#aaa;">序号</td>
		<!-- <td style="border-color:#aaa;">学校</td> -->
		<td style="border-color:#aaa;">班级</td>
		<td style="border-color:#aaa;">姓名</td>
		<td style="border-color:#aaa;">学号</td>
		<td style="border-color:#aaa;">班级id</td>	
	</tr>
	<?php foreach($arr as $k=>$v){?>

	<tr>
		<td style="border-color:#aaa;"><?php echo $k+1;?></td>
		<td style="border-color:#aaa;"><?php echo $v['F']?></td>
		<td style="border-color:#aaa;"><?php echo $v['B']?></td>
		<!-- <td style="border-color:#aaa;"><?php echo $v['name']?></td> -->
		<td style="border-color:#aaa;"><?php echo $v['A']?></td>
		<td style="border-color:#aaa;"><?php echo $v['E']?></td>
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