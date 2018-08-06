<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_num".
 *
 * @property integer $id
 * @property string $name
 * @property integer $num
 * @property integer $time
 * @property string $temid
 */
class WpIschoolNum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_num';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num', 'time'], 'integer'],
            [['name'], 'string', 'max' => 10],
            [['temid'], 'string', 'max' => 200],
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
            'num' => 'Num',
            'time' => 'Time',
            'temid' => 'Temid',
        ];
    }
}
