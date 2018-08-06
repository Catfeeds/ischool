<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_city".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $provincecode
 */
class WpIschoolCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'provincecode'], 'required'],
            [['code', 'provincecode'], 'string', 'max' => 6],
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
            'provincecode' => 'Provincecode',
        ];
    }
}
