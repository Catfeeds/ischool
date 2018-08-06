<?php

namespace api\models;

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
 * @property integer $is_deleted
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
            [['ctime', 'stu_id', 'cid', 'sid', 'isqqtel', 'is_deleted'], 'integer'],
            [['name', 'stu_name'], 'string', 'max' => 10],
            [['openid'], 'string', 'max' => 200],
            [['school', 'Relation'], 'string', 'max' => 20],
            [['class'], 'string', 'max' => 15],
            [['tel'], 'string', 'max' => 11],
            [['ispass'], 'string', 'max' => 1],
            [['email'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'openid' => 'Openid',
            'ctime' => 'Ctime',
            'stu_id' => 'Stu ID',
            'school' => 'School',
            'cid' => 'Cid',
            'class' => 'Class',
            'tel' => 'Tel',
            'stu_name' => 'Stu Name',
            'ispass' => 'Ispass',
            'email' => 'Email',
            'sid' => 'Sid',
            'Relation' => 'Relation',
            'isqqtel' => 'Isqqtel',
            'is_deleted' => 'Is Deleted',
        ];
    }

    public function getPastudent($params){
        return self::find()->where(["uid"=> $params,"isqqtel"=>0])->orderBy('ctime desc')->asArray()->all();
    }
}
