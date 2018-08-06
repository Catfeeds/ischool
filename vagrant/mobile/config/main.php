<?php
$params = array_merge(
	require (__DIR__ . '/../../common/config/params.php'),
	require (__DIR__ . '/../../common/config/params-local.php'),
	require (__DIR__ . '/params.php'),
	require (__DIR__ . '/params-local.php')
);

return [
	'id' => 'app-mobile',
	'defaultRoute' => 'homepage/index',
	'basePath' => dirname(__DIR__),
	'controllerNamespace' => 'mobile\controllers',
	'bootstrap' => ['log'],
	'modules' => [],
	'components' => [
		'request' => [
			'csrfParam' => '_csrf-mobile',
		],
		'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,

		],
		'session' => [
			'name' => 'advanced-mobile',
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'errorHandler' => [
			'errorAction' => 'homepage/error',
		],

		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
                   
			'rules' => [
			],
		],

	],
	'params' => $params,
];
