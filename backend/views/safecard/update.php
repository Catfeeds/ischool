<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolSafecard */

$this->title = 'Update Wp Ischool Safecard: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Wp Ischool Safecards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wp-ischool-safecard-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
