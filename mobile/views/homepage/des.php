<div class="container-fluid">
<div class="row page-shadow" id="header">

<div class="col-xs-10">
<div class="header-home text-omit">
<a href="#" onfocus="this.blur()">
<div class="glyphicon glyphicon-home header-icon"></div>
<span><?= $ischool ?></span>
</a>
</div>
</div>

</div>
</div>
<!-- 顶部导航结束 -->


<!-- 详情 -->
<section class="section-font-size des-waper-margin">
<div class="container-fluid des-waper">
<div class="row">
<div class="col-xs-12 text-center">
<div class="des-title"><?= $title ?></div>
</div>
<div class="col-xs-12">
<span class="badge des-jianjie-title">
简介
</span>
<hr class="des-hr">
<div class="des-jianjie">
<?php echo $sketch?>
</div>
</div>
</div>
<div class="row file-bg">
<div class="col-xs-12">
<div class="add-img">

<a href="###" data-toggle="modal" data-target="#ytShow"><img src="<?php echo $toppicture?:"/img/zhengfan.png"?>"></a>

</div>
</div>
</div>
<div class="row">
<div class="col-xs-12 des-info-title-top">
<span class="badge des-info-title">详情内容</span>
<hr class="des-hr">
</div>
<!--原图模态-->
<div class="modal fade" id="ytShow" tbindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
	  &times;
        </button>
        <h4>原图<span style="font-weight:normal;">(可长按保存本地查看)</span></h4>
      </div>
      <div style="width:100%;max-height:350px;overflow:auto;" class="modal-body">
        <img style="width:none;max-width:none;" src="<?php echo $toppicture?:"/img/zhengfan.png"?>">
      </div>
    </div>
  </div>
</div>
<div class="col-xs-12 des-info-content" id="changeImage">
<?php echo $content?>
</div>
</div>
</div>
<!--原图模态-->
<div class="modal fade" id="cngShow" tbindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
          &times;
        </button>
        <h4>原图<span style="font-weight:normal;">(可长按保存本地查看)</span></h4>
      </div>
      <div style="width:100%;max-height:350px;overflow:auto;" class="modal-body">
        <img class="cngShow" style="width:none;max-width:none;display:block;">
      </div>
      <script>
        var imgSrc=$("#changeImage").find("img");
        imgSrc.attr("data-toggle","");
        imgSrc.attr("data-target","");
        imgSrc.each(function(index){
          $(this).click(function(){
            //alert(index)
            imgSrc.eq(index).attr("data-target","#cngShow");
            imgSrc.eq(index).attr("data-toggle","modal");
            var cngSrc=imgSrc.eq(index).attr("src");
            $(".cngShow").eq(0).prop("src",cngSrc);
          })
        }) 
      </script>
    </div>
  </div>
</div>

</section>
<script>
  //var imgSrc=$("#changeImage").find("img").attr("src");
  //alert(imgSrc);
</script>
