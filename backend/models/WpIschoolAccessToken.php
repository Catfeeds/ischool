<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_access_token".
 *
 * @property integer $id
 * @property string $access_token
 * @property integer $last_time
 */
class WpIschoolAccessToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_access_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'access_token', 'last_time'], 'required'],
            [['id', 'last_time'], 'integer'],
            [['access_token'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'access_token' => 'Access Token',
            'last_time' => 'Last Time',
        ];
    }
}
