<?php

/* @var $this \yii\web\View */
/* @var $content string */

use kefu\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use kefu\models\Im;
$result=Im::getKefuInfo();
// var_dump($result);die;
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
    <link rel="stylesheet" href="/im/im1.css">
    <script src="//cdn.ronghub.com/RongIMLib-2.3.0.js"></script>
    <script src="//cdn.ronghub.com/RongEmoji-2.2.6.min.js"></script> 
    <script src="/im/libs/utils.js"></script>
    <script src="/im/libs/qiniu-upload.js"></script>
    <script src="/im/template.js"></script>
    <script src="/im/emoji.js"></script>
    <script src="/im/im.js"></script>
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
    			['label'=>"刷卡信息",'url'=>'/safecard/index'],
				['label'=>"补卡信息",'url'=>'/order-check/index']
    	]],
    	['label' => '教师管理', 'items'=>[
    			['label'=>"教师信息",'url'=>'/teacher/index'],
			['label'=>"教师群发",'url'=>'/teacher/grouppage']
    	]],
    	['label' => '家长管理', 'items'=>[
    			['label'=>"家长信息",'url'=>'/parents/index'],
		        ['label'=>"家长群发",'url'=>'/parents/grouppage'],
				['label'=>"分组群发",'url'=>'/parents/groupfenzu'],
    	]],

    	['label'=>"用户管理",'url'=>'/users/index'],
    	['label' => '订单管理', 'items'=>[
    			['label'=>"订单信息",'url'=>'/order/index'],
    	]],
    	['label' => '数据导入', 'url'=>"/import/index"],
    	['label' => '数据查询', 'items'=>[
    			['label'=>"整体信息",'url'=>'/query/index'],
			    ['label'=>"卡库信息",'url'=>'/kaku/index'],
				['label'=>"分类信息",'url'=>'/query/wbdrs'],
			    ['label'=>"意见反馈",'url'=>'/suggest/index'],
                ['label'=>"圈存状态",'url'=>'/customer/qcstatus'],
                ['label'=>"更新班级",'url'=>'/customer/upclass']
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
        //app客服会话入口
        echo '<div id="rcs-app"></div>';
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

<script>

(function(){
    var appKey = "<?= $result['appkey']?>";
    var token = "<?= $result['token']?>";
    RCS.init({
        appKey: appKey,
        token: token,
        target: document.getElementById('rcs-app'),
        showConversitionList: true,
        templates: {
            button: ['<div class="rongcloud-consult rongcloud-im-consult">',
                    '   <button onclick="RCS.showCommon()"><span class="rongcloud-im-icon">APP咨询</span></button>',
                    '</div>',
                    '<div class="customer-service" style="display:none;"></div>'].join('')//"templates/button.html",
        },
        extraInfo: {
            // 当前登陆用户信息
            userInfo: {
                name: "游客",
                grade: "VIP"
            },
            // 产品信息
            requestInfo: {
                productId: "123",
                referrer: "10001",
                define: "" // 自定义信息
            }
        }
    });
})()


</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
