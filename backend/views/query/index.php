<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\controllers\UtilsController;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '学校信息';
//$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
	.input-group .form-control{width: 250px;}
</style>
<div class="wp-ischool-class-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <form role="form" class="container" method="get" action="index">
    <div style="margin: 10px 0;" class="row">

	<?php 
	$from_date = isset($dateInfo['from_date'])?$dateInfo['from_date']:"2017-01-01 00:00:00";
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
    <p class="container">
    <?= Html::a('导出', [strpos(\yii::$app->request->url,"?")>-1? \yii::$app->request->url.'&type=export':\yii::$app->request->url.'?type=export'], ['class' => 'btn btn-danger pull-right','target'=>"_blank"]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
        	[ 
        		'attribute'=>'name',
        		'label'=>"学校名称"
        	],
            [
            	'attribute'=>'snum',
            	'label'=>"总人数"
            ],
            [
            	'attribute'=>'bnum',
            	'label'=>"绑定数量"
            ],
 
            [
            	'attribute'=>'brate',
            	'label'=>"绑定率",
            	'format'=>['percent','2']
            ],
        	[
        		"attribute" => "mnumpa",
        		'label'=>"平安通知缴费数量"
    		],
        	[
        		"attribute" => "mratepa",
        		'label'=>"平安通知缴费率",
        		'format'=>['percent','2']
        	],
			[
				"attribute" => "mnumjx",
				'label'=>"家校沟通缴费数量"
			],
			[
				"attribute" => "mratejx",
				'label'=>"家校沟通缴费率",
				'format'=>['percent','2']
			],
			[
				"attribute" => "mnumqq",
				'label'=>"亲情电话缴费数量"
			],
			[
				"attribute" => "mrateqq",
				'label'=>"亲情电话缴费率",
				'format'=>['percent','2']
			],
			[
				"attribute" => "mnumck",
				'label'=>"餐卡缴费数量"
			],
			[
				"attribute" => "mrateck",
				'label'=>"餐卡缴费率",
				'format'=>['percent','2']
			],
        	[
        		"attribute" => "cnum",
        		'label'=>"平安卡使用数量"
        	],
        	[
        		"attribute" => "crate",
        		'label'=>"使用率",
        		'format'=>['percent','2']
        	],
            [
            	'header'=>"操作",
            	'class' => 'yii\grid\ActionColumn',
            	'template' => '{local_school} {local_card} {local_fee} {local_bind} {local_contact}',
            	'buttons' => [
            		"local_school"=> function ($url, $model, $key)
            		{
            			return \yii\bootstrap\Html::a("本校班级",Url::to("/query/class?sid=".$model['id']));
            		},
            		"local_card"=>	function ($url, $model, $key)
            		{
            			return \yii\bootstrap\Html::a("本校平安",Url::to("/query/safecard?sid=".$model['id']));
            		},
            		"local_fee"=>	function ($url, $model, $key)
            		{
            			return \yii\bootstrap\Html::a("本校缴费",Url::to("/query/fee?sid=".$model['id']));
            		},
            		"local_bind"=>	function ($url, $model, $key)
            		{
            			return \yii\bootstrap\Html::a("本校绑定",Url::to("/query/bind?sid=".$model['id']));
            		},
            		"local_contact"=>	function ($url, $model, $key)
            		{
            			return \yii\bootstrap\Html::a("本校家校沟通",Url::to("/query/connect?sid=".$model['id']));
            		}
    			]
            		
    		],
        ],
    ]); ?>
</div>

<script>

    var school_id = <?=\yii::$app->view->params['schoolid']?>;
    if (school_id == 0) {
        var tr = $("<tr><td>所有学校汇总信息</td><td><?= $dataProvider2->allModels[0]['snum']?></td><td><?= $dataProvider2->allModels[0]['bnum']?></td><td><?= ($dataProvider2->allModels[0]['brate']*100)."%"; ?></td><td><?= $dataProvider2->allModels[0]['mnumpa']?></td><td><?= ($dataProvider2->allModels[0]['mratepa']*100)."%";?></td><td><?= $dataProvider2->allModels[0]['mnumjx']?></td><td><?= ($dataProvider2->allModels[0]['mratejx']*100)."%";?></td><td><?= $dataProvider2->allModels[0]['mnumqq']?></td><td><?= ($dataProvider2->allModels[0]['mrateqq']*100)."%";?></td><td><?= $dataProvider2->allModels[0]['mnumck']?></td><td><?= ($dataProvider2->allModels[0]['mrateck']*100)."%";?></td><td><?= $dataProvider2->allModels[0]['cnum']?></td><td><?= ($dataProvider2->allModels[0]['crate']*100)."%";?></td></tr>")
        $(".table tbody").append(tr);
    }
</script>