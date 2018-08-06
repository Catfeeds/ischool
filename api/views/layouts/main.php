<?php

/* @var $this \yii\web\View */
/* @var $content string */

use kefu\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?= Html::csrfMetaTags() ?>
    <title>正梵智慧校园</title>
    <?php $this->head() ?>
     <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>

  	<script src="/lib/html5shiv.min.js"></script>
  	<script src="/lib/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
	if(!yii::$app->user->isGuest){
    NavBar::begin([
        'brandLabel' => '正梵智慧校园',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    	$menuItems = [
        ['label' => '学校管理', 'items'=>[
        		['label'=>"学校信息",'url'=>'/school/index'],
        		['label'=>"班级信息",'url'=>'/class/index']
    	]],
    	['label' => '学生管理', 'items'=>[
    			['label'=>"学生管理",'url'=>'/student/index'],
    	]],
    	['label' => '教师管理', 'items'=>[
    			['label'=>"教师信息",'url'=>'/teacher/index'],
    	]],
    	['label' => '家长管理', 'items'=>[
    			['label'=>"家长信息",'url'=>'/parents/index'],
    	]],
    	['label' => '设备状态', 'items'=>[
                ['label'=>"平安通知",'url'=>'/query/newsbzt'],
                ['label'=>"亲情电话",'url'=>'/query/newqqzt'],
                ['label'=>"平安汇总",'url'=>'/query/newsbxx'],
                ['label'=>"亲情汇总",'url'=>'/query/newqqxx'],
    	]],
	['label' => '退出 (' . \Yii::$app->user->identity->username . ')',
			'url' => ['/site/logout'],
			'linkOptions' => ['data-method' => 'post']],
    	];
        
    	echo Nav::widget([
        	'options' => ['class' => 'navbar-nav navbar-right'],
        	'items' => $menuItems,
    	]);
    	NavBar::end();
	}
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        	'homeLink'=>false
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="text-center">&copy; 正梵智慧校园<?= date('Y') ?></p>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
