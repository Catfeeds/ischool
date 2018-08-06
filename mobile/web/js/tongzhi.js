jQuery(document).ready(function($){
	var $lateral_menu_trigger = $('#cd-menu-trigger'),
		$all_menu_trigger = $('.all-menu-trigger'),
		$all_menu_trigger2 = $('.all-menu-trigger2'),
		$content_wrapper = $('.cd-main-content'),
		$navigation = $('header');

	//点击 all-menu-trigger 时，切换到main页面
	$all_menu_trigger.on('click', function(event){
		event.preventDefault();
		
		$lateral_menu_trigger.toggleClass('is-clicked');
        $navigation.toggleClass('lateral-menu-is-open');
		$content_wrapper.toggleClass('lateral-menu-is-open').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
			// firefox transitions break when parent overflow is changed, so we need to wait for the end of the trasition to give the body an overflow hidden
			$('body').toggleClass('overflow-hidden');
		});
		$('#cd-lateral-nav').toggleClass('lateral-menu-is-open');
		$("#Send-out").toggle();
		$("#confirm").toggle();
		//check if transitions are not supported - i.e. in IE9
		if($('html').hasClass('no-csstransitions')) {
			$('body').toggleClass('overflow-hidden');
		}
		$("#icon_selectstu").show();
	});	


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
		$("#Send-out").toggle();
	    $("#confirm").toggle();
		//check if transitions are not supported - i.e. in IE9
		if($('html').hasClass('no-csstransitions')) {
			$('body').toggleClass('overflow-hidden');
		}

		$("#confirm").show();
		$("#Send-out").hide();
	});	

	//open (or close) submenu items in the lateral menu. Close all the other open submenu items.
	$('.item-has-children').children('a').on('click', function(event){
		event.preventDefault();
		$(this).toggleClass('submenu-open').next('.sub-menu').slideToggle(200).end().parent('.item-has-children').siblings('.item-has-children').children('a').removeClass('submenu-open').next('.sub-menu').slideUp(200);
	});

	$("#confirm").on('click',function(){
     $("#icon_selectstu").hide();
	});

	$("#clas").on("click",function(){

		$(".class-list").show();
		$(".user-list").hide();
		$(".user-search").hide();
		$(this).removeClass("header-menu-on").removeClass("header-menu-off").addClass("header-menu-on");
	  $("#stus").removeClass("header-menu-on");
	});

	$("#stus").on("click",function(){

		$(".class-list").hide();
		$(".user-list").show();
		$(".user-search").show();
		$(this).removeClass("header-menu-on").removeClass("header-menu-off").addClass("header-menu-on");
	  $("#clas").removeClass("header-menu-on");
	});

});