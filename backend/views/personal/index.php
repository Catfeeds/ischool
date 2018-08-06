<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PersonalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Wp Ischool Pastudents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-ischool-pastudent-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Wp Ischool Pastudent', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'openid',
            'ctime:datetime',
            'stu_id',
            // 'school',
            // 'cid',
            // 'class',
            // 'tel',
            // 'stu_name',
            // 'ispass',
            // 'email:email',
            // 'sid',
            // 'Relation',
            // 'isqqtel',
            // 'is_deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
