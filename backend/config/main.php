<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
	'defaultRoute'=>'/school/index',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        /*'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],*/
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
    	'formatter' => [
    		'dateFormat' => 'php:Y-m-d',
    		'datetimeFormat' => 'php:Y-m-d H:i:s',
    		'timeFormat' => 'php:H:i:s',
    	],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        
    ],
    'params' => $params,
	'modules' => [
				'rbac' => 'dektrium\rbac\RbacWebModule',
                'user' => [
                        'class' => 'dektrium\user\Module',
                        'admins' => ['lee'],
                        'enableRegistration'=>false,
                        'enablePasswordRecovery'=>false,
                        'enableConfirmation'=>false,
                        // you will configure your module inside this file
                        // or if need different configuration for frontend and backend you may
                        // configure in needed configs
                ],

	],
];
