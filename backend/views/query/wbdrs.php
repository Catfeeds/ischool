<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\controllers\UtilsController;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '学生分类信息查询';
//$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
	.input-group .form-control{width: 250px;}
</style>
<div class="wp-ischool-class-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <form role="form" class="container" method="get" action="wbdrs">
    <div style="margin: 10px 0;" class="row">
    <div class="col-md-11 row">
		<div class='col-md-9 row'>
			学校: <input type="text" value="" name="school"  placeholder="请输入学校名字">
				<input id="wbd" type="radio" name="role" value="wbd"/>
				<label for="wbd">未绑定学生 </label>&nbsp;&nbsp;
				<input id="ybd" type="radio" name="role" value="ybd"/>
				<label for="ybd">已绑定学生 </label>&nbsp;&nbsp;
				<input id="wjf" type="radio" name="role" value="wjf"/>
				<label for="wjf">未缴费学生 </label>&nbsp;&nbsp;
				<input id="yjf" type="radio" name="role" value="yjf"/>
				<label for="yjf">已缴费学生 </label>&nbsp;&nbsp;
				<input id="yjfwbd" type="radio" name="role" value="yjfwbd"/>
				<label for="yjfwbd">已缴费未绑定 </label>&nbsp;&nbsp;

		</div>
    </div>
    <div class="pull-right">
    <?= Html::submitButton("查询",['class'=>"btn btn-success"]) ?>
    </div>

    </div>
	</form>
    <p class="container">
    <?= Html::a('导出', [strpos(\yii::$app->request->url,"?")>-1? \yii::$app->request->url.'&type=export':\yii::$app->request->url.'?type=export'], ['class' => 'btn btn-danger pull-right','target'=>"_blank"]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
			[
				'attribute'=>'id',
				'header'=>"学生ID",
			],
			[
				'attribute'=>'name',
				'header'=>"学生姓名",
			],
			[
				'attribute'=>'class',
				'header'=>"班级",
			],
			[
			'attribute'=>'school',
			'header'=>"学校",
			]
		]
    ]); ?>
</div>
