<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_outbox".
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property string $outopenid
 * @property integer $ctime
 * @property string $fujian
 * @property integer $type
 */
class WpIschoolOutbox extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_outbox';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['ctime', 'type'], 'integer'],
            [['title'], 'string', 'max' => 20],
            [['outopenid', 'fujian'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'outopenid' => 'Outopenid',
            'ctime' => 'Ctime',
            'fujian' => 'Fujian',
            'type' => 'Type',
        ];
    }
}
