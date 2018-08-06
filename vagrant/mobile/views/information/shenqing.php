<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/oneclass?cid=<?php echo $cid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">        
        <i class="fa fa-reply"></i>  
      </div>
    </div>
   
    <div class="col-xs-5 text-align-l text-omit">
      <?php echo $cname?>申请请假列表
    </div>

</div>
<?php if($list_stu==1){?>
<!-- <switch name="list_stu" >
          <case value="1">-->
         <div class="row edit-user-row">

          <div class="col-xs-10 col-xs-offset-2 text-align-l edit-user-top">

              <i class="fa fa-exclamation-circle"></i> 审核尚未通过，请联系管理员

          </div>
          
         </div>
 <?php }else if($list_stu==2) {?>   
<!--          </case>
          <case value="2">-->
             <div class="row edit-user-row">

          <div class="col-xs-10 col-xs-offset-2 text-align-l edit-user-top">

              <i class="fa fa-exclamation-circle"></i> 没有请假学生

          </div>
          
      </div>
 <?php }else {?>   
<!--        </case>
          <default />-->
          <input type="hidden" id="cid" value="<?php echo $cid?>">
          <input type="hidden" id="tcid" value="<?php echo $tcid?>">
          <input type="hidden" id="path" value="<?php echo URL_PATH?>">
          <input type="hidden" id="cname" value="<?php echo $cname?>">
          <input type="hidden" id="sid" value="<?php echo $sid?>">
          <input type="hidden" id="openid" value="<?php echo $openid?>">
          <input type="hidden" id="role" value="<?php echo $role?>">
<!--    <foreach name="list_stu" item="vo" key="key">-->
      <?php foreach($list_stu as $v){?> 
        <div class="row edit-user-row">
          <div class="col-xs-5 col-xs-offset-1 edit-user-top text-omit" onclick="backto('<?php echo URL_PATH?>/information/qingjiainfo?cid=<?php echo $cid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>&id=<?php echo $v['id']?>&name=<?php echo $v['name']?>&back=shenqing')">
              <i class="fa fa-user"></i> <?php echo $v['name']?>
          </div> 
            <?php if($role==0){?>
<!--           <if condition="$role eq 0">-->
          
               <div class="col-xs-3 edit-user-top" onclick="approveLeave(<?php echo $v['id']?>,0)">
                  <i class="fa fa-check-square-o"></i> 批准
              </div> 
              <div class="col-xs-3 edit-user-top" onclick="approveLeave(<?php echo $v['id']?>,1)">
                  <i class="fa fa-ban"></i> 拒绝
              </div> 
          <?php } ?>
           
        </div>   
 <?php } ?>

        <div style="height:50px;"></div>
  <?php } ?>

        <div class="row help-row">
        <div class="col-xs-12">
          <span class="badge">

            帮助
          </span>
          <hr>
          <div class="help-row-text">
              请把没有使用平安卡的学生设置为请假。</br>
              点击学生名字，可以查看请假原因。
          </div>
        </div>
</div>

<script type="text/javascript">
  function approveLeave (id,flag) {
    var openid = $("#openid").val();
    var sid = $("#sid").val();
    var cid = $("#cid").val();
    var tcid = $("#tcid").val();
    var cname = $("#cname").val();
    var path = $("#path").val();
    var to_url=path+"/information/shenqing?cid="+cid+"&openid="+openid+"&cname="+cname+"&sid="+sid+"&tcid="+tcid;
    var url = path+"/information/responsestuleave";
    $.getJSON(url,{'lid':id,'flag':flag,'openid':openid},function(data){
            if(data==0){
              alert("成功！");
              loadHtmlByUrl(to_url);
            }else if(data==2){
              alert("今天请假名额已用完");
            }else{
              alert("失败，请重试！");
            }
          });
  }

</script>



