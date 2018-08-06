<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="initial-scale=1, maximum-scale=1">
  <link rel="shortcut icon" href="/favicon.ico">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
 

  <style type="text/css">
 
    .video{
        background:transparent url('images/address_default_cover11.png') center no-repeat;
        -webkit-background-size:cover;
        -moz-background-size:cover;
        -o-background-size:cover;
        background-size:cover;
    }
  </style>
</head>
<body style="overflow-x:hidden;margin:0;background:url() no-repeat center;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;">


<div style="background:#ffffff url('') no-repeat center;background-size:auto 62px;width:100%;height:62px"></div>

<video id="video" class="video" src="http://alhlsgw.lechange.com:9001/LCO/3C07A7BPAU01528/0/1/20171124160635/dev_20171124160635_th8xcpt880it9em9.m3u8"  
                     
       poster="/img/images/video.jpg"  
       controls="controls"  width="100%" height:"211px" style="">
</video>


<div style="width:100%;height:auto;">
  <p style="padding:5px 15px 15px 15px;font-size:14px;color:;" id="brief">
 
  </p>
</div>


<script type="text/javascript">
  var screenW;
  var flag = false; // �ж���Ƶ�Ƿ�׼������
  window.document.title = 'video';
  
  total = document.documentElement.clientHeight;
  document.body.style.height=total+"px";


  var video = document.getElementById('video');
  // ��Ƶ׼��������Ϊ
  video.oncanplay=function(){
    flag = true;
  }
  // ��Ƶʱ����ѵ�� ÿ20s��ѵһ�� ֱ����������������Ƶ
  var playVideo=setInterval(function(){ 
      if(flag){
        // ���Բ��� �����ʱ��
        clearInterval(playVideo);
      }else{
        // �����ȴ�
        video.load();
      }
    },20000);

</script>
</body>
</html>



