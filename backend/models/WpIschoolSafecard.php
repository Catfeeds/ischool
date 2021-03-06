<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_safecard".
 *
 * @property string $id
 * @property integer $stuid
 * @property string $info
 * @property integer $ctime
 * @property integer $yearmonth
 * @property integer $yearweek
 * @property integer $weekday
 * @property integer $receivetime
 */
class WpIschoolSafecard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_safecard';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stuid', 'info', 'ctime'], 'required'],
            [['stuid', 'ctime', 'yearmonth', 'yearweek', 'weekday', 'receivetime'], 'integer'],
            [['info'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stuid' => '学生ID',
            'info' => '类型',
            'ctime' => '刷卡时间',
            'receivetime' => '发送时间',
        ];
    }
}
