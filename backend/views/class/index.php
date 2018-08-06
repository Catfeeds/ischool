<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use backend\controllers\UtilsController;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '班级管理';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wp-ischool-class-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?php $form = ActiveForm::begin(["method"=>"get","action"=>"/class/index"]); ?>
    	<?= "学校名称：".html::textInput("school",$searchSchool,['id'=>"sid"]) ?>
    	<?= Html::submitButton("Search", ['class' => 'btn btn-default']) ?>
    	<?php ActiveForm::end(); ?>
        <?= Html::a('创建', ['create'], ['class' => 'btn btn-success pull-right']) ?>
         <?= Html::a('导出', [strpos(\yii::$app->request->url,"?")>-1? \yii::$app->request->url.'&type=export':\yii::$app->request->url.'?type=export'], ['class' => 'btn btn-danger pull-right','target'=>"_blank"]) ?>

    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
        	[ 
        		'attribute'=>'school',
        		'label'=>"学校名称"
        	],
            [
            	'attribute'=>'id',
            	'label'=>"班级ID"
            ],
            [
            	'attribute'=>'name',
            	'label'=>"班级名字"
            ],
            [
            	'attribute'=>'number',
            	"label"=>"人数",
            	"value"=>function ($model)
            	{
            		//$info = UtilsController::getClassCount($model['id']);
            		return $model['number'] ? : 0;
            	}
            ],
            [
            	'attribute'=>'tname',
            	'label'=>"班主任"
            ],
        	[
        		"attribute" => "tel",
        		'label'=>"联系电话"
    		],
            [
            	'class' => 'yii\grid\ActionColumn',
            	'template' => '{update} {delete}'
            		
    		],
        ],
    ]); ?>
</div>
<script type="text/javascript">
<!--
$("#search_button").click(function(){
		window.location.href=""
})
//-->
</script>
