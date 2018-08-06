<!doctype html>
<html>
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
              <div class="Contentbox"> 
                    <div id="con_menu_1" class="hover" style="display: block;">    
                      <p>请填写您需要充值的学生姓名和学生卡号，并输入充值金额</p>
                        <form name="form1" method="post">
                      <input type="text" class="hover_01" placeholder="充值金额" id="money"><br><br>
               
                     <input type="button" value="确认支付" class="button" id="btn2">
                        </form>
                    </div>
                    <div id="con_menu_2" style="display: none;">
                      <p>请填写您需要充值的学生姓名和学生卡号</p>
                      <input type="text" class="hover_01" placeholder="学生卡号"><br><br>
                      <input type="text" class="hover_01" placeholder="学生姓名"><br><br>
                     <a href="<?php echo URL_PATH?>/zfend/yue"><input type="button" value="查询" class="button" onclick="location.href='bill.html'"> </a>
                    </div>
              </div>
		</div>
</div>
<input type="hidden" id="path" value="<?php echo URL_PATH?>">
<input type="hidden" id="trade_name" value="<?php echo $nxinxi?>">
<input type="hidden" id="sid" value="<?php echo $sid?>">
<script type="text/javascript">

    $(function(){
        var url = $("#path").val()+"/zfend/ckredirecpay";
        $("#btn2").on("click",function(){
            var formData = {};
            formData.tc = $('#trade_name').val();
            formData.total = $("#money").val();
            formData.sid = $("#sid").val();
            if(formData.total <= 0 || (!formData.total)){
                alert("价格不合法，请您重新填写！");
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
    });
</script>
</body>
</html>

