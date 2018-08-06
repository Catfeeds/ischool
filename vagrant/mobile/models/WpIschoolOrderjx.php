<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_orderjx".
 *
 * @property string $id
 * @property string $openid
 * @property double $money
 * @property string $trade_no
 * @property string $trade_name
 * @property string $paytype
 * @property integer $ispasspa
 * @property integer $ispassjx
 * @property integer $ispassqq
 * @property integer $ispassck
 * @property integer $ispass
 * @property integer $ctime
 * @property integer $utime
 * @property string $zfopenid
 * @property integer $stuid
 * @property string $trans_id
 */
class WpIschoolOrderjx extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_orderjx';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money'], 'number'],
            [['ispasspa', 'ispassjx', 'ispassqq', 'ispassck', 'ispass', 'ctime', 'utime', 'stuid'], 'integer'],
            [['openid', 'trade_name', 'zfopenid', 'trans_id'], 'string', 'max' => 100],
            [['trade_no'], 'string', 'max' => 32],
            [['paytype'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'money' => 'Money',
            'trade_no' => 'Trade No',
            'trade_name' => 'Trade Name',
            'paytype' => 'Paytype',
            'ispasspa' => 'Ispasspa',
            'ispassjx' => 'Ispassjx',
            'ispassqq' => 'Ispassqq',
            'ispassck' => 'Ispassck',
            'ispass' => 'Ispass',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'zfopenid' => 'Zfopenid',
            'stuid' => 'Stuid',
            'trans_id' => 'Trans ID',
        ];
    }
}
