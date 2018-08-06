<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolUser */

$this->params['breadcrumbs'][] = ['label' => "用户管理", 'url' => ['index']];
$this->params['breadcrumbs'][] = '更新用户';
?>
<div class="wp-ischool-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
