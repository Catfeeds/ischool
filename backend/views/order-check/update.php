<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolOrderck */

$this->title = '更新学生补卡信息 ';
$this->params['breadcrumbs'][] = ['label' => '学校管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '学校更新';
//$this->params['breadcrumbs'][] = ['label' => 'Wp Ischool Ordercks', 'url' => ['index']];
?>
<div class="wp-ischool-orderck-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
