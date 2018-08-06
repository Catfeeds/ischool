<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_class_images".
 *
 * @property string $id
 * @property integer $sid
 * @property integer $cid
 * @property string $picurl
 */
class WpIschoolClassImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_class_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'cid'], 'required'],
            [['sid', 'cid'], 'integer'],
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
            'cid' => 'Cid',
            'picurl' => 'Picurl',
        ];
    }
}
