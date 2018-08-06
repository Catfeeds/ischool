<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_orderbk".
 *
 * @property string $id
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
 * @property integer $sfbk
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
            [['ispass', 'ctime', 'utime', 'sfbk'], 'integer'],
            [['openid', 'trade_name', 'zfopenid', 'trans_id'], 'string', 'max' => 100],
            [['trade_no'], 'string', 'max' => 32],
            [['paytype'], 'string', 'max' => 10],
            [['stuid'], 'string', 'max' => 24],
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
            'ispass' => 'Ispass',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'zfopenid' => 'Zfopenid',
            'stuid' => 'Stuid',
            'trans_id' => 'Trans ID',
            'sfbk' => 'Sfbk',
        ];
    }
}
