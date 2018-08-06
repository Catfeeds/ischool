<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_subject".
 *
 * @property integer $id
 * @property string $name
 */
class WpIschoolSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 200],
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
        ];
    }
}
