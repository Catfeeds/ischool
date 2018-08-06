<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_leave_rule".
 *
 * @property string $id
 * @property integer $sid
 * @property string $start_time
 * @property string $stop_time
 * @property integer $num
 * @property integer $type
 */
class WpIschoolLeaveRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_leave_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'num', 'type'], 'integer'],
            [['start_time', 'stop_time'], 'string', 'max' => 10],
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
            'start_time' => 'Start Time',
            'stop_time' => 'Stop Time',
            'num' => 'Num',
            'type' => 'Type',
        ];
    }
}
