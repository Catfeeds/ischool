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
        <!--    <button class="btn btn_primary" id="chooseImage">选择图片</button>
           <button class="btn btn_primary" id="previewImage">预览图片</button>
           <button class="btn btn_primary" id="uploadImage">图片上传</button> -->
    <form  action="/information/saveclassinfo?sid=<?= $sid?>&cid=<?= $cid?>&school=<?= $school?>&classname=<?= $classname?>" method="post"  enctype="multipart/form-data" id="ImgForm" role="form">
     <div class="input-group input-group-lg">
            <span class="input-group-addon">姓名</span>
            <!-- <input type="text" class="form-control" placeholder="请输入学生姓名..." name="stu_name"  id="stu_name" onblur="checkstu(this.value)"> -->
            <select class="form-control" name="stu_name"  id="stu_name">
              <?php foreach($allstu as $key=>$value){ ?>
                <option value ="<?= $value['name']?>"><?= $value['name']?></option>
              <?php }?>
            </select>
     </div>
    <br/>
    <div id="upload-picture" >拍摄照片</div>
     <br/>
    <div class="col-sm-6 col-md-3">
        <a href="#" class="thumbnail">
            <img src="http://mobile.jxqwt.cn/img/beijingtu.jpg"
               id="show" width='319' height="425" alt="学生图片">
        </a>
    </div>
    <button type="button" class="btn btn-primary btn-lg btn-block" onclick="ok()">上传</button>
       <!-- <input style="visibility: visible;" type="checkbox"  name="yasuo"  value="压缩" id="checkb" />是否压缩 -->
   <input type="hidden" id="sid" name="sid" value="<?php echo $sid?>">
   <input type="hidden" id="cid" name="cid" value="<?php echo $cid?>">
   <input type="hidden" id="school" name="school" value="<?php echo $school?>">
   <input type="hidden" id="classname" name="classname" value="<?php echo $classname?>">  
    </form>
     <!-- <iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>  -->
</div>
<?php }else{?>
<div class="empty-picture">您没有相关权限,请【联系客服】或者【拨打电话】0371-55030687。</div>
<?php }?>

<input type="hidden" id="appid" value="<?php echo $appid?>">
<input type="hidden" id="timestamp" value="<?php echo $timestamp?>">
<input type="hidden" id="nonceStr" value="<?php echo $nonceStr?>">
<input type="hidden" id="signature" value="<?php echo $signature?>">
<!-- <script type="text/javascript" src="/js/jweixin-1.0.0.js"></script>-->
 <script type="text/javascript" src="/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">   
        // 上传按钮事件
        $("#upload-picture").click(function(){
            if($("#checkb").is(':checked')){
                 $("#upload-picture").after("<input id='upload-input' accept='image/*' capture='camera' type='file' name='upload-input' style='display:none'>");
                 manage.setUploadimages();  
            }else{
                 $("#upload-picture").after("<input id='upload-input' accept='image/*' capture='camera' type='file' onchange='auto()' name='UploadForm[file]' style='display:none'>");
                $("#upload-input").click();                  
            }           
        });
    function auto(){
         // var r= new FileReader();
         // f=document.getElementById('upload-input').files[0];
           
         // r.readAsDataURL(f);
         // r.onload=function (e) {
         //    document.getElementById('show').src=this.result;
         // };

         var preview = document.querySelector('img');
         var file  = document.querySelector('input[type=file]').files[0];
         var reader = new FileReader();
         reader.onloadend = function () {
          preview.src = reader.result;
         }
         if (file) {
          reader.readAsDataURL(file);
         } else {
          preview.src = "";
         }
         $('#footer_na').css('display','none');
    }
    function ok(){
        var stu=$('#stu_name').val(); 
        var s=$('input[name="UploadForm[file]"]').val(); 
        if(stu==""){
             alert("请输入姓名");
             return false;
        } 
        if(s==""){
             alert("请选择文件");
             return false;
        }
       $('#footer_na').css('display','block');  
       $("#ImgForm").submit();
        
    }
    function checkstu(sname){
      var school=$("#school").val();
      var classname=$("#classname").val();
      $.ajax({
          url:'/information/checkstuname',
          data:'sname='+sname+'&school='+school+'&classname='+classname,
          dataType:'json',
          type:'post',
          success:function(msg){
            if(msg.status=='fail' && msg.name!=''){
              alert(msg.name+'信息暂未入库！');
              $('#stu_name').val("");
            }
          }
      })
    }
    
</script>


  