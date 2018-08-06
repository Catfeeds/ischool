// JavaScript Document

// 幻灯片
$(function() {
    $('.banner').unslider();
});


// 更改鼠标浮动 列表栏 的样式
function register_user_over(ths){
  $(ths).css({"-moz-opacity":"0.7","opacity":".70","filter":"alpha(opacity=70)"});
}

function register_user_out(ths){
  $(ths).css({"-moz-opacity":"1.0","opacity":"1.0","filter":"alpha(opacity=100)"});
};


function witchInfo(ths){
  var divid = "#with-"+$(ths).attr("id");
  if($(divid).hasClass("on")){

     $(divid).removeClass("on").addClass('off').slideToggle(200);
  }else{
     $(".on").hide().addClass("off").removeClass("on");
     $(divid).addClass("on").removeClass('off').slideToggle(200);
  }
}

