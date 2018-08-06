
<div id="pickup"> <!-- 发件箱 -->
  <input type="hidden" value="<?php echo \yii::$app->view->params['openid']?>" id="openid">
  <input type="hidden" value="<?php echo $sid?>" id="sid">
  <input type="hidden" value="<?php echo URL_PATH?>" id="path">
    <div class="container-fluid register-user-margin">

 <!-- 发件箱 --> 
<!--    <if condition="$list_msg eq '' ">   -->
 <?php if($list_msg=="" ||empty($list_msg)){?>
        
       <div class="row register-user-container-title">

           <div class="col-xs-12 register-user-title">
               <span class="badge">我的文档</span>
           </div>
               <div class="col-xs-12 text-center list-location">对不起暂无相关信息</div>
               <div class="col-xs-12 send-one-center-margin"></div>
       </div>
<!--    <else />-->
 <?php }else{?>
      <div class="row user-search" id="search_res">
        <form class="form-horizontal" role="form">
          <div class="col-xs-12">
            <div class="form-group has-success has-feedback">
              <input type="text" class="form-control" id="inputSuccess3" placeholder="输入关键字检索" onchange="search_doc(this)">
              <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
          </div>
        </form>
      </div> <!-- 搜索结束 -->
<!--     <foreach name="list_msg" item="vo" key="key"> -->
<?php foreach($list_msg as $k=>$v ){?>
<!-- ...................................信息........................................ -->
       <div id="list<?php echo $v['id']?>" class="row register-user-container panel-default">
           <div class="col-xs-12 register-user-center-margin">

               <div class="row examine-user-list-out" style="margin-top:-8px;margin-bottom:-8px;" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list<?php echo $v['id']?>" onclick="witchInfo(this)">
                 <div class="col-xs-8 text-omit"><?php echo $v['title']?></div>
                 <div class="col-xs-4 title-more-time">
                     <?php echo date('m月d H:i',$v['create_time']);?>
                 </div>
               </div>

               <div id="with-list<?php echo $v['id']?>" style="display:none;" class="on">
                  <div class="row">
                      <div class="col-xs-12 content-text">
                 
                        <br />
<!--                        <if condition="$vo.fujian neq f"> -->
                   <?php if($v['url']!=''){ $arr=explode('/',$v['url']); ?>  
                    <div id="inbox-file-waper">
                      <div class="file-title">附件</div>
    
                          <div class="file-center">
                            <div class="file-ico">
                              <i class="fa fa-folder-open file-record-ico"></i>
                              <div class="file-name">附件</div>
                               <p style="color:white;"><?= $arr[count($arr)-1]?></p>
                            </div>
                            <div class="file-down">
                              <a href="<?php echo 'http://pc.jxqwt.cn'.$v['url'] ?>" target="_self">
                                <div class="down-popo">
                                  下载附件
                                </div>
                              </a>
                            </div>
                        </div>
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
<!--                       <div class="col-xs-2">
                      <a href="<?php echo URL_PATH?>/exchange/sendmsg?id=<?php echo $v['id']?>&type=outtransmit" onclick="loadHtml(this,event);sendMsg()">
                        <span class="badge time-badge reply_span badge_oprate" value="<?php echo $v['id']?>">转发</span>
                      </a>
                      </div>-->
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
      var url=path+'/information/deleoutbox';
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
 function search_doc(ths){
  var sid = $("#sid").val();
  var qunzu = $("#qunzu").val();
  var name = $.trim($(ths).val());
  var url = $("#path").val()+"/information/searchdoc";
  $.getJSON(url,{sid:sid,name:name,qunzu:qunzu},function(data){
     var htmls = "";

     if(data.length){
        var leng = data.length;      
        var i =0 ;
        for(i=0;i<leng;i++){
          var title = data[i]['title'];
          var id = data[i]['id'];
          var name = data[i]['name'];
          var time= getLocalTime(data[i]['create_time']);
          var url= data[i]['url'];
          var filename=url.split('/');
          htmls = htmls+"<div id='list"+id+"' class='row register-user-container panel-default'>"
                       +"<div class='col-xs-12 register-user-center-margin'>"
                       +"<div class='row examine-user-list-out' style='margin-top:-8px;margin-bottom:-8px;' onmouseover='register_user_over(this)' onmouseout='register_user_out(this)' id='list"+id+"' onclick='witchInfo(this)'>"
                       +"<div class='col-xs-7 text-omit'>"+title+"("+name+")</div>"
                       +"<div class='col-xs-5 title-more-time'>"+time+"</div>"
                       +"</div>" 
                       +"<div id='with-list"+id+"' style='display:none;' class='on'>" 
                       +"<div class='row'>" 
                       +"<div class='col-xs-12 content-text'>" 
                       +"<br>"                                                                
                       +"<div id='inbox-file-waper'>"
                       +"<div class='file-title'>附件</div> "            
                       +"<div class='file-center'>" 
                       +"<div class='file-ico'>"   
                       +"<i class='fa fa-folder-open file-record-ico'></i>" 
                       +"<div class='file-name'>附件</div>" 
                       +"<p style='color:white;'>"+filename[filename.length-1]+"</p>"
                       +"</div>"  
                       +"<div class='file-down'>" 
                       +"<a href='http://pc.jxqwt.cn"+url+"' target='_self'> "
                       +"<div class='down-popo'>"
                       +"下载附件"         
                       +"</div></a></div></div> </div><hr /></div></div>"                             
                       +"<div class='row' style='margin-bottom:20px;'>"    
                       +"<div class='col-xs-3'>" 
                       +"<span class='badge time-badge'></span>"
                       +"</div></div></div></div></div>"
             
        }
     }else{
        htmls = htmls+"<div id='list' class='row register-user-container panel-default'>"
                +"<div class='col-xs-12 register-user-center-margin'>没有结果</div>" 
                +"</div>"
     }
     $(".panel-default").hide();
     $("#search_res ").after(htmls);
     
  });
}
function getLocalTime(nS) {  
  return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');  
}     
</script>



