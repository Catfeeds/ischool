<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>充值</title>
  <meta name="viewport" content="initial-scale=1, maximum-scale=1">
  <link rel="shortcut icon" href="/favicon.ico">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <link rel="stylesheet" href="/css/pay.css">
  <link rel="stylesheet" href="//g.alicdn.com/msui/sm/0.6.2/css/sm.min.css">
  <link rel="stylesheet" href="//g.alicdn.com/msui/sm/0.6.2/css/sm-extend.min.css">
  
  <script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
  <script type="text/javascript" src="/js/ajaxload.js"></script>
  <script type="text/javascript" src="/js/dialog-min.js"></script>
  <script type="text/javascript" src="/js/myDialog.js"></script>
</head>
<body>
<div class="maxwrap">
  <div class="page-group">
    <div class="page page-current">
      <header class="bar bar-nav">
          <h1 class="title" style="color: white">
            <a style="color: white;position: absolute;left: 10px" href="<?php echo URL_PATH?>/information/index?openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo \yii::$app->view->params['sid']?>">&lt;</a>
            充值
          </h1>
      </header>
      <!--以上是头部结构-->
      <div class="content">
        <form id="tijiao" method="post" >
        <div class="studentname">
          <h5>学生名称</h5>
          <select class="downmenu" id="downmenu" name="tc"  onchange="selectOptions()">
<!--            <foreach name="childs" item="vo">-->
            <?php foreach($childs as $k=>$v){?>
              <option id="<?php echo $v['sid'] ?>" value="<?php echo $v['school'] ?>|<?php echo $v['class'] ?>|<?php echo $v['stu_name'] ?>|<?php echo $v['sid'] ?>|<?php echo $v['stu_id'] ?>|<?php echo \yii::$app->view->params['openid']?>|<?php echo $v['stuno2']; ?>" name="<?php echo $v['endck'];?>" sid="<?php echo $v['sid'] ?>"><?php echo $v['school'] ?>|<?php echo $v['class'] ?>|<?php echo $v['stu_name'] ?></option>
<!--            </foreach>-->
            <?php }?>
          </select>
        </div>
        <!--以上是学生姓名结构-->
        <input type="submit"  value="功能支付" id="btn" style="background: #8ac007;"> 
        <?php if(\yii::$app->view->params['sid']!=56650) {?>  
        <input type="button" name="submit" id="btn2" value="学生补卡" style="background: #f1c40f">
        <?php }?>  
        <input type="submit" name="submit" id="btn1" value="餐卡充值" style="background: #ff6666">
  
         <?php if(\yii::$app->view->params['openid']=='oUMeDwEvQistOP5DywTiHdTBdpBs') {?> 
        <input type="submit" name="submit" id="btn4" value="水卡充值" style="background: #8ac007">
        <?php }?>
        <input type="submit" name="submit" id="btn3" value="学费缴费" style="background: #f1c40f">
    
        </form>


        <!--以上是学生补卡按钮结构-->
        <div class="help">
          <p>帮助</p>
          <p>功能支付：包括平安通知、家校沟通、亲情电话、一卡通等功能
            <br>餐卡充值：学校开通餐卡功能，可在此充值学生餐卡。</p>
        </div>
        <!--以上是帮助结构-->
      </div>
      <!-- 以上是页面内容区 -->
    </div>
  </div>
</div>
<input type="hidden" id="path" value="<?php echo URL_PATH?>">
<input type="hidden" id="tcxq" value="<?php echo URL_PATH?>/zfend/recharge">
<input type="hidden" id="ckcz" value="<?php echo URL_PATH?>/zfend/solution">
<input type="hidden" id="skcz" value="<?php echo URL_PATH?>/zfend/water-solution">
<input type="hidden" id="ckbk" value="<?php echo URL_PATH?>/zfend/buka">
<input type="hidden" id="xfjf" value="<?php echo URL_PATH?>/zfend/xuefei">

<script type='text/javascript' src='//g.alicdn.com/sj/lib/zepto/zepto.min.js' charset='utf-8'></script>
<script type='text/javascript' src='//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js' charset='utf-8'></script>
<script type='text/javascript' src='//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js' charset='utf-8'></script>
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script>
  $('#btn').css({'width':'70%',
    'padding': '.4rem 0',
    'color': 'white',
    'text-align':'center',
    '-webkit-border-radius':'.2rem',
    '-moz-border-radius':'.2rem',
    'border-radius':'0.3rem',
    'margin-left':'15%',
    'border-color':'white',
    'margin-top':'1.5rem',
    'border':'none',
  })
  $('#btn1').css({'width':'70%',
    'padding': '.4rem 0',
    'color': 'white',
    'text-align':'center',
    '-webkit-border-radius':'.2rem',
    '-moz-border-radius':'.2rem',
    'border-radius':'0.3rem',
    'margin-left':'15%',
    'border-color':'white',
    'margin-top':'0.6rem',
    'border':'none',
  })
  $('#btn2').css({'width':'70%',
    'padding': '.4rem 0',
    'color': 'white',
    'text-align':'center',
    '-webkit-border-radius':'.2rem',
    '-moz-border-radius':'.2rem',
    'border-radius':'0.3rem',
    'margin-left':'15%',
    'border-color':'white',
    'margin-top':'0.6rem',
    'border':'none',

  })
  $('#btn3').css({'width':'70%',
    'padding': '.4rem 0',
    'color': 'white',
    'text-align':'center',
    '-webkit-border-radius':'.2rem',
    '-moz-border-radius':'.2rem',
    'border-radius':'0.3rem',
    'margin-left':'15%',
    'border-color':'white',
    'margin-top':'0.6rem',
    'border':'none',
  })
   $('#btn4').css({'width':'70%',
    'padding': '.4rem 0',
    'color': 'white',
    'text-align':'center',
    '-webkit-border-radius':'.2rem',
    '-moz-border-radius':'.2rem',
    'border-radius':'0.3rem',
    'margin-left':'15%',
    'border-color':'white',
    'margin-top':'0.6rem',
    'border':'none',
  })
 /* $('.buka').css({'width':'100%','padding':'1rem 0 1rem 0.5rem','background':'white','margin-top':'2rem'}).find('h5').eq(1).css('padding','1rem 0 0 2rem')
  $('.text').css({'line-height':'1.8rem','padding':'0 0 0 2rem'})
  $('input[type="text"]').css({'width':'58%','margin-top':'.2rem','border':'1px solid #999','line-height':'1rem','background':'#f5f5f5'})*/

  function mapAllParam(type){
    var param = {};
    param.total = 10;
    return param;
  }
  tc = $('#downmenu option:selected').val();
  function selectOptions(){        
     var p=document.getElementsByClassName("downmenu")[0];
     var index=p.selectedIndex;
     var text=p.options[index].getAttribute("name");
     var sid=p.options[index].getAttribute("id");
     if(text=='mkt'&& sid!='56757'){
         $("#btn1").css('display','none');     
     }else{
         $("#btn1").css('display','block');   
     } 
     if(sid=='56740'){
        $("#btn1").css('display','none');
     }
     if(sid=='56757'){
        $("#btn3").css('display','none'); 
     }else{
        $("#btn3").css('display','block');
     } 
  }
  $(function(){
      
     var p=document.getElementsByClassName("downmenu")[0];
     var index=p.selectedIndex;
     var text=p.options[index].getAttribute("name");
     var sid=p.options[index].getAttribute("id");
    
     if(text=='mkt'&& sid!='56757'){
         $("#btn1").css('display','none');  
     }else{
         $("#btn1").css('display','block');
     } 
     if(sid=='56740'){
        $("#btn1").css('display','none');
     }
     if(sid=='56757'){
        $("#btn3").css('display','none'); 
     }else{
        $("#btn3").css('display','block');
     } 
        //跳转套餐支付详情页面
        $("#btn").on("click",function(){
          if (!tc || tc == ""){
            alert("您还没有绑定学生，请先绑定您的学生");
            return false;
          }
          var p=document.getElementsByClassName("downmenu")[0];
          var index=p.selectedIndex;
          var text=p.options[index].getAttribute("name");
          var sid=p.options[index].getAttribute("id");

          if(sid=='56769'){
            alert('目前免费试用！');
            return false;
          }
          if(sid=='56775'){
            alert('暂未开通！');
            return false;
          }
          url =$("#tcxq").val();
         // alert(url);
          $("#tijiao").attr("action",url);
          //window.location = "www.baidu.com";
        });
       //补卡页面跳转
      //  $("#btn2").on("click",function(){
      //     if (!tc || tc == ""){
      //       alert("您还没有绑定学生，请先绑定您的学生");
      //       return false;
      //     }
      //     url =$("#ckbk").val();
      //     $("#tijiao").attr("action",url);
      // });
    //补卡支付跳转
   $("#btn2").on("click",function(){
      var url = $("#path").val()+"/zfend/redirectcpay";
      var index=p.selectedIndex;
      var sid=p.options[index].getAttribute("id");
      
      if(sid=='56775' || sid=='1'){
          alert("暂未开通");
          return false;
       } 
     var obj = document.getElementsByClassName("downmenu"); //定位id
     if (!tc || tc == ""){
       alert("您还没有绑定学生，请先绑定您的学生");
       return false;
     }
     var formData = {};
     formData.tc = $('#downmenu option:selected').val();
     formData.total = 10;
     $.post(url,formData).done(function(data){
       if(data.retcode==0){
        document.write("正在进入支付页面请您稍等待……");
           window.clear;
         window.location.href = data.url;
       }else{
         alert(data.retmsg);
       }
     })
   });

    $("#btn1").on("click",function(){
      if (!tc || tc == ""){
        alert("您还没有绑定学生，请先绑定您的学生");
        return false;
      }
      var p=document.getElementsByClassName("downmenu")[0];
      var index=p.selectedIndex;
      var text=p.options[index].getAttribute("name");     
      var sid=p.options[index].getAttribute("id");
      if(sid=='56757'){
          alert("获嘉县第一中学的餐卡微信充值服务暂未开通");
          return false;
      }
      if(sid=='56775'){
          alert("暂未开通");
          return false;
       }  
      if (text == "mkt"){
        alert("您的餐卡微信充值服务暂未开通，请开通后使用");
        return false;
      }
      url =$("#ckcz").val();
      $("#tijiao").attr("action",url);
    })
    $("#btn4").on("click",function(){
      if (!tc || tc == ""){
        alert("您还没有绑定学生，请先绑定您的学生");
        return false;
      }
      var p=document.getElementsByClassName("downmenu")[0];
      var index=p.selectedIndex;
      var text=p.options[index].getAttribute("name");     
      var sid=p.options[index].getAttribute("id");
      if (text == "mkt"){
        alert("您的水卡微信充值服务暂未开通，请开通后使用");
        return false;
      }

      url =$("#skcz").val();
      $("#tijiao").attr("action",url);
    })
     $("#btn3").on("click",function(){
      var p=document.getElementsByClassName("downmenu")[0];
      var index=p.selectedIndex;    
      var sid=p.options[index].getAttribute("id");
      if(sid=="56744"){
        url =$("#xfjf").val();
        $("#tijiao").attr("action",url);
      }else{
        alert("该校此服务暂未开通！");
        return false;
      }
      
    })
  });
 
</script>
</body>
</html>

