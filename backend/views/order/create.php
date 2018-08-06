<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolOrder */

$this->title = 'Create Wp Ischool Order';
$this->params['breadcrumbs'][] = ['label' => 'Wp Ischool Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-ischool-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
