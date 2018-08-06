<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpIschoolStudentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '批量更新';
$this->params['breadcrumbs'][] = ['label' => "学生管理", 'url' => ['index']];
$this->params['breadcrumbs'][] = '批量更新';
?>

<div class="wp-ischool-student-update ">
<h3 style="color: red" class="text-center">请仔细核对更新的条件！！！选择日期，提交即可！！！</h3>
<div>

</div>
<?php echo Html::beginForm("/student/batchupdate?".$querystring,"post") ?>
<!--  
<div class="row">
<div class="col-md-3 text-right"><h3>ID</h3></div><div class="col-md-3 text-left"><h3></h3></div>

</div>
-->

<div>
<?php
    echo DatePicker::widget(
  [
    'name' => 'enddatepa',
    'options' => ['placeholder' => '请选择平安通知截止日期'],
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd'
    ]
  ]);
echo DatePicker::widget(
    [
        'name' => 'enddateqq',
        'options' => ['placeholder' => '请选择亲情电话截止日期'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
echo DatePicker::widget(
    [
        'name' => 'enddatejx',
        'options' => ['placeholder' => '请选择家校沟通截止日期'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);

echo DatePicker::widget(
    [
        'name' => 'enddateck',
        'options' => ['placeholder' => '请选择餐卡充值截止日期'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]
);
?>
</div>
<div class="text-center">
<button class="btn btn-danger" type="submit">提交</button>
</div>

<?php echo Html::endForm() ?>
</div>



