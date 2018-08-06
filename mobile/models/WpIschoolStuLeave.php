<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_stu_leave".
 *
 * @property string $id
 * @property integer $stu_id
 * @property integer $begin_time
 * @property integer $stop_time
 * @property string $openid
 * @property integer $ctime
 * @property integer $flag
 * @property string $reason
 * @property integer $oktime
 * @property string $okopenid
 */
class WpIschoolStuLeave extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_stu_leave';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stu_id', 'begin_time', 'stop_time', 'ctime', 'flag', 'oktime'], 'integer'],
            [['openid', 'okopenid'], 'string', 'max' => 100],
            [['reason'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stu_id' => 'Stu ID',
            'begin_time' => 'Begin Time',
            'stop_time' => 'Stop Time',
            'openid' => 'Openid',
            'ctime' => 'Ctime',
            'flag' => 'Flag',
            'reason' => 'Reason',
            'oktime' => 'Oktime',
            'okopenid' => 'Okopenid',
        ];
    }
}
