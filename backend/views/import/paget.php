<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpIschoolSchoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '正梵掌上学校';
?>
<div class="text-center"><h1><?php echo $errorinfo;?></h1></div>
<div id="div1" class="text-center"></div>
<table class="table table-striped" id="zhuangtai">
    <tbody>
    <tr>
        <td>学号</td>  <td>epc号</td> <td>电话卡号</td></tr>
    <?php foreach($chongfu as $key => $value){?>
        <tr>
            <td><?=$value['学号']?></td>  <td><?=$value['epc号']?></td> <td><?=$value['电话卡号']?></td></tr>
<?php } ?>
    </tbody>

</table>