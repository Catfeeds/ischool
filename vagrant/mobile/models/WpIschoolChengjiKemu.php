<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_chengji_kemu".
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property integer $sort
 */
class WpIschoolChengjiKemu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_chengji_kemu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 10],
            [['type'], 'string', 'max' => 5],
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
            'type' => 'Type',
            'sort' => 'Sort',
        ];
    }
}
