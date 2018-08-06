<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_pastudent".
 *
 * @property string $id
 * @property string $name
 * @property string $openid
 * @property integer $ctime
 * @property integer $stu_id
 * @property string $school
 * @property integer $cid
 * @property string $class
 * @property string $tel
 * @property string $stu_name
 * @property string $ispass
 * @property string $email
 * @property integer $sid
 * @property string $Relation
 * @property integer $isqqtel
 */
class WpIschoolPastudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_pastudent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctime', 'stu_id', 'cid', 'sid', 'isqqtel'], 'integer'],
            [['name', 'stu_name'], 'string', 'max' => 10],
            [['openid'], 'string', 'max' => 200],
            [['school', 'Relation'], 'string', 'max' => 20],
            [['class'], 'string', 'max' => 15],
            [['tel'], 'string', 'max' => 11],
            [['ispass'], 'default', 'value' => "y"],
            [['email','openid','is_deleted'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'stu_id' => '学生ID',
            'school' => '学校',
            'cid' => '班级ID',
            'class' => '班级名字',
            'tel' => '电话',
            'stu_name' => '学生姓名',
            'openid' => 'openid'
        ];
    }
    public static function getParents($stuid)
    {
    	return self::find()->where(['stu_id'=>$stuid,"is_deleted"=>0])->asArray()->all();
    }
}
