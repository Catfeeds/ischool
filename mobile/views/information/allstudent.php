<div class="row heard-list-waper">
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/oneclass?cid=<?php echo $cid?>&openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">        
         <i class="fa fa-reply"></i>  
      </div>
    </div>
   
    <div class="col-xs-5 text-align-l text-omit">
      <?php echo $cname?>
    </div>
      <?php if($list_stu==1){?>
<!--      <switch name="list_stu" >-->
<!--          <case value="1"></case>-->
      <?php }else{?>   
<!--          <default /> -->
    <div class="col-xs-4 text-align-l">
      <span  class="add-class" onclick="forwardTo('<?php echo URL_PATH?>/information/addstudent?openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo $sid?>&cid=<?php echo $cid?>&tcid=<?php echo $tcid?>')">
        <i class="fa fa-plus"></i>   
      </span>   
    </div> 
       <?php }?>       
<!--        </switch>-->


       
</div>
   <?php if($list_stu==1){?>
<!-- <switch name="list_stu" >-->
<!--          <case value="1">-->
             <div class="row edit-user-row">

          <div class="col-xs-10 col-xs-offset-2 text-align-l edit-user-top">

              <i class="fa fa-exclamation-circle"></i> 审核尚未通过，请联系管理员

          </div>
          
      </div>
<!--          </case>-->
   <?php } else if($list_stu==2){?>
<!--          <case value="2">-->
             <div class="row edit-user-row">

          <div class="col-xs-10 col-xs-offset-2 text-align-l edit-user-top">

              <i class="fa fa-exclamation-circle"></i> 该班没有学生


          </div>
          
      </div>

<!--        </case>-->
 <?php } else{?>
<!--          <default />-->
          <input type="hidden" id="cid" value="<?php echo $cid?>">     
          <span style="margin-bottom:5px;">当前进宿舍人数：<?= $renshu;?> (红色显示为进宿舍学生)</span>
          <input type="hidden" id="tcid" value="<?php echo $tcid?>">
     <?php foreach($list_stu as $v){?>      
<!--          <foreach name="list_stu" item="vo" key="key">-->
       
        <div class="row edit-user-row">

                <div class="col-xs-2  edit-user-top">  
                   <?php echo $v['rownum']?>
                </div> 

                <div class="col-xs-4  edit-user-top text-omit" onclick="forwardTo('<?php echo URL_PATH?>/information/studes?openid=<?php echo \yii::$app->view->params['openid']?>&stuid=<?php echo $v['id']?>&sid=<?php echo $sid?>&cid=<?php echo $cid?>&tcid=<?php echo $tcid?>')">
                    <i class="fa fa-user"></i> 
                    <?php if(isset($v['status'])){?>
                      <span style="color:red;"> <?php echo $v['name']?></span>
                    <?php }else{?>
                    <?php echo $v['name']?>
                    <?php }?>
                </div> 

           <?php if($role==0){?>
<!--          <if condition="$role eq 0">-->
              <?php if($v['leave']==1){?>
<!--              <if condition="$vo.leave eq 1">       -->
                <div class="col-xs-3  edit-user-top">
                    <i class="fa fa-star"></i> 已请假
                </div> 
<!--              <else/>-->
              <?php }else{?>    
                <div class="col-xs-3  edit-user-top" onclick="leave(<?php echo $v['id']?>)">
                    <i class="fa fa-envelope-square"></i> 请假
                </div> 
              <?php }?> 
<!--          <else/>-->
          <?php }else{?>     
               <div class="col-xs-6  edit-user-top"></div>
          <?php }?>
                 
        </div>   
<!--  </foreach>-->
<?php }?>

        <div style="height:50px;"></div>
<!--        </switch>-->
<?php }?>
