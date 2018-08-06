<?php

namespace backend\models;

use Yii;
use backend\controllers\UtilsController;

/**
 * This is the model class for table "wp_ischool_school".
 *
 * @property string $id
 * @property string $name
 * @property string $pro
 * @property string $city
 * @property string $county
 * @property integer $ctime
 * @property string $address
 * @property string $jcname
 * @property string $schtype
 * @property string $pic
 * @property string $ispass
 */
class WpIschoolSchool extends \yii\db\ActiveRecord
{
	public $half_qinqing_money,$half_pingan_money,$half_jiaxiao_money,$half_jiaxiao_money_ck,$half_canka_money,$half_sss_money,$half_ww_money;
	public $one_qinqing_money,$one_pingan_money,$one_jiaxiao_money,$one_jiaxiao_money_ck,$one_canka_money,$one_sss_money,$one_ww_money;
//	public $papass,$jxpass,$qqpass,$ckpass;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_school';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctime','rmoney'], 'integer'],
            [['name', 'schtype', 'ispass'], 'string', 'max' => 20],
            [['pro', 'city', 'county'], 'string', 'max' => 10],
            [['address', 'jcname'], 'string', 'max' => 50],
            [['pic'], 'string', 'max' => 100],
        	[['is_deleted','half_money','one_money','half_qinqing_money','half_qinqing_money','half_pingan_money','half_jiaxiao_money','half_jiaxiao_money_ck','half_canka_money','half_sss_money','half_ww_money','one_qinqing_money','one_pingan_money','one_jiaxiao_money','one_jiaxiao_money_ck','one_canka_money','one_sss_money','one_ww_money'],'safe'],
        	[['rmoney_note'],'string','max'=>500],
			[['papass'],'string','max'=>20],
			[['jxpass'],'string','max'=>20],
			[['qqpass'],'string','max'=>20],
			[['ckpass'],'string','max'=>20],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
        	'id' => 'ID',
            'pro' => '省份',
            'city' => '城市',
            'county' => '县区',
            'address' => '地址',
            'schtype' => '类型',
        ];
    }
    public function afterSave($insert, $changedAttributes)
    {
    	if($this->isNewRecord) return ;
    	if(!isset($changedAttributes['is_deleted']) && isset($changedAttributes['name']))
    	{
    		WpIschoolClass::updateAll(['school'=>$this->name],['sid'=>$this->id]);
    		WpIschoolStudent::updateAll(['school'=>$this->name],['sid'=>$this->id]);
    		WpIschoolTeacher::updateAll(['school'=>$this->name],['sid'=>$this->id]);
    		WpIschoolTeaclass::updateAll(['school'=>$this->name],['sid'=>$this->id]);
    		WpIschoolPastudent::updateAll(['school'=>$this->name],['sid'=>$this->id]);
    	}else if(isset($changedAttributes['is_deleted']) && $changedAttributes['is_deleted'] = 1)
    	{
    		WpIschoolClass::updateAll(['is_deleted'=>1],['sid'=>$this->id]);
    		WpIschoolStudent::updateAll(['is_deleted'=>1],['sid'=>$this->id]);
    		WpIschoolTeacher::updateAll(['is_deleted'=>1],['sid'=>$this->id]);
    		//WpIschoolTeaclass::updateAll(['is_deleted'=>1],['sid'=>$this->id]);
    		WpIschoolPastudent::updateAll(['is_deleted'=>1],['sid'=>$this->id]);
    		WpIschoolTeaclass::deleteAll(['sid'=>$this->id]);
    	}
    }
    public function beforeSave($insert)
    {
    	if(parent::beforeSave($insert)){
    		if($this->isNewRecord){
    			$this->pro = UtilsController::getProName($this->pro);
    			$this->city = UtilsController::getCityName($this->city);
    			$this->county = UtilsController::getAreaName($this->county);

    			$this->ctime = time();
    		}else{
    		}
			\yii::trace($this->half_qinqing_money);
			$this->half_money = json_encode(["qinqing"=>$this->half_qinqing_money,"pingan"=>$this->half_pingan_money,"jiaxiao"=>$this->half_jiaxiao_money,"jiaxiaock"=>$this->half_jiaxiao_money_ck,"canka"=>$this->half_canka_money,"sss"=>$this->half_sss_money,'ww'=>$this->half_ww_money]);
			$this->one_money = json_encode(["qinqing"=>$this->one_qinqing_money,"pingan"=>$this->one_pingan_money,"jiaxiao"=>$this->one_jiaxiao_money,"jiaxiaock"=>$this->one_jiaxiao_money_ck,"canka"=>$this->one_canka_money,"sss"=>$this->one_sss_money,'ww'=>$this->one_ww_money]);
    		return true;
    	}else{
    		return false;
    	}
    }
}
