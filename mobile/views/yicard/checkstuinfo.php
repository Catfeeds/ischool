<?php
use yii\helpers\Url;

?>
 <div class="container-fluid register-user-margin">
       <div class="row register-user-container">

           <div class="col-xs-12 examine-user-title">
               <span class="badge"><?php echo $stu[0]['name']?>考勤信息</span>
           </div>

<!--          <if condition="$list_cards eq '' ">-->
    <?php if(!$list_cards){?>       
               <div class="col-xs-12 text-center list-location">暂无相关信息</div>
    <?php }else{?> 
    <?php if($stu[0]['enddatepa']>time()): ?> 
         <?php foreach($list_cards as $k=>$v){?>
           <div class="col-xs-12 register-user-center-margin" style="margin-top:20px">

               <div class="row examine-user-list stuinfo-day" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list<?php echo $v['day']?>" onclick="witchInfopar(this)">
                 <div class="col-xs-6 text-center"><?php echo $stu[0]['name']?></div>
                 <div class="col-xs-6 text-center"><?php echo $v['day']?>考勤</div>
               </div>

               <div id="with-list<?php echo $v['day']?> " style="display:none;margin-top:15px;">

             <?php foreach($v['card'] as $k1=>$v1){?>
                    <div class="row inout-with-list">
                        <div class="col-xs-7">
                               <?php echo date('Y年m月d日H时i分',$v1['ctime'])?> 
                        </div>
                        <div class="col-xs-5"><?php echo $v1['info']?></div>
                    </div>
             <?php }?>
               </div>

           </div>                        
      <?php }?>
    <?php else: ?>
         <div class="col-xs-12 text-center list-location">暂无相关信息</div>
   <?php endif; ?>  
     <?php }?>
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
