 <script type="text/javascript" src="/js/simditor-all.js"></script>
 <script type="text/javascript" src="/js/page-demo.js"></script>
<?php echo $this->render('../layouts/menu');?>

<input type="hidden" id="ty" value="<?php echo $ty?>">
<input type="hidden" id="fujian" value="<?php echo $fujian?>">
<input type="hidden" id="serverId" value="<?php echo $serverId?>">
<input type="hidden" id="operate_type" value="<?php echo $type?>">
<input type="hidden" id="path" value="<?php echo URL_PATH?>">

  <div id="receive"> <!-- 写信息 -->
      <div class="container-fluid">

        <div class="row receive-user all-menu-trigger" onclick="selectStuS(this)">
          <div class="col-xs-10">
            <input type="text" class="form-control" id="to" value="<?php echo $name?>" placeholder="收件人">
          </div>

          <input type="hidden" id="ids" value="<?php echo $toopenid?>" />
          <input type="hidden" id="msgType" value="ly" />

          <div class="col-xs-2"><span class="glyphicon glyphicon-user"></span></div>
        </div>

      </div> <!-- container-fluid -->

      <div class="container-fluid receive-content">
        <div class="row" style="display:block;margin-bottom: 5px;">
          <div class="col-xs-12">
            <div class="jxt_op_btn" id="sendMsg">发送</div>
          </div>
        </div>
        <div class="row" id="textarea-text" style="display:block">
          <div class="col-xs-12">
            <textarea class="form-control" rows="14" id="txt-content" ><?php echo $content?></textarea>
          </div>
        </div>
        
    <div class="row">
      <div class="col-xs-12" id="record-waper" style="display:none">
        <div class="close-record-waper" onclick="close_record_waper()">
          <i class="fa fa-chevron-down"></i>
        </div>
        <div class="record-time">
          录音时间: <font id="record-time-text">00:00</font>
        </div>
        <div class="record-popo" style="display:block" onclick="record_popo();startTime(0,0)">
          <i class="fa fa-microphone record-popo-ico"></i>
        </div>
        <div class="record-stop" style="display:none" onclick="record_stop();stoptime()">
          <i class="fa fa-stop record-stop-ico"></i>
        </div>
        <div class="record-play" style="display:none"  onclick="record_play()">
          <i class="fa fa-play record-play-ico"></i>
        </div>
        <div class="record-play-stop" style="display:none"  onclick="record_play_stop()">
          <i class="fa fa-pause record-stop-ico"></i>
        </div>        
        <div class="col-xs-5" id="record-close" style="display:none" onclick="record_close();cleartime()">
          重录
        </div>
        <div class="col-xs-5 col-xs-offset-2" id="record-up" style="display:none" onclick="recordup();cleartime()">
          确定
        </div>

       
      </div>
    </div>


 <div class="row">
      <div class="col-xs-12" id="uloadfile-waper" style="display:none">
        <div class="close-uloadfile-waper" onclick="close_uloadfile_waper()">
          <i class="fa fa-chevron-down"></i>
        </div>

        <div class="col-xs-12" id="uloadfile-up" onclick="uloadfile_up()">
          上传附件
          <form  action="<?php echo URL_PATH?>/upload/function/upfile.php" method="post" target="hidden_frame" enctype="multipart/form-data" id="formuloadfile" role="form">
              <input type="file" accept="" title="上传附件" name="uloadfile" style="display:none" id="uloadfile" onchange="uploadfile()">
          </form>
          <iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe> 
        </div>
      </div>
    </div>

     <div id="record-file-waper"></div>
      </div>
  </div>
  
<script type="text/javascript">



function callback(message,success,name) 
{
      if(success==false) 
      { 
         alert("上传失败");
      } 
      else
      { 

      if ($("#record-file-waper").find("#file-waper").length == 1) {
      $("#file-waper").append("<div class='file-center'><input type='hidden' value='"+message+"' name='one'><div class='file-ico'><i class='fa fa-folder-open file-record-ico'></i><div class='file-name'>"+name+"</div></div><div class='file-down'><div class='down-popo' onclick='delfile(this)'>删除</div></div></div>");
      }else{
        $("#record-file-waper").append("<div id='file-waper'><div class='file-title'>附件</div><div class='file-center'><input type='hidden' value='"+message+"' name='one'><div class='file-ico'><i class='fa fa-folder-open file-record-ico'></i><div class='file-name'>"+name+"</div></div><div class='file-down'><div class='down-popo' onclick='delfile(this)'>删除</div></div></div></div>");
      };

      } 
}
function delfile(th){
$(th).parent().parent().remove()
}

function uploadfile() {

  var names=$("#uloadfile").val().split("."); 
  if(names[1]=="exe") { 

      alert("您上传的文件格式不符合");

      return; 
  } 

    $("#formuloadfile").submit();


}

$(document).ready(function(){

    var fujian=$("#fujian").val();
    var ty=$("#ty").val();
    if(ty=="voice")
    {

      var serid=$("#serverId").val();
      $("#serid").val(serid);
      $("#record-file-waper").append("<div id='play-waper'><div class='play-title'>语音消息</div><div id='play-center-waper'><div class='play-center'><div class='play-record' style='display:block' onclick='text_play_record(this);'><i class='fa fa-play play-record-ico'></i></div><div class='stop-record' style='display:none' onclick='text_stop_record(this)'><i class='fa fa-pause stop-record-ico'></i></div><div class='record-text'>语音信息</div></div><div class='del-center-waper'><div class='del-record' onclick='delrecord(this)'>删除</div></div></div></div>");
    }
    if(fujian!="f"&&fujian!="")
    {
      $("#record-file-waper").append("<div id='file-waper'><div class='file-title'>附件</div></div>");
      var html="";
      var fuji = fujian.split("#");
      for(var i=0;i<fuji.length;i++)
      {
        html+="<div class='file-center'><input type='hidden' value='"+fuji[i]+"' name='one'><div class='file-ico'><i class='fa fa-folder-open file-record-ico'></i><div class='file-name'>附件</div></div><div class='file-down'><div class='down-popo' onclick='delfile(this)'>删除</div></div></div>";
      }
      

      $("#file-waper").append(html);
      
    }

    $("#sendMsg").bind("click",doSendMsg);

})


</script>

