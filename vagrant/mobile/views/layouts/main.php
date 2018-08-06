<?php

/* @var $this \yii\web\View */
/* @var $content string */

use mobile\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;
//var_dump($sid=\yii::$app->view->params['openid']);die;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
    <?= Html::csrfMetaTags() ?>
    <title>正梵智慧校园</title>
    <?php $this->head() ?>
    <link media="all" rel="stylesheet" type="text/css" href="/css/home-css.css" />
	<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
	<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css" />
        
        <link media="all" rel="stylesheet" type="text/css" href="/css/tongzhi.css" />
        <link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" />
        <link media="all" rel="stylesheet" type="text/css" href="/css/ui-dialog.css" />
        <link media="all" rel="stylesheet" type="text/css" href="/css/style.css" />
        <link media="all" rel="stylesheet" type="text/css" href="/css/font-awesome.css" />
        <link media="all" rel="stylesheet" type="text/css" href="/css/simditor.css" />
        <link media="all" rel="stylesheet" type="text/css" href="/css/record.css" />
        
	<script type="text/javascript" src="/js/css3-mediaqueries.js"></script>
	<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
	<!--<script type="text/javascript" src="/js/Headroom.js"></script>-->
	<script type="text/javascript" src="/js/unslider.js"></script>
	<script type="text/javascript" src="/js/home-js.js"></script>
        
         <script type="text/javascript" src="/js/LocalResizeIMG.js"></script>
        <script type="text/javascript" src="/js/mobileBUGFix.mini.js"></script>
         <script type="text/javascript" src="/js/bootstrap-confirmation.js"></script>
          <script type="text/javascript" src="/js/dialog-min.js"></script>
          <script type="text/javascript" src="/js/ajaxload.js"></script>
          <script type="text/javascript" src="/js/myDialog.js"></script>
          <script type="text/javascript" src="/js/jweixin-1.0.0.js"></script>
          <script type="text/javascript" src="/js/record.js"></script>
          <script type="text/javascript" src="/js/simditor-all.js"></script>
          <script type="text/javascript" src="/js/page-demo.js"></script>
</head>
<body class="body-color">

<?= $content ?>




</body>
</html>
<?php $this->endPage() ?>
