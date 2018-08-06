<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zf_auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property ZfAuthItem $itemName
 */
class assignment extends \app\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zf_auth_assignment';
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
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => ZfAuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Item Name',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(ZfAuthItem::className(), ['name' => 'item_name']);
    }
}
