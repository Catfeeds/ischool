<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolSuggest */

$this->title = '信息更新';
?>
<div class="wp-ischool-suggest-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
