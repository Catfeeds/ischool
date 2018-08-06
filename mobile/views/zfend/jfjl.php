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
				<span>缴费记录</span>
			</div>
		</div>
		<div class="clearfix"></div>
		<div id="main">
			<div id="text"><?=date('Y年m月',time())?></div>
			<ul class="list-group">
                            <?php foreach($childs as $k=>$v){?>
                            <li class="list-group-item">
                                    <span><?=$v['tname']?></span>
                                    <p class="jlp">
                                    <span><?=$v['type']?></span><br />
                                    <span class="sj"><?=date('Y-m-d H:i:s',$v['ctime'])?></span>
                                </p>
                                <span class="pull-right jls"><?=$v['total']?></span>
                            </li>
                            <?php }?>
			</ul>
		</div>
	</body>
</html>
<script>
   $("#back").click(function(){
       window.history.go(-1);
   })  
</script>