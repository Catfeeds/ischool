<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title = '正梵智慧校园';
?>
<!-- 顶部导航 -->
<div class="container-fluid">
  <div class="row page-shadow" id="header">
     <div class="col-xs-8">
      <div class="header-home text-omit">
          <a href="#" onfocus="this.blur()">
            <div class="glyphicon glyphicon-home header-icon"></div>
              <span><?php echo $ischool?></span>
          </a>
      </div>
     </div>
<!--<if condition="$bool eq 1 ">-->
 <?php if($bool==1){?>
 <div class="col-xs-3" onclick="release_input()">
    <div class="header-home">
      <a href="<?php echo Url::toRoute(['schoolnews/add','openid'=>\yii::$app->view->params['openid'],'sid'=>$sid])?>" onfocus="this.blur()">
        <span class="header-submit">发布</span>
      </a>
    </div>
   </div> 
<?php }?>

  </div>
</div>


<!-- 顶部导航结束 -->
<!-- 最新动态、活动、贴贴、促销 -->
<section class="section-font-size-home section-home-margin">
<div id="accordion" role="tablist" aria-multiselectable="true">
<input type="hidden" value="<?php echo URL_PATH?>" id="path">
    <input type="hidden" value="<?php echo \yii::$app->view->params['openid']?>" id="openid">
     <input type="hidden" value="<?php echo $sid?>" id="sid">
<!--<if condition="$list_news eq '' ">-->
     <?php if($list_news==''){?>
  <div class="container-fluid list-container">
     <div class="row list-location">
         <div class="col-xs-2">
            <span class="badge list-badge gonggao">动态</span>
         </div>
         <div class="col-xs-8 title-location text-center">暂无相关信息</div>
     </div> 
  </div>
<!--<else />  -->
 <?php }else{?>
<!--<foreach name="list_news" item="vo" key="key">-->
  <?php foreach($list_news as $k=>$v){?>
<!-- 最新动态 --> 
<div id="<?php echo $v['id'].'s'?>" class="parpanel">
  <div class="container-fluid list-container">
     <div class="row list-location">
     
         <div class="col-xs-2">
            <span class="badge list-badge gonggao">动态</span>
         </div>
         
         <a href="<?php echo Url::toRoute(['schoolnews/des','openid'=>\yii::$app->view->params['openid'],'sid'=>$sid,'school'=>$ischool,'gid'=>$v['id']])?>" onfocus="this.blur()">
           <div class="col-xs-6 title-location text-omit"><?php echo $v['title']?></div>
         </a>
         <a href="<?php echo Url::toRoute(['schoolnews/des','openid'=>\yii::$app->view->params['openid'],'sid'=>$sid,'school'=>$ischool,'gid'=>$v['id']])?>" onfocus="this.blur()">
            <div class="col-xs-4 title-more-time">
              <?php echo  date('Y-m-d',$v['ctime'])?>
            </div>
         </a>
     </div> 
  </div>

<!-- 最新动态结束 --> 

</div>
<!--</foreach>-->
 <?php  } ?>
<!--</if>-->
 <?php  } ?>

</div>
</section>
<!-- 最新动态、活动、贴贴、促销 结束 -->
<!-- 页脚菜单 -->
<div class="container-fluid">
    
    <div class="row" id="next-node">

       <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 next-node-center">
         <li class="dropup">
             <a href="<?php echo $start?>" class="dropdown-toggle" onfocus="this.blur()">
                 <div class="row">
                    <span class="glyphicon glyphicon-step-backward"></span>
                 </div>
                 <span>首页</span>
             </a>
         </li>
       </div>

       <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 next-node-center">
       
         <li class="dropup">
             <a href="<?php echo $up?>" class="dropdown-toggle" onfocus="this.blur()">
                 <div class="row">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                 </div>
                 <span>上一页</span>
             </a>
         </li>
       </div>
  
       <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 next-node-center">
       
         <li class="dropup">
             <a href="<?php echo $down?>" class="dropdown-toggle" onfocus="this.blur()">
                 <div class="row">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                 </div>
                 <span>下一页</span>
             </a>
         </li>
       </div>

       <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 next-node-center">
         <li class="dropup">
             <a href="<?php echo $end?>" class="dropdown-toggle" onfocus="this.blur()">
                 <div class="row">
                    <span class="glyphicon glyphicon-step-forward"></span>
                 </div>
                 <span>尾页</span>
             </a>
         </li>       
       </div> 

   </div>



</div>
 <?php echo $this->render('../layouts/footer')?>
<!-- 页脚菜单结束 -->



