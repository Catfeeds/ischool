<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolPastudent */

$this->title = 'Create Wp Ischool Pastudent';
$this->params['breadcrumbs'][] = ['label' => 'Wp Ischool Pastudents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-ischool-pastudent-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
