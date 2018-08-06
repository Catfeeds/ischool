<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_role".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sid
 * @property string $school
 * @property integer $level
 */
class WpIschoolRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sid'], 'required'],
            [['sid', 'level'], 'integer'],
            [['name'], 'string', 'max' => 10],
            [['school'], 'string', 'max' => 20],
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
            'sid' => 'Sid',
            'school' => 'School',
            'level' => 'Level',
        ];
    }
}
