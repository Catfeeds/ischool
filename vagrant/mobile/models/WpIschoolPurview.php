<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_purview".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $type
 */
class WpIschoolPurview extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_purview';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['name'], 'string', 'max' => 15],
            [['description'], 'string', 'max' => 300],
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
            'description' => 'Description',
            'type' => 'Type',
        ];
    }
}
