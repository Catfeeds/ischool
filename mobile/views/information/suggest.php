<?php
/* @var $this yii\web\View */
use mobile\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;
AppAsset::register($this);
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
  <link media="all" rel="stylesheet" type="text/css" href="/css/tongzhi.css" />
  <link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" />
  <link media="all" rel="stylesheet" type="text/css" href="/css/ui-dialog.css" />
  <link media="all" rel="stylesheet" type="text/css" href="/css/style.css" />

  <link media="all" rel="stylesheet" type="text/css" href="/css/font-awesome.css" />
  <link media="all" rel="stylesheet" type="text/css" href="/css/simditor.css" />
  <link media="all" rel="stylesheet" type="text/css" href="/css/record.css" />

  <script type="text/javascript" src="/js/css3-mediaqueries.js"></script>
  <script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/js/LocalResizeIMG.js"></script>
<script type="text/javascript" src="/js/mobileBUGFix.mini.js"></script>
  
  <script type="text/javascript" src="/js/bootstrap-confirmation.js"></script>
  <script type="text/javascript" src="/js/dialog-min.js"></script>
  <script type="text/javascript" src="/js/ajaxload.js"></script>
  <script type="text/javascript" src="/js/myDialog.js"></script>
  <script type="text/javascript" src="/js/jweixin-1.0.0.js"></script>
  <script type="text/javascript" src="/js/record.js"></script>

  <script type="text/javascript" src="/js/simditor-all.js"></script>
 <script type="text/javascript" src="/js/page-demo.js"></script>
  <style>
    .jxt_op_btn{

      width: 80px;
      color: #fff;
      padding: 8px 0 8px 0;
      margin-top: 5px;
      border-radius: 5px;
      text-align: center;
    }
    .jxt_op_btnol{

        width: 80px;
        color: #fff;
        padding: 8px 0 8px 0;
        margin-top: 5px;
        border-radius: 5px;
        text-align: center;
    }
    .jxt_current_btn{
      margin-top: 10px;
    }
    #send_btn{
      background-color: #f66;
    }
    #back-btn{
        padding: 8px 8px;
        width: 70px;
        text-align:center;
        font-size:1.6rem;
        margin-top:2%;
    }
    #receive_btn{
      background-color: #8ac007;
    }
    #sent_btn{
      background-color: #3498db;
    }
    #sendMsg{
      width: 100%;
      margin-bottom: 5px;
      background-color: #f66;
    }
  </style>
  
</head>
<body class="body-color">
<title>投诉建议</title>
<input type="hidden" id="path" value="<?php echo URL_PATH?>">
<header>
  <div class="container-fluid">
    <div class="row header-menu" id="header-menu1">
      <div class="col-xs-12">

       <div class="col-xs-3 jxt_op_btnol" id="back-btn" >
              <a href="<?php echo URL_PATH?>/information/index?openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo \yii::$app->view->params['sid']?>"><span class="fa fa-reply"></span>&nbsp;返回</a>
         </div > 
         <div class="col-xs-6" style="color:#fff; padding:8px 0 8px 0;margin-top:5px;margin-left:5%;border-radius:5px;text-align:center;font-size:1.7rem;">
      我的意见
        </div>
      </div>
    </div>
  </div>
</header>
<main class="cd-main-content" id="mycontainer"> <!-- 页面主体 -->
    <div id="receive"> <!-- 写信息 -->
      <div class="container-fluid">

        <div class="row receive-user all-menu-trigger" >
          <div class="col-xs-2" style="text-align:center;border-radius:0.7rem; background-color:#f66;margin-top:1%;margin-left:5%;color:#fff;padding:3px 2px;" >标题</div>
          <div class="col-xs-9">
            <input type="text" class="form-control" id="content-title" value="<?php echo $name?>" placeholder="请输入主题信息...">
          </div>
          <input type="hidden" id="ids" value="<?php echo $toopenid?>" />
          <input type="hidden" id="msgType" value="ly" />        
        </div>

      </div>  

      <div class="container-fluid receive-content">
        
        <div class="row" id="textarea-text" style="display:block">
          <div class="col-xs-12">
            <textarea placeholder="请输入内容信息..." class="form-control" rows="14" id="txt-content" ></textarea>
          </div>
        </div>
        <div class="row" style="display:block;margin-bottom: 5px;">
          <div class="col-xs-12">
            <div class="jxt_op_btn" id="sendMsg">发送</div>
          </div>
        </div>
        
    <div class="row">
      <div class="col-xs-12" id="record-waper" style="display:none">
        <div class="close-record-waper" onclick="close_record_waper()">
          <i class="fa fa-chevron-down"></i>
        </div>
        <div class="record-time">
          录音时间: <font id="record-time-text">00:00</font>
        </div>
        <div class="record-popo" style="display:block" onclick="record_popo();startTime(0,0)">
          <i class="fa fa-microphone record-popo-ico"></i>
        </div>
        <div class="record-stop" style="display:none" onclick="record_stop();stoptime()">
          <i class="fa fa-stop record-stop-ico"></i>
        </div>
        <div class="record-play" style="display:none"  onclick="record_play()">
          <i class="fa fa-play record-play-ico"></i>
        </div>
        <div class="record-play-stop" style="display:none"  onclick="record_play_stop()">
          <i class="fa fa-pause record-stop-ico"></i>
        </div>        
        <div class="col-xs-5" id="record-close" style="display:none" onclick="record_close();cleartime()">
          重录
        </div>
        <div class="col-xs-5 col-xs-offset-2" id="record-up" style="display:none" onclick="recordup();cleartime()">
          确定
        </div>

       
      </div>
    </div>


   <div class="row">
      <div class="col-xs-12" id="uloadfile-waper" style="display:none">
        <div class="close-uloadfile-waper" onclick="close_uloadfile_waper()">
          <i class="fa fa-chevron-down"></i>
        </div>

        <div class="col-xs-12" id="uloadfile-up" onclick="uloadfile_up()">
          上传附件
          <form  action="<?php echo URL_PATH?>/upload/function/upfile.php" method="post" target="hidden_frame" enctype="multipart/form-data" id="formuloadfile" role="form">
              <input type="file" accept="" title="上传附件" name="uloadfile" style="display:none" id="uloadfile" onchange="uploadfile()">
          </form>
          <iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe> 
        </div>
      </div>
    </div>

     <div id="record-file-waper"></div>
      </div>
  </div>

</main> <!-- cd-main-content 页面主体 结束 -->
<input type="hidden" value="<?php echo $qunzu?>" id="qunzu" />
<input type="hidden" value="<?php echo $username?>" id="username" />
<!--公共隐藏域-->
<input type="hidden" value="<?php echo \yii::$app->view->params['sid']?>" id="hidden_sid" />
<?php if(isset($ischool)){?>
<input type="hidden" value="<?php echo $ischool?>" id="hidden_school" />
<?php }?>
<input type="hidden" value="<?php echo \yii::$app->view->params['openid']?>" id="hidden_openid" />


<input type="hidden" id="appid" value="<?php echo $appid?>">
<input type="hidden" id="timestamp" value="<?php echo $timestamp?>">
<input type="hidden" id="nonceStr" value="<?php echo $nonceStr?>">
<input type="hidden" id="signature" value="<?php echo $signature?>">
<input type="hidden" id="localId" value="ta">
<input type="hidden" id="calId" value="">
<input type="hidden" id="serid" value="one">

<input type="hidden" id="ty" value="<?php echo $ty?>">
<input type="hidden" id="fujian" value="<?php echo $fujian?>">
<input type="hidden" id="serverId" value="<?php echo $serverId?>">
<input type="hidden" id="operate_type" value="<?php echo $type?>">
<input type="hidden" id="path" value="<?php echo URL_PATH?>">
<?php echo $this->render('../layouts/footer')?>
</body>
</html>
<script type="text/javascript">

var appid=$("#appid").val();
var timestamp=$("#timestamp").val();
var nonceStr=$("#nonceStr").val();
var signature=$("#signature").val();
wx.config({
  debug:false,
  appId:appid,
  timestamp:timestamp,
  nonceStr:nonceStr,
  signature:signature,
  jsApiList:['checkJsApi',
    'onMenuShareTimeline',
    'onMenuShareAppMessage',
    'onMenuShareQQ',
    'onMenuShareWeibo',
    'hideMenuItems',
    'showMenuItems',
    'hideAllNonBaseMenuItem',
    'showAllNonBaseMenuItem',
    'translateVoice',
    'startRecord',
    'stopRecord',
    'onRecordEnd',
    'playVoice',
    'pauseVoice',
    'stopVoice',
    'uploadVoice',
    'downloadVoice',
    'chooseImage',
    'previewImage',
    'uploadImage',
    'downloadImage',
    'getNetworkType',
    'openLocation',
    'getLocation',
    'hideOptionMenu',
    'showOptionMenu',
    'closeWindow',
    'scanQRCode',
    'chooseWXPay',
    'openProductSpecificView',
    'addCard',
    'onVoiceRecordEnd',
    'onVoicePlayEnd',
    'chooseCard',
    'openCard']
});


wx.error(function(res){
  //alert(res);
  // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
});


function close_record_waper(){
  $("#record-waper").slideToggle();
}

function close_uloadfile_waper(){
  $("#uloadfile-waper").slideToggle();
}

function record_popo(){
  wx.startRecord({
    cancel: function () {
      alert('用户拒绝授权录音');
    }
  });

  $(".record-popo").css({"display":"none"});
  $(".record-stop").css({"display":"block"});
}
function record_stop(){
  wx.stopRecord({
    success: function (res) {
      var localId = res.localId;
      $("#localId").val(localId);
    }
  });

  $(".record-stop").css({"display":"none"});
  $(".record-play").css({"display":"block"});
  $("#record-close").css({"display":"block"});
  $("#record-up").css({"display":"block"});
}



wx.ready(function () {

  //播放完毕
  wx.onVoicePlayEnd({
    complete: function (res) {
      $(".play-record").css({"display":"block"});
      $(".stop-record").css({"display":"none"});

      $(".record-play").css({"display":"block"});
      $(".record-play-stop").css({"display":"none"});
    }
  });
  // 录音时间超过一分钟没有停止的时候会执行 complete 回调
  wx.onVoiceRecordEnd({


    complete: function (res) {
      var localId = res.localId;
      alert("录音时间超过最长时间")
    }
  });

});

function record_play(){
  var localId=$("#localId").val();
  wx.playVoice({
    localId: localId // 需要播放的音频的本地ID，由stopRecord接口获得
  });
  $(".record-play").css({"display":"none"});
  $(".record-play-stop").css({"display":"block"});
}
function record_play_stop(){
  var localId=$("#localId").val();
  wx.pauseVoice({
    localId: localId // 需要暂停的音频的本地ID，由stopRecord接口获得
  });
  $(".record-play-stop").css({"display":"none"});
  $(".record-play").css({"display":"block"});
}

function record_close(){
  $("#record-close").css({"display":"none"});
  $("#record-up").css({"display":"none"});
  $(".record-play").css({"display":"none"});
  $(".record-play-stop").css({"display":"none"});
  $(".record-popo").css({"display":"block"});
}
function one(thi)
{
  alert(thi);
}


function recordup(){
  $("#play-waper").remove();
  var localId=$("#localId").val();

  wx.uploadVoice({
    localId:localId, // 需要上传的音频的本地ID，由stopRecord接口获得
    isShowProgressTips: 1, // 默认为1，显示进度提示
    success: function (res) {
      var serverId = res.serverId; // 返回音频的服务器端ID
      $("#serid").val(serverId);
      alert("上传成功");
    }
  });
  $("#record-waper").slideToggle();

  if ($("#record-file-waper").find("#play-waper").length == 1) {
    $("#play-center-waper").append("<div id='play-center-waper'><div class='play-center'><div class='play-record' style='display:block' onclick='text_play_record(this);'><i class='fa fa-play play-record-ico'></i></div><div class='stop-record' style='display:none' onclick='text_stop_record(this)'><i class='fa fa-pause stop-record-ico'></i></div><div class='record-text'>语音信息</div></div><div class='del-center-waper'><div class='del-record' onclick='delrecord(this)'>删除</div></div>");
  }else{
    $("#record-file-waper").append("<div id='play-waper'><div class='play-title'>语音消息</div><div id='play-center-waper'><div class='play-center'><div class='play-record' style='display:block' onclick='text_play_record(this);'><i class='fa fa-play play-record-ico'></i></div><div class='stop-record' style='display:none' onclick='text_stop_record(this)'><i class='fa fa-pause stop-record-ico'></i></div><div class='record-text'>语音信息</div></div><div class='del-center-waper'><div class='del-record' onclick='delrecord(this)'>删除</div></div></div></div>");
  }
}

function delrecord(the){
  $(the).parent().parent().parent().remove();
  $("#serid").val("onee");
}
function text_play_record(){
  var localId=$("#localId").val();

  if(localId=="ta")
  {
    var serid=$("#serid").val();
    wx.downloadVoice({
      serverId:serid, // 需要下载的音频的服务器端ID，由uploadVoice接口获得
      isShowProgressTips: 1, // 默认为1，显示进度提示
      success: function (res) {
        localId = res.localId; // 返回音频的本地ID
        wx.playVoice({
          localId: localId // 需要播放的音频的本地ID，由stopRecord接口获得
        });
      }
    });
  }
  else
  {
    wx.playVoice({
      localId: localId // 需要播放的音频的本地ID，由stopRecord接口获得
    });
  }

  $(".play-record").css({"display":"none"});
  $(".stop-record").css({"display":"block"});

}
function text_stop_record(){
  $(".play-record").css({"display":"block"});
  $(".stop-record").css({"display":"none"});
  var localId=$("#localId").val();
  wx.pauseVoice({
    localId: localId // 需要暂停的音频的本地ID，由stopRecord接口获得
  });
}


function down(id){
  var serverid=$("#s"+id).val();
  wx.downloadVoice({
    serverId:serverid, // 需要下载的音频的服务器端ID，由uploadVoice接口获得
    isShowProgressTips: 1, // 默认为1，显示进度提示
    success: function (res) {
      var localId = res.localId; // 返回音频的本地ID
      $("#d"+id).attr("value",localId);
      wx.playVoice({
        localId: localId // 需要播放的音频的本地ID，由stopRecord接口获得
      });
    }
  });
}

function play_record(ths){
  $(ths).next().show();
  $(ths).hide();
}

function stop_record(ths){
  var key = $(ths).attr("name"); 
  var localId = $("#d"+key).val();  
  wx.pauseVoice({
    localId: localId // 需要暂停的音频的本地ID，由stopRecord接口获得
  });
  $(ths).prev().show();
  $(ths).hide();
}

function dotion(serverid){
  var serverid=serverid;
  wx.downloadVoice({
    serverId:serverid, // 需要下载的音频的服务器端ID，由uploadVoice接口获得
    isShowProgressTips: 1, // 默认为1，显示进度提示
    success: function (res) {
      var localId = res.localId; // 返回音频的本地ID
      wx.playVoice({
        localId: localId // 需要播放的音频的本地ID，由stopRecord接口获得
      });
    }
  });
  $(".record-play").css({"display":"none"});
  $(".record-play-stop").css({"display":"block"});
}
function startTime(i,s)
{
// add a zero in front of numbers<10
  op=checkTime(i);
  sp=checkTime(s);
  if(i>58)
  {
    i=0;
    s=s+1;
  }

  document.getElementById('record-time-text').innerHTML=sp+":"+op;
  i=i+1;
  t=setTimeout('startTime('+i+','+s+')',1000);
}

function checkTime(i)
{
  if (i<10)
  {
    i="0" + i;
  }
  return i;
}

var t;
function stoptime()
{
  clearTimeout(t);
}

function cleartime()
{
  $("#record-time-text").text("00:00");
}
jQuery(document).ready(function($){
    var fujian=$("#fujian").val();
    var ty=$("#ty").val();
    if(ty=="voice")
    {

      var serid=$("#serverId").val();
      $("#serid").val(serid);
      $("#record-file-waper").append("<div id='play-waper'><div class='play-title'>语音消息</div><div id='play-center-waper'><div class='play-center'><div class='play-record' style='display:block' onclick='text_play_record(this);'><i class='fa fa-play play-record-ico'></i></div><div class='stop-record' style='display:none' onclick='text_stop_record(this)'><i class='fa fa-pause stop-record-ico'></i></div><div class='record-text'>语音信息</div></div><div class='del-center-waper'><div class='del-record' onclick='delrecord(this)'>删除</div></div></div></div>");
    }
    if(fujian!="f"&&fujian!="")
    {
      $("#record-file-waper").append("<div id='file-waper'><div class='file-title'>附件</div></div>");
      var html="";
      var fuji = fujian.split("#");
      for(var i=0;i<fuji.length;i++)
      {
        html+="<div class='file-center'><input type='hidden' value='"+fuji[i]+"' name='one'><div class='file-ico'><i class='fa fa-folder-open file-record-ico'></i><div class='file-name'>附件</div></div><div class='file-down'><div class='down-popo' onclick='delfile(this)'>删除</div></div></div>";
      }
      

      $("#file-waper").append(html);
      
    }

    $("#sendMsg").bind("click",doSendMsg);

});

function callback(message,success,name) 
{
      if(success==false) 
      { 
         alert("上传失败");
      } 
      else
      { 

      if ($("#record-file-waper").find("#file-waper").length == 1) {
      $("#file-waper").append("<div class='file-center'><input type='hidden' value='"+message+"' name='one'><div class='file-ico'><i class='fa fa-folder-open file-record-ico'></i><div class='file-name'>"+name+"</div></div><div class='file-down'><div class='down-popo' onclick='delfile(this)'>删除</div></div></div>");
      }else{
        $("#record-file-waper").append("<div id='file-waper'><div class='file-title'>附件</div><div class='file-center'><input type='hidden' value='"+message+"' name='one'><div class='file-ico'><i class='fa fa-folder-open file-record-ico'></i><div class='file-name'>"+name+"</div></div><div class='file-down'><div class='down-popo' onclick='delfile(this)'>删除</div></div></div></div>");
      };

      } 
}
function delfile(th){
$(th).parent().parent().remove()
}

function uploadfile() {

  var names=$("#uloadfile").val().split("."); 
  if(names[1]=="exe") { 

      alert("您上传的文件格式不符合");

      return; 
  } 

    $("#formuloadfile").submit();


}


//发信息操作/
function doSendMsg(){
  var serid=$("#serid").val();
  var path=$("#path").val();
//  var operate_type = $("#operate_type").val();
  var strpath="one";
  if($.trim($("#content-title").val())==""){
    alertDialog('请填写标题');
    return false;
  }
  if($.trim($("#txt-content").val())==""&&serid=="one"){
    alertDialog('请填写信息内容！');
  }else{
    var msg=$("#txt-content").val();
    var openid= $("#hidden_openid").val();
    var sid=$("#hidden_sid").val();
    var one =document.getElementsByName("one");
    for (var i = 0, j = one.length; i < j; i++){
      strpath+="#"+one[i].value;
    }
    var title = $("#content-title").val();
    var url=path+'/information/dosendmsg';
    var para={msg:msg,serid:serid,strpath:strpath,title:title};
    var to_url=path+"/information/index?openid="+openid+"&sid="+sid+"&status=1";
    $.getJSON(url,para,function(data){
    if (data == 0){
          alertDialog("感谢您的建议");
                window.location='/information/index?&openid='+openid+'&sid='+sid+'&status=1';
          }else {
             alertDialog("提交失败");
         }
     })
    // var options = {para:para,ele:$("#add_span7"),sub_url:url,to_url:to_url,urltype:0,status:[{code:0,content:'信息发送失败，请重试'}]};
    // sub_dialog(options);
    $("#serid").val("");

  }
}

function uloadfile_up(){
  var res;
  res = document.getElementById("uloadfile");
  res.click();
}

</script>







