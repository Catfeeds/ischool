<div class="row heard-list-waper">
  
     <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
<div onclick="backto('<?php echo URL_PATH?>/information/studes?cid=<?php echo $cid?>&sid=<?php echo $sid?>&openid=<?php echo $openid?>&tcid=<?php echo $tcid?>&stuid=<?php echo $stuid?>')">        
        <i class="fa fa-reply"></i>  
      </div>    
    </div>
   
    <div class="col-xs-6 text-align-l">         
      联系人信息
      <input type="hidden" id="stuid" value="<?php echo $the_stuinfo[0]['id']?>" />       
    </div>

   <div class="col-xs-3 text-align-r">         
      <span class="add-class" onclick="forwardTo('<?php echo URL_PATH?>/information/addlxr?cid=<?php echo $cid?>&sid=<?php echo $sid?>&openid=<?php echo $openid?>&tcid=<?php echo $tcid?>&stuid=<?php echo $stuid?>')">
        <i class="fa fa-plus"></i> 
      </span>   
    </div> 
   
       
</div>

<input type="hidden" value="<?php echo $cid?>" id="cid"/>
<input type="hidden" value="<?php echo $sid?>" id="sid"/>
<input type="hidden" value="<?php echo $openid?>" id="openid"/>
<input type="hidden" value="<?php echo $tcid?>" id="tcid"/>
<input type="hidden" value="<?php echo $stuid?>" id="stui"/>
<?php foreach($res as $v){?>
<!--<foreach name="res" item="vo" key="key">-->
<div class="row edit-user-row" style="margin-bottom:0;background-color:pink;">
  <div class="col-xs-3 col-xs-offset-1 edit-user-top">
    
       <i class="fa fa-user"></i> 姓名
   
  </div>
   <div class="col-xs-5 edit-user-top">
    
<?php echo $v['name']?>
   
  </div>  
<!--<if condition="$vo.type eq 0"> -->
    <?php if($v['type'] == 0){?>

   <div class="col-xs-3" onclick="dellxr(<?php echo $v['id']?>)">
              <i class="fa fa-trash-o"></i> 删除
          </div>
       <?php } ?>
</div>

<div class="row edit-user-row" style="margin-bottom:0;">
  <div class="col-xs-5 col-xs-offset-1 edit-user-top">

      <i class="glyphicon glyphicon-earphone"></i>     电话

  </div>
   <div class="col-xs-5 edit-user-top">

<?php echo $v['tel']?>

  </div>  
</div>

<div class="row edit-user-row" style="margin-bottom:0;">
  <div class="col-xs-5 col-xs-offset-1 edit-user-top">

      <i class="glyphicon glyphicon-envelope"></i>     邮箱

  </div>
   <div class="col-xs-5 edit-user-top">
     <?php if(empty($v['email'])){?>   
<!--   <empty name="vo.email">-->
       暂无
     <?php }else{ ?>   
<!--   <else/>-->
      <?php echo $v['email']?>
   <!--</empty>-->
    <?php } ?>  



  </div>  
</div>

<div class="row edit-user-row" style="margin-bottom:10px;">
  <div class="col-xs-5 col-xs-offset-1 edit-user-top">

      <i class="glyphicon glyphicon-user"></i>     绑定方式

    </div>
   <div class="col-xs-5 edit-user-top">
<!--   <if condition="$vo.type eq 0"> -->
 <?php if($v['type'] == 0){?>     
       
  非微信绑定
<!--  <else/>-->
  <?php }else{ ?>
  微信绑定
  <?php } ?>

    </div>  
</div>
<?php } ?>
<div class="row edit-user-row" style="height:30px;">
   
</div>








 

