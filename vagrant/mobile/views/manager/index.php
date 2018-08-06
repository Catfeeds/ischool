
<header>
<div class="container-fluid">
<div class="row header-menu" id="header-menu1">

<div style="padding-left: 20px" class="col-xs-6 header-nav-menu-on"><a href='#' onclick="javascript:history.back(-1)" data-transition='slide-out'><span class="fa fa-reply pull-left">返回</span></a></div> <!-- header-menu-on 选中   off 未选中 -->
<div class="col-xs-6 header-nav-menu-off all-menu-trigger2" onclick="menu_hide()" id="main-list">
<span class="cd-menu-text">学校管理</span>
<span class="cd-menu-icon"></span>
</div> <!-- header-menu-on 选中   off 未选中 -->

</div>
</div>
</header> <!-- 顶部菜单 结束 -->

<main class="cd-main-content" id="mycontainer"> <!-- 页面主体 -->

</main> <!-- cd-main-content 页面主体 结束 -->
<input type="hidden" value="<?php echo $sid?>" id="hidden_sid" />
<input type="hidden" value="<?php echo $ischool?>" id="hidden_school" />
<input type="hidden" value="<?php echo \yii::$app->view->params['openid']?>" id="hidden_openid" />

<!-- ................................................................... -->
<nav id="cd-lateral-nav2"> <!-- 邮件菜单 -->

<div class="header-nav">

<div class="container-fluid">
<div class="row all-menu-trigger2 text-omit">

<div class="col-xs-12 text-omit" onclick="menu_show()">
<a href="#" onfocus="this.blur()" class="header-nav-back col-xs-3">
<span class="fa fa-reply pull-left" style="padding-top: 10px"></span>
</a>
<a href="#" onfocus="this.blur()" class="header-nav-text">
<span><?php echo $ischool?></span>
</a>
</div>

</div>
</div>
</div>
<ul class="cd-navigation ul-top">
<li class="item-has-children li-top">
<a href="#" onfocus="this.blur()" class="li-top-a">
班级管理
</a>
<ul class="sub-menu">
<li class="title-menu all-menu-trigger2">
<a href="/manager/gotoallclass" onfocus="this.blur()" class="oncload" onclick="loadHtml(this,event);header_text_op()">
班级列表
</a>
</li>
<li class="title-menu all-menu-trigger2">
<a href="/manager/configclass" onfocus="this.blur()" onclick="loadHtml(this,event);header_text_op12()">
配置班级
</a>
</li>
</ul> <!-- sub-menu -->
</li> <!-- item-has-children -->

<li class="item-has-children li-top">
<a href="#" onfocus="this.blur()" class="li-top-a">
考勤管理
</a>
<ul class="sub-menu">

<li class="title-menu all-menu-trigger2">
<a href="/manager/downkaoqin" onfocus="this.blur()" onclick="loadHtml(this,event);header_text_op2()">
考勤导出
</a>
</li>
</ul> <!-- sub-menu -->
</li> <!-- item-has-children -->
<li class="item-has-children li-top">
<a href="#" onfocus="this.blur()" class="li-top-a" id="main-list2">
教师管理
</a>
<ul class="sub-menu">
<li class="title-menu all-menu-trigger2">
<a href="/manager/alluser" onfocus="this.blur()" onclick="loadHtml(this,event);header_text_op33()">
所有教师
</a>
</li>
<li class="title-menu all-menu-trigger2">
<a href="/manager/getpasseduser" onfocus="this.blur()" onclick="loadHtml(this,event);header_text_op3()">
已审核教师
</a>
</li>
<li class="title-menu all-menu-trigger2">
<a href="/manager/getnopassuser" onfocus="this.blur()" onclick="loadHtml(this,event);header_text_op4()">
待审核教师
</a>
</li>
</ul> <!-- sub-menu -->
</li> <!-- item-has-children -->

<li class="item-has-children li-top">
<a href="#" onfocus="this.blur()" class="li-top-a">
权限管理
</a>
<ul class="sub-menu">
<li class="title-menu all-menu-trigger2">
<a href="/manager/editrolepro" onfocus="this.blur()" onclick="loadHtml(this,event);header_text_op5()">
权限编辑
</a>
</li>
<?php if($school == "school") { ?>
<li class="title-menu all-menu-trigger2">
<a href="/manager/edituserrole" onfocus="this.blur()" onclick="loadHtml(this,event);header_text_op6()">
角色分配
</a>
</li>
<li class="title-menu all-menu-trigger2">
<a href="/manager/super" onfocus="this.blur()" onclick="loadHtml(this,event);header_text_op8()">
分配管理员
</a>
</li>
<li class="title-menu all-menu-trigger2">
<a href="/manager/schoolinfo" onfocus="this.blur()" onclick="loadHtml(this,event);header_text_op9()">
编辑学校信息
</a>
</li>
<li class="title-menu all-menu-trigger2">
<a href="javascript:void(0);" onfocus="this.blur()" onclick="outschool('<?php echo sid?>','<?php echo \yii::$app->view->params['openid']?>')">
退出学校
</a>
</li>
<?php } ?>
</ul> <!-- sub-menu -->
</li> <!-- item-has-children -->

<li class="item-has-children li-top">
<a href="#" onfocus="this.blur()" class="li-top-a">
设置
</a>
<ul class="sub-menu">

<li class="title-menu all-menu-trigger2">
    <a href="/manager/hpagesetting?sid=<?php echo $sid?>" onfocus="this.blur()" onclick="loadHtml(this,event);header_text_op11(this)">
学校首页设置
</a>
</li>


</ul> <!-- sub-menu -->
</li> <!-- item-has-children -->

</ul> <!-- cd-navigation -->
</nav> <!-- 邮件菜单 结束 -->
<!-- ................................................................... -->
<div class="container-fluid">
<div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >

<div class="modal-dialog modal-sm">
<div class="modal-content">
<div class="modal-header" style="background-color:#00CC66; color:#FFF; border-radius:5px 5px 0px 0px;">新增角色</div>

<div class="modal-body">
<input type="text" class="form-control" id="rolename" />
</div>

<div class="modal-footer">
<button type="button" class="btn" data-dismiss="modal" style="color:#4e6361;">取消</button>
<button type="button" class="btn" data-dismiss="modal" style="background-color:#00CC66; color:#FFF;" onclick="subb()">确定</button>
</div>

</div>
</div>
</div>
</div>
<div class="container-fluid">
<div class="row animated headroom bounceInDown" id="new-class-room">
<div class="col-xs-12">
<a href="/manager/addclass"  onclick="loadHtml(this,event)">
<div class="data-new-class">新建班级</div>
</a>
</div>
</div>
</div>
<div class="ui active dimmer" id="dimmer-loader">
<div class="ui text loader">加载中...</div>
</div>
<script type="text/javascript">

jQuery(document).ready(function(){
    $('.header-nav-menu-on a').mouseover(function(){
        $(this).css({'color':'white'})
    })
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
          names=names[0]+"等"+len+"人留言";
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

function outschool(id,openid){
  var path=$("#pathurl").val();
  var url="/manager/outschool";
  var openid=$('#hidden_openid').val();
  var sid=$('#hidden_sid').val();

  var tourl="/information/index?token=gh_b07d971c7a39&openid="+openid+"&sid="+sid;
//    alert(tourl);die;
var d = dialog({
    title: '提示',
    content: '您确定要退出该学校吗？',
    okValue: '确定',

    ok: function () {
        doGetReturnRes(url,{sid:sid,openid:openid},function(data){
         if(data=="success")
         {
          alertDialog("退出学校成功")
           window.location.href=tourl;
         }
        }
      );
    },

    cancelValue: '取消',
    cancel: function () {
    }

 });

 d.showModal();


}
// 更改鼠标浮动 列表栏 的样式
function register_user_over(ths){
  $(ths).css({"-moz-opacity":"0.9","opacity":".90","filter":"alpha(opacity=90)"});
}

function register_user_out(ths){
  $(ths).css({"-moz-opacity":"1.0","opacity":"1.0","filter":"alpha(opacity=100)"});
}

function witchInfo(ths){
  var divid = "#with-"+$(ths).attr("id");
  if($(divid).hasClass("on")){

     $(divid).removeClass("on").addClass('off').slideToggle(200);
  }else{
     $(".on").hide(200).addClass("off").removeClass("on");
     $(divid).addClass("on").removeClass('off').slideToggle(200);
  }
}

// 页面进入后 鼠标点击默认页面
$(document).ready(function(){
  var res;
  res = document.getElementById("main-list");
  res.click();

//  loadHtmlByUrl($(".oncload").attr("href"));

});

function header_text_op(){
    $(".header-nav-menu-on").html("<a href='#' onclick='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+"班级列表");

    $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
        $(this).css({'color':'white'})
    });
  $("#new-class-room").show();
  $("#new-class-room").headroom();
}
function header_text_op2(){
    $(".header-nav-menu-on").html("<a href='#' onclick='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+"考勤导出");

  $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
      $(this).css({'color':'white'})
  });
  $("#new-class-room").hide();
}
function header_text_op3(){
    $(".header-nav-menu-on").html("<a href='#' onclick='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+"已审核教师");

  $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
      $(this).css({'color':'white'})
  });
  $("#new-class-room").hide();
}
function header_text_op33(){
    $(".header-nav-menu-on").html("<a href='#' onclick='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+"所有教师");

    $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
        $(this).css({'color':'white'})
    });
    $("#new-class-room").hide();
}
function header_text_op4(){
    $(".header-nav-menu-on").html("<a href='#' onclick='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+"待审核教师");

    $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
        $(this).css({'color':'white'})
    });
  $("#new-class-room").hide();
}
function header_text_op5(){
  $(".header-nav-menu-on").html("<a href='#' onclick='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+"权限编辑");
  $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
      $(this).css({'color':'white'})
  });
  $("#new-class-room").hide();
}
function header_text_op6(){
  $(".header-nav-menu-on").html("<a href='#' onclick='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+"角色分配");

    $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
        $(this).css({'color':'white'})
    });
  $("#new-class-room").hide();
}
function header_text_op7(){
  $(".header-nav-menu-on").text("设置");
  $(".header-nav-menu-on").css({"background-color":"#666666"});
  $("#new-class-room").hide();
}
function header_text_op8(){
    $(".header-nav-menu-on").html("<a href='#' onclick='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+"学校超管");

  $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
      $(this).css({'color':'white'})
  });
  $("#new-class-room").hide();
}
function header_text_op9(){
    $(".header-nav-menu-on").html("<a href='#' onclick='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+"编辑学校信息");

    $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
        $(this).css({'color':'white'})
    });
  $("#new-class-room").hide();
}
function header_text_op10(){
    $(".header-nav-menu-on").html("<a href='#' onclick='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+"作息时间");

  $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
      $(this).css({'color':'white'})
  });
  $("#new-class-room").hide();
}
function header_text_op11(ths){
  $(".header-nav-menu-on").html("<a href='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+$(ths).text());
  $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
      $(this).css({'color':'white'})
  });
  $("#new-class-room").hide();
}
function header_text_op12(){
    $(".header-nav-menu-on").html("<a href='#' onclick='javascript:history.back(-1)'><span class='fa fa-reply pull-left'></span></a>"+"配置班级");

    $(".header-nav-menu-on").css({"background-color":"#666666"}).find('a').mouseover(function(){
        $(this).css({'color':'white'})
    });
    $("#new-class-room").hide();
}
function menu_hide(){
	$("#new-class-room").hide();
}
function menu_show(){
	$("#new-class-room").show();
}


function checkschoolInfo(){
    alertDialog($("#sccity").val());
 
  return false;
}

function upl(){

  var path=$("#pathurl").val();
  var sccity=$("#sccity").val();
  var scarea=$("#scarea").val();
  var sctype=$("#sctype").val();
  var scname=$("#scname").val();
  var scpro=$("#scpro").val();
  if(sccity=="")
  {
    alertDialog("市不能为空");
    return false;
  }

  if(scarea=="")
  {
    alertDialog("县区不能为空");
    return false;
  }

  if(sctype=="")
  {
    alertDialog("学校类型不能为空");
    return false;
  }

  if(scname=="")
  {
    alertDialog("学校名字不能为空");
    return false;
  }

  if(scpro=="")
  {
    alertDialog("省不能为空");
    return false;
  }
  var scsid=$("#hidden_sid").val();
  var url="/manager/saveschool";


  var d = dialog({
    title: '提示',
    content: '您确定要修改学校的信息吗？',
    okValue: '确定',

    ok: function () {
        doGetReturnRes(url,{scarea:scarea,sctype:sctype,scname:scname,scpro:scpro,scsid:scsid,sccity:sccity},function(data){
         if(data=="success")
         {
          alertDialog("更新学校信息成功")
           window.location.reload();
         }
         else
         {
          alertDialog("该学校已经存在，请更换名字后重新尝试")
         }
        }
      );
    },

    cancelValue: '取消',
    cancel: function () {
    }

 });

 d.showModal();
   
}

function subb(){
  var path=$("#pathurl").val();

  if($.trim($("#rolename").val())==""){
    alertDialog("请输入角色名！");
  }else{

    $.getJSON('/manager/saverole',
      {role:$("#rolename").val(),sid:$("#hidden_sid").val(),school:$("#hidden_school").val()},function (data){
            if(data.f=='success'){
                alertDialog("新增成功！");
               $("#rolename").val("");
              $("#role").append("<option class='select-option' value='"+data.data[0].id+"'>"+data.data[0].name+"</option>");
            }else{
                alertDialog("操作失败，请重试！");
            }
        })
  }
  
}
//$(".sub-menu").eq(0).find("li").eq(0).find("a").click();

</script>


