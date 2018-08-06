<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title></title>
		<link rel="stylesheet" href="/css/bootstrap.css" />
		<link rel="stylesheet" href="/css/xuefei.css" />
                <script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
	</head>
	<body>
		<div id="header">
			<div id="back">
				<img src="/img/icon.png" />
				<span>学杂费缴费</span>
			</div>
			<a href="<?php echo Url::toRoute(['zfend/jfjl'])?>">缴费记录</a>
		</div>
		<div class="clearfix"></div>
		<div id="main">
			<div id="text">缴费项目</div>
			<ul class="list-group">
                <a href="<?php echo Url::toRoute(['zfend/xf'])?>" class="list-group-item">
                    学费
                    <img style="height: 1.5rem;" class="pull-right" src="/img/left.png" />
                </a>
                <a href="<?php echo Url::toRoute(['zfend/zsf'])?>" class="list-group-item">
                    住宿费
                    <img style="height: 1.5rem;" class="pull-right" src="/img/left.png" />
                </a>
                <a href="<?php echo Url::toRoute(['zfend/sf'])?>" class="list-group-item">
                    书费
                    <img style="height: 1.5rem;" class="pull-right" src="/img/left.png" />
                </a>
			</ul>
		</div>
	</body>
</html>
<script>
   $("#back").click(function(){
       window.history.go(-1);
   }) 
</script>
    
