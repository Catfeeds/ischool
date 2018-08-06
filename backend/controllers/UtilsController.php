<?php

namespace backend\controllers;

use yii\base\Controller;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

class UtilsController extends \yii\web\Controller
{
	private  $connection;
	public function init()
	{
		$this->connection = \yii::$app->getDb();
		if(!$this->connection) exit();
	}
    public static function getProvs()
    {
    	$provs_cache = \yii::$app->cache->get("province");
    	if(!$provs_cache)
    	{
    		$provs_data = \yii::$app->getDb()->createCommand('SELECT  code,name FROM wp_ischool_province')->queryAll();
    		$provs_cache = [];
    		foreach ($provs_data as $row)
    		{
    			$provs_cache[$row['code']] = $row['name'];
    		}
    		\yii::$app->cache->set("province", $provs_cache);
    	}
    	return $provs_cache;
    	
    }
    public static function getSchools()
    {
    	$provs_cache = \yii::$app->cache->get("school");
    	if(true || !$provs_cache)
    	{
    		$provs_data = \yii::$app->getDb()->createCommand('SELECT id,name FROM wp_ischool_school')->queryAll();
    		$provs_cache = [];
    		foreach ($provs_data as $row)
    		{
    			$provs_cache[$row['id']] = $row['name'];
    		}
    		\yii::$app->cache->set("school", $provs_cache);
    	}
    	return $provs_cache;
    	 
    }

    public  function actionCitys()
    {
    	$prov_paramers = \yii::$app->request->post('depdrop_all_params');
    	if(!$prov_paramers || !isset($prov_paramers['pro-id'])) exit();
    	$prov_id = $prov_paramers['pro-id'];
    	$citys_cache = \yii::$app->cache->get("province_".$prov_id);
    	if(true || !$citys_cache)
    	{
    		$citys_cache = [];
    		$citys_info = $this->connection->createCommand('select code as id,name from wp_ischool_city where provincecode = :provincecode')
    		->bindValue(":provincecode", $prov_id)
    		->queryAll();
    		\yii::$app->cache->set("province_".$prov_id,$citys_cache);
    	}
    	return json_encode(["output"=>$citys_info]);
    }
    public  function actionClasses()
    {
    	$prov_paramers = \yii::$app->request->post('depdrop_all_params');
    	if(!$prov_paramers || !isset($prov_paramers['sid'])) exit();
    	$school_id = $prov_paramers['sid'];
    	$citys_info = $this->connection->createCommand('select  id,name from wp_ischool_class where sid = :sid')
    	->bindValue(":sid", $school_id)
    	->queryAll();
    	return json_encode(["output"=>$citys_info]);
    }
    public function actionMulticlasses()
    {
    	$info = \backend\models\WpIschoolClass::find()->where(["sid"=>\yii::$app->request->post("sids"),"is_deleted"=>0])->select(["id","concat(school,'-',name) as text"])->asArray()->all();
    	return json_encode($info);
    }
    public static function getClasses($schoolid)
    {
    	$current_cache = \yii::$app->cache->get("school_class_".intval($schoolid));
    	if(true ||  !$current_cache)
    	{
    		$current_cache = \yii::$app->getDb()->createCommand('SELECT id,name FROM wp_ischool_class where sid = :sid',[":sid"=>$schoolid])->queryAll();
    		$current_cache = ArrayHelper::map($current_cache, "id", "name");
    		\yii::$app->cache->set("school_class_".intval($schoolid), $current_cache);
    	}
    	return $current_cache;
    }
    public  function actionCountry()
    {
    	$prov_paramers = \yii::$app->request->post('depdrop_all_params');
    	if(!$prov_paramers || !isset($prov_paramers['city-id'])) exit();
    	$city_id = $prov_paramers['city-id'];
    	$area_cache = \yii::$app->cache->get("city_".$city_id);
    	if(true || !$area_cache)
    	{
    		$area_cache = $this->connection->createCommand('select code as id,name from wp_ischool_area where citycode = :citycode')
    		->bindValue(":citycode", $city_id)
    		->queryAll();
    		\yii::$app->cache->set("city_".$city_id,$area_cache);
    	}
    	return json_encode(['output'=>$area_cache]);
    }
    public static function getClassCount($classid)
    {
    	$class_data = \yii::$app->getDb()->createCommand('SELECT count(*)number from wp_ischool_student where cid = :cid',[":cid"=>$classid])->queryOne();
    	return $class_data['number'];
    	
    }
    public static function getSchoolName($schoolid)
    {
    	$class_data = \yii::$app->getDb()->createCommand('SELECT id,name from wp_ischool_student where cid = :cid',[":cid"=>$classid])->queryOne();
    	return $class_data['name'];
    }
    public static function getProName($code)
    {
    	$data = \yii::$app->getDb()->createCommand('SELECT name from wp_ischool_province where code = :code',[":code"=>$code])->queryOne();
    	return $data['name']?:"河南省";
    }
    public static function getCityName($code)
    {
    	$data = \yii::$app->getDb()->createCommand('SELECT name from wp_ischool_city where code = :code',[":code"=>$code])->queryOne();
    	return $data['name']?:"郑州市";
    }
    public static function getAreaName($code)
    {
    	$data = \yii::$app->getDb()->createCommand('SELECT name from wp_ischool_area where code = :code',[":code"=>$code])->queryOne();
    	return $data['name']?:"金水区";
    }
    public static function getSchoolTypes()
    {
                $data = \yii::$app->getDb()->createCommand('select name from wp_ischool_schooltype')->queryAll();
                $ret = ArrayHelper::map($data, "name", "name");
                return $ret;

    }
	public static function getClassLevel()
	{
		return [
				"选择班级",
				"一年级",
				"二年级",
				"三年级",
				"四年级",
				"五年级",
				"六年级",
				"七年级",
				"八年级",
				"九年级"
		];
	}
	public static function getClassNumber()
	{
		return array(
				'0'=>"选择班级",
				'1'=>'一',
				'2'=>'二',
				'3'=>'三',
				'4'=>'四',
				'5'=>'五',
				'6'=>'六',
				'7'=>'七',
				'8'=>'八',
				'9'=>'九',
				'10'=>'十',
				'11'=>'十一',
				'12'=>'十二',
				'13'=>'十三',
				'14'=>'十四',
				'15'=>'十五',
				'16'=>'十六',
				'17'=>'十七',
				'18'=>'十八',
				'19'=>'十九',
				'20'=>'二十',
				'21'=>'二十一',
				'22'=>'二十二',
				'23'=>'二十三',
				'24'=>'二十四',
				'25'=>'二十五',
				'26'=>'二十六',
				'27'=>'二十七',
				'28'=>'二十八',
				'29'=>'二十九',
				'30'=>'三十',
				'31'=>'三十一',
				'32'=>'三十二',
				'33'=>'三十三',
				'34'=>'三十四',
				'35'=>'三十五',
				'36'=>'三十六',
				'37'=>'三十七',
				'38'=>'三十八',
				'39'=>'三十九',
				'40'=>'四十'
		);
	}

    /**
连接redis
 */
    public static function getRedis(){
        $redis = new \redis();
        $redis->connect('127.0.0.1',6379,5); //本机6379端口，5秒超时
        $redis->select(2);      //2库        
        return $redis;
    }
	
}
