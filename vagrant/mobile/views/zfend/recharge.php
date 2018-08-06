<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>我要支付</title>
	<link rel="stylesheet" href="/css/zf.css">
	<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
</head>
	<body>

	<div class="top">
		<a href="<?php echo URL_PATH?>/zfend/pay"><div class="left"> </div></a>
		<h1>我要支付</h1>
		<div class="clearfix"></div>
	</div>
		<div class="yw">
			<p class="yw_1">请选择您需订购的业务</p>
			<p>可多选，若多选可享受套餐优惠</p>
		</div>
		<div class="xz">
			<ul>
				<li class="xz1" id="cos0">
					<div class="check">
						<input id="ischange" type="checkbox" name="xz0" class="check0" />
						<div class="check_img"></div>
					</div>
					<div class="xz2">
						<span class="danj" style="float: right;"></span>
						<h3>平安通知</h3>
						<p>本业务家长可实时收到学生进出学校通知</p>
					</div>
				</li>
				<li class="xz1" id="cos1">
					<div class="check">
						<input type="checkbox" name="xz0" class="check0" value="1" />
						<div class="check_img"></div>
					</div>
					<div class="xz2">
						<span class="danj" style="float: right;"></span>
						<h3>家校沟通</h3>
						<p>本业务可使家长和老师实时在线沟通</p>
					</div>
				</li>
				<li class="xz1" id="cos2">
					<div class="check">
						<input type="checkbox" name="xz0" class="check0" />
						<div class="check_img"></div>
					</div>
					<div class="xz2">
						<span  class="danj" style="float: right;"></span>
						<h3>亲情电话</h3>
						<p>本业务可使学生用学生证无限量与家长打电话</p>
					</div>
				</li>
				<li class="xz1" id="cos3">
					<div class="check">
						<input type="checkbox" name="xz0" class="check0" />
						<div class="check_img"></div>
					</div>
					<div class="xz2">
						<span class="danj" style="float: right;"></span>
						<h3>餐卡</h3>
						<p>本业务可对餐卡充值和收到餐卡消费信息</p>
					</div>
				</li>
			</ul>
		</div>
		<div class="sj">
			<button type="button" class="btn1">
				一学年
				<input type="radio" name="time" checked="checked" class="timec"  id="yxni"/>
			</button>
			<button type="button" class="btn1">
				一学期
				<input type="radio" name="time"  class="timec"  id="yxqi"/>
			</button>
			<button type="button" class="btn1">
				一个月
				<input type="radio" name="time"  class="timec"  id="ygyu"/>
			</button>
		    </div>
		<div class="ts">
			已选套餐： <span id="tc"></span>
			<p class="tsxj">
				套餐每月价格 <span id="ygm"></span> 元，无优惠；<br />
				套餐学期原价 <span id="yhj"></span> 元，优惠 <span id="byh"></span> 元；<br />
				套餐学年原价 <span id="nyhj"></span> 元，优惠 <span id="nyh"></span> 元。
			</p>
			<p class="price">优惠价：<span id="nowp"></span></p>
		</div>
		<div class="footer">
			<a id="zongjia">确认支付</a>
		</div>
	<input type="hidden" id="path" value="<?php echo URL_PATH?>">
	<input type="hidden" id="trade_name" value="<?php echo $nxinxi?>">
	<input type="hidden" id="openid" value="<?php echo \yii::$app->view->params['openid']?>">
	<script type="text/javascript">
		function mapAllParam(type){
			var param = {};
			var one=$("input[type='checkbox']").eq(0).is(':checked');//四中套餐的选中状态
			var two=$("input[type='checkbox']").eq(1).is(':checked');
			var three=$("input[type='checkbox']").eq(2).is(':checked');
			var four=$("input[type='checkbox']").eq(3).is(':checked');
			var xqxn = $('.btn1 input[type="radio"]:checked').attr("id");//判断是学期还是学年还是一个月
			zfzl=((one==true?"|pa":"|npa")+(two==true?"-jx":"-njx")+(three==true?"-qq":"-nqq")+(four==true?"-ck-":"-nck-")+(xqxn));//支付种类
			total = parseInt($("#nowp").html().substr(1,5));//总价
			param.zfzl = zfzl;
			param.total = total;
			param.trade_name = $("#trade_name").val();
			param.openid = $("#openid").val();
			return param;
		}
		$(function(){
			var url = $("#path").val()+"/zfend/redirectpay";
			$("#zongjia").on("click",function(){
				total = parseInt($("#nowp").html().substr(1,5));
				if(total <= 0 || (!total)){
					alert("价格不合法，请您重新选择！");
					return false;
				}
				$.getJSON(url,mapAllParam("JSAPI"),function(data){
					if(data.retcode==0){
						document.write("正在进入支付页面请您稍等待……");
						            window.clear;
						// $("#zongjia").attr("href",data.url);
						window.location = data.url;
					}else{
						alert(data.retmsg);
					}
				});
			});
});
	</script>
	<script>
		var $zsz = <?php echo json_encode($tc) ?>;
		var $pass = <?php echo json_encode($pass) ?>;
		console.log($pass);
		for(var i in $zsz){
			for(var j in $zsz[i]){
				if ($zsz[i].hasOwnProperty(j)) { //filter,只输出man的私有属性
					console.log(j,":",$zsz[i][j]);
					$zsz[i][j] = $zsz[i][j]*1;
				};
			}
		}
//		$zsz.each(function(k,v){
//			console.log(v);
//		});
	</script>
		<script type="text/javascript" src="/js/index.js" ></script>
	</body>

</html>


