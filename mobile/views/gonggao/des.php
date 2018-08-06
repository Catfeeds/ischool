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

  <?php if($bool==1){?>

   <div class="col-xs-3" onclick="release_input()">
    <div class="header-home">
      <a href="<?php echo Url::toRoute(['gonggao/add','openid'=>\yii::$app->view->params['openid'],'sid'=>$sid])?>" onfocus="this.blur()">
        <span class="header-submit">发布</span>
      </a>
    </div>
   </div>
 <?php } ?>

  </div>
</div>
<!-- 顶部导航结束 -->


<!-- 校内公告 -->
<section class="section-font-size-home section-home-margin">
<div id="accordion" role="tablist" aria-multiselectable="true">

 <input type="hidden" value="<?php echo \yii::$app->view->params['openid']?>" id="openid"/>
 <input type="hidden" value="<?php echo $sid?>" id="sid"/>
  <input type="hidden" value="<?php echo URL_PATH?>" id="path">
<!-- 校内公告 --> 
  <div class="container-fluid list-container">
     <div class="row list-location">
     
         <div class="col-xs-2">
            <span class="badge list-badge gonggao">公告</span>
         </div>
         
         <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" onfocus="this.blur()">
           <div class="col-xs-6 title-location text-omit"> <?php echo $gg[0]['title']?></div>
         </a>
         <a href="#" onfocus="this.blur()">
            <div class="col-xs-4 title-more-time">
 
               <?php echo date('Y-m-d',$gg[0]['ctime'])?>
            </div>
         </a>
     </div> 
  </div>
  <div class="panel panel-default content-margin">
    <div id="collapse1" class="panel-collapse collapse in">
      <div class="panel-body">
        <div class="row">
           <div class="col-xs-12 content-text">
           
             <div class="row content-title">
                <font><span> <?php echo $gg[0]['title']?></span></font>
             </div>
             
             <br />
              <?php echo $gg[0]['content']?>
             <hr />

           </div>   
        </div>

        <div class="row">
         <?php if($bool==1){?>
          <div class="col-xs-2 col-xs-offset-7">
            <a href="javascript:void(0)" onClick="edit_gg(<?php echo $gg[0]['id']?>)" onfocus="this.blur()">
                <span class="badge time-badge">编辑</span>
            </a>    
          </div>
          <div class="col-xs-2">
            <a href="javascript:void(0)" onClick="delete_gg(<?php echo $gg[0]['id']?>)" onfocus="this.blur()">
                <span class="badge time-badge">删除</span>
            </a>    
          </div>
          <?php } ?> 
        </div>
        
      </div>
    </div>
  </div>
<!-- 校内公告结束 --> 


</div>

<div class="container-fluid list-container more-list-badge-margin">
  <div class="row">
    <div class="col-xs-12 text-center">
      <a href="<?php echo Url::toRoute(['gonggao/index','openid'=>\yii::$app->view->params['openid'],'sid'=>$sid])?>" onfocus="this.blur()" class="badge more-list-badge gonggao">更多公告</a>
    </div>
  </div>
</div>

</section>
<?php echo $this->render('../layouts/footer')?>
<script type="text/javascript">
  function delete_gg(id){
    var path=$("#path").val();

    var gid = id;
    var url=path+'/gonggao/delete';
    var para={gid: gid};
    var d = dialog({
      title: '提示',
      content: '您确定要删除吗?',
      okValue: '确定',

      ok: function () {
        $.post(url,para,function (data){
          
          if(data.result=='success'){
            var openid=document.getElementById("openid").value;
            var sid=document.getElementById("sid").value;
            window.location.href =path+"/gonggao/index?openid="+openid+"&sid="+sid;
//                    path + "/Gonggao/index/sid/"+sid+"/openid/"+openid;
          }
        });
      },

      cancelValue: '取消',
      cancel: function () {

      }

    });

    d.showModal();

  }


  function edit_gg(id){
    var path = $("#path").val();
    var openid=document.getElementById("openid").value;
    var sid=document.getElementById("sid").value;
    window.location.href = path+"/gonggao/edit?gid="+id+"&openid="+openid+"&sid="+sid;
//            path+"/Gonggao/edit/gid/"+id+"/openid/"+openid+"/sid/"+sid+".html";

  }
   
</script>
<!-- 校内公告 结束 -->

