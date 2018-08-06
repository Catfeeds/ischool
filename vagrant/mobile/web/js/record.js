
wx.error(function(res){

    // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。

});

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
function record_up(){
  $("#record-close").css({"display":"none"});
  $("#record-up").css({"display":"none"});
  $(".record-play").css({"display":"none"});
  $(".record-play-stop").css({"display":"none"});
  $(".record-popo").css({"display":"block"});
}

function startTime(i,s)
{
// add a zero in front of numbers<10


op=checkTime(i)
sp=checkTime(s)
if(i>58)
{
    i=0
    s=s+1
}

document.getElementById('record-time-text').innerHTML=sp+":"+op
i=i+1;
t=setTimeout('startTime('+i+','+s+')',1000)
}

function checkTime(i)
{
if (i<10) 
  {i="0" + i}
  return i
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

// 播放录音
function play_record(ths){
  $(ths).css({"display":"none"});
  $(ths).next().css({"display":"block"});
}
function stop_record(ths){
  $(ths).css({"display":"none"});
  $(ths).prev().css({"display":"block"});
}