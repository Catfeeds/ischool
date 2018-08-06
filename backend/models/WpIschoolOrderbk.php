<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_orderck".
 *
 * @property integer $id
 * @property string $openid
 * @property double $money
 * @property string $trade_no
 * @property string $trade_name
 * @property string $paytype
 * @property integer $ispass
 * @property integer $ctime
 * @property integer $utime
 * @property string $zfopenid
 * @property string $stuid
 * @property string $trans_id
 */
class WpIschoolOrderbk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_orderbk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money'], 'number'],
            [['ispass', 'ctime', 'utime'], 'integer'],
            [['openid', 'trade_name', 'zfopenid', 'trans_id'], 'string', 'max' => 100],
            [['trade_no'], 'string', 'max' => 32],
            [['paytype'], 'string', 'max' => 10],
            [['stuid'], 'string', 'max' => 24],
            [['sfbk'],'default','value'=>0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '学生ID',
            'openid' => '创建订单者Openid',
            'money' => '支付金额',
            'trade_no' => '订单号',
            'trade_name' => '补卡者详细信息',
            'paytype' => '支付方式',
            'ispass' => '是否缴费',
            'ctime' => '订单创建时间',
            'utime' => '支付成功时间',
            'zfopenid' => '支付人openid',
            'stuid' => '学号',
            'trans_id' => '交易单号',
            'sfbk' => '是否补卡（0为还没补卡，1为已经补卡）',
        ];
    }
}
