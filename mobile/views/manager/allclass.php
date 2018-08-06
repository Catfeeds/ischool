<div class="container-fluid register-user-margin">
<div class="row register-user-container">


<div class="col-xs-12 register-user-title">
<span class="badge">所有班级列表</span>
</div>

<div class="col-xs-12 register-user-center-margin">
<a href="/manager/addclass" onclick="loadHtml(this,event);header_text_op7()"  onfocus="this.blur()">
<div class="row register-user-title data-card" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)">
<div class="col-xs-11 text-right new-data-class">
<div class="glyphicon glyphicon-plus-sign add-span" id="add_span"></div>
新建班级/内部交流组
</div>
</div>
</a>
</div>

<if condition="$list_class eq '' ">
<?php if(empty($list_class)) {?>
<div class="col-xs-12 text-center list-location">暂无相关信息</div>
<?php }else { 
	foreach ($list_class as $key=>$vo)
	{
?>
<div id="s<?= $vo['id'] ?>" class="col-xs-12 register-user-center-margin">

<div class="row register-user-title data-card" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list<?= $vo['id'] ?>" onclick="witchInfo(this)">
<div class="col-xs-6 text-center">班级</div>
<div class="col-xs-6 text-center"><?= $vo['name'] ?></div>
</div>

<div id="with-list<?= $vo['id'] ?>" style="display:none;margin-top:15px;">

 
<div class="row inout-with-list">
<div class="col-xs-4">班级名:</div>
<div class="col-xs-7"><?= $vo['name'] ?></div>
</div>
<div class="row inout-with-list">
<div class="col-xs-4">老师:</div>
<div class="col-xs-7"><?= $vo['tname'] ?></div>
</div>


<div class="row inout-with-list">
<hr>
 
<!--<a href="/manager/classmsgcount?cid=<?php echo $vo['id']?>" onfocus="this.blur()" onclick="loadHtml(this,event)">
<div class="col-xs-4 text-right" style="padding-left:2px;"><span class="data-card-op badge_oprate">信息</span></div>
</a>-->
<div class="col-xs-4 text-right" style="padding-left:2px;"><span class="data-card-del" onclick="del(<?php echo $vo['id']?>)">删除</span></div>
<a href="/manager/configclass" onfocus="this.blur()" onclick="loadHtml(this,event)">
<div class="col-xs-4 text-right" style="padding-left:2px;"><span class="data-card-op badge_oprate">配置</span></div>
</a>
</div>
<div class="row inout-with-list">
<a href="/manager/classleave?cid=<?php echo $vo['id']?>" onfocus="this.blur()" onclick="loadHtml(this,event)">
<div class="col-xs-5 text-right" style="padding-left:2px;">
<span class="data-card-op badge_oprate" >
查看请假
</span>
</div>
</a>
<a href="/manager/classkaoqin?cid=<?php echo $vo['id']?>&type=all&ttype=today" onfocus="this.blur()" onclick="loadHtml(this,event)">
<div class="col-xs-5 text-right" style="padding-left:2px;"><span class="data-card-op badge_oprate" >查看考勤</span></div>
</a>
</div>

</div>

</div>
<?php } } ?>

</div>


<div class="row register-user-container">

<div class="col-xs-12 register-user-center-margin">
<div class="row register-user-Paging">
<div class="col-sm-2 list-display">记录：共<?php echo $sum?>条信息</div>
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
function del(id){
	var cid=id;
	var url= '/manager/deleteclass';
	var para={cid: cid};
	var ths = $(this);

	var d = dialog({
		title: '提示',
		content: '您确定要删除吗?',
		okValue: '确定',

		ok: function () {
			$.post(url,para,function (data){
				if(data=='success'){$("#s"+cid).remove();}else if (data=='has'){
					alert("改班级已经存在学生，不能删除！")
				}
			});
		},

		cancelValue: '取消',
		cancel: function () {

		}

	});

		d.showModal();
}

function header_text_op7(){
	$("#new-class-room").hide();
}

</script>
