<div class="row myinfo-heard" onclick="forwardTo('<?php echo URL_PATH?>/information/edituserinfo?openid=<?php echo \yii::$app->view->params['openid']?>')">
    <div class="col-xs-4">
        <img src="/img/user.png" class="user-head">
    </div>
    <div class="col-xs-6 my-user-info">
   <?php if($uname=='' && $utel==''){?>
<!--      <if condition="$uname eq '' and $utel eq '' ">-->
        <div class="row">
          <div class="col-xs-12 user-tel">编辑个人信息</div>
        </div>
<!--      <else />-->
   <?php }else{?>
        <div class="row">
          <div class="col-xs-12 user-name"><?php echo $uname?></div>
        </div>
        <div class="row">
          <div class="col-xs-12 user-lv">等级：<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
        </div>
        <div class="row">
          <div class="col-xs-12 user-tel">学分：<?php echo $score?>分</div>
        </div>        
<!--      </if>-->
   <?php } ?>
    </div> 
    <div class="col-xs-2 my-user-shezhi">
        <i class="fa fa-cog"></i>
    </div>     
</div>
<div class="row" onclick="loadAllStu()">  
    <div class="col-xs-10 col-xs-offset-1 jiazhang-user-row">
        <i class="fa fa-user"></i> 我是家长
    </div>     
</div>

<div class="row" onclick="loadAllClass()">  
    <div class="col-xs-10 col-xs-offset-1 laoshi-user-row">
        <i class="fa fa-user"></i> 我是老师
    </div>  
</div>

<div class="row" onclick="loadschool()">  
    <div class="col-xs-10 col-xs-offset-1 xiaozhang-user-row">
        <i class="fa fa-user"></i> 我是校长
    </div>     
</div>

<div class="row" onclick="uploadimages()">  
    <div class="col-xs-10 col-xs-offset-1 jiaoyuju-user-row">
        <i class="fa fa-user"></i> 图片上传
    </div>     
</div>



