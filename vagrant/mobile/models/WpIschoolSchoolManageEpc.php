<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_school_manage_epc".
 *
 * @property string $id
 * @property integer $sid
 * @property string $name
 * @property string $pwd
 * @property integer $ctime
 * @property string $openid
 */
class WpIschoolSchoolManageEpc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_school_manage_epc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'name', 'pwd', 'ctime', 'openid'], 'required'],
            [['sid', 'ctime'], 'integer'],
            [['name'], 'string', 'max' => 11],
            [['pwd'], 'string', 'max' => 32],
            [['openid'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => 'Sid',
            'name' => 'Name',
            'pwd' => 'Pwd',
            'ctime' => 'Ctime',
            'openid' => 'Openid',
        ];
    }
}
