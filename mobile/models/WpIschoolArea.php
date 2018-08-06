<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_area".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $citycode
 */
class WpIschoolArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'citycode'], 'required'],
            [['code', 'citycode'], 'string', 'max' => 6],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'citycode' => 'Citycode',
        ];
    }
}
