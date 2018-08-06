<link media="all" rel="stylesheet" type="text/css" href="/simditor/styles/font-awesome.css" />
<link media="all" rel="stylesheet" type="text/css" href="/simditor/styles/simditor.css" /> 
<link media="all" rel="stylesheet" type="text/css" href="/css/ui-dialog.css" />

<script type="text/javascript" src="/js/ajaxload.js"></script>
<script type="text/javascript" src="/js/LocalResizeIMG.js"></script>
<script type="text/javascript" src="/js/patch/mobileBUGFix.mini.js"></script>
<script type="text/javascript" src="/simditor/scripts/js/simditor-all.js"></script>
<script type="text/javascript" src="/simditor/scripts/js/home-page-demo.js"></script>
<script type="text/javascript" src="/js/dialog-min.js"></script>
<style>
    #upload{
        line-height: 100px;
    }
</style>
<?php
$this->title = \yii::$app->view->params['homepage'];
?>
<div class="container-fluid">
  <div class="row page-shadow" id="header">
     <div class="col-xs-8">
      <div class="header-home text-omit">
          <a href="#" onfocus="this.blur()">
            <div class="glyphicon glyphicon-home header-icon"></div>
              <span><?= $ischool ?></span>
          </a>
      </div>
     </div>

   <div class="col-xs-3" onclick="sub()">
    <div class="header-home">
      <a href="#0" onfocus="this.blur()">
        <span class="header-submit">保存</span>
      </a>
    </div>
   </div>

  </div>
</div>

<section class="section-font-size-home section-home-margin margin-footer-nav">
<div id="accordion" role="tablist" aria-multiselectable="true">
	<input type="hidden" id="imghidden" name="img" value=""/>
        <input type='hidden' name='sid' id="sid" value="<?php echo $sid?>">
        <input type='hidden' name='cid' id="cid" value="<?php echo $cid?>">
        <input type='hidden' name='type' id="type" value="<?php echo $type?>">
        <input type='hidden' name='tem' id="tem" value="<?php echo $tem?>">
<!-- 校园公告 --> 
  <div class="container-fluid list-container">
     <div class="row list-location">
         <div class="col-xs-2">
            <span class="badge list-badge gonggao">标题</span>
         </div>
         <div class="col-xs-10">
            <input type="text" class="form-control input-sm title-input-text" placeholder="请输入主题信息.." name="title" id="title" >
         </div>
     </div> 
  </div>
 <div class="container-fluid list-container">
     <div class="row list-location">
         <div class="col-xs-2">
            <span class="badge list-badge gonggao">简介</span>
         </div>
         <div class="col-xs-10">
            <input type="text" class="form-control input-sm title-input-text" placeholder="请输入主题信息.." name="title" id="sketch" >
         </div>
     </div> 
  </div>

  <div class="panel panel-default content-margin">
    <div class="panel-collapse collapse in">
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-12">
            <textarea class="form-control" rows="13" placeholder="请输入内容信息.."  id="txt-content" name="content"></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section> 

<div class="container-fluid" style="margin:-50px 0 40px 0;">

  <div class="row file-bg">
    <div class="col-xs-6">
      <div class="add-img">
      
        <div id="loading" class="ui-dialog-loading" style="margin-top:10px;display:none">Loading..</div>
        
        <img src="/img/add_img.png" id="img">
      </div>
    </div>
    <div class="col-xs-6">
      <div class="file-btn" onclick="file_input()">上传封面</div>
       <input id="upload-input" accept="image/*" capture="camera" type="file" name="upload-input" style="display:none">
    </div>
  </div>

</div> 

<script type="text/javascript">

$('#upload-input').localResizeIMG({
  	 width: 400,
  	 quality: 1,
  	 before:function(ths,b,f){
  		var names=$(ths).val().split(".");  		
  		if(names[1]!="gif"&&names[1]!="GIF"&&names[1]!="jpg"&&names[1]!="JPG"&&names[1]!="png"&&names[1]!="PNG"&&names[1]!="jpeg"&&names[1]!="JPEG"&&names[1]!="bmp"&&names[1]!="BMP")
  		{ 
  		alert("图片必须为gif,jpg,png,bmp,jpeg格式!!"); 
  		return; 
  		} 
  	 },
  	 success: function (result) {
  		 var submitData={
  		 	data:result.clearBase64,
  	 	 };
  		 $("#img").hide();
  		 $("#loading").show();
  		 $("#dimmer-loader").show();
  		 $("#prevImg").attr("src",result.base64);
  		 $.ajax({
  			 type: "POST",
  			 url: '/utils/uploadimg',
  			 data: submitData,
  			 dataType:"json",
  			 success: function(data){
  				 if(data){					 
  					  $("#img").attr("src",data.file_path);
  					  $("#imghidden").attr("value",data.file_path);
  					  $("#img").show();
  					  $("#loading").hide(); 
  				 }else{
  					 alert("上传错误，请重试");
  				 }
  			 }
  			
  		 });
  	 }
  });


  var first = true;
function sub(){
  var imghidden=$("#imghidden").val();
  var openid=$("#openid").val();
  var sid=$("#sid").val();
  var cid=$("#cid").val();
  var title=$.trim($("#title").val());
  var content=$.trim($("#txt-content").val());
  var sketch=$("#sketch").val();
  var type=$("#type").val();
  var tem = $("#tem").val();
  if (imghidden==""&&content==""&&title=="") {
      var td = dialog({
  
        title: '提示',
        content: '请至少上传一张封面',
        okValue: '确定',
  
        ok: function () {
          this.remove();
        }
  
      });
      td.showModal();
   
  
  }
  else
  {
    if(first == true)
    {
      var path=$("#path").val();
      //关掉可点击发布按钮的开关，禁止重复发布
      first = false;
  
         //构造一个对话框，等ajax执行后后再显示
         var d = dialog({
            title: '提示',
            content: '正在提交中...请稍等片刻'+'<span class="ui-dialog-loading" style="margin-top:10px">Loading..</span>',
         });
  
         $.ajax({
          url:"/homepage/doadd",
          data:{openid:openid,sid:sid,cid:cid,title:title,content:content,img:imghidden,sketch:sketch,type:type,tem:tem},
          type:'post',
          complete:function(XHR, TS)
          {
            //网络错误
            if(XHR.readyState == 0)
            {
              if (d != null){
                d.remove();
                }
              
              var errd = dialog({
                title: '警告',
                content: '网络连接错误,检查后重试！',
                okValue: '确定',
                ok: function () {
                  this.remove();
                }
  
              });
              errd.showModal();
              first = true; 
            }
            //网络传输完成
            else if (XHR.readyState == 4)
            {
              //发布按钮可点击开关打开，可以重新点击发布
              first = true;
              //status:200-299 用于表示请求成功。 
              if  ((XHR.status >= 200) && (XHR.status <300)) 
              {
                if (d != null){
                  d.content('提交成功！2秒后将自动跳转...'); 
                  d.showModal();
                }
  
                //这里直接跳转到公告列表，无需再设置first
                setTimeout(function () {
                  window.location.replace("/homepage/index");
                }, 2000); 
  
              }
              //408:(SC_REQUEST_TIMEOUT)是指服务端等待客户端发送请求的时间过长。该状态码是新加入 HTTP 1.1中的
              else if (XHR.status == 408) 
              {
                //更新对话框提示超时
                if (d != null){
                  d.content('服务器超时，请重试！'); 
                  d.showModal();
                }
                
              }
              //其他网络错误
              else
              {
                //更新对话框提示执行失败
                if (d != null){
                  d.content('提交失败，请重试！'); 
                  d.showModal();
                }
              }
            }
          }
        });
  
        //不用等ajax请求成功，显示模态对话框
        if (d != null){
        d.showModal();
        }
      }
    }
  }


function file_input(){
  $("#upload-input").click();
};

  $("input").focus(function(){
    $("#footer-display").hide();
  });  

  $("input").blur(function(){
    $("#footer-display").show();
  }); 

</script>   













