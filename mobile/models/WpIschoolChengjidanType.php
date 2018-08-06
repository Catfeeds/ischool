<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_chengjidan_type".
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property integer $sort
 */
class WpIschoolChengjidanType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_chengjidan_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['type'], 'string', 'max' => 10],
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
