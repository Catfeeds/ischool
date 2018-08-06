<?php

/* @var $this \yii\web\View */
/* @var $content string */

use mobile\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;
//var_dump($sid=\yii::$app->view->params['openid']);die;
AppAsset::register($this);
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>消费记录</title>
    <link rel="stylesheet" href="/css/basic.css">
    <link rel="stylesheet" href="/css/styleck.css">
    <link rel="stylesheet" href="/css/qqphone.css?v=<?php echo time();?>"/>
    <link rel="stylesheet" href="/css/bootstrap.min.css?v=<?php echo time();?>"/>
    <script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="/js/css3-mediaqueries.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-confirmation.js"></script>
    <script type="text/javascript" src="/js/dialog-min.js"></script>
    <script type="text/javascript" src="/js/ajaxload.js"></script>
    <script type="text/javascript" src="/js/Headroom.js"></script>
    <script type="text/javascript" src="/js/jQuery.headroom.min.js"></script>
    <script type="text/javascript" src="/js/LocalResizeIMG.js"></script>
    <script type="text/javascript" src="/js/patch/mobileBUGFix.mini.js"></script>

    <script type="text/javascript" src="/js/dimmer.min.js"></script>
    <script type="text/javascript" src="/js/manage.js"></script>
    <script src="/js/DateTimePicker.min.js" type="text/javascript"></script>

    <style type="text/css">
        .select-input{
            flex:1;
            background: #ffffff;
            border: 1px solid #A4A4A4;
            border-radius: 2px;
            height: 32px;
        }
    </style>
</head>
<body>
<header>
    <a href="javascript:history.back(-1)" class="back-btn"></a>
    消费记录
</header>
   <?php if($ckpass==0){?> 
  <div class="car">餐卡：家长可通过该平台或学校管理员给学生充值，充值后，学生凭学生卡可以在学校刷卡用餐、购物等。家长可实时收到学生的消费情况，也可查询学生的消费记录</div>
    <div class="tishi">
        <span>提示</span>
        <hr style="border: 1px solid #e9e9e9">
        <p>该业务需要学生开通餐卡服务即可使用。</p>
    </div>
   <?php }else{?> 
<div class="query-area">
    <div class="input-item">
        <label for="starttime">开始时间：</label>
        <input type="date" id="starttime" class="text-input">
    </div>
    <div class="input-item">
        <label for="endtime">结束时间：</label>
        <input type="date" id="endttime" class="text-input">
    </div>
   <div class="input-item">
        <!--  <label class="col-sm-2  control-label" >姓名：</label>  -->
        <select  style="border: 1px solid #A4A4A4;" class="col-sm-8  selectpicker show-tick form-control" id="downmenu" name="tc" onchange="selectOptions()" >
            <?php foreach($childs as $k=>$v){?>
            <?php if($v['endck']=='ykt' ): ?>
              <option  value="<?php echo $v['sid'] ?>|<?php echo $v['stuno2']; ?>" ><?php echo $v['school'] ?>|<?php echo $v['class'] ?>|<?php echo $v['stu_name'] ?></option>
            <?php endif;?>
            <?php }?>
        </select>
    </div>    
    <button class="query-btn"  onclick="doDownCK()">查询</button>
</div>
<div class="result-area">
    <div class="result-label" id="ckshuju">
        <span>时间</span>
        <span>地点</span>
        <span>姓名</span>
        <span>金额</span>
        <span>余额</span>
    </div>
    <ul class="result-list" id="ul" style="font-size:12px;">      
    </ul>
</div>
   <?php }?> 
</body>
</html>
<script type="text/javascript">
	function doDownCK(){             
		var downtime = $("#starttime").val();
		var endttime = $("#endttime").val();
        var downmenu=$("#downmenu").val();             
		if(downtime==""){
			alert("请选择开始日期");
			return 0;
		}
		if(endttime==""){
			alert("请选择结束日期");
			return 0;
		}
                
		var path = $("#path").val();
		var url = "/zfend/ckcx";
		var para = {"downtime":downtime,"endttime":endttime,"downmenu":downmenu};
		$.getJSON(url,para,function(data){
			if(data.flag==0){
			    $("#ul").empty();                        
			    $.each(data.ckshuju,function(i,info){
				  var span = "<li class='result-item'> <span class='result-time'>"+info.year+"<br/>"+info.time+"</span> <span> "+info.position+" </span><span> "+info.user_name+" </span><span>"+info.amount+"</span><span>"+info.balance+"</span></li>";	
				  $("#ul").append(span);
			    })
		
			}else{
          alert('没有相关记录');
  			
			}
		});
	}

	
 // 底部菜单导航 遮盖  掌上物业
  $(".footer-menu").click(function(){ 
     var thisid = $(this).attr("id");
   $(".mynav").each(function() {
     if($(this).hasClass(thisid)){
       if($(this).hasClass("on")){
            $(this).slideUp(300);
         $(this).removeClass("on").addClass("off");
         }else{
         $("."+thisid).slideDown(300);
         $(this).removeClass("off").addClass("on");
           
      }
       
     }else{
            $(this).slideUp(300);
      $(this).removeClass("on").addClass("off");
     }
     });
   
  });

</script>


