<?php echo $this->render('../layouts/menu');?>
<div id="pickup"> <!-- 发件箱 -->
  <input type="hidden" value="<?php echo \yii::$app->view->params['openid']?>" id="openid">
  <input type="hidden" value="<?php echo $sid?>" id="sid">
  <input type="hidden" value="<?php echo URL_PATH?>" id="path">
    <div class="container-fluid register-user-margin">

 <!-- 发件箱 --> 
<!--    <if condition="$list_msg eq '' ">   -->
 <?php if($list_msg=="" ){?>
        
       <div class="row register-user-container-title">

           <div class="col-xs-12 register-user-title">
               <span class="badge">发件箱</span>
           </div>
               <div class="col-xs-12 text-center list-location">对不起暂无相关信息</div>
               <div class="col-xs-12 send-one-center-margin"></div>
       </div>
<!--    <else />-->
 <?php }else{?>
<!--     <foreach name="list_msg" item="vo" key="key"> -->
<?php foreach($list_msg as $k=>$v ){?>
<!-- ...................................信息........................................ -->
       <div id="list<?php echo $v['id']?>" class="row register-user-container panel-default">
           <div class="col-xs-12 register-user-center-margin">

               <div class="row examine-user-list-out" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list<?php echo $v['id']?>" onclick="witchInfo(this)">
                 <div class="col-xs-8 text-omit"><?php echo $v['title']?></div>
                 <div class="col-xs-4 title-more-time">
<!--                     {$vo.ctime|date='m月d H:i',###}-->
                     <?php echo date('m月d H:i',$v['ctime']);?>
                 </div>
               </div>

               <div id="with-list<?php echo $v['id']?>" style="display:none;">
                  <div class="row">
                      <div class="col-xs-12 content-text">
                 
                        <br />
<!--                 <if condition="$vo.type eq txt"> -->
               <?php if($v['type']=='txt'){?>      
                  <?php echo $v['content']?>
<!--                  <else />-->
               <?php }else{ ?>   
                  <?php echo $v['content']?>
                   <div class="col-xs-12" id="play-waper">
                        <div class="play-record" style="display:block" onclick="play_record(this);down('<?php echo $k?>')">
                            <i class="fa fa-play play-record-ico"></i>
                        </div>
                        <div class="stop-record" style="display:none" onclick="stop_record(this)" name="<?php echo $k?>">
                            <i class="fa fa-pause stop-record-ico"></i>
                        </div>
                        <div class="record-text">
                            语音信息
                        </div>
                    </div>
                  <input type="hidden" value="<?php echo $v['serverId']?>" id="s<?php echo $k?>">
                  <input type="hidden" id="d<?php echo $k?>" value="" >
                 <?php } ?>
                     


<!--                        <if condition="$vo.fujian neq f"> -->
                <?php if($v['fujian']!='f'){?>  
                    <div id="inbox-file-waper">
                      <div class="file-title">附件</div>

<!--                       <foreach name="vo.fujian" item="v" key="key"> -->
                     <?php foreach($v['fujian'] as $k1=>$v1){?>     
                          <div class="file-center">
                            <div class="file-ico">
                              <i class="fa fa-folder-open file-record-ico"></i>
                              <div class="file-name">附件</div>
                            </div>
                            <div class="file-down">
                              <a href="<?php echo $v1 ?>" target="_self">
                                <div class="down-popo">
                                  下载附件
                                </div>
                              </a>
                            </div>
                        </div>
                     <?php } ?>
                    </div> 
                   <?php } ?>

                      
                        <hr />

                      </div>   
                  </div>
                  <div class="row" style="margin-bottom:20px;">
                      <div class="col-xs-3">
                          <span class="badge time-badge"></span>
                      </div>

                      <div class="col-xs-2 col-xs-offset-4">
                          <span class="badge time-badge delete_span badge_oprate" onclick="del(<?php echo $v['id']?>)">删除</span>
                      </div>
                       <div class="col-xs-2">
                      <a href="<?php echo URL_PATH?>/exchange/sendmsg?id=<?php echo $v['id']?>&type=outtransmit" onclick="loadHtml(this,event);sendMsg()">
                        <span class="badge time-badge reply_span badge_oprate" value="<?php echo $v['id']?>">转发</span>
                      </a>
                      </div>
                  </div>
               </div>
           </div>
       </div> 
<!-- ...................................信息........................................ -->            
  <?php } ?>
 <?php } ?>
     <!-- 翻页 -->
       <div class="row register-user-container">

           <div class="col-xs-12 register-user-center-margin">
               <div class="row register-user-Paging">
                 <div class="col-sm-2 list-display">记录：<?php echo $count ?>条信息</div>
                 <a href="<?php echo $start?>" onfocus="this.blur()" onclick="loadHtml(this,event)"><div class="col-sm-2 col-xs-3">首页</div></a>
                 <a href="<?php echo $up?>" onfocus="this.blur()" onclick="loadHtml(this,event)"><div class="col-sm-2 col-xs-3">上一页</div></a>
                 <a href="<?php echo $down?>" onfocus="this.blur()" onclick="loadHtml(this,event)"><div class="col-sm-2 col-xs-3">下一页</div></a>
                 <a href="<?php echo $end?>" onfocus="this.blur()" onclick="loadHtml(this,event)"><div class="col-sm-2 col-xs-3">末页</div></a>
                 <div class="col-sm-2 list-display">页数：<?php echo $totalPage ?>页</div>
               </div>
           </div>

       </div>
    </div> <!-- container-fluid -->

</div> <!-- 发件箱 结束 -->

<script>
   function del(id){
    var mid = id;     
    var path=$("#path").val();
          var url=path+'/exchange/deleoutbox';
          var para={mid: mid};
          var ths = $(this);
          var d = dialog({
            title: '提示',
            content: '您确定要删除吗?',
            okValue: '确定',

            ok: function () {
               $.post(url,para,function (data){
                 if(data=='success'){
                  $("#list"+mid+"").hide().remove();
                }
               });
            },

            cancelValue: '取消',
            cancel: function () {

            }

         });

         d.showModal();
 }
     
</script>

