<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "zf_card_info".
 *
 * @property string $id
 * @property string $card_no
 * @property string $user_no
 * @property string $user_name
 * @property string $department_id
 * @property string $status
 * @property integer $school_id
 * @property string $balance
 * @property integer $created_by
 * @property integer $created
 * @property integer $updated
 * @property string $deposit
 * @property string $type
 * @property integer $role_id
 * @property string $phyid
 */
class ZfCardInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zf_card_info';
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
            [['user_no', 'user_name', 'department_id', 'school_id', 'balance', 'created_by', 'created', 'updated'], 'required'],
            [['department_id', 'school_id', 'created_by', 'created', 'updated', 'role_id'], 'integer'],
            [['status', 'type'], 'string'],
            [['balance', 'deposit'], 'number'],
            [['card_no', 'user_no', 'user_name'], 'string', 'max' => 50],
            [['phyid'], 'string', 'max' => 200],
            [['card_no', 'school_id'], 'unique', 'targetAttribute' => ['card_no', 'school_id'], 'message' => 'The combination of Card No and School ID has already been taken.'],
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
            'user_no' => 'User No',
            'user_name' => 'User Name',
            'department_id' => 'Department ID',
            'status' => 'Status',
            'school_id' => 'School ID',
            'balance' => 'Balance',
            'created_by' => 'Created By',
            'created' => 'Created',
            'updated' => 'Updated',
            'deposit' => 'Deposit',
            'type' => 'Type',
            'role_id' => 'Role ID',
            'phyid' => 'Phyid',
        ];
    }
}
