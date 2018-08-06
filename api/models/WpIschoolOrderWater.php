<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_order_water".
 *
 * @property string $id
 * @property double $money
 * @property string $trade_name
 * @property string $type
 * @property string $paytype
 * @property integer $ispass
 * @property string $ctime
 * @property string $utime
 * @property integer $stuid
 * @property integer $uid
 * @property string $trade_no
 * @property string $trans_id
 * @property integer $amount_adult
 * @property integer $amount_stu
 * @property string $is_expired
 * @property string $use_date
 * @property string $scan_time
 */
class WpIschoolOrderWater extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_order_water';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money'], 'number'],
            [['type', 'is_expired'], 'string'],
            [['ispass', 'stuid', 'uid', 'amount_adult', 'amount_stu'], 'integer'],
            [['ctime', 'utime', 'use_date', 'scan_time'], 'safe'],
            [['trade_name', 'trans_id'], 'string', 'max' => 100],
            [['paytype'], 'string', 'max' => 10],
            [['trade_no'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'money' => 'Money',
            'trade_name' => 'Trade Name',
            'type' => 'Type',
            'paytype' => 'Paytype',
            'ispass' => 'Ispass',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'stuid' => 'Stuid',
            'uid' => 'Uid',
            'trade_no' => 'Trade No',
            'trans_id' => 'Trans ID',
            'amount_adult' => 'Amount Adult',
            'amount_stu' => 'Amount Stu',
            'is_expired' => 'Is Expired',
            'use_date' => 'Use Date',
            'scan_time' => 'Scan Time',
        ];
    }
}
