$(function(){
	$(".check_toggle").parent().siblings().find("input[type='checkbox']").prop("checked",true);
	$(".danj").eq(0).html("&yen;"+36);
	$(".danj").eq(1).html("&yen;"+30);
	$(".danj").eq(2).html("&yen;"+50);
	$(".danj").eq(3).html("&yen;"+50);
	//价格隐藏
	$(".danj").css({'display':'none'});
	//单项选择隐藏
	// $("input[type='checkbox'] + div").css({'display':'none'});
	// $(".tsxj").css({'display':'none'});
	$("ul").css({'margin-top':'-3%'});
	function B(){
		if($pass['jxpass']=='n'){
			$(".danj").eq(0).closest('.xz1').remove();
	    }
		if($pass['papass']=='n'){
			$(".danj").eq(1).closest('.xz1').remove();
	    }
		if($pass['qqpass']=='n'){
			$(".danj").eq(2).closest('.xz1').remove();
	    }
		if($pass['ckpass']=='n'){
            $(".danj").eq(3).closest('.xz1').remove();
            $("#ykt_remind").text('(该项全选8元/月，80元/年)');
	    }
	};
	B();
	$(".btn1").eq(0).css({"background-color":"#38af03","color":"white"});

	var $sfkh =0;
	var $sfky=0;
	var $zjm=0;
	$("#nowp").html("&yen;"+0);
	function A(){
		var one=$("input[type='checkbox']").eq(0).is(':checked');
		var two=$("input[type='checkbox']").eq(1).is(':checked');
		var three=$("input[type='checkbox']").eq(2).is(':checked');
		var four=$("input[type='checkbox']").eq(3).is(':checked');
		//套餐项33,35,335,3353,333 对应的价格 one-》five		
		$onm = 3; $twm=3;$threm=5;$foum=5;
		$one=$zsz['half']['qinqing'];
		$two=$zsz['half']['pingan'];
		$three=$zsz['half']['jiaxiao'];
		$seven=$zsz['half']['jiaxiaock'];
		$four=$zsz['half']['canka'];
		$five=$zsz['half']['sss'];
		$six=$zsz['half']['ww'];
		$ony=$zsz['year']['qinqing'];
		$twy=$zsz['year']['pingan'];
		$threy=$zsz['year']['jiaxiao'];
		$seveny=$zsz['year']['jiaxiaock'];
		$fouy=$zsz['year']['canka'];
		$fivy=$zsz['year']['sss'];
		$sixy=$zsz['year']['ww'];
		$zjm=(one==true&&two==true&&three==true&&four==true)?13:((two==true&&three==true&&four==true)?10:(one==true?$onm:0)+(two==true?$twm:0)+(three==true?$threm:0)+(four==true?$foum:0));	//月套餐
		$zjh=((one==true?$onm*6:0)+(two==true?$twm*6:0)+(three==true?$threm*6:0)+(four==true?$foum*6:0));	//半年的原价格
		$zjy=((one==true?$onm*12:0)+(two==true?$twm*12:0)+(three==true?$threm*12:0)+(four==true?$foum*12:0));	//一年的原价格
		$yearyouhui=((one==true?$onm*12:0)+(two==true?$twm*10:0)+(three==true?$threm*10:0)+(four==true?$foum*10:0));	//一年的单项优惠价格
		if($("input[type='radio']").eq(0).is(':checked')){
			$(".tsxj").html("套餐学年原价 <span id='nyhj'></span> 元，优惠 <span id='nyh'></span> 元。");
		}else if($("input[type='radio']").eq(1).is(':checked')){
			$(".tsxj").html("套餐学期原价 <span id='yhj'></span> 元，优惠 <span id='byh'></span> 元；<br />");
		}else{
			$(".tsxj").html("套餐每月价格 <span id='ygm'></span> 元;<br />");				
		}
		$("#ygm").css({"color":"red","font-size":"1em"});
		$("#yhj").css({"color":"red","font-size":"1em"});
		$("#nyhj").css({"color":"red","font-size":"1em"});
		$("#byh").css({"color":"red","font-size":"1em"});
		$("#nyh").css({"color":"red","font-size":"1em"});
		$("#ygm").html($zjm);
		$("#yhj").html($zjh);
		$("#nyhj").html($zjy);
		var checks = document.getElementsByName("xz0");//获取被选中套餐的个数
		
		n = 0;
		for(i=0;i<checks.length;i++){
			if(checks[i].checked)
				n++;
		}
		if(n==2  && (three !=true) && (four !=true)){
			$sfkh = $one;					//一学期的优惠价格 33
			$sfky = $ony;					//一学年的优惠价格
		}else if(n==2 && (three==true||four==true) && (two==true)){
			$sfkh = $two;					//35套餐(一卡通)
			$sfky = $twy;
		}else if(n==2 && (one==true) && (three==true||four==true) ){
			$sfkh = $two;					//35套餐
			$sfky = 86;
		}
		else if(n==2 && (three==true && four==true)){
			$sfkh = $six;					//55套餐
			$sfky = $sixy;
		}else if(n==3 && (one==true && two==true && three==true) ){
			$sfkh = $three;					//335组合
			$sfky = $threy;
		}else if(n==3 && (one==true && two==true && four==true) ){
			$sfkh = $seven;					//33组合+餐卡
			$sfky = $seveny;
		}else if(n==3 && (two==true && three==true && four==true) ){
			$sfkh =$five;						//355组合(一卡通)
			$sfky =$fivy;			
		}else if(n==3 && (one==true && three==true && four==true)){
			$sfkh =78;						//355组合
			$sfky =136;
		}
		else if(n==4){
			$sfkh = $four;					//3355组合
			$sfky = $fouy;
		}else if(n==1){
			$sfkh = $zjh;
			$sfky = $yearyouhui;
		}

		$byh=$zjh-$sfkh;			//半年优惠了多少钱
		$nyh=$zjy-$sfky;			//一年优惠了多少钱
		$("#byh").html($byh);
		$("#nyh").html($nyh);

		if(n == 0){
			$("#yhj").html(0);
			$("#nyhj").html(0);
			$("#byh").html(0);
			$("#nyh").html(0);
			$sfkh=0;
			$sfky=0;
		}

	};
	A();
   	$.each($("input"), function(index,element) {
		if (element.checked == true && index < 4) {
			var t = $("h3").eq(index).text();
			var p = $("#tc").text();
			$("#tc").text(p + " " + t);
			$("#tc").css("color", "red");
		} else {
			var t = $("h3").eq(index).text();
			var p = $("#tc").text();
			p = p.replace(t, "");
			$("#tc").text(p);
		}
	});
	$("#nowp").html("&yen;"+$sfky);
	$.each($("input"), function(index,element) {
		$(this).change(function(){
			if(element.checked==true&&index<4){
			    var t=$("h3").eq(index).text();
			    var p=$("#tc").text();
			    $("#tc").text(p+" "+t);
			    $("#tc").css("color","red");
			}else{
				var t=$("h3").eq(index).text();
				var p=$("#tc").text();
				p=p.replace(t,"");
				$("#tc").text(p);
			};
			if(index<4){
				var checked_total=$(this).parent().parent().parent().find("input[type='checkbox']");
				var i=0;			
				$.each(checked_total, function(index,element) {
					if(element.checked==true){
						i++;
					}
				});
				if(i==checked_total.length){
					$(this).parent().parent().siblings().find(".toggle_this").addClass("check_toggle");
				}else if(i==0){
					$(this).parent().parent().siblings().find(".toggle_this").removeClass("check_toggle");
				}
			}
			change_total();
		});
	});
	$(".toggle_this").click(function(){	  	  	
		$(this).toggleClass("check_toggle");
		if($(this).hasClass('check_toggle')){
						
			var checkbox=$(this).parent().siblings().find("input[type='checkbox']");			
			$.each(checkbox, function(index,element) {
				if(element.checked!=true){
					var h3=$(this).parent().siblings().find("h3");								
					var t=$(h3).eq(0).text();
				    var p=$("#tc").text();
				    $("#tc").text(p+" "+t);
				    $("#tc").css("color","red");
				}
								
	        });
			$(this).parent().siblings().find("input[type='checkbox']").prop("checked",true);
		}else{
			
			var checkbox=$(this).parent().siblings().find("input[type='checkbox']");
			$.each(checkbox, function(index,element) {
				var h3=$(this).parent().siblings().find("h3");
				var t=$(h3).eq(0).text();
				var p=$("#tc").text();
				p=p.replace(t,"");
				$("#tc").text(p);
	        });
			 $(this).parent().siblings().find("input[type='checkbox']").prop("checked",false);
		}
		change_total();								   
	});
	
	function change_total(){
	    A();
	    // $(".danj").css({'display':'none'});
		//计总开始
		if($("input[type='radio']").eq(0).is(':checked')){
			// alert($sfky);
			$(".btn1").eq(0).css({"background-color":"#38af03","color":"white"});
			$("#nowp").html("&yen;"+$sfky);	
			$(".danj").eq(0).html("&yen"+36);
			$(".danj").eq(1).html("&yen"+30);
			$(".danj").eq(2).html("&yen"+50);
			$(".danj").eq(3).html("&yen"+50);				
			// B();
		}else{
			$(".btn1").eq(0).css({"background-color":"white","color":"black"});
		}
	    if($("input[type='radio']").eq(1).is(':checked')){
	    	$(".btn1").eq(1).css({"background-color":"#38af03","color":"white"});
	    	$("#nowp").html("&yen;"+$sfkh);
	        $(".danj").eq(0).html("&yen"+18);
	        $(".danj").eq(1).html("&yen"+18);
	        $(".danj").eq(2).html("&yen"+30);
	        $(".danj").eq(3).html("&yen"+30);		          		            	        	
	        // B();
	    }else{	        	
	    	$(".btn1").eq(1).css({"background-color":"white","color":"black"});		        
	    }
		if($("input[type='radio']").eq(2).is(':checked')){
			$(".btn1").eq(2).css({"background-color":"#38af03","color":"white"});
			$("#nowp").html("&yen;"+$zjm);
			$(".danj").eq(0).html("&yen"+3);
			$(".danj").eq(1).html("&yen"+3);
			$(".danj").eq(2).html("&yen"+5);
			$(".danj").eq(3).html("&yen"+5);
			// B();
		}else{
			$(".btn1").eq(2).css({"background-color":"white","color":"black"});
		}
	}
})

