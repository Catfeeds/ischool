<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_hpage_lunbo".
 *
 * @property integer $id
 * @property integer $sid
 * @property string $picurl
 */
class WpIschoolHpageLunbo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_hpage_lunbo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'picurl'], 'required'],
            [['sid'], 'integer'],
            [['picurl'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => 'Sid',
            'picurl' => 'Picurl',
        ];
    }
}
