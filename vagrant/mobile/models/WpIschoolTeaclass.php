<?php

namespace mobile\models;

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
            [['tname'], 'string', 'max' => 50],
            [['openid'], 'string', 'max' => 200],
            [['school'], 'string', 'max' => 20],
            [['class', 'role'], 'string', 'max' => 10],
            [['cid', 'ispass'], 'string', 'max' => 11],
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
        ];
    }
}
