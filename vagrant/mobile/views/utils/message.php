<?php

/* @var $this \yii\web\View */
/* @var $content string */

use mobile\assets\AppAsset;
use yii\helpers\Html;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
    <?= Html::csrfMetaTags() ?>
    <title>正梵智慧校园</title>
    <?php $this->head() ?>
        <link media="all" rel="stylesheet" type="text/css" href="/css/home-css.css" />
	<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
	<link media="all" rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css" />
	<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
</head>
<body class="body-color">
<h2 style="text-align: center">
<?= $message ?>
</h2>

</body>

</html>
<?php $this->endPage() ?>
