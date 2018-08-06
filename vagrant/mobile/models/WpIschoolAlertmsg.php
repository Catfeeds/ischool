<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_alertmsg".
 *
 * @property string $id
 * @property string $type
 * @property string $alert
 */
class WpIschoolAlertmsg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_alertmsg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'string', 'max' => 10],
            [['alert'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'alert' => 'Alert',
        ];
    }
}
