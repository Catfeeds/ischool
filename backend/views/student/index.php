<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpIschoolStudentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '学生信息';

?>
<div class="wp-ischool-student-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新建', ['create'], ['class' => 'btn btn-success pull-right']) ?>
        <?= Html::a('导出', [strpos(\yii::$app->request->url,"?")>-1? \yii::$app->request->url.'&type=export':\yii::$app->request->url.'?type=export'], ['class' => 'btn btn-info pull-right','target'=>"_blank"]) ?>
        <?= Html::a('批量更新', ['batchedit?'.\yii::$app->request->queryString], ['class' => 'btn btn-danger pull-right','target'=>"_blank"]) ?>
        
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'stuno2',
            'school',
            'class',
            'cardid',
        	[
        		"attribute"=>"card_no",
        		"value"=>'card.card_no',
        		"label"=>"电话卡"
    		],
        	[
        		"attribute"=>'enddatepa',
        		"format"=>"date",
        		"label"=>"平安通知有效期"
        	],
			[
				"attribute"=>'enddateqq',
				"format"=>"date",
				"label"=>"亲情电话有效期"
			],
			[
				"attribute"=>'enddatejx',
				"format"=>"date",
				"label"=>"家校沟通有效期"
			],
			[
				"attribute"=>'enddateck',
				"format"=>"date",
				"label"=>"餐卡有效期"
			],
			[
                'label'=>'二维码',
                'format' =>['raw'],
                'value' => function($model){
                    return Html::img($model->img,[
                        'height' =>50,
                        'width' => 50,
                    ]);
                }
			],
            [
            		'header' => "操作",
            		'class' => 'yii\grid\ActionColumn',
            		'template' => '{update}	{delete}  {binding}',
            		'buttons'=>[
            			"binding"=>	function ($url, $model, $key)
            			{
        					return \yii\bootstrap\Html::a("Bind",Url::to("/student/bind?id=".$model->id));
        				}
        			]
        	],
        ],
    ]); ?>
</div>
<script>
	$(function(){
		$("a[title='更新']").each(function(){
			$(this).attr("target","_blank");
			console.log($(this).attr("href"));
		})
	})
</script>