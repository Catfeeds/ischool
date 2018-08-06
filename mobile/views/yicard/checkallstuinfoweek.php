<?php
use yii\helpers\Url;

?>
<style type="text/css">
    .col-xs-4{ width: 55%}
/*    .col-xs-5{ width: 20%}*/
    .col-xs-2{ width: 20%}
</style>
    <div class="container-fluid register-user-margin">
       <div class="row register-user-container">

           <div class="col-xs-12 register-user-title">
               <span class="badge"><?php echo $cname?>出勤信息</span>
           </div>
           <div class="col-xs-12 register-user-center-margin">

  <div class="row register-user-title week-day" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list1" onclick="witchInfo(this)">
                 <div class="col-xs-12 text-center">周一考勤</div>
               </div>

               <div id="with-list1" style="display:none;margin-top:15px;">
 <?php if(!$one ){?>    
                <p>今天无人刷卡</p>

 <?php }else{?> 
    <?php foreach($one as $k1=>$v1 ){?> 
      <?php if($v1['enddatepa']>time()): ?>   
        <div class="row inout-with-list">
            <div class="col-xs-4"> <?php echo date('Y年m月d日H时i分',$v1['ctime'])?> </div>
            <div class="col-xs-5"><?php echo $v1['info']?></div>
            <div class="col-xs-3"><?php echo $v1['name']?></div>
        </div>
      <?php endif;?>
    <?php }?>  
<?php }?>  
               </div>
           </div>
           <div class="col-xs-12 register-user-center-margin">

               <div class="row register-user-title week-day" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list2" onclick="witchInfo(this)">
                 <div class="col-xs-12 text-center">周二考勤</div>
               </div>

               <div id="with-list2" style="display:none;margin-top:15px;">
 <?php if(!$two ){?> 
                            <p>今天无人刷卡</p>
      <?php }else{?>    
  <?php foreach($two as $k1=>$v1 ){?> 
     <?php if($v1['enddatepa']>time()): ?>  
    <div class="row inout-with-list">
        <div class="col-xs-4"><?php echo date('Y年m月d日H时i分',$v1['ctime'])?></div>
        <div class="col-xs-5"><?php echo $v1['info']?></div>
        <div class="col-xs-3"><?php echo $v1['name']?></div>
    </div>
    <?php endif;?>
   <?php }?>  
   <?php }?>  
               </div>
           </div>
           <div class="col-xs-12 register-user-center-margin">

               <div class="row register-user-title week-day" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list3" onclick="witchInfo(this)">
                 <div class="col-xs-12 text-center">周三考勤</div>
               </div>

               <div id="with-list3" style="display:none;margin-top:15px;">
  <?php if(!$three ){?>                   
                            <p>今天无人刷卡</p>
   <?php }else{?>   
  <?php foreach($three as $k1=>$v1 ){?> 
    <?php if($v1['enddatepa']>time()): ?> 
    <div class="row inout-with-list">
        <div class="col-xs-4"><?php echo date('Y年m月d日H时i分',$v1['ctime'])?></div>
        <div class="col-xs-5"><?php echo $v1['info']?></div>
        <div class="col-xs-3"><?php echo $v1['name']?></div>
    </div>
    <?php endif;?>
   <?php }?>  
   <?php }?>  
               </div>
           </div>
           <div class="col-xs-12 register-user-center-margin">
               <div class="row register-user-title week-day" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list4" onclick="witchInfo(this)">
                 <div class="col-xs-12 text-center">周四考勤</div>
               </div>
               <div id="with-list4" style="display:none;margin-top:15px;">
 <?php if(!$four ){?>                   
                            <p>今天无人刷卡</p>
   <?php }else{?>   
     <?php foreach($four as $k1=>$v1 ){?> 
     <?php if($v1['enddatepa']>time()): ?> 
    <div class="row inout-with-list">
        <div class="col-xs-4"><?php echo date('Y年m月d日H时i分',$v1['ctime'])?></div>
        <div class="col-xs-5"><?php echo $v1['info']?></div>
        <div class="col-xs-3"><?php echo $v1['name']?></div>
    </div>
    <?php endif;?>
    <?php }?>  
  <?php }?>  
               </div>
           </div>           
           <div class="col-xs-12 register-user-center-margin">

               <div class="row register-user-title week-day" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list5" onclick="witchInfo(this)">
                 <div class="col-xs-12 text-center">周五考勤</div>
               </div>

               <div id="with-list5" style="display:none;margin-top:15px;">
  <?php if(!$five ){?>  
                            <p>今天无人刷卡</p>
   <?php }else{?>  

 <?php foreach($five as $k1=>$v1 ){?> 
       <?php if($v1['enddatepa']>time()): ?>
          <div class="row inout-with-list">
              <div class="col-xs-4"><?php echo date('Y年m月d日H时i分',$v1['ctime'])?></div>
              <div class="col-xs-5"><?php echo $v1['info']?></div>
              <div class="col-xs-3"><?php echo $v1['name']?></div>
          </div>
       <?php endif;?>
  <?php }?>  
  <?php }?>  
               </div>
           </div>   
           <div class="col-xs-12 register-user-center-margin">

               <div class="row register-user-title week-day" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list6" onclick="witchInfo(this)">
                 <div class="col-xs-12 text-center">周六考勤</div>
               </div>

               <div id="with-list6" style="display:none;margin-top:15px;">

  <?php if(!$six ){?>                   
                            <p>今天无人刷卡</p>
   <?php }else{?>
 <?php foreach($six as $k1=>$v1 ){?> 
    <?php if($v1['enddatepa']>time()): ?>
    <div class="row inout-with-list">
        <div class="col-xs-4"><?php echo date('Y年m月d日H时i分',$v1['ctime'])?></div>
        <div class="col-xs-5"><?php echo $v1['info']?></div>
        <div class="col-xs-3"><?php echo $v1['name']?></div>
    </div>
    <?php endif;?>
 <?php }?> 
 <?php }?> 
               </div>

           </div>           


           <div class="col-xs-12 register-user-center-margin">

               <div class="row register-user-title week-day" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list7" onclick="witchInfo(this)">
                 <div class="col-xs-12 text-center">周日考勤</div>
               </div>

               <div id="with-list7" style="display:none;margin-top:15px;">

  <?php if(!$seven ){?>                     
                            <p>今天无人刷卡</p>

    <?php }else{?>

 <?php foreach($seven as $k1=>$v1 ){?> 
   <?php if($v1['enddatepa']>time()): ?>
    <div class="row inout-with-list">
        <div class="col-xs-4"><?php echo date('Y年m月d日H时i分',$v1['ctime'])?></div>
        <div class="col-xs-5"><?php echo $v1['info']?></div>
        <div class="col-xs-3"><?php echo $v1['name']?></div>
    </div>
    <?php endif;?>
 <?php }?> 

 <?php }?> 
               </div>

           </div>              
          

       </div>


     


    </div> <!-- container-fluid -->
<input type="hidden" value="<?php echo URL_PATH?>" id="path">


<!-- JS代码区 --> 
<script>
  $(function(){

     $('.delete_span').confirmation({
        onConfirm: function(e,ths){
          var cid = $(ths).attr("value");         
          doGet({cid:cid},$(ths));
        },
        onCancel: function(){}
      });

     function doGet(para,ths){
      var path=$("#path").val();
        $.getJSON(path+'/manage/deleteclass',para,function (data){
            if(data=='success'){
              ths.parents(".panel-default").hide().remove();
            }else{
              showDialog("操作失败，请重试！");
            }

        })
     }
        
  })
     
</script>
