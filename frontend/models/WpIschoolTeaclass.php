<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_teaclass".
 *
 * @property string $id
 * @property string $tname
 * @property string $openid
 * @property string $school
 * @property integer $sid
 * @property string $class
 * @property string $cid
 * @property string $role
 * @property integer $ctime
 * @property string $ispass
 * @property string $tel
 */
class WpIschoolTeaclass extends \app\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_teaclass';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'ctime'], 'integer'],
            [['tname', 'class', 'role'], 'string', 'max' => 10],
            [['openid'], 'string', 'max' => 200],
            [['school'], 'string', 'max' => 20],
            [['cid', 'ispass'], 'string', 'max' => 11],
            [['tel'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tname' => 'Tname',
            'openid' => 'Openid',
            'school' => 'School',
            'sid' => 'Sid',
            'class' => 'Class',
            'cid' => 'Cid',
            'role' => 'Role',
            'ctime' => 'Ctime',
            'ispass' => 'Ispass',
            'tel' => 'Tel',
        ];
    }

    //所有已审核的教师
    public function getTeachers($params){
        return self::find()->where(['and',["openid"=> $params,'ispass'=>'y'],['!=','class','管理']])->orderBy('ctime asc')->asArray()->all();
    }
//所有待审核和已审核的教师
    public function getTeacheres($params){
        return self::find()->where(['and',["openid"=> $params],['!=','ispass','n'],['!=','class','管理']])->orderBy('ctime asc')->asArray()->all();
    }

}
