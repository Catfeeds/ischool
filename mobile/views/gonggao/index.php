<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title = '正梵智慧校园';

?>
<!-- 顶部导航 -->
<input type="hidden" value="<?php echo $path?>" id="path">
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
      <a href="<?php echo Url::toRoute(['gonggao/add','openid'=>\yii::$app->view->params['openid'],'sid'=>$sid])?>" onfocus="this.blur()">
        <span class="header-submit">发布</span>
      </a>
    </div>
   </div>
<?php }?>

  </div>
</div>
<!-- 顶部导航结束 -->
<!-- 小区公告、活动、贴贴、促销 -->
<section class="section-font-size-home section-home-margin">
<div id="accordion" role="tablist" aria-multiselectable="true">
    <input type="hidden" value="<?php echo $openid?>" id="openid">
     <input type="hidden" value="<?php echo $sid?>" id="sid">
<!--<if condition="$list_gonggao eq '' ">-->
 <?php if(empty($list_gonggao)){?>
  <div class="container-fluid list-container">
     <div class="row list-location">
         <div class="col-xs-2">
            <span class="badge list-badge gonggao">公告</span>
         </div>
         <div class="col-xs-8 title-location text-center">暂无相关信息</div>
     </div> 
  </div>
<!--<else />  -->
 <?php }else{?>
<!--<foreach name="list_gonggao" item="vo" key="key">-->
<?php foreach($list_gonggao as $k=>$v){?>
<!-- 小区公告 --> 
<div id="<?php echo $v['id'].'s'?>" class="parpanel">
  <div class="container-fluid list-container">
     <div class="row list-location">
     
         <div class="col-xs-2">
            <span class="badge list-badge gonggao">公告</span>
         </div>
         
         <a href="<?php echo Url::toRoute(['gonggao/des','openid'=>\yii::$app->view->params['openid'],'sid'=>$sid,'school'=>$ischool,'gid'=>$v['id']])?>
             " onfocus="this.blur()">
           <div class="col-xs-6 title-location text-omit"><?php echo $v['title']?></div>
         </a>
         <a href="<?php echo Url::toRoute(['gonggao/des','openid'=>\yii::$app->view->params['openid'],'sid'=>$sid,'school'=>$ischool,'gid'=>$v['id']])?>" onfocus="this.blur()">
            <div class="col-xs-4 title-more-time">
<!--               {$vo.ctime|date='Y-m-d',###}-->
               <?php echo  date('Y-m-d',$v['ctime'])?>
               
            </div>
         </a>
     </div> 
  </div>
<!-- 小区公告结束 --> 

</div>
 <?php  } ?>
<!--</foreach>-->
<!--</if>-->
 <?php  } ?>

</div>
</section>
<!-- 小区公告、活动、贴贴、促销 结束 -->
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
