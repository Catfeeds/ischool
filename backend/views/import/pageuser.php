<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpIschoolSchoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '正梵智慧校园';
?>
<div class="text-center"><h1><?php echo $errorinfo;?></h1></div>
<div id="div1" class="text-center"></div>
<table class="table table-striped" id="zhuangtai">
    <tbody>
    <tr>
        <td>姓名</td>  <td>班级</td> <td>学校</td></tr>
    <?php foreach($chongfu as $key => $value){?>
        <tr>
            <td><?=$value['姓名']?></td>  <td><?=$value['班级']?></td> <td><?=$value['学校']?></td></tr>
    <?php } ?>
    </tbody>

</table>