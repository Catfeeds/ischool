<link media="all" rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
<style>
.select_span{
	background-color: #776e71;
}
.header{
	background-color: #000;
	height: 50px;
	color: #fff;
	padding: 17px 0 0 0;
	margin-top: 1px;
}
.div_row{
	height: 40px;
	margin-bottom: 5px;
	padding: 12px 0 0 0;

}
.foot{
	padding: 5px;
	margin-bottom: 40px;
}

.kq-date{
	margin-top: -8px;
}

.clear{
	clear: both;
}

</style>
<div class="row header">

<div class="col-xs-2 col-xs-offset-1">
<div onclick="loadHtmlByUrl('/manager/gotoallClass')">
<i class="fa fa-reply"></i>
</div>
</div>

<div class="col-xs-3 text-omit">
<?php echo $class?>
</div>

<div class="col-xs-5 kq-date">
<input class="form-control text" value="<?php echo $ttype?>" data-field="date" readonly id="startTime" type="text" placeholder="选择查看日期">
</div>
<div id="dtBox"></div>
</div>

<!-- 迟到学生信息 -->
<input type="hidden" value="<? echo $cid ?>" id="cid"/>

<div>
<div style="text-align: center;background-color: #3498db;color:#FFF;line-height: 40px;width: 50%;float: left;" onclick="allKQ()">
查看全部考勤
</div>
<div style="text-align: center;background-color: #c0392b;color:#FFF;line-height: 40px;width: 50%;float: left;" onclick="laterKQ()">
查看迟到学生
</div>
<div class="clear"></div>
</div>
<?php if(empty($kaoqing)){?>
<?php if($type == "all") {?>
<div class="col-xs-12" style="text-align: center;margin:20px 0 20px 0;">今天暂时没有刷卡信息</div>
<?php }?>
<?php if($type == "later") {?>
<div class="col-xs-12" style="text-align: center;margin:20px 0 20px 0;">今天暂时没有迟到信息</div>
<?php }}else {?>


<?php foreach ($kaoqing as $key=>$vo) {?>
<div class="row div_row">
<div class="col-xs-4 col-xs-offset-1 edit-user-top"><!-- 学生名字 -->

<?php echo $key + 1 ?>    <?php echo $vo['name'] ?>(<?php echo $vo['counter']?>)

</div>

<div class="col-xs-3 col-xs-offset-4 edit-user-top" onclick="loadHtmlByUrl('/manager/onestukaoqin')">

<i class="fa fa-chevron-right"></i><!-- 查看学生信息的箭头 -->

</div>

</div>
<?php }}?>


<div class="row foot">
<div class="col-xs-12">
<span class="badge">
帮助
</span>
<hr>
<div class="help-row-text">
点击【今天全部】，查看当前班级今天多有学生的刷卡情况，括号内为进出校刷卡条数。点击学生姓名右方箭头即可进入查看该学生详细进出时间<br/>
点击【迟到学生】，查看当前班级迟到学生刷卡情况，括号内为迟到次数。<br/>

</div>
</div>
</div>


<link href="/css/DateTimePicker.css" rel="stylesheet" type="text/css">
<script src="/js/DateTimePicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {

	$("#new-class-room").hide();

	$("#dtBox").DateTimePicker(
	{
		dateFormat: "yyyy-MM-dd",
		dateTimeFormat: "yyyy-MM-dd HH:mm:ss",
		timeFormat: "HH:mm",
		shortDayNames: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
		fullDayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
		shortMonthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
		fullMonthNames:  ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
		titleContentDate: "您要查看哪一天的考勤？",
		titleContentTime: "您要查看哪一天的考勤？",
		titleContentDateTime: "您要查看哪一天的考勤？",
		buttonsToDisplay: ["HeaderCloseButton", "SetButton", "ClearButton"],
		setButtonContent: "确定",
		clearButtonContent: "取消"
	});
});

	function allKQ(){
		doKQ("all");
	}

	function laterKQ(){
		doKQ("later");
	}

	function doKQ(type){
		var ttype = $("#startTime").val();
		var path = $("#path").val();
		var sid = $("#sid").val();
		var cid = $("#cid").val();
		var openid = $("#openid").val();
		var url = "/manager/classkaoqin?cid="+cid+"&type="+type+"&ttype="+ttype;
		loadHtmlByUrl(url);
	}

	</script>

