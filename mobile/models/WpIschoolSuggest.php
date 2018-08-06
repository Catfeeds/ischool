<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_suggest".
 *
 * @property string $id
 * @property string $content
 * @property string $outopenid
 * @property integer $sid
 * @property integer $ctime
 * @property string $title
 * @property string $fujian
 */
class WpIschoolSuggest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_suggest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['sid', 'ctime'], 'integer'],
            [['outopenid', 'fujian'], 'string', 'max' => 200],
            [['title'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'outopenid' => 'Outopenid',
            'sid' => 'Sid',
            'ctime' => 'Ctime',
            'title' => 'Title',
            'fujian' => 'Fujian',
        ];
    }
}
