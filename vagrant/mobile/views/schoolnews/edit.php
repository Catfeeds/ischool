<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title = '正梵智慧校园';

?>
<!-- 顶部导航 -->
<div class="container-fluid">
  <div class="row page-shadow" id="header">
     <div class="col-xs-8">
      <div class="header-home text-omit">
          <a href="#" onfocus="this.blur()">
            <div class="glyphicon glyphicon-home header-icon"></div>
              <span><?php echo $ischool?></span>
          </a>
      </div>
     </div>

   <div class="col-xs-3" onclick="submit()">
    <div class="header-home">
      <a href="#0" onfocus="this.blur()">
        <span class="header-submit">提交</span>
      </a>
    </div>
   </div>

  </div>
</div>
<!-- 顶部导航结束 -->


<!-- 小区公告、活动、贴贴、促销 -->
<section class="section-font-size-home section-home-margin margin-footer-nav">
<div id="accordion" role="tablist" aria-multiselectable="true">
<input type="hidden" value="<?php echo URL_PATH?>" id="path">
<?php foreach($schoolnews_edit as  $k=>$v){?>
<!-- <foreach name="schoolnews_edit" item="vo" key="key">-->

                <input type='hidden' name='sid' id ="sid" value="<?php echo $sid?>">
                <input type='hidden' name='openid' id ="openid" value="<?php echo \yii::$app->view->params['openid']?>">
                <input type='hidden' name='id' id="id" value="<?php echo $v['id']?>">

<!-- 小区公告 --> 
  <div class="container-fluid list-container">
     <div class="row list-location">
         <div class="col-xs-2">
            <span class="badge list-badge schoolnews">公告</span>
         </div>
         <div class="col-xs-10">
            <input type="text" class="form-control input-sm title-input-text" placeholder="请输入主题信息.." name="title" id="title" value="<?php echo $v['title']?>">
         </div>
     </div> 
  </div>

  <div class="panel panel-default content-margin">
    <div class="panel-collapse collapse in">
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-12">
            <textarea class="form-control" rows="13" placeholder="请输入内容信息.."  id="txt-content" name="content" ><?php echo $v['content']?></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- 小区公告结束 --> 
<!--</foreach>-->
<?php }?>
</div>

<!-- 提交按钮 --> 
<div class="row" style="display: none;">
    <div class="col-xs-12 text-center">
      <button type="submit" class="btn btn-danger btn-sm" id="submit-input">提交</button>
    </div>
</div>
<!-- 提交按钮 结束 --> 
</section>
<?php echo $this->render('../layouts/footer')?>
<!-- JS代码区 --> 
<script type="text/javascript">
   var first = true;
function submit(){
  var path=$("#path").val();
  var openid=$("#openid").val();
  var sid=$("#sid").val();
  var gid=$("#id").val();
  var title=$.trim($("#title").val());
  var content=$.trim($("#txt-content").val());

  if (title=="") {
      var td = dialog({
  
        title: '提示',
        content: '文章主题不能为空',
        okValue: '确定',
  
        ok: function () {
          this.remove();
        }
  
      });
      td.showModal();
   
  
  }else if(content==""){
     var td = dialog({
  
        title: '提示',
        content: '文章内容不能为空',
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
      //关掉可点击发布按钮的开关，禁止重复发布
      first = false;
  
         //构造一个对话框，等ajax执行后后再显示
         var d = dialog({
            title: '提示',
            content: '正在提交中...请稍等片刻'+'<span class="ui-dialog-loading" style="margin-top:10px">Loading..</span>',
         });
  
         $.ajax({            
//        url:path+'/index.php?s=/addon/Gonggao/Gonggao/sub_edit.html',
          url:path+'/schoolnews/sub_edit',
          data:{openid:openid,sid:sid,gid:gid,title:title,content:content},
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
                  d.content('修改成功！2秒后将自动跳转...'); 
                  d.showModal();
                }
  
                //这里直接跳转到公告列表，无需再设置first
                setTimeout(function () {
                  window.location.replace(path+"/schoolnews/index?token=gh_1570853a2962&openid="+openid+"&sid="+sid );
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
</script>



