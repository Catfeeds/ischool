<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_chengjidan".
 *
 * @property string $id
 * @property string $name
 * @property integer $sid
 * @property integer $ctime
 */
class WpIschoolChengjidan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_chengjidan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'ctime'], 'integer'],
            [['name'], 'string', 'max' => 50],
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
            'sid' => 'Sid',
            'ctime' => 'Ctime',
        ];
    }
}
