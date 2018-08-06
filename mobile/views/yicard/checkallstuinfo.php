<?php
use yii\helpers\Url;

?>
<style type="text/css">
    .col-xs-8{ width: 55%}
    .col-xs-2{ width: 20%}
    .col-xs-2{ width: 20%}
</style>
    <div class="container-fluid register-user-margin">
       <div class="row register-user-container">

           <div class="col-xs-12 examine-user-title">
               <span class="badge"><?php echo $cname?>出勤信息</span>
           </div>


 <?php if(!$list_stu){?>           
               <div class="col-xs-12 text-center list-location">今天无人刷卡</div>
<!--           <else />-->
 <?php }else{?> 

<!--               <foreach name="one" item="vi" key="key">-->
    <?php foreach($one as $k1=>$v1 ){?>   
                   <div class="row inout-with-list">
                       <div class="col-xs-4">

                           <?php echo date('Y年m月d日H时i分',$v1['ctime'])?> 
                       </div>
                       <div class="col-xs-5"><?php echo $v1['info']?></div>
                       <div class="col-xs-3"><?php echo $v1['name']?></div>
                   </div>

    <?php }?>        

    <?php foreach($list_stu as $k=>$v ){?>  
          <?php if($v['enddatepa']>time()):?>
                   <div id="s<?= $v['id']?>" class="col-xs-12 register-user-center-margin">

                       <div class="row examine-user-list stuinfo-day" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list<?php echo $v['id']?> " onclick="witchInfo(this)">
                           <div class="col-xs-12 text-center">
                                        <?php echo $v['info']?>
                         
                           </div>
                           
                       </div>
                       <div class="col-xs-9 text-left"><?php echo date('Y年m月d日H时i分',$v['ctime'])?></div>
                       <div class="col-xs-3 text-right"><?php echo $v['name']?></div>
                   </div>
          <?php endif;?>

     <?php }?> 


 <?php }?> 
       </div>
       <input type="hidden" value="<?php echo URL_PATH?>" id="path">

       <div class="row register-user-container">

           <div class="col-xs-12 register-user-center-margin">
               <div class="row register-user-Paging">
                 <div class="col-sm-2 ">记录：<?php echo $stunum?>位学生</div>
               <!--   <div class="col-sm-2 col-xs-3">首页</div>
                 <div class="col-sm-2 col-xs-3">上一页</div>
                 <div class="col-sm-2 col-xs-3">下一页</div>
                 <div class="col-sm-2 col-xs-3">末页</div> -->
                 <div class="col-sm-2 list-display">页数：<?php echo $pagenum?>页</div>
               </div>
           </div>

       </div>


    </div> <!-- container-fluid -->



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
    //确认学生已到
     function center(id){
      var path=$("#path").val();
     $.post(path+'/yicard/center',
      {id:id},function (data){
            if(data=='success'){
              $("#s"+id).remove();
              showDialog("操作成功");
            }else{
              showDialog("操作失败，请重试！");
            }

        })
     }
     //确定学生未到
     function sendmsgtopa(id){
      var path=$("#path").val();
    $.post(path+'/yicard/sendmsgtopa',
      {id:id},function (data){
            if(data=='success'){
              $("#s"+id).remove();
              showDialog("操作成功");
            }else{
              showDialog("操作失败，请重试！");
            }

        })


     }
</script>
