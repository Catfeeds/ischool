<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/onechild?stuid=<?php echo $stuid?>&stuname=<?php echo $stuname?>&openid=<?php echo $openid?>')">        
        <i class="fa fa-reply"></i>
      </div>    
    </div>
   
    <div class="col-xs-6 text-align-l">         
     班级信息 
      <input type="hidden" id="stuid" value="<?php echo $the_stuinfo[0]['id']?>" />       
    </div>
       
</div>

<div class="row edit-user-row">
  <div class="col-xs-4 col-xs-offset-1 edit-user-top">
    
      <i class="fa fa-home"></i> 学校
   
  </div>
   <div class="col-xs-7 edit-user-top text-omit">
    
      <?php echo $school?>
   
  </div>  
</div>

<div class="row edit-user-row">
  <div class="col-xs-4 col-xs-offset-1 edit-user-top">

      <i class="fa fa-users"></i> 班级

  </div>
   <div class="col-xs-7 edit-user-top text-omit">

      <?php echo $class?>

  </div>  
</div>
<div class="row edit-user-row">
  <div class="col-xs-4 col-xs-offset-1 edit-user-top">

      <i class="fa fa-user"></i> 班主任

  </div>
   <div class="col-xs-7 edit-user-top text-omit">

      <?php echo $name?>

  </div>  
</div>

<div class="row edit-user-row">
  <div class="col-xs-4 col-xs-offset-1 edit-user-top">

      <i class="fa fa-phone"></i> 手机号

  </div>
   <div class="col-xs-7 edit-user-top text-omit">

     <?php echo $tel?>

  </div>  
</div>


 

