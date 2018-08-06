<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/onechild?stuid=<?php echo $stuid?>&stuname=<?php echo $stuname?>&openid=<?php echo $openid?>')">        
        <i class="fa fa-reply"></i>
      </div>
    </div>

    <div class="col-xs-4 col-xs-offset-4 text-align-l">         
  <span class="add-class" id="ask_leave">
        提交  
  </span>   
    </div>  

</div>

<div class="row edit-user-row">

  <div class="col-xs-4 col-xs-offset-1 text-align-l">
    开始时间 ：
  </div>
  <div class="col-xs-6 text-align-l">
    <input class="form-control text" data-field="datetime" readonly id="startTime" type="text" placeholder="点我选取开始时间">
  </div>

</div>

<div class="row edit-user-row">

  <div class="col-xs-4 col-xs-offset-1 text-align-l">
    结束时间 ：
  </div>
  <div class="col-xs-6 text-align-l">
    <input value="" class="form-control text" data-field="datetime" readonly id="endTime" type="text">
  </div>

  <div id="dtBox"></div>

</div>

<div class="row">
    <div class="col-xs-12">
        <textarea class="form-control" id="reason" rows="14" placeholder="请假理由"></textarea>
    </div>
</div>
<input type="hidden" id="path" value="<?php echo URL_PATH?>">
<input type="hidden" id="openid" value="<?php echo $openid?>">
<input type="hidden" id="stuid" value="<?php echo $stuid?>">

<link href="/css/DateTimePicker.css" rel="stylesheet" type="text/css">
<script src="/js/DateTimePicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
  $(function () {
      var d = new Date();
      var ymd = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate();
      $("#endTime").val(ymd+" "+"23:59");
      $("#dtBox").DateTimePicker(
        {
          dateFormat: "yyyy-MM-dd",
          dateTimeFormat: "yyyy-MM-dd HH:mm:ss",
          timeFormat: "HH:mm",
          shortDayNames: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
          fullDayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
          shortMonthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
          fullMonthNames:  ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
          titleContentDate: "设置日期",
          titleContentTime: "设置时间",
          titleContentDateTime: "设置请假时间",
          buttonsToDisplay: ["HeaderCloseButton", "SetButton", "ClearButton"],
          setButtonContent: "确定",
          clearButtonContent: "取消"
        });
  });

  $("#ask_leave").on("click",function(){


    if ($("#startTime").val() == ""){

      alert("请选择开始时间");
    }else if($("#endTime").val() == "") {

      alert("请选择结束时间");

    }else{
      var startTime=$("#startTime").val();
      var start=new Date(startTime.replace("-", "/").replace("-", "/"));
      var endTime=$("#endTime").val();
      var end=new Date(endTime.replace("-", "/").replace("-", "/"));

      if(end<=start){

        alert("结束时间应大于开始时间");

      }else{

          var path=$("#path").val();
          var openid=$("#openid").val();
          var stuid =$("#stuid").val();
          var leave_reason = $.trim($("#reason").val());

          var url =path+"/information/askchildleave/";
          var para = {"openid":openid,"stuid":stuid,"begintime":startTime.replace(" ","-").replace(":","-"),"endtime":endTime.replace(" ","-").replace(":","-"),'leave_reason':leave_reason};
          $.getJSON(url,para,function(data){
            if(data==0){
              alert("请假成功！");
              $(".text").val("");
                backto('<?php echo URL_PATH?>/information/onechild?stuid=<?php echo $stuid?>&stuname=<?php echo $stuname?>&openid=<?php echo $openid?>');
            }else{
              alert("请假失败，请重试！");
            }
          });

      }
    }
  })
</script>

