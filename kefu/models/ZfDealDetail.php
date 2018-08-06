<?php

namespace kefu\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "zf_deal_detail".
 *
 * @property integer $id
 * @property string $pos_sn
 * @property string $card_no
 * @property string $amount
 * @property string $balance
 * @property integer $created
 * @property string $type
 * @property string $ser_no
 * @property integer $school_id
 */
class ZfDealDetail extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zf_deal_detail';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_sn', 'card_no', 'amount', 'balance', 'created'], 'required'],
            [['amount', 'balance'], 'number'],
            [['created', 'school_id'], 'integer'],
            [['type'], 'string'],
            [['pos_sn', 'card_no', 'ser_no'], 'string', 'max' => 50],
            [['created', 'ser_no', 'card_no'], 'unique', 'targetAttribute' => ['created', 'ser_no', 'card_no'], 'message' => 'The combination of Card No, Created and Ser No has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pos_sn' => 'Pos Sn',
            'card_no' => 'Card No',
            'amount' => 'Amount',
            'balance' => 'Balance',
            'created' => 'Created',
            'type' => 'Type',
            'ser_no' => 'Ser No',
            'school_id' => 'School ID',
        ];
    }
}
