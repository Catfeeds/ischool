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

<img src="<?php echo $toppicture?:"/img/zhengfan.png"?>">

</div>
</div>
</div>
<div class="row">
<div class="col-xs-12 des-info-title-top">
<span class="badge des-info-title">详情内容</span>
<hr class="des-hr">
</div>
<div class="col-xs-12 des-info-content">
<?php echo $content?>
</div>
</div>
</div>

</section>