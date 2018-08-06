<!doctype html>
<html>
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<head>
<meta charset="utf-8">
<title>一卡通补卡</title>
<link rel="stylesheet" type="text/css" href="/css/zhi.css">
</head>

<body>
<div class="gzh_wdhz_head"> <a href="<?php echo URL_PATH?>/zfend/pay"><</a>
  <p>一卡通补卡</p>
  <div class="clear"></div>
</div>
<div class="weui_cell zhongcai" href="javascript:;" style="border-bottom: 1px solid #B9B9B9;">
    <div class="weui_cell_bd weui_cell_primary"> 
      <img src="/img/images/meal_card-icon.png" width="16%">
      <div class="zhifu">
        <h2>一卡通补卡</h2>
        学生饭卡使用补卡等功能
      </div>
    </div>
    <!--<div class="you"> <img src="/images/zhifu/支付宝支付_03.png" /> </div>-->
    <div class="clear"></div>
</div>
<div style="background:#FFF; margin-top:2%;">
		<div id="Tab">
              <div class="Menubox">
                <ul>
                  <li id="menu1" style="width: 99%" onmouseover="" class="hover">一卡通补卡</li>
       
                </ul>
              </div>
              <div class="Contentbox"> 
                    <div id="con_menu_1" class="hover" style="display: block;">    
                      <p>请谨慎使用该操作，该操作将使原来的卡号失效</p>
                        <form name="form1" method="post">
                            <label for=""  >
                           <bm style="font-size:2em;">卡号：</bm><input type="text" class="hover_001" placeholder="请输入学生卡号" id="cardId"> 
                            </label>
                            <br> <br>
                            <label for="">
                                <bm style="font-size:2em;">姓名：</bm><input type="text" class="hover_001" disabled="disabled" value="<?php echo $name?>" id="name"><br>   
                            </label>
                  
               
                            <input type="button" value="保存" class="button" id="btn2">
                        </form>
                    </div>
                  
              </div>
		</div>
</div>
<input type="hidden" id="path" value="<?php echo URL_PATH?>">
<input type="hidden" id="trade_name" value="<?php echo $nxinxi?>">
<input type="hidden" id="xinxi" value="<?php echo $xinxi?>">
<input type="hidden" id="sid" value="<?php echo $sid?>">
<script type="text/javascript">
$(function(){
           var xinxi=$('#xinxi').val();
           var url = $("#path").val()+"/zfend/bucard";
           var tourl=$("#path").val()+"/zfend/solution?tc="+xinxi;
           $("#btn2").on("click",function(){
            if(confirm('该操作将清空旧卡信息，您确定执行该操作？')){
                var formData = {};
                formData.tc = $('#trade_name').val();            
                formData.cardId = $("#cardId").val();
                formData.sid = $("#sid").val();
                if(formData.cardId <= 0 || (!formData.cardId)){
                    alert("请输入卡号！");
                    return false;
                }             
                $.post(url,formData).done(function(data){
                    if(data.retcode==0){  
                        if(confirm('补卡操作成功，是否前往支付?')){
                          window.location.href = tourl;  
                        }
                        
                    }else{
                        alert(data.retmsg);
                    }
                })
              }else{
                  return false;
              }
          
        });
    });
</script>
</body>
</html>



