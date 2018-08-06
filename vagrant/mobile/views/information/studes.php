<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/allstudent?cid=<?php echo $cid?>&sid=<?php echo $sid?>&openid=<?php echo $openid?>&tcid=<?php echo $tcid?>')">        
        <i class="fa fa-reply"></i>  
      </div>    
    </div>
   
    <div class="col-xs-6 text-align-l">         
      学生信息 
      <input type="hidden" id="stuid" value="<?php echo $the_stuinfo[0]['id']?>" />       
    </div>
       
</div>

<div class="row edit-user-row">
  <div class="col-xs-5 col-xs-offset-2 edit-user-top">
    
      <i class="fa fa-user"></i> 姓名
   
  </div>
   <div class="col-xs-5 edit-user-top">
    
  <?php echo $name?>
   
  </div>  
</div>

<div class="row edit-user-row">
  <div class="col-xs-5 col-xs-offset-2 edit-user-top">

      <i class="fa fa-star-o"></i> 备注信息

  </div>
   <div class="col-xs-5 edit-user-top">

     <?php echo $address?>

  </div>
</div>

<div class="row edit-user-row">
  <div class="col-xs-5 col-xs-offset-2 edit-user-top">

      <i class="fa fa-street-view"></i> 学号

  </div>
   <div class="col-xs-5 edit-user-top">

      <?php echo $stuno?>

  </div>  
</div>

 <div class="row edit-user-row" onclick="forwardTo('<?php echo URL_PATH?>/information/lxr?stuid=<?php echo $stuid?>&openid=<?php echo $openid?>&cid=<?php echo $cid?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">

    <div class="col-xs-7 col-xs-offset-2 edit-user-top">

        <i class="fa fa-user"></i> 联系人

    </div>

    <div class="col-xs-2 edit-user-top">

        <i class="fa fa-chevron-right"></i>

    </div>
    
    </div>






 

