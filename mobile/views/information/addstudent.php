<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/allstudent?openid=<?php echo $openid?>&cid=<?php echo $cid?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">
        <i class="fa fa-reply"></i>  
      </div>    
    </div>
   
    <div class="col-xs-5 text-align-l">         
      填写学生信息       
    </div>
    <div class="col-xs-4 text-align-l">         
      <span id="add_span" class="add-class" onclick="addstudent()">
        保存   
      </span>          
    </div>
       
</div>
<input type="hidden" id="cid" value="<?php echo $cid?>" />
<input type="hidden" id="tcid" value="<?php echo $tcid?>" />
<div class="row edit-user-row">
  <div class="col-xs-5 col-xs-offset-1 edit-user-top">
   
      <i class="fa fa-user"></i> 学生姓名
   
  </div>
   <div class="col-xs-6 text-align-l">

      <input type="text" autocomplete="off" class="form-control" id="name"/>
    
 
  </div>  
</div>

<div class="row edit-user-row">
  <div class="col-xs-5 col-xs-offset-1 edit-user-top">   
      <i class="fa fa-star-o"></i> 备注信息  
  </div>
   <div class="col-xs-6 text-align-l">

      <input type="text" autocomplete="off" class="form-control" id="address">
     
  
  </div>  
</div>

<div class="row edit-user-row">
  <div class="col-xs-5 col-xs-offset-1 edit-user-top">   
      <i class="fa fa-street-view"></i> 学号
  </div>
   <div class="col-xs-6 text-align-l">

      <input type="text" autocomplete="off" class="form-control" id="stuno">
     
  
  </div>  
</div>




