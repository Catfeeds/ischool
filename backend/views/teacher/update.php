<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolTeacher */

$this->title = '更新老师';
$this->params['breadcrumbs'][] = ['label' => '教师管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '更新老师';
?>
<div class="wp-ischool-teacher-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
