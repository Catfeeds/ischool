<div class="row heard-list-waper">
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/oneclass?sid=<?php echo $sid?>&cid=<?php echo $cid?>&tcid=<?php echo $tcid?>&openid=<?php echo $openid?>')">
        <i class="fa fa-reply"></i> 
      </div>    
    </div>
    <div class="col-xs-7 text-align-l">
      <?php echo $cname?>班级成绩管理
    </div>
</div>

<input type="hidden" value="<?php echo $openid?>" id="openid"/>
<div id="wrapper" style="padding-top: 10px;">
      <div class="row edit-user-row" onclick="forwardTo('<?php echo URL_PATH?>/information/sendchengji?cid=<?php echo $cid?>&openid=<?php echo $openid?>&cname=<?php echo $cname?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">
        <div class="col-xs-7 col-xs-offset-2 edit-user-top">
           <i class="fa fa-envelope-o"></i> 成绩上传
        </div>
        <div class="col-xs-2 edit-user-top">
            <i class="fa fa-chevron-right"></i>
        </div>
      </div>
      <div class="row edit-user-row" onclick="forwardTo('<?php echo URL_PATH?>/information/querychengji?cid=<?php echo $cid?>&openid=<?php echo $openid?>&cname=<?php echo $cname?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">
        <div class="col-xs-7 col-xs-offset-2 edit-user-top">
           <i class="fa fa-envelope-o"></i> 成绩查询
        </div>
        <div class="col-xs-2 edit-user-top">
            <i class="fa fa-chevron-right"></i>
        </div>
      </div>
    
</div>


