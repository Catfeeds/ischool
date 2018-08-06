<?php
use mobile\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
<link media="all" rel="stylesheet" type="text/css" href="/css/home-css.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/font-awesome.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/simditor.css" /> 
<link media="all" rel="stylesheet" type="text/css" href="/css/ui-dialog.css" />

<script type="text/javascript" src="/js/css3-mediaqueries.js"></script>
<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/Headroom.js"></script>
<script type="text/javascript" src="/js/unslider.js"></script>
<script type="text/javascript" src="/js/home-js.js"></script>
<!--<script type="text/javascript" src="/js/jquery.validate.min.js"></script>-->
<script type="text/javascript" src="/js/LocalResizeIMG.js"></script>
<script type="text/javascript" src="/js/mobileBUGFix.mini.js"></script>

<script type="text/javascript" src="/js/simditor-all.js"></script>
<script type="text/javascript" src="/js/home-page-demo.js"></script>
<script type="text/javascript" src="/js/dialog-min.js"></script>
<script type="text/javascript" src="/js/ajaxload.js"></script>
<script type="text/javascript" src="/js/myDialog.js"></script>

<title>最新公告</title>
</head>
<body class="body-color">
    <?= $content ?>
    

<script type="text/javascript" src="/js/bootstrap-confirmation.js"></script>
<!-- 页脚菜单结束 -->

</body>
</html>
<?php $this->endPage() ?>