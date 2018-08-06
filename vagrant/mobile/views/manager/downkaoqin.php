<div class="col-xs-12 register-user-center-margin">
<div class="row examine-user-list" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)">
<div class="col-xs-3">日期</div>
<div class="col-xs-8" style="top:-7px;">

<input  class="form-control text col-xs-6" value="" data-field="date" readonly id="startTime" type="text" placeholder="选择导出开始日期">
<br/>
<input style="margin-top: 10px" class="form-control text col-xs-6" value="" data-field="date" readonly id="endtTime" type="text" placeholder="选择导出结束日期">

</div>
<div id="dtBox"></div>
<div class="row examine-user-list" style="margin-top: 90px" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)">
<div class="col-xs-3"></div>
<div class="col-xs-7" style="top:-7px;margin-top: 30px">
<span style="width:150px; padding:7.5px 35px" class="data-card-add" onclick="doDownKQ()">导出考勤</span><br>
<span style="width: 120px;padding:6px 0px; display:block;margin-top: 10px" class="data-card-add text-center" onclick="doDownKQhz()">导出考勤汇总</span>
</div>
</div>
</div>
</div>


<div class="col-xs-12 register-user-center-margin">

</div>

<!-- 迟到学生信息 -->

<a href="" id="downhref" target="_self"></a>
<div class="row foot" style="margin-top: 150px;position:relative ;z-index: -1;padding:0 10px ">
<div class="col-xs-12">
<span class="badge">
帮助
</span>
<div class="help-row-text" style="margin-top: 10px;border-top: 1px solid #cccccc;line-height: 50px">
点击【导出考勤】，默认下载所选日期当天全部出校信息<br/>
</div>
</div>
</div>


<link href="/css/DateTimePicker.css" rel="stylesheet" type="text/css">
<script src="/js/DateTimePicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {

	$("#new-class-room").hide();
	$("#dtBox").css({'z-index':'9999'})
	$("#dtBox").DateTimePicker(
	{
		dateFormat: "yyyy-MM-dd",
		dateTimeFormat: "yyyy-MM-dd HH:mm:ss",
		timeFormat: "HH:mm",
		shortDayNames: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
		fullDayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
		shortMonthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
		fullMonthNames:  ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
		titleContentDate: "您要导出哪一天的考勤？",
		titleContentTime: "您要导出哪一天的考勤？",
		titleContentDateTime: "您要导出哪一天的考勤？",
		buttonsToDisplay: ["HeaderCloseButton", "SetButton", "ClearButton"],
		setButtonContent: "确定",
		clearButtonContent: "取消"
	});
});

	function doDownKQ(){
		var downtime = $("#startTime").val();
		var endtTime = $("#endtTime").val();
		if(downtime==""){
			alertDialog("请先选择考勤开始日期");
			return 0;
		}
		if(endtTime==""){
			alertDialog("请先选择考勤结束日期");
			return 0;
		}
		var path = $("#path").val();
		var sid = $("#sid").val();
		var openid = $("#openid").val();
		var url = "/manager/dodownkaoqin";
		var para = {"sid":sid,"openid":openid,"downtime":downtime,"endtTime":endtTime};
		$.getJSON(url,para,function(data){
			if(data.flag==0){
				window.location.href = data.url;
			}else{
				alertDialog("导出失败请重试");
			}
		});
	}

	function doDownKQhz(){
		var downtime = $("#startTime").val();
		var endtTime = $("#endtTime").val();
		if(downtime==""){
			alertDialog("请先选择考勤开始日期");
			return 0;
		}
		if(endtTime==""){
			alertDialog("请先选择考勤结束日期");
			return 0;
		}
		var path = $("#path").val();
		var sid = $("#sid").val();
		var openid = $("#openid").val();
		var url = "/manager/dodownkaoqinhz";
		var para = {"sid":sid,"openid":openid,"downtime":downtime,"endtTime":endtTime};
		$.getJSON(url,para,function(data){
			if(data.flag==0){
				window.location.href = data.url;
			}else{
				alertDialog("导出失败请重试");
			}
		});
	}
	</script>

