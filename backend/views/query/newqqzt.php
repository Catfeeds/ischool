<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\controllers\UtilsController;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '亲情话机状态';
//$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div id="myCollapsibleExample"><a href="#demo" data-toggle="collapse">点击我扩展并且再次点击我折叠。</a></div>-->
<!--<div id="demo" class="collapse">-->
<!--    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehe.-->
<!--</div>-->


<div class="wp-ischool-class-index">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
<table class="table table-striped table-hover" id="zhuangtai">
<?php if(!empty($bzc)){ foreach($bzc as $key=>$value){ ?>
    <tr style="cursor:pointer;" class="alert alert-warning" data-toggle="collapse" data-target="#demob<?=$key?>"><td><?=$value['sname'];?></td><td><?=$value['sid'];?></td><td>不正常</td></tr>
<!--    class=" collapse" 默认闭合-->
    <tr  id="demob<?=$key?>"><td class="text-danger" colspan="3"><b><?php foreach($value['pingan_bzcid'] as $k=>$v){?><?=$v.'号机 / '?><?php } ?></b></td>
    </tr>
<?php }} ?>
<?php if(!empty($zc['bfzc'])){ foreach($zc['bfzc'] as $key=>$value){ ?>
    <tr style="cursor:pointer;" class="alert alert-warning" data-toggle="collapse" data-target="#demo<?=$key?>"><td><?=$value['sname'];?></td><td><?=$value['sid'];?></td><td>部分正常</td></tr>
    <tr id="demo<?=$key?>"><td class="text-danger" colspan="3"><b><?php foreach($value['pingan_bzcid'] as $k=>$v){?><?=$v.'号机 / '?><?php } ?></b>
            <b class="text-success"><?php foreach($value['pingan_zcid'] as $k=>$v){?><?=$v.'号机 / '?><?php } ?></b>
        </td>
<!--        <td class="text-success"> --><?php //foreach($value['pingan_zcid'] as $k=>$v){?><!----><?//=$v.'---'?><!----><?php //} ?><!--</td>-->
    </tr>
<?php }} ?>

<?php  if(!empty($zc['zc'])){foreach($zc['zc'] as $key=>$value){ ?>
    <tr class="success" style="cursor:pointer;" data-toggle="collapse" data-target="#demozc<?=$key?>"><td><?=$value['sname'];?></td><td><?=$value['sid'];?></td><td>正常</td></tr>
    <tr  id="demozc<?=$key?>">
        <td class="text-danger" colspan="3"><b class="text-success"><?php foreach($value['pingan_id'] as $k=>$v){?><?=$v.'号机/ '?><?php } ?></b></td>
    </tr>
<?php }} ?>

    <?php if(!empty($zc['zcmwz'])){foreach($zc['zcmwz'] as $key=>$value){ ?>
        <tr class="success"><td><?=$value['sname'];?><a style="color: red">（暂时没有位置信息）</a></td><td><?=$value['sid'];?></td><td>正常</td></tr>
    <?php }} ?>
    <tr><td></td><td></td><td></td></tr>
</table>