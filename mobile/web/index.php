<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'dev');

defined('URL_PATH') or define('URL_PATH','http://mobile.jxqwt.cn');
defined('UPLOAD_PATH') or define('UPLOAD_PATH', '.');
defined('APP_NAME') or define('APP_NAME', '正梵掌上学校');
defined('ICARD_NAME') or define('ICARD_NAME', '平安通知');
defined('TONGZHI_NAME') or define('TONGZHI_NAME', '家校沟通');
defined('HOME_PAGE') or define('HOME_PAGE', '正梵掌上学校');
defined('APPID') or define('APPID', 'wx8c6755d40004036d');      //智慧校园appid
defined('APPSECRET') or define('APPSECRET', '22f68f4da5b36641ed492c596406b75f');//智慧校园APPSECRET
defined('SGAPPID') or define('SGAPPID', 'wxc5c7e311f8d5d759');      //许昌三高appid
defined('SGAPPSECRET') or define('SGAPPSECRET', 'e6ccb6b6817cfe5e9c58bc360b0a05b7');//许昌三高APPSECRET

defined('PayNotify') or define('PayNotify', 20);//支付提醒期限
defined('URL_IP') or define('URL_IP', 'ttp://122.114.51.145:888'); //发布tornado项目的服务器地址，用于即时请假
error_reporting(0);
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

(new yii\web\Application($config))->run();

