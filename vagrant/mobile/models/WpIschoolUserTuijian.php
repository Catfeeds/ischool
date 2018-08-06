<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_user_tuijian".
 *
 * @property string $id
 * @property string $name
 * @property string $openid
 * @property string $utel
 */
class WpIschoolUserTuijian extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_user_tuijian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 15],
            [['openid'], 'string', 'max' => 200],
            [['utel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'openid' => 'Openid',
            'utel' => 'Utel',
        ];
    }
}
