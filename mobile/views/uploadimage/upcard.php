<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
</head>
<body>
<div class="container">
<!-- <form action="/uploadimage/up" method="post" enctype="multipart/form-data" target="hidden_frame"> -->
<form id="check" class="form-horizontal" action="/uploadimage/upcard" method="post" enctype="multipart/form-data">
<!-- <label for="file">文件名:</label>
<input type="file" name="ImportData[upload]" id="file" />  -->
<div style="max-width:1000px;margin:20px auto;" class="form-group">
	<label for="schoolid" class="col-xs-1 control-label">学校id</label>
	 <div class="col-xs-4">
    <input type="text" class="form-control" id="schoolid" name="schoolid" placeholder="请输入学校id">
	 </div>	 
	 <hr />

	 <label for="level" class="col-xs-1 control-label">年级</label>
	 <div class="col-xs-4">
       <input type="text" class="form-control" id="level"  name ="level" placeholder="请输入年级，例如高一">
	 </div>
	 <hr />
	<br/>
    <label class="col-xs-1 control-label" for="file">文件名</label>
    <div class="col-xs-4">
      <input class="form-control" id="file" type="file" name="ImportData[upload]" onchange="checkgeshi(this.value)">
    </div>
    <hr />

    <div class="clearfix"></div>
    <div class="clear" style="padding-left:10%; margin:15px 0px;">
    	<input type="button"  class="btn btn-primary float-left" name="name" onclick="checkinfo()" value="确认" />
    	<a class="btn btn-success float-left" href="/upload/template/tiaoban.xls"> 下载模板 </a>
    </div>
	
</div>
</form>
</div>
<table class="text-center table table-bordered" width="100%" style="max-width:1000px;margin:0 auto;">
    <tr>
		<td style="border-color:#aaa;" colspan="8">不匹配名单</td>
	
	</tr>	
	<tr>
		<td style="border-color:#aaa;">学号</td>
		<td style="border-color:#aaa;">姓名</td>
		<td style="border-color:#aaa;">班级id</td>
		
		<td style="border-color:#aaa;">物理卡号</td>
		<td style="border-color:#aaa;">餐卡卡号</td>
	</tr>
	<?php foreach($arr as $k=>$v){ ?>
	<tr>
		<td style="border-color:#aaa;"><?= $v['A']?></td>
		<td style="border-color:#aaa;"><?= $v['B']?></td>
		<td style="border-color:#aaa;"><?= $v['C']?></td>

		<td style="border-color:#aaa;"><?= $v['D']?></td>
		<td style="border-color:#aaa;"><?= $v['E']?></td>
	</tr>
	<?php } ?>
</table>
</body>
</html>
<script>
	function checkinfo(){
		var file=$('#file').val();
		var level=$('#level').val();
		var schoolid=$('#schoolid').val();
		if(schoolid=='' || isNaN(schoolid) ){
			alert('请输入数字学校id且必须为整数！');
			return false;
		}
		if(level==''){
			alert('请输入年级！');
			return false;
		}
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