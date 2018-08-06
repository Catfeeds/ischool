<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_adminer".
 *
 * @property integer $id
 * @property string $name
 * @property string $openid
 * @property string $school_name
 * @property integer $sid
 * @property integer $role
 */
class WpIschoolAdminer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_adminer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'role'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['openid', 'school_name'], 'string', 'max' => 200],
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
            'school_name' => 'School Name',
            'sid' => 'Sid',
            'role' => 'Role',
        ];
    }
}
