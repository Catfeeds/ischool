<?php

namespace backend\models;

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
 *  @property string $tel
 */
class WpIschoolTeaclass extends \yii\db\ActiveRecord
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
            [['cid', 'ispass','tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tname' => '教师姓名',
            'openid' => '教师Openid',
            'school' => '学校名',
            'sid' => '学校ID',
            'class' => '班级',
            'cid' => '班级ID',
            'role' => '教师角色',
            'ctime' => '绑定时间',
            'ispass' => '是否通过审核,0未审核，y已审核，n已拒绝',
            'tel' => '电话',
        ];
    }
}
