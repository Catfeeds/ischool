<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_orderjf".
 *
 * @property string $id
 * @property string $openid
 * @property double $total
 * @property string $trade_no
 * @property string $trade_name
 * @property string $paytype
 * @property integer $issuccess
 * @property integer $ctime
 * @property integer $uptime
 * @property string $zfopenid
 * @property string $stuid
 * @property string $trans_id
 * @property string $type
 */
class WpIschoolOrderjf extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_orderjf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['total'], 'number'],
            [['issuccess', 'ctime', 'uptime'], 'integer'],
            [['openid', 'trade_name', 'zfopenid', 'trans_id'], 'string', 'max' => 100],
            [['trade_no'], 'string', 'max' => 32],
            [['paytype'], 'string', 'max' => 10],
            [['stuid'], 'string', 'max' => 24],
            [['type'], 'string', 'max' => 20],
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
            'total' => 'Total',
            'trade_no' => 'Trade No',
            'trade_name' => 'Trade Name',
            'paytype' => 'Paytype',
            'issuccess' => 'Issuccess',
            'ctime' => 'Ctime',
            'uptime' => 'Uptime',
            'zfopenid' => 'Zfopenid',
            'stuid' => 'Stuid',
            'trans_id' => 'Trans ID',
            'type' => 'Type',
        ];
    }
}
