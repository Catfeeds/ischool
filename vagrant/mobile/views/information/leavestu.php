<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/oneclass?cid=<?php echo $cid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">        
        <i class="fa fa-reply"></i>
      </div>
    </div>
   
    <div class="col-xs-5 text-align-l text-omit">
      <?php echo $cname?>
    </div>

</div>
<?php if($list_stu==1){?>
<!-- <switch name="list_stu" >-->
<!--          <case value="1">-->
             <div class="row edit-user-row">

          <div class="col-xs-10 col-xs-offset-2 text-align-l edit-user-top">

              <i class="fa fa-exclamation-circle"></i> 审核尚未通过，请联系管理员

          </div>
          
      </div>
<!--          </case>
          <case value="2">-->
    <?php }else if($list_stu==2) {?>    
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
<!--            <foreach name="list_stu" item="vo" key="key">-->
         <?php foreach($list_stu as $v){?>
                <div class="row edit-user-row">
                  <div class="col-xs-3 col-xs-offset-1 edit-user-top text-omit" onclick="forwardTo('<?php echo URL_PATH?>/information/qingjiainfo?cid=<?php echo $cid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>&id=<?php echo $v['id']?>&name=<?php echo $v['name']?>&back=leavestu')">
                      <i class="fa fa-user"></i> <?php echo $v['name']?>
                  </div>
                  <div class="col-xs-5 edit-user-top text-omit" onclick="forwardTo('<?php echo URL_PATH?>/information/qingjiainfo?cid=<?php echo $cid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>&id=<?php echo $v['id']?>&name=<?php echo $v['name']?>&back=leavestu')">
<!--                    {$vo.begin_time|date='m-d H:i',###}至{$vo.stop_time|date='m-d H:i',###}-->
                    <?php echo date('m-d H:i',$v['begin_time'])?> 至 <?php echo date('m-d H:i',$v['stop_time'])?>
                  </div>
                 <?php if($role==0){?>
<!--                  <if condition="$role eq 0">-->
                     <div class="col-xs-3  edit-user-top" value="<?php echo $v['id']?>-<?php echo $v['cardid']?>" onclick="cenleave(this)">
                        <i class="fa fa-share-square-o"></i> 销假
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
            请把没有使用平安卡的学生设置为请假
          </div>
        </div>
</div>



