<!doctype html>
<html>
    <link rel="stylesheet" href="/css/zf.css">
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script>
	function setTab(name,cursel,n){
		for(i=1;i<=n;i++){
		var menu=document.getElementById(name+i);
		var con=document.getElementById("con_"+name+"_"+i);
		menu.className=i==cursel?"hover":"";
		con.style.display=i==cursel?"block":"none";
		}
	}  
 </script>
 <script>
    function goUrl(x)
    {
     window.location.href=x;
    }
</script>
<head>
<meta charset="utf-8">
<title>一卡通充值</title>
<link rel="stylesheet" type="text/css" href="/css/zhi.css">
<style type="text/css">
    .Contentbox1{
            clear: both;
            margin-top: 0px;
            border-top: none;
            padding-top: 8px;
            font-family: "黑体";
            font-size: 16px;
            font-size: 16px;
            list-style: none;
    }
    .btn1{
            font-size:2.5rem;
            width: 20%;
            line-height: 3.3em;
   
    }
</style>
</head>

<body>
<div class="gzh_wdhz_head"> <a href="<?php echo URL_PATH?>/zfend/pay"><</a>
  <p>一卡通充值</p>
  <div class="clear"></div>
</div>
<div class="weui_cell zhongcai" href="javascript:;" style="border-bottom: 1px solid #B9B9B9;">
    <div class="weui_cell_bd weui_cell_primary"> 
      <img src="/img/images/meal_card-icon.png" width="16%">
      <div class="zhifu">
        <h2>一卡通充值</h2>
        学生饭卡使用充值等功能
      </div>
    </div>
    <!--<div class="you"> <img src="/images/zhifu/支付宝支付_03.png" /> </div>-->
    <div class="clear"></div>
</div>
<div style="background:#FFF; margin-top:2%;">
		<div id="Tab">
              <div class="Menubox">
                <ul>
                  <li id="menu1" style="width: 99%" onmouseover="setTab('menu',1,2)" class="hover">一卡通充值</li>
                  <!--<li id="menu2" onmouseover="setTab('menu',2,2)" class="">一卡通余额查询</li>-->
                </ul>
              </div>
                <div class="Contentbox1">
                      <p style="font-size:2rem;">请选择充值金额</p>
                            <button type="button" class="btn1">
                                    20
                                    <input type="radio" name="jine" checked="checked" class="timec"  value="20" form="form1"/>
                            </button>
                            <button type="button" class="btn1">
                                    50
                                    <input type="radio" name="jine" class="timec"  value="50" form="form1"/>
                            </button>
                            <button type="button" class="btn1">
                                    100
                                    <input type="radio" name="jine"  class="timec"  value="100" form="form1"/>
                            </button>
                            <button type="button" class="btn1">
                                    自定义
                                    <input type="radio" name="jine"  class="timec"   value="0" form="form1"/>
                            </button>	
              </div>     
              <div class="Contentbox" style="display: none;"> 
                    <div id="con_menu_1" class="hover" >    
                      <p>请输入充值金额</p>
                       
                        <form name="form1" method="post">
                      <input type="text" class="hover_01" placeholder="充值金额" id="money"><br><br>
               
                    
                        </form>
                    </div>
              
              </div>
                     <input type="button" value="确认支付" class="button" id="btn2" form="form1" >
		</div>
</div>
<input type="hidden" id="path" value="<?php echo URL_PATH?>">
<input type="hidden" id="trade_name" value="<?php echo $nxinxi?>">
<input type="hidden" id="sid" value="<?php echo $sid?>">
<input type="hidden" id="openid" value="<?php echo $openid?>">
<script type="text/javascript">

    $(function(){
        var url = $("#path").val()+"/zfend/ckredirecpay";
        $("#btn2").on("click",function(){
            var formData = {};  
            formData.openid = $('#openid').val();         
            formData.tc = $('#trade_name').val();
            var a = Number($("#money").val());
            formData.sid = $("#sid").val();
     
            var b= Number($('input:radio[name="jine"]:checked').val());
            formData.total=a+b;
            if(formData.total <= 0 || (!formData.total)){
                alert("价格不合法，请您重新填写！");
                return false;
            }
            if(formData.total >500){
                alert("金额超限，请重新输入！");
                return false;
            }
            $.post(url,formData).done(function(data){
                if(data.retcode==0){
                    window.location.href = data.url;
                }else{
                    alert(data.retmsg);
                }
            })
        });
//        $(".btn1").click(function(){
//            $(this).children().attr('checked')
//		 		.siblings().children().removeAttr('checked');
//        });

        $("input[type='radio']").each(function(){
            if($(this).is(':checked')){
                $(this).parent().css({"background-color":"#38af03","color":"white"});
           }
        });
       $.each($(".btn1"), function(index,element) {
           $(this).click(function(){
               $(this).css({"background-color":"#38af03","color":"white"}).siblings().css({"background-color":"white","color":"black"});
               $(this).children().prop("checked",true);
             
               var indexe=$(".btn1").index(this);
               if(indexe==3){
                  $(".Contentbox").css('display','block');            
               }

               
           });
           
       });
    });
</script>
</body>
</html>

