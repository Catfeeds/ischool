<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_province".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $sort
 */
class WpIschoolProvince extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 15],
            [['code'], 'string', 'max' => 6],
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
            'code' => 'Code',
            'sort' => 'Sort',
        ];
    }
}
