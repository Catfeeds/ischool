<?php

/* @var $this \yii\web\View */
/* @var $content string */

use mobile\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;
use mobile\models\WpIschoolUser;
//var_dump($sid=\yii::$app->view->params['openid']);die;
AppAsset::register($this);
$group=WpIschoolUser::find()->select('label')->where(['openid'=>\yii::$app->view->params['openid']])->asArray()->one();
if($group){
    $arr_group=explode(',',$group['label']); 

}
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
<link media="all" rel="stylesheet" type="text/css" href="/css/tongzhi.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/ui-dialog.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/style.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/animate.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/index-columns.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/dimmer.min.css" />
<link media="all" rel="stylesheet" type="text/css" href="/css/loader.min.css" />
 <link media="all" rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
<link media="all" rel="stylesheet" type="text/css" href="/simditor/styles/font-awesome.css" />
    <!--<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>-->
    <script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="/js/css3-mediaqueries.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/bootstrap-confirmation.js"></script>
<script type="text/javascript" src="/js/dialog-min.js"></script>
<script type="text/javascript" src="/js/ajaxload.js"></script>
<script type="text/javascript" src="/js/Headroom.js"></script>
<script type="text/javascript" src="/js/jQuery.headroom.min.js"></script>
<script type="text/javascript" src="/js/LocalResizeIMG.js"></script>
<script type="text/javascript" src="/js/patch/mobileBUGFix.mini.js"></script>

<script type="text/javascript" src="/js/dimmer.min.js"></script>
<script type="text/javascript" src="/js/manage.js"></script>
</head>
<body class="body-color">

<?= $content ?>
<div style="margin-top:20%;width:80%;margin-left:10%;" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                        请选择
                </h4>
            </div>
            <div class="modal-body">
               <?php if($arr_group[0]!=""){?>
                    
                    <ul class="form-group text-center" style="max-height:100px;overflow:auto;"> 
                       <?php for($i=0;$i<count($arr_group);$i++){?>   
                        <a style="width:100%;display:block;margin-top:5px;" class="btn bt btn-primary" href="/group/index?qunzu=<?=$arr_group[$i]?>"><span style="padding-right:5px;" class="glyphicon glyphicon-user"></span><?= $arr_group[$i]?></a>
                        <?php }?>
                    </ul>
                    
                <?php }else{ ?>
                 <ul class="form-group text-center" style="max-height:100px;overflow:auto;"> 
                    您暂时没有群组
                </ul>
               <?php } ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<div class="container-fluid" id="footer-display">
 <div class="footer-nav mynav off">
  <div class="row">
   <div class="col-xs-12 footer-nav-background">
      
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
     <?php if(\yii::$app->view->params['sid'] == "56740"){ ?>
     <a href="<?php echo Url::toRoute(['zfend/skfw']); ?>" onfocus="this.blur()">
        <div class="footer-div">
           水卡服务
        </div>
     </a>
     <?php }?>
     <a href="<?php echo Url::toRoute(['zfend/ckfw']); ?>" onfocus="this.blur()">
        <div class="footer-div">
           餐卡服务
        </div>
     </a>
     <a href="<?php echo Url::toRoute(['zfend/qqdh']); ?>" onfocus="this.blur()">
        <div class="footer-div">
            亲情电话
        </div>
     </a>
    
<!--      <a href="" onfocus="this.blur()">
        <div class="footer-div">
            我要支付
        </div>
      </a>-->
   </div> 
  </div>
 </div> 
 
 <div class="footer-nav3 mynav off">
  <div class="row">
   <div class="col-xs-12 footer-nav-background">
       <a href="<?php echo Url::toRoute(['information/index','openid'=>\yii::$app->view->params['openid'],'sid'=>\yii::$app->request->get("sid"),'status'=>'1']); ?>" onfocus="this.blur()">
        <div class="footer-div">
            我的资料
        </div>
       </a>    
       <a href=" <?php echo Url::toRoute(['schoolnews/index','openid'=>\yii::$app->view->params['openid'],'sid'=>\yii::$app->request->get("sid")])?>" onfocus="this.blur()">
        <div class="footer-div">
            最新动态
        </div>
       </a>
        <a href="<?php echo Url::toRoute(['exchange/index','openid'=>\yii::$app->view->params['openid'],'sid'=>\yii::$app->request->get("sid")]); ?>" onfocus="this.blur()">
        <div class="footer-div">
            内部交流
        </div>
       </a>
       <a href="###" onfocus="this.blur()" data-target="#myModal" data-toggle="modal">
        <div class="footer-div">
            班主任组
        </div>
       </a>
    
       <a href="<?php echo Url::toRoute(['gonggao/index','openid'=>\yii::$app->view->params['openid'],'sid'=>\yii::$app->request->get("sid")])?>
         " onfocus="this.blur()">
        <div class="footer-div">
            学校公告
        </div>
       </a> 
         <?php if(\yii::$app->view->params['sid']=='56744'){ ?>
       <a href="<?php echo Url::toRoute(['information/video'])?>
         " onfocus="this.blur()">
        <div class="footer-div">
            监控视频
        </div>
       </a> 
       <?php }?>         
       
       <a href="<?php echo Url::toRoute(['information/suggest'])?>" onfocus="this.blur()">
        <div class="footer-div">
            投诉建议
        </div>
       </a>       
       
       <!-- <a href="<?php if (\yii::$app->view->params['sid'] == 56650) {
         echo 'http://mp.weixin.qq.com/s/fWcxBf10hZGyOq4WTQANyg';
       }else {echo '/information/help';}?>" onfocus="this.blur()">
        <div class="footer-div">
            使用帮助
        </div>
       </a> -->
       <a href="/information/help" onfocus="this.blur()">
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
             <a href="<?php echo Url::toRoute(['zfend/pay']);?>" onfocus="this.blur()">
                 <div class="row">
                    <span class="glyphicon glyphicon-send"></span>
                 </div>
                 <span>我要支付</span>
             </a>
         </li>
       </div>
<!--       <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 footer-center">
         <li>
             <div class="footer-menu" id="footer-nav">
                 <div class="row">
                    <span class="glyphicon glyphicon-send"></span>
                 </div>
                 <span>掌上教育</span>
             </div>
         </li>
       </div>-->
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
                 <span>个人中心</span>
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
