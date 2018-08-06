<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
  <link media="all" rel="stylesheet" type="text/css" href="/css/home-css.css" />
  <script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
  <style type="text/css">
    body{
      margin: 0;
      padding: 0;
      font-size:15px;
    }
    #cost-main{
      margin: 10px 0 10px 0;
    }
    .cost-text{
      float: left;
      width: 25%;
      text-align: right;
      line-height: 50px;
    }
    .cost-title{
      float: left;
      width: 75%;
      line-height: 50px;
      text-align: center;
      color: #E74C3C;
    }
    .pay-main{
      font-family: "微软雅黑";
      font-size: 1rem;
    }
    .cost-num{
      width: 25%;
      float: left;
      line-height: 50px;
      text-align: right;
    }
    .cost-list{
      width: 70%;
      float: left;
      padding: 0 0 0 5%;
      height: 50px;
    }
    .cost-list-bg{
      height: 50px;
      border-radius: 7px;
    }
    .cost-form{
      float: left;
      background-color: #ecf0f1;
      color: #000000;
      width: 100%;
      text-align: center;
      padding: 0 0 50px 0;
    }
    .radio-form{
      background-color: #FFFFFF;
    }
    .clear{
      clear: both;
    }
    /* 单选按钮 */
    input[type=radio]
    {
      visibility:hidden;
    }
    .radio
    {
      width:30%;
      margin-left: 2.5%;
      line-height: 50px;
      position:relative;
      color: #FFFFFF;
      float: left;
    }
    .radio label
    {
      display:block;
      width:100%;
      height:50px;
      cursor:pointer;
      position:absolute;
      top:0;
      left:0;
      z-index:1;
      color: #000000;
      background-color:#FFFFFF;
      border-radius: 7px;
      border: 1px solid #95a5a6;
    }
    .radio input[type=radio]:checked + label
    {
      background-color:#27ae60;
      color: #FFFFFF;
      border-radius: 7px;
      border: 1px solid #27ae60;
    }
    .cost-pay{
      margin-top: 50px;
    }
    #jsapi_pay{
      float: left;
      width: 40%;
      margin-left: 5%;
      text-align: center;
      line-height: 50px;
      background-color: #E74C3C;
      border-radius: 7px;
      color: #FFFFFF;
    }
    #native_pay{
      float: right;
      width: 40%;
      margin-right: 5%;
      text-align: center;
      line-height: 50px;
      background-color: #27ae60;
      border-radius: 7px;
      color: #FFFFFF;
    }
    #item{
      margin: 10px 0 10px 0;
    }
    .item-name{
      float: left;
      width: 25%;
      text-align: right;
      line-height: 50px;
    }
    .item-select{
      float: left;
      width: 75%;
      line-height: 50px;
    }
    .item-select select{
      width: 80%;
      border-radius: 5px;
      padding: 5px;
    }
    #inall{
      margin: 10px 0 10px 0;
    }
    .inall-text{
      float: left;
      width: 25%;
      text-align: right;
      line-height: 50px;
    }
    .inall-num{
      float: left;
      width: 75%;
      line-height: 50px;
      color: #E74C3C;
    }
  </style>
  <title><?php $tlx = $_GET['lx']; if($tlx == "jiashou"){echo "家校沟通";}else{echo "平安通知";} ?>功能费支付</title>
</head>
<body>
<div class="pay-main">

  <div class="cost-form">
    <form class="radio-form">

      <div id="item">
        <div class="item-name">学生名称:</div>
        <div class="item-select">
          <select id="trade_name">
            <foreach name="childs" item="vo">
              <option id="{$vo.sid}" value="{$vo.stu_id}" class="{$vo.school}|{$vo.class}|{$vo.stu_name}">{$vo.school}{$vo.class}{$vo.stu_name}</option>
            </foreach>
          </select>
        </div>
        <div class="clear"></div>
      </div>
      <div id="cost">
        <div class="cost-num">缴费时长:</div>
        <div class="cost-list">
          <div class="cost-list-bg">
            <div class="radio">
              <input id="oneyear" type="radio" name="cost" value="12" class="num" checked />
              <label class="label" for="oneyear">一学年</label>
            </div>
            <div class="radio">
              <input id="sixmonths" type="radio" name="cost" value="6" class="num" />
              <label class="label" for="sixmonths">一学期</label>
            </div>

          </div>
        </div>
        <div class="clear"></div>
      </div>

      <div id="inall">
        <div class="inall-text">共计:</div>
        <div class="inall-num" id="total" data-id="60"></div>
        <div class="clear"></div>
      </div>

    </form>

    <div class="cost-pay">
      <div id="native_pay">扫码支付</div>
      <div id="jsapi_pay">微信支付</div>
      <div class="clear"></div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<input type="hidden" id="path" value="{$path}">
<input type="hidden" id="openid" value="{$openid}">
<input type="hidden" id="leixing" value="{$leixing}">
</body>
<script type="text/javascript">
  function mapAllParam(type){
    var param = {};
    param.total = $("#total").attr("data-id");
    param.trade_name = $("#trade_name option:selected").attr("class");
    param.stu_id = $("#trade_name").val();
    param.openid = $("#openid").val();
    param.paytype = type;
    param.sjgx = $('input[name="cost"]:checked ').val();
    param.leixing = $("#leixing").val();
    return param;
  }

  $(function(){
    var url = $("#path").val()+"/index.php?s=/addon/ZhiFu/ZhiFu/redirectPay";
    $("#jsapi_pay").on("click",function(){

      $.getJSON(url,mapAllParam("JSAPI"),function(data){
        if(data.retcode==0){
          window.location = data.url;
        }else{
          alert(data.retmsg);
        }
      });
    });
    $("#native_pay").on("click",function(){
      $.getJSON(url,mapAllParam("NATIVE"),function(data){
        if(data.retcode==0){
          window.location = data.url;
        }else{
          alert(data.retmsg);
        }
      });
    });
    $(".num").on("click",function(){
      var num = parseInt($(this).val());
      var num1 = parseInt($("#oneyear").val());
      var num2 = parseInt($("#sixmonths").val());
      var lx = $("#leixing").val();
      $stu_id = $(":selected","#trade_name").val();
      $slid = $(":selected","#trade_name").attr("id");
      /*      if($stu_id == 93958){
       $("#total").attr("data-id",1).text((1)+"元");
       exit();
       }*/
      if($stu_id == 96957 || $stu_id == 93958)
      {
        num=0.01;
        $("#total").attr("data-id",0.01).text((0.01)+"元");
        exit();
      }
      if($(this).val() == 12)
      {
        $("#total").attr("data-id",30).text((30)+"元");
      }
      if($(this).val() == 6)
      {
        $("#total").attr("data-id",18).text((18)+"元");
      }
    });
  });
</script>
</html>


