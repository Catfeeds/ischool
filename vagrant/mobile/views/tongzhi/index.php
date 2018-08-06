<?php
/* @var $this yii\web\View */

$this->title = '正梵智慧校园';
?>
<input type="hidden" id="path" value="<?php echo URL_PATH?>">
<header>
  <div class="container-fluid">
    <div class="row header-menu" id="header-menu1">

      <div class="col-xs-12">
         <div class="col-xs-3 jxt_op_btnol" id="back-btn">
              <a href="<?php echo URL_PATH?>/information/index?openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo $sid?>"><span class="fa fa-reply"></span>返回</a>
         </div>
        <div class="col-xs-3 pull-right">
            <div name="<?php echo URL_PATH?>/tongzhi/sendmsg?openid=<?php echo \yii::$app->view->params['openid']?>" class="jxt_op_btn" id="send_btn">写信</div>
        </div>
        <div class="col-xs-3 pull-right">
          <div name="<?php echo URL_PATH?>/tongzhi/inbox?openid=<?php echo \yii::$app->view->params['openid']?>" class="jxt_op_btn jxt_current_btn" id="receive_btn">收信</div>
        </div>
        <div class="col-xs-3 pull-right">
          <div name="<?php echo URL_PATH?>/tongzhi/outbox?openid=<?php echo \yii::$app->view->params['openid']?>" class="jxt_op_btn" id="sent_btn">已发</div>
        </div>
      </div>
    </div>
  </div>
</header> 

<main class="cd-main-content" id="mycontainer"> <!-- 页面主体 -->

</main> <!-- cd-main-content 页面主体 结束 -->
<!-- ................................................................... -->
<nav id="cd-lateral-nav"> <!-- 通讯录 -->
  <div class="container-fluid margin-footer">
    <div id="display-list" style="margin-top:50px;"> <!-- display-list 设置页面的显示 和 隐藏 -->
      <div class="row user-search">
        <form class="form-horizontal" role="form">
          <div class="col-xs-12">
            <!--<div class="form-group has-success has-feedback">
                <input type="text" class="form-control" id="inputSuccess3" placeholder="搜索您想要找的人">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>-->
          </div>
        </form>
      </div> <!-- 搜索结束 -->
      <div class="row user-list" id="stu-list">
<!--        <foreach name="list_lxr" item="vo" key="key">-->
    <?php foreach( $list_lxr as $k=>$v){?>
            
          <div class="user-list-row" onclick="user_checkbox(this,event)">
            <div class="col-xs-9"><?php echo $v['name'] ?>家长</div>
            <div class="col-xs-3">
              <div class="checkbox">
                <input type="checkbox" value="<?php echo $v['id'] ?>" name="<?php echo $v['name'] ?>" id="u<?php echo $v['name'] ?>" class="lxr" />
                <label for="u<?php echo $v['name'] ?>"></label>
              </div>
            </div>
          </div>
<!--        </foreach>-->
    <?php }?>


<!--        <foreach name="list_tea" item="vo" key="key">-->
    <?php foreach( $list_tea as $k=>$v){?>
          <div class="user-list-row" onclick="user_checkbox(this,event)">
            <div class="col-xs-9"><?php echo $v['tname'] ?>老师</div>
            <div class="col-xs-3">
              <div class="checkbox">
                <input type="checkbox" value="<?php echo $v['openid'] ?>" name="<?php echo $v['tname'] ?>" id="u<?php echo $v['tname'] ?>" class="lxr" />
                <label for="u<?php echo $v['tname'] ?>"></label>
              </div>
            </div>
          </div>
<!--        </foreach>-->
     <?php }?>
      </div> <!-- user-list 用户列表 -->

      <div class="row user-list" id="class-list" style="display:none;">

<!--        <foreach name="list_class" item="vo" key="key">-->
    <?php foreach( $list_class as $k=>$v){?>
          <div class="user-list-row" onclick="user_checkbox(this,event)">
            <div class="col-xs-9"><?php echo $v['class'] ?></div>
            <div class="col-xs-3">
              <div class="checkbox">
                <input type="checkbox" value="<?php echo $v['cid'] ?>" name="<?php echo $v['class'] ?>" id="c<?php echo $v['cid'] ?>" class="bj" />
                <label for="c<?php echo $v['cid'] ?>"></label>
              </div> 
            </div>
          </div>
<!--        </foreach>-->
  <?php }?>
      </div> <!-- user-list 班级列表 -->

    </div> <!-- display-list -->
  </div> <!-- container-fluid -->
</nav> <!-- 通讯录 结束 -->
<!-- ................................................................... -->
<div class="container-fluid">
  <div class="row header-menu-tongxun" style="display:none;" id="header-menu2">
    <div class="col-xs-6 header-menu-on" id="list_stu">联系人</div> <!-- header-menu-on 选中   off 未选中 -->
    <div class="col-xs-6 header-menu-off" id="list_class">班级</div>
  </div>
</div>

<div class="container-fluid" id="confirm" style="display:none;">
  <div class="row footer-nav-queding all-menu-trigger" id="confirm2" onclick="selectStuS(this)">
    <div class="col-xs-12">确 定</div>
  </div>
</div>

<!--公共隐藏域-->
<input type="hidden" value="<?php echo $sid?>" id="hidden_sid" />
<input type="hidden" value="<?php echo $ischool?>" id="hidden_school" />
<input type="hidden" value="<?php echo \yii::$app->view->params['openid']?>" id="hidden_openid" />
<input type="hidden" id="appid" value="<?php echo $appid?>">
<input type="hidden" id="timestamp" value="<?php echo $timestamp?>">
<input type="hidden" id="nonceStr" value="<?php echo $nonceStr?>">
<input type="hidden" id="signature" value="<?php echo $signature?>">
<input type="hidden" id="localId" value="ta">
<input type="hidden" id="calId" value="">
<input type="hidden" id="serid" value="one">
<?php echo $this->render('../layouts/footer')?>
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
    localId: localId // 需要停止的音频的本地ID，由stopRecord接口获得
  });

  $(".record-play-stop").css({"display":"none"});
  $(".record-play").css({"display":"block"});
}

function record_close(){
  var localId=$("#localId").val();
  wx.stopVoice({
    localId: localId // 需要停止的音频的本地ID，由stopRecord接口获得
  });

  $("#record-close").css({"display":"none"});
  $("#record-up").css({"display":"none"});
  $(".record-play").css({"display":"none"});
  $(".record-play-stop").css({"display":"none"});
  $(".record-popo").css({"display":"block"});
}
function recordup(){
   // alert("ok");
  $("#play-waper").remove();
  var localId=$("#localId").val();

  wx.uploadVoice({
    localId:localId, // 需要上传的音频的本地ID，由stopRecord接口获得
    isShowProgressTips: 1, // 默认为1，显示进度提示
    success: function (res) {
      var serverId = res.serverId; // 返回音频的服务器端ID
      $("#serid").val(serverId);
    }
  });
  $("#record-waper").slideToggle();
  if ($("#record-file-waper").find("#play-waper").length == 1) {
    $("#play-center-waper").append("<div id='play-center-waper'><div class='play-center'><div class='play-record' style='display:block' onclick='text_play_record(this);'><i class='fa fa-play play-record-ico'></i></div><div class='stop-record' style='display:none' onclick='text_stop_record(this)'><i class='fa fa-pause stop-record-ico'></i></div><div class='record-text'>语音信息</div></div><div class='del-center-waper'><div class='del-record' onclick='delrecord(this)'>删除</div></div>");
  }else{
    $("#record-file-waper").append("<div id='play-waper'><div class='play-title'>语音消息</div><div id='play-center-waper'><div class='play-center'><div class='play-record' style='display:block' onclick='text_play_record(this);'><i class='fa fa-play play-record-ico'></i></div><div class='stop-record' style='display:none' onclick='text_stop_record(this)'><i class='fa fa-pause stop-record-ico'></i></div><div class='record-text'>语音信息</div></div><div class='del-center-waper'><div class='del-record' onclick='delrecord(this)'>删除</div></div></div></div>");
  }
}


function delrecord(th){
  $(th).parent().parent().remove();
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
  var $lateral_menu_trigger = $('#cd-menu-trigger'),
    $all_menu_trigger = $('.all-menu-trigger'),
    $all_menu_trigger2 = $('.all-menu-trigger2'),
    $content_wrapper = $('.cd-main-content'),
    $navigation = $('header');

  //点击 all-menu-trigger 时，切换到main页面

  // 左侧菜单
  $all_menu_trigger2.on('click', function(event){
    event.preventDefault();

    $lateral_menu_trigger.toggleClass('is-clicked');
    $navigation.toggleClass('lateral-menu-is-open2');
    $content_wrapper.toggleClass('lateral-menu-is-open2').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
      // firefox transitions break when parent overflow is changed, so we need to wait for the end of the trasition to give the body an overflow hidden
      $('body').toggleClass('overflow-hidden');
    });
    $('#cd-lateral-nav2').toggleClass('lateral-menu-is-open2');
    $("#confirm").toggle();
    //check if transitions are not supported - i.e. in IE9
    if($('html').hasClass('no-csstransitions')) {
      $('body').toggleClass('overflow-hidden');
    }

    $("#confirm").hide();
  });

  //open (or close) submenu items in the lateral menu. Close all the other open submenu items.
  $('.item-has-children').children('a').on('click', function(event){
    event.preventDefault();
    $(this).toggleClass('submenu-open').next('.sub-menu').slideToggle(200).end().parent('.item-has-children').siblings('.item-has-children').children('a').removeClass('submenu-open').next('.sub-menu').slideUp(200);
  });

  $("#list_stu").on("click",function(){
    $(this).removeClass('header-menu-on').removeClass('header-menu-off').addClass('header-menu-on');
    $("#list_class").removeClass('header-menu-on').addClass('header-menu-off');
    $("#stu-list").show();
    $("#class-list").hide();
    $("#msgType").val("ly");
    clearCheckbox('gg');
  });

  $("#list_class").on("click",function(){
    $(this).removeClass('header-menu-on').removeClass('header-menu-off').addClass('header-menu-on');
    $("#list_stu").removeClass('header-menu-on').addClass('header-menu-off');
    $("#stu-list").hide();
    $("#class-list").show();
    $("#msgType").val("gg");
    clearCheckbox('ly');
  });

  //菜单按钮事件
  $(".jxt_op_btn").on("click",function(){
    var url = $(this).attr("name");
    loadHtmlByUrl(url);
    $(".jxt_current_btn").removeClass("jxt_current_btn");
    $(this).addClass("jxt_current_btn");
  });
    $(".jxt_op_btnol a").mouseover(function(){
        $(this).css({"color":"white"})
    })
});

function selectStuS(ths){
  $('#cd-menu-trigger').toggleClass('is-clicked');
  $('header').toggleClass('lateral-menu-is-open');
  $('.cd-main-content').toggleClass('lateral-menu-is-open').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
    // firefox transitions break when parent overflow is changed, so we need to wait for the end of the trasition to give the body an overflow hidden
    $('body').toggleClass('overflow-hidden');
  });
  $('#cd-lateral-nav').toggleClass('lateral-menu-is-open');
  $("#confirm").toggle();
  //check if transitions are not supported - i.e. in IE9
  if($('html').hasClass('no-csstransitions')) {
    $('body').toggleClass('overflow-hidden');
  }

  if($(ths).attr('id')=='confirm2'){  //点确定
    $("#header-menu2").hide();
    var ids="";
    var names="";
    var len=0;
    if($("#msgType").val()=='ly'){
      var lxr = $("#stu-list").find(".lxr");
      for (var i = 0; i < lxr.length; i++) {
        if(lxr[i].checked){
          if(i!=(lxr.length-1)){
            ids = ids + lxr[i].value + ";";
            names = names + lxr[i].name + ";";
          }else{
            ids = ids + lxr[i].value;
            names = names + lxr[i].name;
          }
          len++;
        }
      }
      if(names!=""){
        names=names.split(";");
        names="给"+names[0]+"等"+len+"人留言";
      }
    }else{
      var bj = $("#class-list").find(".bj");
      for (var i = 0; i < bj.length; i++) {
        if(bj[i].checked){
          if(i!=(bj.length-1)){
            ids = ids + bj[i].value + ";";
            names = names + bj[i].name + ";";
          }else{
            ids = ids + bj[i].value;
            names = names + bj[i].name;
          }
        }
      }
      if(names!=""){
        names=names+"公告";
      }
    }

    $("#to").val(names);
    $("#ids").val(ids);
  }else{                               //点选择收件人
    $("#header-menu2").show();
  }

}

function clearCheckbox(type){
  if(type=='ly'){
    var lxr = $("#stu-list").find(".lxr");
    for (var i = 0; i < lxr.length; i++) {
      lxr[i].checked=false;
    }
  }else{
    var bj = $("#class-list").find(".bj");
    for (var i = 0; i < bj.length; i++) {
      bj[i].checked=false;
    }
  }
}

// 更改鼠标浮动 列表栏 的样式
function register_user_over(ths){
  $(ths).css({"background-color":"#FF6666","color":"#FFFFFF"});
  $(".title-more-time",ths).css({"color":"#FFFFFF"});
}

function register_user_out(ths){
  $(ths).css({"background-color":"#FFFFFF","color":"#666666"});
  $(".title-more-time",ths).css({"color":"#999999"});
}

function witchInfo(ths){
  var divid = "#with-"+$(ths).attr("id");
  if($(divid).hasClass("on")){

    $(divid).removeClass("on").addClass('off').slideToggle(400);
  }else{
    $(".on").hide(400).addClass("off").removeClass("on");
    $(divid).addClass("on").removeClass('off').slideToggle(400);
  }
}

function user_checkbox(ths){
  var che=$(ths).find("input[type='checkbox']");
  var time = che.attr("time");    //学生有效期时间
  var dtime = che.attr("dtime");  //当前时间
  $(".lxr").attr("disabled", "disabled");
  if(time < dtime){
    alert("您的孩子家校沟通已欠费，请续费后使用！");
    return false;
  }else if(che[0].checked){
    che[0].checked=false;
  }else{
    che[0].checked=true;
  }
}

//发信息操作/
function doSendMsg(){
  var serid=$("#serid").val();
  var path=$("#path").val();
  var strpath="one";
  if ($.trim($("#to").val())==""){
    alertDialog('请选择信息接收人！');
  }else if($.trim($("#txt-content").val())==""&&serid=="one"){
    alertDialog('请填写信息内容！');
  }else{

    var msg=$("#txt-content").val();

    var one =document.getElementsByName("one");
    for (var i = 0, j = one.length; i < j; i++){
      strpath+="#"+one[i].value
    }
    var title = $("#to").val();
    var url=path+'/tongzhi/dosendmsg';
    var para={to:$('#ids').val(),msg:msg,serid:serid,strpath:strpath,msgType:$("#msgType").val(),openid:$("#hidden_openid").val(),title:title};

    var to_url=path+"/tongzhi/outbox?openid="+$("#hidden_openid").val();
    var options = {para:para,ele:$("#add_span7"),sub_url:url,to_url:"",urltype:1,status:[{code:0,content:'信息发送失败，请重试'}]};
   
    sub_dialog(options);

    $(".header-nav-menu-on").text("发件箱");
    $(".header-nav-menu-on").css({"background-color":"#33CC66"});
    $("#sent_btn").click();
    $("#serid").val("");
  }
}

// 页面进入后 鼠标点击默认页面
$(document).ready(function(){
  loadHtmlByUrl($(".jxt_current_btn").attr("name"));
});

//上传附件
function close_uloadfile_waper(){
  $("#uloadfile-waper").slideToggle();
}

function uloadfile_up(){
  var res = document.getElementById("uloadfile");
  res.click();
}
</script>

