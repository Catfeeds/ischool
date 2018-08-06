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
<link media="all" rel="stylesheet" type="text/css" href="/css/tongzhi.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/index-columns.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/home-css.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/user-css.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/ui-dialog.css" />

<script type="text/javascript" src="/js/css3-mediaqueries.js"></script>
<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/ajaxload.js"></script>
<script type="text/javascript" src="/js/dialog-min.js"></script>
<script type="text/javascript" src="/js/myDialog.js"></script>
<script type="text/javascript" src="/js/LocalResizeIMG.js"></script>
<script type="text/javascript" src="/js/mobileBUGFix.mini.js"></script>
<script type="text/javascript" src="/js/manage.js"></script>
<style>

    .page{
        display: block;
    }
    .page-off{
        display: none;
    }
    .off{
        display: none;
    }
    .on{
        display: block;
    }

</style>
<title>我的资料</title>
</head>
<body class="body-color">
    
  <?= $content ?>
    
</body>
</html>

<?php $this->endPage() ?>