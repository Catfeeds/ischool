<div class="row heard-list-waper">
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/myallclass?openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo $sid?>')">
        <i class="fa fa-reply"></i> 
      </div>    
    </div>
    <div class="col-xs-6 text-align-l">         
      <?php echo $the_class[0]['class']?>
    </div>
</div>
<input type="hidden" value="<?php echo \yii::$app->view->params['openid']?>" id="openid"/>
<?php if( $type!= 1){?>
<!--<if condition="$type neq 1"> 管理人员不显示一下菜单-->
  <div class="row edit-user-row" onclick="forwardTo('<?php echo URL_PATH?>/information/allstudent?cid=<?php echo $cid?>&openid=<?php echo \yii::$app->view->params['openid']?>&cname=<?php echo $the_class[0]['class']?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">
    <div class="col-xs-7 col-xs-offset-2 edit-user-top">
        <i class="fa fa-users"></i> 所有学生
    </div>
    <div class="col-xs-2 edit-user-top">
        <i class="fa fa-chevron-right"></i>
    </div>
  </div>
<?php if( $role == 1 ){?>
<!--  <if condition="$role eq 1">  班主任显示，其他教师不显示-->
    <div class="row edit-user-row" onclick="forwardTo('<?php echo URL_PATH?>/information/leavestu?cid=<?php echo $the_class[0]['cid']?>&openid=<?php echo \yii::$app->view->params['openid']?>&cname=<?php echo $the_class[0]['class']?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">
      <div class="col-xs-7 col-xs-offset-2 edit-user-top">
          <i class="fa fa-users"></i> 已请假学生
      </div>
      <div class="col-xs-2 edit-user-top">
          <i class="fa fa-chevron-right"></i>
      </div>
    </div>
    <div class="row edit-user-row" onclick="forwardTo('<?php echo URL_PATH?>/information/shenqing?cid=<?php echo $the_class[0]['cid']?>&openid=<?php echo \yii::$app->view->params['openid']?>&cname=<?php echo $the_class[0]['class']?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">
        <div class="col-xs-7 col-xs-offset-2 edit-user-top">
           <i class="fa fa-envelope-o"></i> 请假待审核学生
        </div>
        <div class="col-xs-2 edit-user-top">
            <i class="fa fa-chevron-right"></i>
        </div>
    </div>
    <div class="row edit-user-row" onclick="forwardTo('<?php echo URL_PATH?>/information/chengjiindex?cid=<?php echo $the_class[0]['cid']?>&openid=<?php echo \yii::$app->view->params['openid']?>&cname=<?php echo $the_class[0]['class']?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">
      <div class="col-xs-7 col-xs-offset-2 edit-user-top">
         <i class="fa fa-envelope-o"></i> 班级成绩管理
      </div>
      <div class="col-xs-2 edit-user-top">
          <i class="fa fa-chevron-right"></i>
      </div>
    </div>
<!--  </if>-->
<?php  } ?>
   <div class="row edit-user-row" onclick="removeOneClass(<?php echo $tcid?>)">
    <div class="col-xs-7 col-xs-offset-2 edit-user-top">
        <i class="fa fa-times-circle-o"></i> 退出班级
    </div>
  </div>
<!--</if>-->
<?php  } ?>
<?php if( $bool != 1 ){?>
<!--<if condition="$bool neq 1"> -->
   <div class="row edit-user-row" onclick="tchange(<?php echo $sid?>)">
    <div class="col-xs-7 col-xs-offset-2 edit-user-top">
        <i class="fa fa-retweet"></i> 切换学校
    </div>
  </div>
<?php  } ?>
<!--</if>-->
<div class="row help-row">
  <div class="col-xs-12">
    <span class="badge">
      帮助
    </span>
    <hr>
    <div class="help-row-text">
      点击【所有学生】，查看您班级中的所有学生。<br/>
      点击【请假学生】，查看所有已请假学生。<br/>
      点击【切换学校】，切换至当前班级所在的学校，若您在当前学校则不会显示【切换学校】按钮。<br/>
      点击【退出班级】，则退出当前班级，退出当前班级后不能进行与该班相关的操作。
    </div>
  </div>
</div>

