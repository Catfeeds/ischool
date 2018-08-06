
<input type="hidden" value="<?php echo $sid;?> "  id="hidden_sid"/>
<input type="hidden" value="<?php echo $cid;?>"  id="hidden_cid"/>
<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/uploadimgs?openid=<?php echo \yii::$app->view->params['openid']?>')">
        <i class="fa fa-reply"></i>  
      </div>    
    </div>
   
    <div class="col-xs-5 text-align-l">         
      2.图片上传     
    </div>
    
</div>
<?php if($level['level']==1){?>
<div class="upload-wrapper">

    <form  action="/information/uploading?sid=<?php echo $sid?>&cid=<?php echo $cid?>" method="post" target="hidden_frame" enctype="multipart/form-data" id="ImgForm" role="form">
      <div id="upload-picture" >上传图片</div>
<!--   <input id="upload-input" accept="img/*" capture="camera" type="file" name="upload-input" style="display:none">-->
<!--      <input id='upload-input' accept='img/*' capture='camera' type='file' name='upload-input' style='display:none'>-->
<!--       <input id="upload-input" accept="img/*" capture="camera" type="file" onchange="ok()" name="UploadForm[file]"  style="display:none;">-->

       <input style="visibility: visible;" type="checkbox"  name="yasuo"  value="压缩" id="checkb" />是否压缩
    
    </form>
     <iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe> 
</div>
<?php }else{?>
<div class="empty-picture">您没有相关权限,请联系管理员！</div>
<?php }?>
<script type="text/javascript">   
        // 上传按钮事件
        $("#upload-picture").click(function(){
            if($("#checkb").is(':checked')){
                 $("#upload-picture").after("<input id='upload-input' accept='image/*' capture='camera' type='file' name='upload-input' style='display:none'>");
                 manage.setUploadimages();  
            }else{
                 $("#upload-picture").after("<input id='upload-input' accept='image/*' capture='camera' type='file' onchange='ok()' name='UploadForm[file]' style='display:none'>");
                $("#upload-input").click();                  
            }           
        });
    
    function ok(){
        var s=$('input[name="UploadForm[file]"]').val();  
        if(s==""){
             alert("请选择文件");
             return false;
        }
        
       $("#ImgForm").submit();  
    }
    
</script>