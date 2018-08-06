<div class="container-fluid register-user-margin">
<div class="row register-user-container">


<div class="col-xs-12 examine-user-title">
<span class="badge">待审核教师</span>
</div>


<?php if(empty($list_user)) {?>
<div class="col-xs-12 text-center list-location">暂无相关信息</div>

<?php }else{?>

<?php foreach ($list_user as $key=>$vo) { ?>
<div id="s<?php echo $vo['id']?>" class="col-xs-12 register-user-center-margin">

<div class="row register-user-title data-card" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list<?php echo $vo['id']?>" onclick="witchInfo(this)">
<div class="col-xs-6 text-center">姓名</div>
<div class="col-xs-6 text-center"><?php echo $vo['tname']?></div>
</div>

<div id="with-list<?php echo $vo['id']?>" style="display:none;margin-top:15px;">

 
<div class="row inout-with-list">
<div class="col-xs-4">姓名:</div>
<div class="col-xs-7"><?php echo $vo['tname']?></div>
</div>
<div class="row inout-with-list">
<div class="col-xs-4">电话:</div>
<div class="col-xs-7"><a href="tel:<?php echo $vo['tel']?>}" style="color:#2980b9"><?php echo $vo['tel']?></a></div>
</div>
<div class="row inout-with-list">
<div class="col-xs-4">申请:</div>
<div class="col-xs-7"><?php echo $vo['class']?><?php echo $vo['role']?></div>
</div>

<div class="row inout-with-list">
<hr>
<div class="col-xs-4 col-xs-offset-4 text-right"><span class="data-card-del badge_oprate" onclick="doGet(<?php echo $vo['id']?>,'n')">拒绝</span></div>

<div class="col-xs-4 text-right"><span class="data-card-op badge_oprate"  onclick="doGet(<?php echo $vo['id']?>,'y')">通过</span></div>
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

function doGet(id,sta){
	var tid = id;
	var sta = sta;
	$.post( '/manager/checkuser',{tid:tid,ispass:sta},function (data){
		if(data=='success'){
			$("#s"+tid).remove();
			alertDialog("操作成功！");
		}else{
			alertDialog("操作失败，请重试！");
		}
	});
}

</script>
