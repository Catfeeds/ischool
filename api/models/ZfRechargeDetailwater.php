<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "zf_recharge_detail".
 *
 * @property integer $id
 * @property string $card_no
 * @property string $credit
 * @property string $type
 * @property string $balance
 * @property string $pos_no
 * @property integer $created_by
 * @property integer $time
 * @property string $note
 * @property integer $is_active
 * @property string $ser_no
 * @property integer $school_id
 * @property string $trade_no
 * @property integer $qctime
 */
class ZfRechargeDetailwater extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zf_recharge_detail';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db3');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_no', 'credit', 'balance', 'pos_no', 'created_by', 'time', 'qctime'], 'required'],
            [['credit', 'balance'], 'number'],
            [['type'], 'string'],
            [['created_by', 'time', 'is_active', 'school_id', 'qctime'], 'integer'],
            [['card_no', 'pos_no', 'ser_no'], 'string', 'max' => 50],
            [['note', 'trade_no'], 'string', 'max' => 255],
            [['trade_no'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_no' => 'Card No',
            'credit' => 'Credit',
            'type' => 'Type',
            'balance' => 'Balance',
            'pos_no' => 'Pos No',
            'created_by' => 'Created By',
            'time' => 'Time',
            'note' => 'Note',
            'is_active' => 'Is Active',
            'ser_no' => 'Ser No',
            'school_id' => 'School ID',
            'trade_no' => 'Trade No',
            'qctime' => 'Qctime',
        ];
    }
}
