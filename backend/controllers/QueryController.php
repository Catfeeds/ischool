<?php

namespace backend\controllers;
use \yii\data\ArrayDataProvider;
use backend\models\WpIschoolQuery;
use yii\web\ForbiddenHttpException;
use yii\helpers\ArrayHelper;
use backend\models\WXSendMsg;
use yii;
class QueryController extends \yii\web\Controller
{
	public function beforeAction($action)
	{
	$this->viewPath = '@backend/views/query';
        \yii::$app->view->params['schoolid'] = \yii::$app->user->getIdentity()["school_id"];
		if (Yii::$app->user->isGuest) return $this->redirect("/user/login")->send();
		if (\yii::$app->user->getId() == \yii::$app->params['hook_id']) return true;
		if (parent::beforeAction($action)) {
			$permission = \yii::$app->controller->route;
			if (Yii::$app->user->can($permission)) {
				return true;
			}
			else
				throw new ForbiddenHttpException();
		} else {
			throw new ForbiddenHttpException();
		}
	}
    public function actionIndex()
    {
	$allSchoolInfo = WpIschoolQuery::getSchool(\Yii::$app->request->queryParams);
    	$dataProvider = new ArrayDataProvider([
    			'allModels' => $allSchoolInfo,
    			'key'=>"id"
    	]);
        $dataProvider2 = new ArrayDataProvider([
            'allModels' => WpIschoolQuery::gettongji(\Yii::$app->request->queryParams),
        ]);
    	if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
    	{
			$array_values =  [
				[
					'attribute'=>'name',
					'header'=>"学校名称",
				],
				[
					'attribute'=>'snum',
					'header'=>"总人数",
					'value'=>function($model){
						return 	isset($model['snum'])?$model['snum']:"0";
					}
				],
				[
					'attribute'=>'bnum',
					'header'=>"绑定数",
					'value'=>function($model){
						return 	isset($model['bnum'])?$model['bnum']:"0";
					}
				],
				[
					'attribute'=>'brate',
					'header'=>"绑定率",
					'format'=>['percent','2'],
					'value'=>function($model){
						return 	isset($model['brate'])?$model['brate']:"0";
					}
				],
				[
					'attribute'=>'mnumpa',
					'header'=>"平安通知缴费数量",
					'value'=>function($model){
						return 	isset($model['mnumpa'])?$model['mnumpa']:"0";
					}
				],
				[
					'attribute'=>'mratepa',
					'header'=>"平安通知缴费率",
					'format'=>['percent','2'],
					'value'=>function($model){
						return 	isset($model['mratepa'])?$model['mratepa']:"0";
					}
				],
				[
					'attribute'=>'mnumjx',
					'header'=>"家校沟通缴费数量",
					'value'=>function($model){
						return 	isset($model['mnumjx'])?$model['mnumjx']:"0";
					}
				],
				[
					'attribute'=>'mratejx',
					'header'=>"家校沟通缴费率",
					'format'=>['percent','2'],
					'value'=>function($model){
						return 	isset($model['mratejx'])?$model['mratejx']:"0";
					}
				],
				[
					'attribute'=>'mnumqq',
					'header'=>"亲情电话缴费数量",
					'value'=>function($model){
						return 	isset($model['mnumqq'])?$model['mnumqq']:"0";
					}
				],
				[
					'attribute'=>'mrateqq',
					'header'=>"亲情电话缴费率",
					'format'=>['percent','2'],
					'value'=>function($model){
						return 	isset($model['mrateqq'])?$model['mrateqq']:"0";
					}
				],
				[
					'attribute'=>'mnumck',
					'header'=>"餐卡缴费数量",
					'value'=>function($model){
						return 	isset($model['mnumck'])?$model['mnumck']:"0";
					}
				],
				[
					'attribute'=>'mrateck',
					'header'=>"餐卡缴费率",
					'format'=>['percent','2'],
					'value'=>function($model){
						return 	isset($model['mrateck'])?$model['mrateck']:"0";
					}
				],
				[
					'attribute'=>'cnum',
					'header'=>"平安卡使用数量",
					'value'=>function($model){
						return 	isset($model['cnum'])?$model['cnum']:"0";
					}
				],
				[
					'attribute'=>'crate',
					'header'=>"使用率",
					'format'=>['percent','2'],
					'value'=>function($model){
						return 	isset($model['crate'])?$model['crate']:"0";
					}
				],
			];
    		\moonland\phpexcel\Excel::export([
    				'models' => $dataProvider->allModels,
                    'models2' => $dataProvider2->allModels,
					'columns' => $array_values,
    				'fileName' => "all.xlsx"
    		]);
    	}else
    		return $this->render('index', [
    				'dataProvider' => $dataProvider,
                    'dataProvider2' => $dataProvider2,
    				'dateInfo' => \Yii::$app->request->queryParams
    		]);
    	

    }
    public function actionClass($sid)
    {
    	$dataProvider = new ArrayDataProvider([
    			'allModels' => WpIschoolQuery::getClass($sid),
    			"key"=>"id"
    	]);
    	 
    	$array_values = [
        		[
        			'attribute'=>'name',
        			'header'=>"班级"
        		],
        		[
        			'attribute'=>'cnum',
        			'header'=>"班级总人数"
        		],
        		[
        			'attribute'=>'bnum',
        			'header'=>"绑定数量"
        		],
        		[
        			'attribute'=>'brate',
        			'header'=>"绑定率",
					'format'=>['percent','2']
        		],
        		[
        			'attribute'=>'mnumpa',
        			'header'=>"平安通知缴费数量"
        		],
        		[
        			'attribute'=>'mratepa',
        			'header'=>"平安通知缴费率",
					'format'=>['percent','2'],
        		],
				[
					'attribute'=>'mnumjx',
					'header'=>"家校沟通缴费数量"
				],
				[
					'attribute'=>'mratejx',
					'header'=>"家校沟通缴费率",
					'format'=>['percent','2'],
				],
				[
					'attribute'=>'mnumqq',
					'header'=>"亲情电话缴费数量"
				],
				[
					'attribute'=>'mrateqq',
					'header'=>"亲情电话缴费率",
					'format'=>['percent','2'],
				],
				[
					'attribute'=>'mnumck',
					'header'=>"餐卡缴费数量"
				],
				[
					'attribute'=>'mrateck',
					'header'=>"餐卡缴费率",
					'format'=>['percent','2'],
				],
        		[
        			'attribute'=>'dnum',
        			'header'=>"平安通知数量"
        		],
        		[
        			'attribute'=>'drate',
        			'header'=>"使用率",
					'format'=>['percent','2'],
        		],
    ];
    	if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
    	{
    		\moonland\phpexcel\Excel::export([
    				'models' => $dataProvider->allModels,
    				'columns' => $array_values,
    				'fileName' => "local-school.xlsx"
    		]);
    	}else
    	return $this->render('class', [
    			'dataProvider' => $dataProvider,
    			'array_columns' => $array_values
    	]);
    }
    public function actionSafecard($sid)
    {
    	$dataProvider = new ArrayDataProvider([
    			'allModels' => WpIschoolQuery::getSafecard($sid),
    			'key' => "id"
    	]);
    	$array_values = [[
    			'attribute'=>'name',
    			'header'=>"学校名称"
    	],
    			[
    					'attribute'=>'class',
    					'header'=>"班级名称"
    			],
    			[
    					'attribute'=>'stuName',
    					'header'=>"学生姓名"
    			],
    			[
    					'attribute'=>'ctime',
    					'header'=>"刷卡时间",
    					'value'=>function($model){
    						return $model['ctime']?date("Y-m-d H:i:s",$model['ctime']):"";
    						}
    			],
    			[
    					'attribute'=>'info',
    					'header'=>"状态",
    					'value'=>function($model){
    							return $model['info']?:"";
    					}
    			]];
    	if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
    	{
    		\moonland\phpexcel\Excel::export([
    				'models' => $dataProvider->allModels,
    				'columns' => $array_values,
    				'fileName' => "local-school.xlsx"
    		]);
    	}else
    	return $this->render('safecard', [
    			'dataProvider' => $dataProvider,
    			'array_columns' =>$array_values
    	]);
    }
    public function actionFee($sid)
    {
    	$array_values = [
        		[
        			'attribute'=>'name',
        			'header'=>"学校名称"
        		],
        		[
        			'attribute'=>'class',
        			'header'=>"班级名称"
        		],
        		[
        			'attribute'=>'stuName',
        			'header'=>"学生姓名"
        		],
        		[
        			'attribute'=>'upendtimepa',
        			'format'=>"datetime",
        			'header'=>"平安通知缴费时间"
        		],
        		[
        			'attribute'=>'enddatepa',
        			'format'=>"datetime",
        			'header'=>"平安通知有效期"
        		],
				[
					'attribute'=>'upendtimejx',
					'format'=>"datetime",
					'header'=>"家校沟通缴费时间"
				],
				[
					'attribute'=>'enddatejx',
					'format'=>"datetime",
					'header'=>"家校沟通有效期"
				],
				[
					'attribute'=>'upendtimeqq',
					'format'=>"datetime",
					'header'=>"亲情电话缴费时间"
				],
				[
					'attribute'=>'enddateqq',
					'format'=>"datetime",
					'header'=>"亲情电话有效期"
				],
				[
					'attribute'=>'upendtimeck',
					'format'=>"datetime",
					'header'=>"餐卡缴费时间"
				],
				[
					'attribute'=>'enddateck',
					'format'=>"datetime",
					'header'=>"餐卡有效期"
				]
    	];
    	$dataProvider = new ArrayDataProvider([
    			'allModels' => WpIschoolQuery::getFee($sid),
    			'key' => "id"
    	]);
    	if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
    	{
    		\moonland\phpexcel\Excel::export([
    				'models' => $dataProvider->allModels,
    				'columns' => $array_values,
    				'fileName' => "local-fee.xlsx"
    		]);
    	}else
    	return $this->render('fee', [
    			'dataProvider' => $dataProvider,
			'array_columns' =>$array_values
    	]);
    }
    public function actionBind($sid)
    {
    	$array_values = [
    	[
    			'attribute'=>'name',
    			'header'=>"学校名称"
    	],
    	[
    			'attribute'=>'stuno2',
    			'header'=>"学号"
    	],
    	[
    			'attribute'=>'stu_name',
    			'header'=>"学生姓名"
    	],
    	[
    			'attribute'=>'class',
    			'header'=>"班级"
    	]
    	];
    	$dataProvider = new ArrayDataProvider([
    			'allModels' => WpIschoolQuery::getBind($sid),
    			'key' => "id"
    	]);
    	if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
    	{
    		\moonland\phpexcel\Excel::export([
    				'models' => $dataProvider->allModels,
    				'columns' => $array_values,
    				'fileName' => "local-bind.xlsx"
    		]);
    	}else
    	return $this->render('bind', [
    			'dataProvider' => $dataProvider,
    			'array_columns' => $array_values
    	]);
    }
    public function actionConnect($sid)
    {
    	$array_values = [
        		[
        			'attribute'=>'sname',
        			'header'=>"学校名称"
        		],
        		[
        			'attribute'=>'class',
        			'header'=>"班级"
        		],
        		[
        			'attribute'=>'sendUser',
        			'header'=>"发件人"
        		],
        		[
        			'attribute'=>'stuName',
        			'header'=>"学生姓名"
        		],
        		[
        			'attribute'=>'sendNum',
        			'header'=>"发送数量"
        		]
    	];
    	$dataProvider = new ArrayDataProvider([
    			'allModels' => WpIschoolQuery::getConnect($sid)
    	]);
    	if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
    	{
    		\moonland\phpexcel\Excel::export([
    				'models' => $dataProvider->allModels,
    				'columns' => $array_values,
    				'fileName' => "local-connect.xlsx"
    		]);
    	}else
    	return $this->render('connect', [
    			'dataProvider' => $dataProvider,
    			'array_columns' => $array_values
    	]);
    }

    /**
    新平安通知设备状态
     **/
    public function actionNewsbzt(){
        $redis = UtilsController::getRedis();
        try {
            $redis->ping();
        } catch (Exception $e) {
            $redis = UtilsController::getRedis();
        }
        $redis->select(15);
        $rediskey = $redis->keys('*');
        // var_dump($rediskey);exit;
        $sql = "select id,name,pinganid from wp_ischool_school where is_deleted = 0 and pinganid is not null and id not in(56731,56683,10000,56675)";
        $row2 = $row = Yii::$app->db->createCommand($sql)->queryAll();
//         $rediskey = array('5677521','5662301','5665201','5665301','5668401','5676201','5675811','5675711','5674001','5668101','5669801','5673911','5674201','5665401','5673811','5675712','5675901','5667501','5667001','5665004','5675713','5666501','5673802','5675912','5666601','5675801','5665011','5666401','5665003','5670701','5664901','5665013','5673201','5665001','5665012','5674401','5673901','5668201');
        $i=0;
        foreach ($row as $key => $value) {
            foreach ($rediskey as $k => $v) {
                if(substr($v,0,5) == $value['id']){
                    if(strlen($v) == 5){                //暂未位置信息
                        $new['zcmwz'][$value['id']]['sid'] = $value['id'];
                        $new['zcmwz'][$value['id']]['sname'] = $value['name'];
//                      $new['zcmwz'][$value['id']]['pingan_id'][$i] = substr($v,5,2);
                    }else{
                        $new['zc'][$value['id']]['sid'] = $value['id'];
                        $new['zc'][$value['id']]['sname'] = $value['name'];
                        $new['zc'][$value['id']]['pingan_id'][$i] = substr($v,5,2);
                    }
                    unset($row[$key]); 
                }
                $i++;
            }
        }

        foreach ($new['zc'] as $k => $v) {
            foreach ($row2 as $key => $value) {
                $num = count(json_decode($value["pinganid"],true));
                if (intval($value['id']) == $k) {
                    if($num != count($v["pingan_id"])){
                        $new['bfzc'][$k] = $new['zc'][$k];
                        unset($new['zc'][$k]);
                        $bfbzc = array_diff(json_decode($value["pinganid"],true),$v["pingan_id"]); //部分正常不正常部分
                        $bfzc = array_intersect(json_decode($value["pinganid"],true),$v["pingan_id"]); //部分正常正常部分
                        $new['bfzc'][$k]['pingan_bzcid'] = $bfbzc;
                        $new['bfzc'][$k]['pingan_zcid'] = $bfzc;
                    }else{
                        $new['zc'][$k]['pingan_zcid'] = json_decode($value["pinganid"],true);
                    }
                }
            }
        }
     // echo "<pre>";
     // var_dump($new['zc']);//全部正常
      // var_dump($row);//全不正常
      // var_dump($new['bfzc']);//部分正常
     // exit();

    // $bzcxx =json_decode($row[23]["pinganid"],true);     var_dump($bzcxx);exit();
//      $bzcxx = json_decode($value['pinganid'],true);
        return $this->render('newsbzt',[
            'bzc' => $row,
            'zc' => $new
        ]);
    }

    public function snameConfig($sid){
        $sql = "select name from wp_ischool_school WHERE is_deleted=0 and id= :id";
        $row = Yii::$app->db->createCommand($sql,[':id'=>$sid])->queryAll();
//      $sname = isset($row[0]['name'])?$row[0]['name']:"暂时没该学校";
        $sname = isset($row[0]['name'])?$row[0]['name']:"暂时没该学校";
        return $sname;
    }

//平安通知汇总查询信息
    public function actionNewsbxx(){
        $params = \Yii::$app->request->queryParams;
        $from_unix_time = 0;
        $to_unix_time = 4102419661;
        if(isset($params['from_date']) && isset($params['to_date']))
        {
            $from_unix_time = strtotime($params['from_date']);
            $to_unix_time = strtotime($params['to_date']);
        }
        $sql = "select tmp.*,tmp2.* FROM 
(select p.school,p.sid,COUNT(p.ctime)AS zcs,p.pa_id,p.pa_name FROM wp_ischool_pasb p WHERE p.ctime BETWEEN $from_unix_time AND $to_unix_time GROUP BY p.sid,p.pa_id) tmp LEFT JOIN
 (select s.pa_id pa_id2,s.sid sid2,COUNT(s.pa_id) zccs from wp_ischool_pasb s where s.status = 0 and s.ctime BETWEEN $from_unix_time AND $to_unix_time GROUP BY s.pa_id,s.sid) tmp2 ON 
 tmp.pa_id=tmp2.pa_id2 AND tmp.sid=tmp2.sid2  ORDER BY tmp.school,tmp.pa_id";
        $row = Yii::$app->db->createCommand($sql)->queryAll();
        $new = array();
        foreach($row as $k=>$v){
            $new[$v['sid']]['sid'] = $v['sid'];
            $new[$v['sid']]['school'] = $v['school'];
//            $new[$v['sid']]['zcs'] = $v['zcs'];
            $new[$v['sid']]['sbxx'][$k]['zcs'] = $v['zcs'];
            $new[$v['sid']]['sbxx'][$k]['zccs'] = $v['zccs'];
            $new[$v['sid']]['sbxx'][$k]['pa_name'] = $v['pa_name'];
        }
        $sql2 = "select count(id) as zcs,(SELECT COUNT(id) FROM wp_ischool_pasb WHERE `status`=0 and ctime BETWEEN $from_unix_time AND $to_unix_time) as zccs,(SELECT COUNT(id) FROM wp_ischool_pasb 
WHERE `status`=1 and ctime BETWEEN $from_unix_time AND $to_unix_time) as bzccs FROM wp_ischool_pasb WHERE ctime BETWEEN $from_unix_time AND $to_unix_time";
        $row2 = Yii::$app->db->createCommand($sql2)->queryOne();
        Yii::trace($row2);
        $news['zcs'] = $row2['zcs'];
        $news['zccs'] = $row2['zccs'];
        $news['bzccs'] = $row2['bzccs'];
        $news['whl'] = round($row2['zccs']/$row2['zcs']*100,2)."%";   //所有次数完好率
//        echo "<pre>";
//        var_dump($new);exit();
        return $this->render('newsbxx',[
            'new' => $new,
            'news' => $news,
            'dateInfo' => \Yii::$app->request->queryParams
        ]);
    }

    /**
    新亲情电话设备状态
     **/
    public function actionNewqqzt(){
        $sql = "select DISTINCT(sid) FROM wp_ischool_telephone";
        $row = Yii::$app->db->createCommand($sql)->queryColumn();
        $sql2 = "select Device_id,AddressInfo,sid from wp_ischool_telephone where sid NOT in(10000,56623,56689,56707,56675)  ORDER BY sid,Device_id";
        $row2 = Yii::$app->db->createCommand($sql2)->queryAll();
        $redis = UtilsController::getRedis();
        try {
            $redis->ping();
        } catch (Exception $e) {
            $redis = UtilsController::getRedis();
        }
        $redis->select(14);
        $rediskey = $redis->keys('*');

//        $rediskey = array('5677521','5662301','5665201','5665301','5668401','5676201','5675811','5675711','5674001','5668101','5669801','5673911','5674201','5665401','5673811','5675712','5675901','5667501','5667001','5665004','5675713','5666501','5673802','5675912','5666601','5675801','5665011','5666401','5665003','5670701','5664901','5665013','5673201','5665001','5665012','5674401','5673901','5668201');
        $i=0;
        foreach ($row as $key => $value) {
            foreach ($rediskey as $k => $v) {
                if(substr($v,6,5) == $value){
                    $new['zc'][$value]['sid'] = $value;
                    $new['zc'][$value]['sname'] = $this->snameConfig($value);
                    $new['zc'][$value]['pingan_id'][$i] = intval(substr($v,11,4));
                    unset($row[$key]);
                }
                $i++;
            }
        }

        foreach($row2 as $k => $v){
            $newrow[$v['sid']]['id'] = $v['sid'];
            $newrow[$v['sid']]['pinganid'][$k] = intval(substr($v['Device_id'],11,4));
        }

        foreach ($new['zc'] as $k => $v) {
            foreach ($newrow as $key => $value) {
                $num = count($value["pinganid"],true);
                if (intval($value['id']) == $k) {
                    if($num != count($v["pingan_id"])){
                        $new['bfzc'][$k] = $new['zc'][$k];
                        unset($new['zc'][$k]);
                        $bfbzc = array_diff($value["pinganid"],$v["pingan_id"]); //部分正常不正常部分
                        $bfzc = array_intersect($value["pinganid"],$v["pingan_id"]); //部分正常正常部分
                        $new['bfzc'][$k]['pingan_bzcid'] = $bfbzc;
                        $new['bfzc'][$k]['pingan_zcid'] = $bfzc;
                    }
                }
            }
        }
        $new['qbzc'] = [];
        if(isset($row)){
            foreach ($row as $k => $v) {
                foreach ($newrow as $key => $value) {
                    $num = count($value["pinganid"],true);
                    if ($v == $value['id']) {
                        $bzc[$v] = $this->snameConfig($v);
                        $new['qbzc'][$v]['sid'] = $v;
                        $new['qbzc'][$v]['sname'] = $this->snameConfig($v);;
                        $new['qbzc'][$v]['pingan_bzcid'] = $value["pinganid"];
                    }
                }
            }
        }


//      echo "<pre>";
//      var_dump($new['zc']);//全部正常
//       var_dump($new['qbzc']);//全不正常
//       var_dump($new['bfzc']);//部分正常
//      var_dump($bzc);
//      exit();
//      var_dump($row);exit();
        return $this->render('newqqzt',[
            'bzc' => $new['qbzc'],
            'zc' => $new
        ]);
    }

    //亲情电话汇总查询信息
    public function actionNewqqxx(){
        $params = \Yii::$app->request->queryParams;
        $from_unix_time = 0;
        $to_unix_time = 4102419661;
        if(isset($params['from_date']) && isset($params['to_date']))
        {
            $from_unix_time = strtotime($params['from_date']);
            $to_unix_time = strtotime($params['to_date']);
        }
        $sql = "select tmp.*,tmp2.* FROM 
(select p.school,p.sid,COUNT(p.ctime)AS zcs,p.pa_id,p.pa_name FROM wp_ischool_qqsb p WHERE p.ctime BETWEEN $from_unix_time AND $to_unix_time GROUP BY p.sid,p.pa_id) tmp LEFT JOIN
 (select s.pa_id pa_id2,s.sid sid2,COUNT(s.pa_id) zccs from wp_ischool_qqsb s where s.status = 0 and s.ctime BETWEEN $from_unix_time AND $to_unix_time GROUP BY s.pa_id,s.sid) tmp2 ON 
 tmp.pa_id=tmp2.pa_id2 AND tmp.sid=tmp2.sid2  ORDER BY tmp.school,tmp.pa_id ASC ";
        $row = Yii::$app->db->createCommand($sql)->queryAll();
        $new = array();
        foreach($row as $k=>$v){
            $new[$v['sid']]['sid'] = $v['sid'];
            $new[$v['sid']]['school'] = $v['school'];
//            $new[$v['sid']]['zcs'] = $v['zcs'];
            $new[$v['sid']]['sbxx'][$k]['zcs'] = $v['zcs'];
            $new[$v['sid']]['sbxx'][$k]['zccs'] = $v['zccs'];
            $new[$v['sid']]['sbxx'][$k]['pa_name'] = $v['pa_name'];
        }

         $sql2 = "select count(id) as zcs,(SELECT COUNT(id) FROM wp_ischool_qqsb WHERE `status`=0 and ctime BETWEEN $from_unix_time AND $to_unix_time) as zccs,(SELECT COUNT(id) FROM wp_ischool_qqsb 
WHERE `status`=1 and ctime BETWEEN $from_unix_time AND $to_unix_time) as bzccs FROM wp_ischool_qqsb WHERE ctime BETWEEN $from_unix_time AND $to_unix_time";
        $row2 = Yii::$app->db->createCommand($sql2)->queryOne();
        Yii::trace($row2);
        $news['zcs'] = $row2['zcs'];
        $news['zccs'] = $row2['zccs'];
        $news['bzccs'] = $row2['bzccs'];
        $news['whl'] = round($row2['zccs']/$row2['zcs']*100,2)."%";   //所有次数完好率
//        echo "<pre>";
//        var_dump($row);exit();
        return $this->render('newqqxx',[
            'new' => $new,
            'news' => $news,
            'dateInfo' => \Yii::$app->request->queryParams
        ]);
    }


    public function sendmsgt($value){
// oUMeDwHY58TN7eGHRMYabhEzOvAg 张豪openid
    $tos = ["oUMeDwLBklMzOqyGuxhuA-Pmzsu0","oUMeDwHY58TN7eGHRMYabhEzOvAg"];
        $data['title'] = $value."公告";
        $data['content'] = $value;
        $data['url'] = "";
        $result = WXSendMsg::broadMsgToManyUsers($tos,$data);
        // var_dump($result);
    }

	public function actionWbdrs()
	{
		$dataProvider = new ArrayDataProvider([
			'allModels' => WpIschoolQuery::getWeibangding(\Yii::$app->request->queryParams),
			'key'=>"id"
		]);
//	var_dump($dataProvider);exit();
		if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
		{
			$array_values =  [
				[
					'attribute'=>'id',
					'header'=>"学生ID",
				],
				[
					'attribute'=>'name',
					'header'=>"学生姓名",
				],
				[
					'attribute'=>'class',
					'header'=>"班级",
				],
				[
					'attribute'=>'school',
					'header'=>"学校名字",
				],
			];
			\moonland\phpexcel\Excel::export([
				'models' => $dataProvider->allModels,
				'columns' => $array_values,
				'fileName' => "all.xlsx"
			]);
		}else
			return $this->render('wbdrs', [
				'dataProvider' => $dataProvider,
				'dateInfo' => \Yii::$app->request->queryParams
			]);
	}
}
