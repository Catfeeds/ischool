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
<script type="text/javascript" src="/js/css3-mediaqueries.js"></script>
<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/Headroom.js"></script>
<script type="text/javascript" src="/js/unslider.js"></script>
<script type="text/javascript" src="/js/home-js.js"></script>
</head>
<body class="body-color">

<?= $content ?>


<div class="container-fluid" id="footer-display">
 <div class="footer-nav mynav off">
  <div class="row">
   <div class="col-xs-12 footer-nav-background">
      <a href="
         <?php echo Url::toRoute(['gonggao/index','openid'=>\yii::$app->view->params['openid'],'sid'=>\yii::$app->request->get("sid")])?>
         " onfocus="this.blur()">
        <div class="footer-div">
            校内公告
        </div>
      </a>
      <a href=" <?php echo Url::toRoute(['schoolnews/index','openid'=>\yii::$app->view->params['openid'],'sid'=>\yii::$app->request->get("sid")])?>" onfocus="this.blur()">
        <div class="footer-div">
            班级动态
        </div>
      </a>
       <a href="<?php echo Url::toRoute(['exchange/index','openid'=>\yii::$app->view->params['openid'],'sid'=>\yii::$app->request->get("sid")]); ?>" onfocus="this.blur()">
        <div class="footer-div">
            内部交流
        </div>
      </a>
   </div> 
  </div>
 </div>
 <div class="footer-nav2 mynav off">
  <div class="row">
   <div class="col-xs-12 footer-nav-background">
      <a href="<?php echo Url::toRoute(['tongzhi/index','openid'=>\yii::$app->view->params['openid'],'sid'=>\yii::$app->request->get("sid")]); ?>" onfocus="this.blur()">
        <div class="footer-div">
            家校沟通
        </div>
      </a>
      <a href="<?php echo Url::toRoute(['yicard/index','openid'=>\yii::$app->view->params['openid'],'sid'=>\yii::$app->request->get("sid")]); ?>" onfocus="this.blur()">
        <div class="footer-div">
            平安通知
        </div>
      </a>
        <a href="<?php echo Url::toRoute(['zfend/qqdh']); ?>" onfocus="this.blur()">
        <div class="footer-div">
        亲情电话

        </div>
      </a>
        <a href="<?php echo Url::toRoute(['zfend/ckcz']); ?>" onfocus="this.blur()">
        <div class="footer-div">
           餐卡充值
        </div>
      </a>
      <a href="<?php echo Url::toRoute(['zfend/pay']); ?>" onfocus="this.blur()">
        <div class="footer-div">
            我要支付
        </div>
      </a>
   </div> 
  </div>
 </div> 
 
 <div class="footer-nav3 mynav off">
  <div class="row">
   <div class="col-xs-12 footer-nav-background">
      <a href="<?php echo Url::toRoute(['information/index','openid'=>\yii::$app->view->params['openid'],'sid'=>\yii::$app->request->get("sid")]); ?>" onfocus="this.blur()">
        <div class="footer-div">
            我的资料
        </div>
      </a>
      <a href="http://x.eqxiu.com/s/7TXg8O7V" onfocus="this.blur()">
        <div class="footer-div">
            使用帮助
        </div>
      </a>
   </div> 
  </div>
 </div>
</div>
<!-- 页脚菜单 -->
<div class="container-fluid">
  <div class="row" id="footer">
       <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 footer-center">
         <li>
             <a href="<?php echo Url::toRoute(['homepage/index','openid'=>\yii::$app->view->params['openid'],'sid'=>\yii::$app->request->get("sid")]); ?>" onfocus="this.blur()">
                 <div class="row">
                    <span class="glyphicon glyphicon-home"></span>
                 </div>
                 <span>首页</span>
             </a>
         </li>
       </div>
       <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 footer-center">
         <li>
             <div class="footer-menu" id="footer-nav">
                 <div class="row">
                    <span class="glyphicon glyphicon-send"></span>
                 </div>
                 <span>掌上教育</span>
             </div>
         </li>
       </div>
       <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 footer-center">
         <li>
             <div class="footer-menu" id="footer-nav2">
                 <div class="row">
                    <span class="glyphicon glyphicon-map-marker"></span>
                 </div>
                 <span>家校互动</span>
             </div>
         </li>
       </div>
       <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 footer-center">
         <li>
             <div class="footer-menu" id="footer-nav3">
                 <div class="row">
                    <span class="glyphicon glyphicon-heart-empty"></span>
                 </div>
                 <span>我的服务</span>
             </div>
         </li>       
       </div>
  </div>
</div>



</body>
<script type="text/javascript">
  
 // 底部菜单导航 遮盖  掌上物业
  $(".footer-menu").click(function(){ 
     var thisid = $(this).attr("id");
   $(".mynav").each(function() {
     if($(this).hasClass(thisid)){
       if($(this).hasClass("on")){
            $(this).slideUp(300);
         $(this).removeClass("on").addClass("off");
         }else{
         $("."+thisid).slideDown(300);
         $(this).removeClass("off").addClass("on");
           
      }
       
     }else{
            $(this).slideUp(300);
      $(this).removeClass("on").addClass("off");
     }
     });
   
  });

</script>
</html>
<?php $this->endPage() ?>
