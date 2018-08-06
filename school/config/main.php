<?php
$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . '/../../common/config/params-local.php',
	require __DIR__ . '/params.php',
	require __DIR__ . '/params-local.php'
);

return [
	'id' => 'app-kefu',
	'basePath' => dirname(__DIR__),
	'controllerNamespace' => 'school\controllers',
	'controllerMap'=>[  
        	'query'=>['class'=>'backend\controllers\QueryController'],
		'class'=>['class'=>'backend\controllers\ClassController'],
		'import'=>['class'=>'backend\controllers\ImportController'],
		'kaku'=>['class'=>'backend\controllers\KakuController'],
		'order-check'=>['class'=>'backend\controllers\OrderCheckController'],
		'order'=>['class'=>'backend\controllers\OrderController'],
		'parents'=>['class'=>'backend\controllers\ParentsController'],
		'safecard'=>['class'=>'backend\controllers\SafecardController'],
		'school'=>['class'=>'backend\controllers\SchoolController'],
		'student'=>['class'=>'backend\controllers\StudentController'],
		'suggest'=>['class'=>'backend\controllers\SuggestController'],
		'teacher'=>['class'=>'backend\controllers\TeacherController'],
		'users'=>['class'=>'backend\controllers\UsersController'],
        ],  
	'defaultRoute'=>'/query/index',
	'bootstrap' => ['log'],
	'modules' => [ 'rbac' => 'dektrium\rbac\RbacWebModule',],
	'components' => [
		'request' => [
			'csrfParam' => '_csrf-school',
		],
		'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie' => ['name' => '_identity-school', 'httpOnly' => true],
		],
		'session' => [
			// this is the name of the session cookie used for login on the backend
			'name' => 'advanced-school',
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
				'user/login'=>'site/login'
			],
		],

	],
	'params' => $params,
];
