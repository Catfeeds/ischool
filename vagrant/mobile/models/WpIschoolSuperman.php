<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_superman".
 *
 * @property integer $id
 * @property string $name
 * @property string $openid
 * @property string $area
 * @property string $level
 * @property string $type
 */
class WpIschoolSuperman extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_superman';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'openid'], 'required'],
            [['name'], 'string', 'max' => 20],
            [['openid'], 'string', 'max' => 200],
            [['area'], 'string', 'max' => 15],
            [['level', 'type'], 'string', 'max' => 5],
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
            'openid' => 'Openid',
            'area' => 'Area',
            'level' => 'Level',
            'type' => 'Type',
        ];
    }
}
