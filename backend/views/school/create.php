<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolSchool */

$this->title = '新增学校';

$this->params['breadcrumbs'][] = ['label' => '学校管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-ischool-school-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
