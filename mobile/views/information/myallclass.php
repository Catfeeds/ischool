<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <!--<div onclick="backto('<?php echo URL_PATH?>/information/myAllInfo?openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo $sid?>')">-->
        <div onclick="newtz()">
            <script type='text/javascript'>
                function newtz(){
                    window.location.href = '/information/index?openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo $sid?>';
                    res = window.location.href;
                    return res;
                }
            </script>
        <i class="fa fa-reply"></i>
      </div>    
    </div>
   
    <div class="col-xs-5 text-align-l">         
      我的班级        
    </div>

    <div class="col-xs-4 text-align-l">         
      <span class="add-class" onclick="forwardTo('<?php echo URL_PATH?>/information/addoneclass?openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo $sid?>')">
        <i class="fa fa-plus"></i>
      </span>   
    </div> 
</div>
<?php if($list_class == 1){?>
<!-- <switch name="list_class" >
    <case value="1">-->
      <div  class="row edit-user-row" onclick="forwardTo('<?php echo URL_PATH?>/information/addoneclass?openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo $sid?>')">

          <div class="col-xs-10 col-xs-offset-2 text-align-l">
              <i class="fa fa-exclamation-circle"></i>若您是老师，请点击此处绑定教师信息。
          </div>
          
      </div>
<!--    </case>-->
<!--    <default />-->
<?php }else{?>

<!--    <foreach name="list_class" item="vo" key="key">-->
<?php foreach($list_class as $v ){?>
<a href="<?php echo URL_PATH?>/information/oneclass?cid=<?php echo $v['cid']?>&openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo $v['sid']?>&tcid=<?php echo $v['id']?>&type=<?php echo $v['sta']?>" onclick="loadHtml(this,event)">
        
        <div class="row edit-user-row">

          <div class="col-xs-7 col-xs-offset-2 edit-user-top">

              <i class="fa fa-users"></i> <?php echo $v['school']?><?php echo $v['class']?><?php echo $v['role']?>

          </div>
          <div class="col-xs-3 myallchild-shenhe edit-user-top">  
              <?php if($v['ispass']=='y'){?>
                        已审核
              <?php }else if($v['ispass']=='n'){?>
                       未通过 
              <?php }else{?>
                       待审核
              <?php }?>        
<!--            <switch name="vo.ispass" >
              <case value="y">已审核</case>
              <case value="n">未通过</case>
              <default />待审核
            </switch>-->
          </div> 
        
        </div>
      </a>
  <!--      </foreach>-->
  <?php  } ?>
<!--</switch>-->
<?php  } ?>

<div class="row help-row">
        <div class="col-xs-12">
          <span class="badge">

            帮助
          </span>
          <hr>
          <div class="help-row-text">
       若您已绑定班级，点击各个班级名字可管理班内学生。点击【＋】按钮可以增加班级，如果您是多个班的老师，可以增加多个班级。</br>
       若您要绑定学校，点击【＋】按钮，只填写学校信息即可。
       若您没有绑定班级，点击【＋】按钮可以增加班级。</br>
          </div>
        </div>
</div>


