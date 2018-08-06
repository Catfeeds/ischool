<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolOrderck */

$this->title = 'Create Wp Ischool Orderck';
$this->params['breadcrumbs'][] = ['label' => 'Wp Ischool Ordercks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-ischool-orderck-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
