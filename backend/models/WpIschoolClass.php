<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_class".
 *
 * @property string $id
 * @property string $name
 * @property integer $sid
 * @property string $school
 * @property integer $ctime
 * @property string $flag
 * @property integer $level
 * @property integer $class
 */
class WpIschoolClass extends \yii\db\ActiveRecord
{
	public $bupdate;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'ctime', 'level', 'class'], 'integer'],
            [['name'], 'string', 'max' => 15],
            [['school'], 'string', 'max' => 20],
            [['flag'], 'default', 'value' => "c",],
        	[['ctime'],'default','value' => time()],
        	[['is_deleted'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'school' => '学校名称',
            'id' => '班级ID',
            'name' => '班级名字',
            'number' => '人数',
            'tname' => '班主任',
            'tel' => '联系电话',
        ];
    }
    /*public function getTeachers()
    {
    	return $this->hasOne(WpIschoolTeacher::className(), ['openid' => 'openid'])
    	->viaTable('wp_ischool_teaclass', ['cid' => 'id']);
    }
    public static function getAllClassInfo1()
    {
    	return self::find()->joinWith(["teachers"=>function($query){
    		$query->select('tname ,tel ,(1) as number');
    	}]);
    }*/
    // 可以通过via来进行
    public static function getAllClassInfo($school_name)
    {
    	if($school_name)
    		return 
    		self::findBySql("select a.*,b.tname,b.tel,d.number  from wp_ischool_class as a  left join wp_ischool_teaclass as b on a.id = b.cid AND
 b.role='班主任' and b.ispass='y' left join ( SELECT cid,count(1)number
             from wp_ischool_student group by cid)d on d.cid = a.id where a.school = :sname GROUP BY a.id  order by a.id desc",[':sname'=>$school_name])
    		->asArray()
    		->all();
    	else 
    		return
    		self::findBySql("select a.*,b.tname,b.tel,d.number  from wp_ischool_class as a  left join wp_ischool_teaclass as b on a.id = b.cid AND
 b.role='班主任' and b.ispass='y' left join ( SELECT cid,count(1)number
             from wp_ischool_student group by cid)d on d.cid = a.id GROUP BY a.id order by a.id desc")
    		->asArray()
    		->all();
    }
    public function afterSave($insert, $changedAttributes)
    {
    	if($this->isNewRecord) return ;
    	if(!isset($changedAttributes['is_deleted']) && isset($changedAttributes['name']))
    	{
    		WpIschoolPastudent::updateAll(['class'=>$this->name],['cid'=>$this->id]);
    		WpIschoolStudent::updateAll(['class'=>$this->name],['cid'=>$this->id]);
    		WpIschoolTeaclass::updateAll(['class'=>$this->name],['cid'=>$this->id]);
    	}else if (isset($changedAttributes['is_deleted']) && $changedAttributes['is_deleted'] = 1)
    	{
    		WpIschoolTeaclass::updateAll(['class'=>$this->name],['cid'=>$this->id]);
    		WpIschoolTeaclass::deleteAll(['cid'=>$this->id]);
    	}
    }

}
