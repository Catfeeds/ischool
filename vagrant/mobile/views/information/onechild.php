<div class="row heard-list-waper">
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/myallchild?openid=<?php echo $openid?>')">
        <i class="fa fa-reply"></i>  
      </div>    
    </div>
    <div class="col-xs-6 edit-user-top">
        孩子信息
    </div>
</div>

<div class="row edit-user-row" onclick="forwardTo('<?php echo URL_PATH?>/information/childclass?openid=<?php echo $openid?>&name=<?php echo $stu_name?>&stuid=<?php echo $stu_id?>')">
    <div class="col-xs-7 col-xs-offset-2 edit-user-top">
       <i class="fa fa-newspaper-o"></i> 班级信息
    </div>
    <div class="col-xs-2 edit-user-top">
        <i class="fa fa-chevron-right"></i>
    </div>
</div>
<div class="row edit-user-row" onclick="backto('<?php echo URL_PATH?>/information/qinqinghao?id=<?php echo $stu_id?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>')">
    <div class="col-xs-7 col-xs-offset-2 edit-user-top">
      <i class="fa fa-phone"></i> 添加亲情号
    </div>
    <div class="col-xs-2 edit-user-top">
      <i class="fa fa-chevron-right"></i>
    </div>
</div>

<div class="row edit-user-row" onclick="backto('<?php echo URL_PATH?>/information/qingjia?id=<?php echo $stu_id?>&name=<?php echo $stu_name?>&openid=<?php echo $openid?>')">
  <div class="col-xs-7 col-xs-offset-2 edit-user-top">
      <i class="fa fa-envelope"></i> 申请请假
  </div>
   <div class="col-xs-2 edit-user-top">
      <i class="fa fa-chevron-right"></i>
  </div>
</div>
<div class="row edit-user-row" onclick="backto('<?php echo URL_PATH?>/information/querychildcj?cid=<?php echo $cid?>&id=<?php echo $stu_id?>&name=<?php echo $stu_name?>&openid=<?php echo $openid?>')">
  <div class="col-xs-7 col-xs-offset-2 edit-user-top">
      <i class="fa fa-envelope"></i> 成绩查询
  </div>
   <div class="col-xs-2 edit-user-top">
      <i class="fa fa-chevron-right"></i>
  </div>
</div>
<div class="row edit-user-row" onclick="removeOneChild(<?php echo $stu_id?>)">
  <div class="col-xs-7 col-xs-offset-2 edit-user-top">
      <i class="fa fa-heart"></i> 取消关注
  </div>
</div>

<!--<if condition="$bool neq 1">-->
<?php if($bool !=1 ){?>   
<div class="row edit-user-row" onclick="pchange(<?php echo $stu_id?>)">
  <div class="col-xs-7 col-xs-offset-2 edit-user-top" >
      <i class="fa fa-retweet"></i> 切换学校
  </div>
</div>
<?php }?> 
<!--</if>-->
<div class="row help-row">
  <div class="col-xs-12">
    <span class="badge">
      帮助
    </span>
    <hr>
    <div class="help-row-text">
     点击【班级信息】，查看孩子所在班级信息。<br/>点击【取消关注】，不再关注孩子所在班级。
    </div>
  </div>
</div>
