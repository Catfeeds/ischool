<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolSuggest */

$this->title = $model->title;
?>
<div class="wp-ischool-suggest-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'content:html',
            'outopenid',
        	[
        		'attribute'=>'pmobile',	
        		'value'=>function ($model, $widget) use ($pmodel)
        		{
        			return $pmodel[0]['name']."&nbsp;&nbsp;&nbsp;&nbsp;".$pmodel[0]['tel']?:"不存在";
        		},
			'format'=>"html"
    		],
            'sid',
            'ctime:datetime',
        	[
        		'attribute'=>'pcontent',
        		'value'=>function ($model, $widget) use ($pmodel)
        		{
        			$ret = '';
        			foreach ($pmodel as $row)
        			{
                        $ret.=$row['school']."&nbsp;&nbsp;&nbsp;&nbsp;".$row['class']."&nbsp;&nbsp;&nbsp;&nbsp;".$row['stu_name']."<br/>";                   
        				
        			}
        			return $ret;
        		},
        		'format'=>"html"
    		],
		'note'

        ],
    ]) ?>


</div>
