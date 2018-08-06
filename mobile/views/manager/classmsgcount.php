<link media="all" rel="stylesheet" type="text/css" href="__PUBLIC__/eMall/css/font-awesome.min.css" />
<style>
.select_span{
	background-color: #776e71;
}
.header{
	background-color: #000;
	height: 50px;
	color: #fff;
	padding: 17px 0 0 0;
	margin-top: 1px;
}
.div_row{
	height: 40px;
	margin-bottom: 5px;
	padding: 12px 0 0 0;
}
.foot{
	padding: 5px;
	margin-bottom: 40px;
}

</style>
<div class="row header">

<div class="col-xs-2 col-xs-offset-1 text-align-l">
<div onclick="loadHtmlByUrl('/manager/gotoallclass')">
<i class="fa fa-reply"></i>
</div>
</div>

<div class="col-xs-3 text-align-l">
<?php echo $class ?>
</div>

</div>

<div class="row inout-with-list">
<div class="col-xs-4 col-xs-offset-3">共发送公告:<?php echo $ggcount ?>次</div>

</div>

<div class="row inout-with-list">
<div class="col-xs-12 col-xs-offset-3">共发送留言:<?php echo $lycount?>次</div>

</div>


