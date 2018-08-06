<?php
use mobile\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;
//var_dump($sid=\yii::$app->view->params['openid']);die;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
<link media="all" rel="stylesheet" type="text/css" href="/css/tongzhi.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/font-awesome.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/ui-dialog.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/style.css" />

<script type="text/javascript" src="/js/css3-mediaqueries.js"></script>
<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/bootstrap-confirmation.js"></script>
<script type="text/javascript" src="/js/dialog-min.js"></script>
<script type="text/javascript" src="/js/ajaxload.js"></script>
<style type="text/css">
  #tc-main{
    display: none;
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    z-index: 100;
    background-color: rgba(0,0,0,0.8);
  }
  #tc-wapper{
    position: absolute;
    top: 20%;
    width: 90%;
    margin-left: 5%;
    z-index: 101;
    border-radius: 15px;
    background-color: #ecf0f1;
  }
  .tc-text{
    padding: 25px;
  }
  .tc-text-content{
    margin-top: 20px;
    line-height: 25px;
    text-indent: 2em;
  }
  .tc-btn{
    margin: 10px 0 50px 0;
    color: #FFFFFF;
    font-family: "微软雅黑";
  }
  .tc-btn a{
    text-decoration:none;
    color: #FFFFFF;
  }
  .tc-close{
    float: left;
    width: 40%;
    margin-left: 5%;
    text-align: center;
    line-height: 50px;
    background-color: #E74C3C;
    border-radius: 7px;
  }
  .go-pay{
    float: right;
    width: 40%;
    margin-right: 5%;
    text-align: center;
    line-height: 50px;
    background-color: #2ECC71;
    border-radius: 7px;
  }
</style>
<title><?php echo ICARD_NAME?></title>
</head>
<body class="body-color">
    <?= $content ?>
</body>
</html>
<?php $this->endPage() ?>