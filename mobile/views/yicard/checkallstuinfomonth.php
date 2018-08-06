<?php
use yii\helpers\Url;

?>
 <div class="container-fluid register-user-margin">
       <div class="row register-user-container">

           <div class="col-xs-12 user-root-title">
               <span class="badge">出勤信息</span>
           </div>

<!--           <if condition="$info eq '' ">-->
<?php if(empty($info) ){?>   
               <div class="col-xs-12 text-center list-location">暂无相关信息</div>
<!--           <else /> -->
 <?php }else{?>  
<!--<foreach name="info" item="vo" key="key">-->
<?php foreach($info as $k=>$v ){?> 
         <?php if($v['enddatepa']>time()):?>
           <div class="col-xs-12 register-user-center-margin">

               <div class="row user-root-title month-day" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list<?php echo $v['id']?>" onclick="witchInfo(this)">
                 <div class="col-xs-6 text-center"><?php echo $v['stname']?></div>
                 <div class="col-xs-6 text-center">本月考勤</div>
               </div>

               <div id="with-list<?php echo $v['id']?>" style="display:none;margin-top:15px;">

               
                    <div class="row inout-with-list">
<!--                        <foreach name="info2" item="vo2" key="key">-->
                        <?php foreach($info2 as $k1=>$v1 ){?> 
                        <?php if($v1['stuid']==$v['stuid']): ?>
                        <div class="col-xs-12"><?php echo date('Y年m月d日H时i分',$v1['ctime'])?>

<!--                            {$vo2.ctime|date='Y年m月d日H时i分',###}-->
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $v1['info']?></div>
<!--                        </foreach>-->
                        <?php endif;?>
                        <?php }?> 
                    </div>

               </div>

           </div> 
           <?php endif;?>                  
<!--</foreach>-->
<?php }?> 
<!--</if>-->
<?php }?> 
       </div>


       <div class="row register-user-container">

           <div class="col-xs-12 register-user-center-margin">
               <div class="row register-user-Paging">
                 <div class="col-sm-2">记录：<?php echo count($info)?>位学生</div>
                 <!-- <div class="col-sm-2 col-xs-3">首页</div>
                 <div class="col-sm-2 col-xs-3">上一页</div>
                 <div class="col-sm-2 col-xs-3">下一页</div>
                 <div class="col-sm-2 col-xs-3">末页</div> -->
                 <!--<div class="col-sm-2 list-display">页数：{$pagenum}页</div>-->
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
        $.getJSON(path+'/Manage/deleteclass',para,function (data){
            if(data=='success'){
              ths.parents(".panel-default").hide().remove();
            }else{
              showDialog("操作失败，请重试！");
            }

        })
     }
        
  })
     
</script>

