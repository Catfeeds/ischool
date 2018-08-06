<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\controllers\UtilsController;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '平安通知汇总';
?>
<div class="wp-ischool-class-index">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
<form role="form" class="container" method="get" action="newsbxx">
    <div style="margin: 10px 0;" class="row">

        <?php
        $from_date = isset($dateInfo['from_date'])?$dateInfo['from_date']:"2018-01-01 00:00:00";
        $end_date = isset($dateInfo['to_date'])?$dateInfo['to_date']:date("Y-m-d H:i:s");
        ?>
        <div class="col-md-11 row">
            <?php
            echo  "<div class='col-md-6 row'>";
            echo "<label class='col-md-3' style='line-height: 30px'>开始时间</label>";
            echo DateTimePicker::widget([
                'name' => 'from_date',
                'options' => ['placeholder' => ''],  //注意，该方法更新的时候你需要指定value值
                'value' => $from_date,
                'size'=>100,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss',
                    'todayHighlight' => true
                ]
            ]);
            echo '</div>';
            echo  "<div class='col-md-6 row'>";
            echo "<label class='col-md-3' style='line-height: 30px'>结束时间</label>";
            echo DateTimePicker::widget([
                'name' => 'to_date',
                'options' => ['placeholder' => ''],  //注意，该方法更新的时候你需要指定value值
                'value' => $end_date,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss',
                    'todayHighlight' => true
                ]
            ]);
            echo '</div>';
            //		echo DatePicker::widget([
            //			'name' => 'from_date',
            //			'value' => $from_date,
            //			'type' => DatePicker::TYPE_RANGE,
            //			'name2' => 'to_date',
            //			'value2' => $end_date,
            //			'pluginOptions' => [
            //				'autoclose'=>true,
            //				'format' => 'yyyy-mm-dd'
            //			]
            //			]);
            ?>
        </div>
        <div class="pull-right">
            <?= Html::submitButton("查询",['class'=>"btn btn-success"]) ?>
        </div>

    </div>
</form>

<table class="table table-striped table-hover" id="zhuangtai">

    <?php if(!empty($new)){foreach($new as $key=>$value){  ?>
        <tr class="success" style="cursor:pointer;" data-toggle="collapse" data-target="#demozc<?=$key?>">
            <td><?=$value['school'];?></td><td><?=$value['sid'];?></td><td>次数：(正常次数/不正常次数/总次数)</td></tr>
        <tr id="demozc<?=$key?>">
            <td class="text-success" colspan="3"><?php foreach($value['sbxx'] as $k=>$v){?><?=$v["pa_name"].'（<b class="text-success">'.(!empty($v["zccs"])?$v["zccs"]:0).'</b>/<b class="text-danger">'.($v["zcs"]-$v["zccs"]).'</b>/<b class="text-primary">'.(!empty($v["zcs"])?$v["zcs"]:0).'</b>）/&nbsp;&nbsp;&nbsp;&nbsp;'?><?php } ?></b></td>
        </tr>
    <?php }?>
<tr class="success" style="cursor:pointer;" data-toggle="collapse" data-target="#demozc">
            <td>总汇总</td><td>完好率</td><td>次数：(正常次数/不正常次数/总次数)</td></tr>
<tr id="demozc">
            <td></td><td><?=$news["whl"]?></td><td class="text-success" ><?='<b class="text-success">'.(!empty($news["zccs"])?$news["zccs"]:0).'</b>/<b class="text-danger">'.($news["bzccs"]).'</b>/<b class="text-primary">'.(!empty($news["zcs"])?$news["zcs"]:0).'</b>）/&nbsp;&nbsp;&nbsp;&nbsp;'?></b></td>
        </tr>
<?php

}else{
        echo "<h3>没有该时段信息！</h3>";
        } ?>

</table>