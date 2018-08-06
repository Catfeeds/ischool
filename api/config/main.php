<?php
$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . '/../../common/config/params-local.php',
	require __DIR__ . '/params.php',
	require __DIR__ . '/params-local.php'
);

return [
	'id' => 'app-api',
	'basePath' => dirname(__DIR__),
	'controllerNamespace' => 'api\controllers',
	 // 'defaultRoute'=>'/apiuser/index',
	'bootstrap' => ['log'],
	'modules' => [ 'rbac' => 'dektrium\rbac\RbacWebModule',],
	'components' => [
		'request' => [
			'csrfParam' => '_csrf-api',
		],
		'user' => [
			'identityClass' => 'common\models\Apiuser',
			'enableAutoLogin' => true,
            'enableSession'=>true,	
			'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
		],
		'session' => [
			// this is the name of the session cookie used for login on the backend
			'name' => 'advanced-api',
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
			'errorAction' => 'site/error',
		],
/*        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],*/

		'urlManager' => [
			'enablePrettyUrl' => true,
//            'enableStrictParsing' => true,
			'showScriptName' => false,
			'rules' => [
			    // ['class' =>'yii\rest\UrlRule',
       //              'controller'=>['apiuser'],
//                    'except' =>['delete','create','update','index'],
//                    'pluralize' => false,
                // ],
			],
		],
	],
	'params' => $params,
];
