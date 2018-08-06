// JavaScript Document

// 幻灯片
$(function() {
    $('.banner').unslider();
});



$(document).ready(function(){

// 详细信息设置输入框
  $("#box-on").click(function(){ 
	 $(".box-text").slideDown("slow");	 
  });

  $("#box-get").click(function(){
	$(".box-text").slideToggle("slow");
  });


// 搜索框弹出和隐藏
  $("#search").click(function(){ 
	 $(".search-box").slideDown("slow");	 
  });

  $(".choice-on").click(function(){ 
	 $(".search-choice").slideDown("slow");	 
  });

  $("#choice-off").click(function(){
	$(".search-choice").slideUp("slow");
	$(".search-box").slideToggle("slow");
  });
  
  
// 超市总价
  $(".price-on").click(function(){ 
	 $(".shop-total").slideDown("slow");	 
  });

  $(".shop-total").click(function(){
	$(".shop-total").slideToggle("slow");
  });  
  
  
// 个人资料设置按钮

  $(".sub-cog").click(function(){
	$(".cog-sub").slideToggle(50);
  });

});


   $(function(){
      $(".add").click(function(){
          var t=$(this).parent().find('input[class*=text_box]');
          t.val(parseInt(t.val())+1)
		  if(parseInt(t.val())>99){
              t.val(99);
          }
          setTotal();
      })
      $(".min").click(function(){
          var t=$(this).parent().find('input[class*=text_box]');
          t.val(parseInt(t.val())-1)
          if(parseInt(t.val())<0){
              t.val(0);
          }
          setTotal();
      })
      
    function setTotal(){
          var s=0;
          $(".mn").each(function(){
          s+=parseInt($(this).find('input[class*=text_box]').val())*parseFloat($(this).find('span[class*=price]').text());
          });
          
          $("#total").html(s.toFixed(2)+" 元");
    }
          setTotal();
    })


