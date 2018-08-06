 <div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/myallinfo?openid=<?php echo \yii::$app->view->params['openid']?>')">        
        <i class="fa fa-reply"></i> 

      </div>
    </div>
   
    <div class="col-xs-5 text-align-l">         
      我的学生       
    </div>

    <div class="col-xs-4 text-align-l">         
      <span class="add-class" onclick="forwardTo('<?php echo URL_PATH?>/information/addonechild?openid=<?php echo \yii::$app->view->params['openid']?>')">
        <i class="fa fa-plus"></i>  
      </span>   
    </div>  
</div>
<?php if(empty($list_stu)){?>
     <div class="row edit-user-row">
          <div class="col-xs-10 col-xs-offset-2 edit-user-top">
            暂未绑定学生信息
          </div>          
      </div>
<?php }else{?>
 <?php foreach($list_stu as $k=>$v ){?>    
    <div class="row edit-user-row" onclick="forwardTo('<?php echo URL_PATH?>/information/onechild?stuid=<?php echo $v['stu_id']?>&stuname=<?php echo  $v['stu_name'] ?>&openid=<?php echo \yii::$app->view->params['openid']?>')">

      <div class="col-xs-4 col-xs-offset-1 edit-user-top">       
          <i class="fa fa-user"></i> <?php echo $v['stu_name'] ?>       
      </div>
      <div class="col-xs-3 myallchild-shenhe edit-user-top">  
            <?php if($v['ispass']=='y'){?>
                  已审核
            <?php }else if($v['ispass']=='n'){?>
                 未审核
            <?php }else{?>
                  待审核
            <?php }?>    
      </div> 
      <div class="col-xs-2 edit-user-top">        
          <i class="icon-chevron-right icon-margin"></i>       
      </div>     
    </div>
    
   
<!--  </foreach>-->
   <?php } ?>
<!--</if>-->
<?php } ?>
 <div class="row help-row">
        <div class="col-xs-12">
          <span class="badge">
            帮助
          </span>
          <hr>
          <div class="help-row-text">
          若您没有关注学生信息，点击右上角【＋】关注学生信息。<br/>
          若您已经关注学生，点击学生名字查看学生详细信息，姓名后面显示审核状态。
          </div>
        </div>
      </div>
