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
  <link media="all" rel="stylesheet" type="text/css" href="/css/ui-dialog.css" />
  <link media="all" rel="stylesheet" type="text/css" href="/css/style.css" />
  <link media="all" rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
  <link media="all" rel="stylesheet" type="text/css" href="/css/font-awesome.css" />
  <link media="all" rel="stylesheet" type="text/css" href="/css/simditor.css" />
  <link media="all" rel="stylesheet" type="text/css" href="/css/record.css" />

  <script type="text/javascript" src="/js/css3-mediaqueries.js"></script>
  <script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap-confirmation.js"></script>
  <script type="text/javascript" src="/js/LocalResizeIMG.js"></script>
<script type="text/javascript" src="/js/mobileBUGFix.mini.js"></script>
  <script type="text/javascript" src="/js/dialog-min.js"></script>
  <script type="text/javascript" src="/js/ajaxload.js"></script>
  <script type="text/javascript" src="/js/myDialog.js"></script>
  <script type="text/javascript" src="/js/jweixin-1.0.0.js"></script>
  <script type="text/javascript" src="/js/record.js"></script>

  <style type="text/css">
    .jxt_op_btn{

      width: 80px;
      color: #fff;
      padding: 8px 0 8px 0;
      margin-top: 5px;
      border-radius: 5px;
      text-align: center;
    }
    .jxt_op_btnol{

        width: 80px;
        color: #fff;
        padding: 8px 0 8px 0;
        margin-top: 5px;
        border-radius: 5px;
        text-align: center;
    }
    .jxt_current_btn{
      margin-top: 10px;
    }
    #send_btn{
      background-color: #f66;
    }
    #back-btn{
      background-color: rgba(47, 239, 239, 0.93);
        padding: 8px 10px;
        width: 70px;
    }
    #receive_btn{
      background-color: #8ac007;
    }
    #sent_btn{
      background-color: #3498db;
    }
    #sendMsg{
      width: 100%;
      margin-bottom: 5px;
      background-color: #f66;
    }
  </style>

  <title><?php echo $jxt?></title>
</head>
<body class="body-color">
<?= $content ?>
</body>
</html>
<?php $this->endPage() ?>