<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title></title>
		<link rel="stylesheet" href="/css/bootstrap.css" />
		<link rel="stylesheet" href="/css/xuefei.css" />
                <script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
                <style type="text/css">
                  #formjf{font-size:18px;}
                  #jfbtn{font-size:18px;}
                </style>
	</head>
	<body>
		<div id="header">
			<div id="back">
				<img src="/img/icon.png" />
				<span>住宿费</span>
			</div>
		</div>
		<div class="clearfix"></div>
		<form role="form" id="formjf">
			<div class="feiyong text-center">
	                        学生名称：
	    	    <select style="width: 60%;font-size:15px" id="downmenu" class="downmenu" name="tc" onchange="selectOptions()">
    		      <option>请选择</option>
	    	      <?php foreach($childs as $k=>$v){?>
                     <option id="<?php echo $v['sid'] ?>" value="<?php echo $v['school'] ?>|<?php echo $v['class'] ?>|<?php echo $v['stu_name'] ?>|<?php echo $v['sid'] ?>|<?php echo $v['stu_id'] ?>|<?php echo \yii::$app->view->params['openid']?>|<?php echo $v['stuno2']; ?>" name=""><?php echo $v['school'] ?>|<?php echo $v['class'] ?>|<?php echo $v['stu_name'] ?></option>
                      <?php }?>
	    	    </select>
		    </div>
		    <!-- <div id="text1">住宿费</div> -->
		    <div class="jine" style="padding-left:2rem">
		    	缴费金额
         <?php if($sid=='56758'){?>
         <input type="text" value="150" placeholder="请输入缴费金额" name="total" id="total" disabled/>
         <?php }else{ ?> 
          <input type="text" value="" placeholder="请输入缴费金额" name="total" id="total"/>
        <?php }?>
		    <!-- 	<input type="text" value="" placeholder="请输入缴费金额" name="total" id="total"/> -->
		    	元
		    </div>
		    <div class="text-center">
		    	<button id="jfbtn" type="button" onclick="jiaofei()" class="btn btn-default">立即缴费</button>
		    </div>
                    <input type="hidden" id="path" value="<?php echo URL_PATH?>">
                    <input type="hidden" id="sid" value="<?php echo \yii::$app->view->params['sid'];?>">
                    <input type="hidden" id="type" value="住宿费">
        </form>
	</body>
</html>
<script>
   $("#back").click(function(){
       window.history.go(-1);
   }) 
   function jiaofei(){
       
      var url = $("#path").val()+"/zfend/redirectxfpay";
      var tc = $('#downmenu option:selected').val();    
      var total = Number($("#total").val());                                               
      var type= $("#type").val();
      var p=document.getElementsByClassName("downmenu")[0];
      var index=p.selectedIndex;
      var sid=p.options[index].getAttribute("id");    
      if (!tc || tc == "请选择"){
        alert("请选择缴费学生");
        return false;
       }
       if(total <= 0 || (!total)){
            alert("价格不合法，请重新输入！");
            return false;
      }
      var formData = {};
      formData.tc =tc;
      formData.total =total;
      formData.type =type;
      $.post(url,formData).done(function(data){
           if(data.retcode==0){
               window.location.href = data.url;
           }
       })
   }
   function selectOptions(){
      var p=document.getElementsByClassName("downmenu")[0];
      var index=p.selectedIndex;
      var sid=p.options[index].getAttribute("id");
      if(sid=='56758'){
        $("#total").val(150);
        $("#total").attr("disabled","disabled");
      }   
   }
</script>