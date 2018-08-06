<?php

namespace mobile\models;

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
 * @property integer $is_deleted
 */
class WpIschoolClass extends \yii\db\ActiveRecord
{
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
            [['sid', 'ctime', 'level', 'class', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 15],
            [['school'], 'string', 'max' => 20],
            [['flag'], 'string', 'max' => 1],
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
            'ctime' => 'Ctime',
            'flag' => 'Flag',
            'level' => 'Level',
            'class' => 'Class',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
