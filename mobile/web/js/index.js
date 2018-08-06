/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
    $(".danj").eq(0).html("&yen;"+30);
	$(".danj").eq(1).html("&yen;"+30);
	$(".danj").eq(2).html("&yen;"+50);
	$(".danj").eq(3).html("&yen;"+50);	
	if($sid=='56649'){
		$(".danj").eq(0).html("&yen"+20);
		$(".danj").eq(1).html("&yen"+20);
		$(".danj").eq(2).html("&yen"+30);
		$(".danj").eq(3).html("&yen"+30);
		$("#nowp").html("&yen;"+0.01);	        		
		$(".danj").css({'display':'none'});
		$(".tsxj").css({'display':'none'});
		$(".ts").append("<p class='tiyan' style='color: red;font-size: 1em;'>体验时限：1个月，到2018年4月15日</p>");
		$("input[type='checkbox']").eq(0).prop("checked",true);
		$("input[type='checkbox']").eq(1).prop("checked",true);
		$("input[type='checkbox']").eq(2).prop("checked",true);
	}else if($sid=='56739'){
		$(".danj").eq(0).html("&yen"+20);
		$(".danj").eq(1).html("&yen"+20);
		$(".danj").eq(2).html("&yen"+30);
		$(".danj").eq(3).html("&yen"+30);
		$("input[type='checkbox']").eq(0).prop("checked",true);
		$("input[type='checkbox']").eq(1).prop("checked",true);
		$("input[type='checkbox']").eq(3).prop("checked",true);
	}	
	$("#yhj").html(0);
	$("#nyhj").html(0);
	$("#byh").html(0);
	$("#nyh").html(0);
	function B(){
		if($pass['papass']=='n'){
		    $("input[type='checkbox']").eq(0).attr("disabled","disabled");
		    $(".danj").eq(0).html("学校没开通此服务");
		    $(".xz1").eq(0).css({"background-color":"#ddd","border-color":"white"});
		    $(".danj").eq(0).css("color","red");
	    }
		if($pass['jxpass']=='n'){
	    	$("input[type='checkbox']").eq(1).attr("disabled","disabled");
		    $(".danj").eq(1).html("学校没开通此服务");
		    $(".xz1").eq(1).css({"background-color":"#ddd","border-color":"white"});
		    $(".danj").eq(1).css("color","red");
	    }
		if($pass['qqpass']=='n'){
	    	$("input[type='checkbox']").eq(2).attr("disabled","disabled");
		    $(".danj").eq(2).html("学校没开通此服务");
		    $(".xz1").eq(2).css({"background-color":"#ddd","border-color":"white"});
		    $(".danj").eq(2).css("color","red");
	    }
		if($pass['ckpass']=='n'){
			$(".danj").eq(3).closest('.xz1').remove();
	    	// $("input[type='checkbox']").eq(3).attr("disabled","disabled");
      //       $(".danj").eq(3).closest('.xz1').css("display",'none');
		    // $(".xz1").eq(3).css({"background-color":"#ddd","border-color":"white"});
		    // $(".danj").eq(3).css("color","red");
	    }
	};
	B();
	
	if($sid=='56649'){
		$(".btn1").eq(1).css({"background-color":"#38af03","color":"white"});
	}else{
		$(".btn1").eq(0).css({"background-color":"#38af03","color":"white"});
	}
	$("#ygm").css({"color":"red","font-size":"1em"});
	$("#yhj").css({"color":"red","font-size":"1em"});
	$("#nyhj").css({"color":"red","font-size":"1em"});
	$("#byh").css({"color":"red","font-size":"1em"});
	$("#nyh").css({"color":"red","font-size":"1em"});
	var $sfkh =0;
	var $sfky=0;
	var $zjm=0;
	if($sid=='56649'){
		$("#nowp").html("&yen;"+0.01);
	}else{
		$("#nowp").html("&yen;"+0);
	}
	
	A=function(){
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
		$zjm=((one==true?$onm:0)+(two==true?$twm:0)+(three==true?$threm:0)+(four==true?$foum:0));	//一个月的原价格
		$zjh=((one==true?$onm*6:0)+(two==true?$twm*6:0)+(three==true?$threm*6:0)+(four==true?$foum*6:0));	//半年的原价格
		$zjy=((one==true?$onm*12:0)+(two==true?$twm*12:0)+(three==true?$threm*12:0)+(four==true?$foum*12:0));	//一年的原价格
		$yearyouhui=((one==true?$onm*10:0)+(two==true?$twm*10:0)+(three==true?$threm*10:0)+(four==true?$foum*10:0));	//一年的单项优惠价格
		if($sid=='56649' ){
			$zjm=(one==true&&two==true&&three==true)?10:((one==true?$onm:0)+(two==true?$twm:0)+(three==true?$threm:0)+(four==true?$foum:0));
			$zjy=((one==true?20:0)+(two==true?20:0)+(three==true?30:0)+(four==true?30:0));
			$yearyouhui=((one==true?20:0)+(two==true?20:0)+(three==true?30:0)+(four==true?30:0));
		}else if($sid=='56739'){
			$zjm=(one==true&&two==true&&four==true)?10:((one==true?$onm:0)+(two==true?$twm:0)+(three==true?$threm:0)+(four==true?$foum:0));
			$zjy=((one==true?20:0)+(two==true?20:0)+(three==true?30:0)+(four==true?30:0));
			$yearyouhui=((one==true?20:0)+(two==true?20:0)+(three==true?30:0)+(four==true?30:0));
		}
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
		}else if(n==2 && (three==true||four==true) && (one==true||two==true)){
			$sfkh = $two;					//35套餐
			$sfky = $twy;
		}else if(n==2 && (three==true && four==true)){
			$sfkh = $six;					//55套餐
			$sfky = $sixy;
		}else if(n==3 && (one==true && two==true && three==true) ){
			$sfkh = $three;					//335组合
			$sfky = $threy;
		}else if(n==3 && (one==true && two==true && four==true) ){
			$sfkh = $seven;					//33组合+餐卡
			$sfky = $seveny;
		}else if(n==3 && (three==true && four==true) ){
			$sfkh =$five;						//355组合
			$sfky =$fivy;
		}else if(n==4){
			$sfkh = $four;					//3355组合
			$sfky = $fouy;
		}else if(n==1){
			$sfkh = $zjh;
			$sfky = $yearyouhui;
		}

		// if(n ==2 && (three !=true)){		//	33套餐
		// 	$sfkh = $one;					//一学期的优惠价格
		// 	$sfky = $ony;					//一学年的优惠价格
		// }else if(n ==2 && (three ==true) &&(four=!true)){	//35套餐
		// 	$sfkh = $two;
		// 	$sfky = $twy;
		// }else if(n ==3 && (three ==true) &&(four=!true)){	//335套餐
		// 	$sfkh = $three;
		// 	$sfky = $threy;
		// }else if(n ==3 && (three ==!true)){		//333套餐
		// 	$sfkh = $three;
		// 	$sfky = $threy;
		// }else if(n ==1){
		// 	$sfkh = $zjh;
		// 	$sfky = $yearyouhui;
		// }else if(n ==4){
		// 	$sfkh = $four;
		// 	$sfky = $fouy;
		// }else if((four==true) &&(three ==true) && n==2){	//55组合
		// 	$sfkh =60;
		// 	$sfky =100;
		// }else if((four==true) &&(three ==true) && n==3){   //355组合
		// 	$sfkh =78;
		// 	$sfky =130;
		// }else if(n ==2 && (four ==true)){	//35套餐
		// 	$sfkh = $two;
		// 	$sfky = $twy;
		// }else if(n ==3 && (four ==true)){	//335套餐
		// 	$sfkh = $three;
		// 	$sfky = $threy;
		// }

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
	var xqxn = $('.btn1 input[type="radio"]:checked').attr("id");//判断是学期还是学年还是一个月
	if($sid=='56649'){
		$("#nowp").html("&yen;"+0.01);
	}else{
		$("#nowp").html("&yen;"+$sfky);
	}
	
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
			A();
//计总开始
			if($("input[type='radio']").eq(0).is(':checked')){
				$(".btn1").eq(0).css({"background-color":"#38af03","color":"white"});
				$("#nowp").html("&yen;"+$sfky);	
				$(".danj").eq(0).html("&yen"+30);
				$(".danj").eq(1).html("&yen"+30);
				$(".danj").eq(2).html("&yen"+50);
				$(".danj").eq(3).html("&yen"+50);							
				if($sid=='56649'){
					$(".danj").eq(0).html("&yen"+20);
					$(".danj").eq(1).html("&yen"+20);
					$(".danj").eq(2).html("&yen"+30);
					$(".danj").eq(3).html("&yen"+30);
				}else if($sid=='56739'){
					$(".danj").eq(0).html("&yen"+20);
					$(".danj").eq(1).html("&yen"+20);
					$(".danj").eq(2).html("&yen"+30);
					$(".danj").eq(3).html("&yen"+30);
				}				
				B();
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
	        	if($sid=='56649'){
	        		$("#nowp").html("&yen;"+0.01);	        		
	        		$(".danj").css({'display':'none'});
	        		$(".tsxj").css({'display':'none'});
	        		$(".ts").append("<p class='tiyan' style='color: red;font-size: 1em;'>体验时限：1个月，到2018年4月15日</p>");
	        		// $(".check_img").css({'display':'none'});
	        		$("input[type='checkbox']").eq(0).prop("checked",true);
					$("input[type='checkbox']").eq(1).prop("checked",true);
					$("input[type='checkbox']").eq(2).prop("checked",true);
	        	}		          		            	        	
	            B();
	        }else{
	        	
	        	$(".btn1").eq(1).css({"background-color":"white","color":"black"});
	        	if($sid=='56649'){
	        		$(".danj").css({'display':'block'});
	        		$(".tsxj").css({'display':'block'});
	        		// $(".check_img").css({'display':'block'});
	        		$(".tsxj").css({'display':'block'});
	        		$(".ts>.tiyan").remove();

	        	}
		        
	        }
			if($("input[type='radio']").eq(2).is(':checked')){
				$(".btn1").eq(2).css({"background-color":"#38af03","color":"white"});
				$("#nowp").html("&yen;"+$zjm);
				$(".danj").eq(0).html("&yen"+3);
				$(".danj").eq(1).html("&yen"+3);
				$(".danj").eq(2).html("&yen"+5);
				$(".danj").eq(3).html("&yen"+5);
				B();
			}else{
				$(".btn1").eq(2).css({"background-color":"white","color":"black"});
			}
		});
	});
	/*$("#zongjia").click(function(){
		alert($("#nowp").html().substr(1,5));
	})*/
})

