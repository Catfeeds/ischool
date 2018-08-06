<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\controllers\UtilsController;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpIschoolSchoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '学校管理';

?>
<div class="wp-ischool-school-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('新建', ['create'], ['class' => 'btn btn-success pull-right']) ?>
        
        <?= Html::a('导出', [strpos(\yii::$app->request->url,"?")>-1? \yii::$app->request->url.'&type=export':\yii::$app->request->url.'?type=export'], ['class' => 'btn btn-danger pull-right','target'=>"_blank"]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
        	'id',
            'pro',
            'city',
            'county',
        	[
        		'attribute'=>'schtype',
        		'filter'=>UtilsController::getSchoolTypes()	
    		],
            [
            	'class' => 'yii\grid\ActionColumn',
            	'template' => '{update} {delete}',
            		
    		],
        ],
    ]); ?>
</div>
