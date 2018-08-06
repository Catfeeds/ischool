<header>
    <div class="container-fluid">
      <div class="row header-menu" id="header-menu1">    
        <div class="col-xs-6 header-nav-menu-on" id="sendMsg"></div> <!-- header-menu-on 选中   off 未选中 -->
        <div class="col-xs-6 header-nav-menu-off all-menu-trigger2">
          <span class="cd-menu-text">考勤信息</span>
          <span class="cd-menu-icon"></span>
        </div> <!-- header-menu-on 选中   off 未选中 -->     
      </div>  
    </div>
</header> <!-- 顶部菜单 结束 -->
	<main class="cd-main-content" id="mycontainer"> <!-- 页面主体 -->
    <div class="container-fluid register-user-margin">
       <div class="row register-user-container">

           <div class="col-xs-12 user-root-title">
               <span class="badge">帮助指引</span>
           </div>

           <div class="col-xs-12 register-user-center-margin">
               <div class="row examine-user-list text-center">
                 <div class="col-xs-12">请点击右上角考勤信息在考勤信息中查看相关内容，若考勤信息为空，请在我的资料中进行绑定信息</div>
               </div>
           </div>
       </div>
    </div>   
	</main> <!-- cd-main-content 页面主体 结束 -->
<!-- ................................................................... -->
  <nav id="cd-lateral-nav2"> <!-- 邮件菜单 -->

    <div class="header-nav">

      <div class="container-fluid">
        <div class="row all-menu-trigger2">

          <a href="#" onfocus="this.blur()" class="header-nav-back col-xs-5">
            <span class="glyphicon glyphicon-chevron-left"></span>
          </a>

          <a href="#" onfocus="this.blur()" class="header-nav-text col-xs-5">
            <span>考勤信息</span>
          </a>
        
        </div>  
      </div>
    </div>

    <ul class="cd-navigation ul-top"> 

<!--    <if condition="$list_class eq '' and $list_par eq '' "> -->
    <?php if(empty($list_class) &&  empty($list_par)){?>
      <li class="item-has-children li-top">
          <a href="#" onfocus="this.blur()" class="li-top-a">
              暂无相关信息
          </a>
      </li>
<!--    <else /> -->
    <?php }else{?>
<!--    <foreach name="list_class" item="vo" key="key"> -->
          <?php foreach($list_class as $k=>$v){?>  
      <li class="item-has-children li-top">
          <a href="#" onfocus="this.blur()" class="li-top-a" id="main-list">
<!--              {$vo.school}{$vo.class}{$vo.role}-->
            <?php echo $v['school']?><?php echo $v['class']?><?php echo $v['role']?> 
          </a>
          <ul class="sub-menu">
              <li class="title-menu all-menu-trigger2">

            <a href="<?php echo URL_PATH?>/yicard/checkallstuinfo?cid=<?php echo $v['cid']?>&token=gh_e25d98dd302e&openid=<?php echo \yii::$app->view->params['openid']?>" onfocus="this.blur()" class="oncload" onclick="loadHtml(this,event);sendMsg()">
                      今天
                  </a>
              </li>
              <li class="title-menu all-menu-trigger2">

                  <a href="<?php echo URL_PATH?>/yicard/checkallstuinfoweek?cid=<?php echo $v['cid']?>&token=gh_e25d98dd302e&openid=<?php echo \yii::$app->view->params['openid']?>" onfocus="this.blur()" onclick="loadHtml(this,event);outBox()">
                      本周
                  </a>
              </li>
              <li class="title-menu all-menu-trigger2">

                  <a href="<?php echo URL_PATH?>/yicard/checkallstuinfomonth?cid=<?php echo $v['cid']?>&token=gh_e25d98dd302e&openid=<?php echo \yii::$app->view->params['openid']?>" onfocus="this.blur()" onclick="loadHtml(this,event);inbox()">
                      本月
                  </a>
              </li>
          </ul> <!-- sub-menu -->
      </li> <!-- item-has-children --> 
<!--    </foreach>-->
       <?php }?>

<!--    <foreach name="list_par" item="vo" key="key">-->
       <?php foreach($list_par as $k=>$v){?> 
      <li class="item-has-children li-top">
          <a href="#" onfocus="this.blur()" class="li-top-a" id="main-list2">
<!--              {$vo.class}{$vo.stu_name}{$vo.role}-->
            <?php echo $v['class']?><?php echo $v['stu_name']?><?php echo $v['role']?> 
          </a>
          <ul class="sub-menu">
              <li class="title-menu all-menu-trigger2">
                  <a href="<?php echo URL_PATH?>/yicard/checkstuinfo?stuid=<?php echo $v['stu_id']?>&token=gh_e25d98dd302e&type=today" onfocus="this.blur()" class="oncload" onclick="loadHtml(this,event);sendMsg()">
                      今天
                  </a>
              </li>
              <li class="title-menu all-menu-trigger2">
                  <a href="<?php echo URL_PATH?>/yicard/checkstuinfo?stuid=<?php echo $v['stu_id']?>&type=week" onfocus="this.blur()" onclick="loadHtml(this,event);outBox()">
                      本周
                  </a>
              </li>
          </ul> <!-- sub-menu -->
      </li> <!-- item-has-children --> 
<!--    </foreach>-->
    <?php }?>      
<!--    </if>-->
   <?php }?>   
    </ul> <!-- cd-navigation -->
  </nav> <!-- 邮件菜单 结束 -->
<!-- ................................................................... -->
  <div id="tc-main">
    <div id="tc-wapper">
      <div class="tc-text">
        <div class="tc-text-title">尊敬的<font><?php  echo $alert?> 家长</font>：</div>
        <div class="tc-text-content">
                <?php if(empty($alertmsg)){?>

          		<!-- 您的学生 <font><?php  echo $alert?>  </font> 智慧校园业务将于<?php  echo $enddate?>  到期，请进入正梵智慧校园公众号 点击 家校互动 菜单中的平安通知 根据提示进行缴费 。也可交给班主任代收。缴费后您的学生在有效期内将享受在学校免费给您及绑定的亲情号码（可绑定5个亲情号）拨打电话（不限时长），您及绑定的亲情号码可以收到学生进出学校（进出宿舍）的通知（请提醒学生佩戴学生证并规范使用）。若未缴费，自<?php  echo $enddate?>  起将不再享受上述服务，但可免费享受家校沟通服务。若有疑问请咨询人工客服或电话（037155030687）咨询。 -->
              您的学生 <font><?php  echo $alert?>  </font> 平安通知业务将于<?php  echo $enddatepa?>  到期，为避免到期后影响您的使用，请点击【现在续费】，跳转到支付页面进行续费服务。若有疑问请咨询人工客服或电话（037155030687）咨询。
                <?php }else{?>
                 您好!您的平安通知服务尚未开通，请点击“我要支付”或“现在缴费”按钮，根据提示进行交费。如需咨询相关信息，请拨打客服电话：0371-55030687。客服时间：每天 8:30—19:30。 
       <!-- 		<?php  echo $alertmsg?> 
 -->
                <?php }?>
        </div>
      </div>
      <div class="tc-btn">
       <?php if(empty($alertmsg)){?>
        <div class="tc-close" onclick="tc_close()">下次再说</div>
        <a class="go-pay" href="<?php echo URL_PATH?>/zfend/pay?openid=<?php echo \yii::$app->view->params['openid']?>">现在续费</a>      
       <?php }else{ ?>
        <div class="tc-close" style="background-color:gray;" onclick="to_info()" >关闭页面</div>
        <a class="go-pay" href="<?php echo URL_PATH?>/zfend/pay?openid=<?php echo \yii::$app->view->params['openid']?>">现在缴费</a>
       <?php }?>
        <div class="clear"></div>
      </div>
    </div>
  </div>
<!--公共隐藏域-->
<input type="hidden" value="<?php echo URL_PATH?>" id="path" />
<input type="hidden" value="<?php echo \yii::$app->view->params['sid']?>" id="hidden_sid" />
<input type="hidden" value="<?php echo $ischool?>" id="hidden_school" />
<input type="hidden" value="<?php echo \yii::$app->view->params['openid']?>" id="hidden_openid" />
<input type="hidden" value="<?php  echo $alert?>  " id="alert">
<?php echo $this->render('../layouts/footer')?>
<script type="text/javascript">

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
});

// 更改鼠标浮动 列表栏 的样式
function register_user_over(ths){
  $(ths).css({"background-color":"#FF6666"});
}

function register_user_out(ths){
  $(ths).css({"background-color":"#999999"});
};
function witchInfopar(ths){
    var divid = "#with-"+$(ths).attr("id");
    var div = $(ths).next("div"); 
    if(div.css("display")=="none"){ 
    div.css("display","block"); 
    } 
    else{ 
    div.css("display","none"); 
    } 
  if($(divid).hasClass("on")){     
     $(divid).removeClass("on").addClass('off').slideToggle(400);
  }else{
     $(".on").hide(400).addClass("off").removeClass("on");
     $(divid).addClass("on").removeClass('off').slideToggle(400);
  }
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

// 页面进入后 鼠标点击默认页面
$(document).ready(function(){
    var sid=$("#hidden_sid").val();
   $("#main-list").click();
   $("#main-list2").click();
   if(sid!=1){
    loadHtmlByUrl($(".oncload").attr("href"));
    setTimeout(function(){
           if($.trim($("#alert").val())!=""){
             $("#tc-main").show();
           }
         },1000);
    }
});

function tc_close(){
    $("#tc-main").hide();
}
function to_info(){
  var sid=$("#hidden_sid").val();
  var openid=$("#hidden_openid").val();
  window.location="/information/index?openid="+openid+"&sid="+sid+"&status=1";
}
function outBox(){
  $(".header-nav-menu-on").text("本周");
  $(".header-nav-menu-on").css({"background-color":"#33CC66"});
  $("#sendMsg").off("click");
}
function inbox(){
  $(".header-nav-menu-on").text("本月");
  $(".header-nav-menu-on").css({"background-color":"#6699FF"});
  $("#sendMsg").off("click");
}
function sendMsg(){
  $(".header-nav-menu-on").text("今天");
  $(".header-nav-menu-on").css({"background-color":"#FF6666"});
  $("#sendMsg").on("click");
}

function user_checkbox(ths){
  var chex;
  chex = $(ths).find("input");
  chex.click();
}

function register_user_over(ths){
  $(ths).css({"-moz-opacity":"0.9","opacity":".90","filter":"alpha(opacity=90)"});
}

function register_user_out(ths){
  $(ths).css({"-moz-opacity":"1.0","opacity":"1.0","filter":"alpha(opacity=100)"});
}

$(".sub-menu").eq(0).find("li").eq(0).find("a").click();
</script>


