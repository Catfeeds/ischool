<div class="container-fluid register-user-margin">
<div class="row register-user-container">


<div class="col-xs-12 register-user-title">
<span class="badge">已审核教师</span>
</div>

<?php if(empty($list_user)) {?>
<div class="col-xs-12 text-center list-location">暂无相关信息</div>
<?php }else{?>

<?php foreach ($list_user as $key=>$vo) { ?>
<div id="s<?php echo $vo['id']?>" class="col-xs-12 register-user-center-margin">

<div class="row register-user-title data-card" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list<?php echo $vo['id']?>" onclick="witchInfo(this)">
<div class="col-xs-6 text-center">老师</div>
<div class="col-xs-6 text-center"><?php echo $vo['tname']?></div>
</div>

<div id="with-list<?php echo $vo['id']?>" style="display:none;margin-top:15px;">

 
<div class="row inout-with-list">
<div class="col-xs-4">姓名:</div>
<div class="col-xs-7"><?php echo $vo['tname']?></div>
</div>
<!-- <div class="row inout-with-list">
<div class="col-xs-4">电话:</div>
<div class="col-xs-7"><a href="tel:{$vo.tel}" style="color:#2980b9">{$vo.tel}</a></div>
</div> -->
<div class="row inout-with-list">
<div class="col-xs-4">身份:</div>
<div class="col-xs-7"><?php echo $vo['class']?> - <?php echo $vo['role']?></div>
</div>

<div class="row inout-with-list">
<hr>
<div class="col-xs-4 col-xs-offset-7 text-right"><span class="data-card-del badge_oprate" onclick="delete_span(<?php echo $vo['id']?>)" >删除</span></div>
</div>

</div>

</div>
<?php }}?>
</div>


<div class="row register-user-container">

           <div class="col-xs-12 register-user-center-margin">
               <div class="row register-user-Paging">
                 <div class="col-sm-2 list-display">记录：共<?php echo $count?>条信息</div>
                 <a href="<?php echo $start?>" onclick="loadHtml(this,event)">
                    <div class="col-sm-2 col-xs-3">首页</div>
                 </a>
                 <a href="<?php echo $up?>" onclick="loadHtml(this,event)">
                    <div class="col-sm-2 col-xs-3">上一页</div>
                 </a>
                 <a href="<?php echo $down?>" onclick="loadHtml(this,event)">
                    <div class="col-sm-2 col-xs-3">下一页</div>
                 </a>
                 <a href="<?php echo $end?>" onclick="loadHtml(this,event)">
                    <div class="col-sm-2 col-xs-3">末页</div>
                 </a>
                 <div class="col-sm-2 list-display">页数：<?php echo $totalPage?>页</div>
               </div>
           </div>

</div>


</div> <!-- container-fluid -->

<!-- container-fluid DIV -->
<script type="text/javascript" src="/js/bootstrap-confirmation.js"></script>
<!-- JS代码区 -->
<script>

function delete_span(id){
	var tid = id;
	var url= '/manager/deleteuser';
	var para={tid: tid};
	var ths = $(this);
	var d = dialog({
		title: '提示',
		content: '您确定要删除吗?',
		okValue: '确定',

		ok: function () {
			$.post(url,para,function (data){
				if(data=='success'){$("#s"+tid).remove();}
			});
		},

		cancelValue: '取消',
		cancel: function () {

		}

	});

		d.showModal();
}

</script>
