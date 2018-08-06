<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_hpage_colname".
 *
 * @property string $id
 * @property string $name
 * @property integer $sid
 */
class WpIschoolHpageColname extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_hpage_colname';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sid'], 'required'],
            [['sid'], 'integer'],
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
            'name' => 'Name',
            'sid' => 'Sid',
        ];
    }
}
