 <div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l">

      <div onclick="backto('<?php echo URL_PATH?>/information/<?php echo $back?>?cid=<?php echo $cid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>&tcid=<?php echo $tcid?>')">        
        <i class="icon-arrow-left icon-margin"></i>
          <i class="fa fa-reply pull-left"></i>
      </div>

    </div>

     <div class="col-xs-5 text-align-l text-omit">

     <?php echo $name?>请假原因
    </div>

</div>

<div class="row help-row">
    <div class="col-xs-12">
         <span class="badge">
            请假时间
          </span>
         <hr>
         <div class="help-row-text">
          <?php echo date("Y-m-d h:i:s",$begin_time)." 至 ".date("Y-m-d h:i:s",$stop_time)?>
          </div>

          <span class="badge">
            请假原因
          </span>
          <hr>
          <div class="help-row-text">
           <?php echo $content?>
          </div>
    </div>
</div>