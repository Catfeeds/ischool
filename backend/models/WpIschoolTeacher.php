<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_teacher".
 *
 * @property string $id
 * @property string $tname
 * @property integer $sid
 * @property string $school
 * @property string $tel
 * @property string $openid
 * @property string $ispass
 * @property integer $ctime
 * @property string $epc
 */
class WpIschoolTeacher extends \yii\db\ActiveRecord
{
	
    /**
     * @inheritdoc
     */
	public $cid,$class;
    public static function tableName()
    {
        return 'wp_ischool_teacher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'ctime'], 'integer'],
            [['tname'], 'string', 'max' => 10],
            [['school'], 'string', 'max' => 20],
            [['tel'], 'string', 'max' => 13],
            [['openid'], 'string', 'max' => 200],
            [['ispass'], 'string', 'max' => 1],
            [['epc'], 'string', 'max' => 32],
        	[['cid','is_deleted'],'safe']
        ];
    }

    public static function getOne($id)
    {
    	return self::find()->where(['wp_ischool_teacher.id'=>$id,'wp_ischool_teacher.is_deleted'=>0])->joinWith("classes")->one();
    }
    public function getClasses()
    {
    	return $this->hasOne(WpIschoolTeaclass::className(), ['openid' => 'openid']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tname' => '姓名',
            'school' => '学校名',
            'tel' => '电话',
            'epc' => 'Epc',
        	'cid'=>"班级ID",
        	"class"=>"班级名"
        ];
    }
    public function afterSave($insert, $changedAttributes)
    {
    	if($this->isNewRecord) return ;
    	if(!isset($changedAttributes['is_deleted']) && isset($changedAttributes['name']))
    	{
    		WpIschoolTeaclass::updateAll(['tname'=>$this->tname],['openid'=>$this->openid]);
    	}else if(isset($changedAttributes['is_deleted']) && $changedAttributes['is_deleted'] = 1)
    	{
    		WpIschoolTeaclass::deleteAll(['openid'=>$this->openid]);
    	}
    }

}
