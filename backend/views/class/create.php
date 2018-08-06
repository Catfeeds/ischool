<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolClass */

$this->title = '新建班级';
$this->params['breadcrumbs'][] = ['label' => '班级信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-ischool-class-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
