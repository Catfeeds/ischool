<?php

/* @var $this yii\web\View */

$this->title = '正梵智慧校园';
?>
<div class="container-fluid">
  <div class="row page-shadow" id="header">
     <div class="col-xs-7">
     	  <div class="header-home text-omit">
         	<a href="#" onfocus="this.blur()">
         		  <div class="glyphicon glyphicon-home header-icon"></div>
           		<span style="font-size:1.4rem;"><?php echo $ischool; ?></span>
         	</a>
     	  </div>
     </div>

    <?php if($bool == 1) {?>
	     <div class="col-xs-3" onclick="release_input()">
	 	     <div class="header-home">
	 		      <a href="/manager/index?<?php echo \yii::$app->view->params['baseparams']?>" onfocus="this.blur()">
	 			       <span onclick="submit()">管理</span>
	 		      </a>
	 	     </div>
	     </div>
    <?php }else{?>
	     <div class="col-xs-3"></div>
    <?php }?> 	 
    <div class="col-xs-2">
        <a href="#" onfocus="this.blur()">
          <div class="glyphicon glyphicon-qrcode home-qrcode" data-toggle="modal" data-target="#qrcode"></div>
        </a>
    </div>
  </div>
</div>
<!-- 顶部导航结束 -->


<!-- 幻灯片 -->
<div class="banner page-shadow">
    <ul>
    	<?php 
        if (empty($lunbos)){
        ?>
            <li style="background-image:url(/img/1.png)"></li>
            <li style="background-image:url(/img/2.png)"></li>
            <li style="background-image:url(/img/3.png)"></li>
        <?php }
        else {
        	foreach ($lunbos as $key=>$vo){
        ?>
          <li style="background-image:url(<?php echo $vo['picurl']?>)"></li>
        <?php }}?>
    </ul>   
</div>
<!-- 幻灯片结束 -->

<!-- 公告、动态 -->
<section class="section-font-size-home section-home-margin">
<div id="accordion" role="tablist" aria-multiselectable="true">

<?php if(empty($gonggao)) {?>
  <div class="container-fluid list-container">
     <div class="row list-location">
     
         <div class="col-xs-2">
            <span class="badge list-badge gonggao">公告</span>
         </div>
         
         <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" onfocus="this.blur()">
           <div class="col-xs-7 title-location text-omit">暂无相关信息</div>
         </a>
         <a href="/gonggao/add?<?php \yii::$app->view->params['baseparams']?>" onfocus="this.blur()">
            <div class="col-xs-3 title-more-location">
               <span class="glyphicon glyphicon-chevron-right"></span>
            </div>
         </a>
     </div> 
  </div>
  <div class="panel panel-default content-margin">
    <div id="collapse1" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="row">
           <div class="col-xs-12 content-text">
           
             <div class="row content-title">
                <font><span>暂无相关信息</span></font>
             </div>
             
             <br />
             暂无相关信息
             <hr />

           </div>   
        </div>
        <div class="row">
          <div class="col-xs-2 col-xs-offset-8">
             
                <span class="badge time-badge">0000-00-00</span>
         
          </div>
        </div>
      </div>
    </div>
  </div>
<?php }else{?>
  <div class="container-fluid list-container">
     <div class="row list-location">
     
         <div class="col-xs-2">
            <span class="badge list-badge gonggao">公告</span>
         </div>
         
         <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" onfocus="this.blur()">
           <div class="col-xs-7 title-location text-omit"><?php echo $gonggao[0]['title']?></div>
         </a>
         <a href="/gonggao/index" onfocus="this.blur()">
            <div class="col-xs-3 title-more-location">
               <span class="glyphicon glyphicon-chevron-right"></span>
            </div>
         </a>
     </div> 
  </div>
  <div class="panel panel-default content-margin">
    <div id="collapse1" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="row">
           <div class="col-xs-12 content-text">
           
             <div class="row content-title">
                <font><span><?php echo $gonggao[0]['title']?></span></font>
             </div>
             
             <br />
             <?php echo $gonggao[0]['content']?>
             <hr />

           </div>   
        </div>
        <div class="row">
          <div class="col-xs-2 col-xs-offset-8">
             
                <span class="badge time-badge"><?php echo date('Y-m-d',$gonggao[0]['ctime'])?></span>
         
          </div>
        </div>
      </div>
    </div>
  </div>
<?php }?>
<!-- 公告结束 --> 


<?php if(empty($news)) {?>
  <div class="container-fluid list-container">
     <div class="row list-location">
     
         <div class="col-xs-2">
            <span class="badge list-badge huodong">动态</span>
         </div>
         
         <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" onfocus="this.blur()">
           <div class="col-xs-7 title-location text-omit">暂无相关信息</div>
         </a>
         <a href="/schoolnews/index?openid=<?php echo $openid;?>" onfocus="this.blur()">
            <div class="col-xs-3 title-more-location">
               <span class="glyphicon glyphicon-chevron-right"></span>
            </div>
         </a>
     </div> 
  </div>
  <div class="panel panel-default content-margin">
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="row">
           <div class="col-xs-12 content-text">
           
             <div class="row content-title">
                <font><span>暂无相关信息</span></font>
             </div>
             
             <br />
             暂无相关信息
             <hr />

           </div>   
        </div>
        <div class="row">
          <div class="col-xs-2 col-xs-offset-8">
             
                <span class="badge time-badge">0000-00-00</span>
         
          </div>
        </div>
      </div>
    </div>
  </div>
<?php }else {?>
  <div class="container-fluid list-container">
     <div class="row list-location">
     
         <div class="col-xs-2">
            <span class="badge list-badge huodong">动态</span>
         </div>
         
         <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" onfocus="this.blur()">
           <div class="col-xs-7 title-location text-omit"><?php echo $news[0]['title']?></div>
         </a>
         <a href="/schoolnews/index?openid=<?php echo $openid;?>" onfocus="this.blur()">
            <div class="col-xs-3 title-more-location">
               <span class="glyphicon glyphicon-chevron-right"></span>
            </div>
         </a>
     </div> 
  </div>
  <div class="panel panel-default content-margin">
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="row">
           <div class="col-xs-12 content-text">
           
             <div class="row content-title">
                <font><span><?php echo $news[0]['title']?></span></font>
             </div>
             
             <br />
             <?php echo $news[0]['content']?>
             <hr />

           </div>   
        </div>
        <div class="row">
          <div class="col-xs-2 col-xs-offset-8">
             
                <span class="badge time-badge"><?php echo date('Y-m-d',$news[0]['ctime'])?></span>
         
          </div>
        </div>
      </div>
    </div>
  </div>
<?php }?>
<!-- 动态结束 --> 

</div>
</section>



<!-- 学校概况 推荐 -->
<div class="container-fluid hot-rec-container-margin" id="columns">
<?php 
	foreach ($columns as $vo){
	?>
      <div class="row hot-rec-container"> <!-- 学生风采 -->
    <div class="col-xs-12 student-title">
      <div class="school-title">
              <span class="badge"><?php echo $vo['name']?></span>
      </div>
      <?php if($bol == 1) {?>
      <if condition="$bol eq 1">

          <div class="school-get">
            <a href="/homepage/add?cid=<?php echo $vo['cid']?>&type=<?php echo $vo['name']?>&tem=zidingyi">
              <span class="badge">发布</span>
            </a>
            <?php if($vo['sid'] != "") {?>
              <a href="/homepage/edit?id=<?php echo $vo['id']?>&type=<?php echo $vo['name']?>">
                <span class="badge">编辑</span>
              </a>
            <?php }?>
          </div>
      <?php }?>
    </div>
    
    <div class="col-xs-12 hot-rec-center-margin">
      <!-- 内容不为空时 显示下面内容 -->
           <?php if($vo['toppicture'] == '') {?>
             <img alt="加载中..." src="/img/zhengfan.png" class="hot-rec-center-img">
          <?php } else {?>
             <a href="/homepage/des?id=<?php echo $vo['id']?>&type=student&cid=<?php echo $vo['cid']?>" onfocus="this.blur()">
                 <img alt="加载中..." src="<?php echo $vo['toppicture']?>" class="hot-rec-center-img">
             </a>
           <?php }?>
             <?php if($vo['title'] == "") {?>
                <div class="hot-rec-center-title">
                 标题为空
                </div>
            <?php }else {?>
             <a href="/homepage/des?id=<?php echo $vo['id']?>&type=student" onfocus="this.blur()">
             <div class="hot-rec-center-title">
              <?php echo $vo['title']?>
             </div>
             </a>
             <?php }?>
             <?php if($vo['sketch'] == "") {?>
                 <div class="hot-center">
              简介暂时为空  
             </div>
            <?php }else {?>
                <a href="/homepage/des?id=<?php echo $vo['id']?>&type=student" onfocus="this.blur()">
                <div class="hot-center">
              <?php echo $vo['sketch']?>
             </div>
             </a>
            <?php }?>            
        </div>
  </div><!-- 学生风采 结束 -->

    <?php }?>


</div>
<!-- 学校概况 推荐 结束 -->
<div class="container-fluid ">
  <div class="modal fade bs-example-modal-sm" id="qrcode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" data-dismiss="modal">
      <div class="modal-content home-qrcode-modal">
	      <div class="row home-qrcode-waper">
		      <div class="col-xs-12 text-center home-qrcode-margin">
             <img src="<?php echo $pic?>" class="img-rounded">
		      </div>
		      <div class="col-xs-12 text-center home-qrcode-text">
			       扫描二维码关注
		      </div>
	      </div>
      </div>
    </div>
  </div> 
</div>
    
    

<script type="text/javascript">
$(document).ready(function(){
    
    var columns_colors = new Array();
    columns_colors[0] = "#FF6666";
    columns_colors[1] = "#3498db";
    columns_colors[2] = "#2ecc71";

    var columns_num = $("#columns").find(".student-title").length;
    if( columns_num < 1 ){
        
    }else{
        for(var a = 0 ; a < columns_num ; a++){
            var b = a % 3;
            $("#columns").find(".student-title").eq(a).css("background-color",columns_colors[b]);
            $("#columns").find(".student-title").eq(a).find("span").css("color",columns_colors[b]);
        }
    }

});


</script>





