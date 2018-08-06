<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_inbox".
 *
 * @property string $id
 * @property string $content
 * @property string $outopenid
 * @property string $inopenid
 * @property integer $ctime
 * @property string $title
 * @property string $fujian
 * @property integer $type
 * @property integer $out_uid
 * @property integer $in_uid
 */
class WpIschoolInbox extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_inbox';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['ctime', 'type', 'out_uid', 'in_uid'], 'integer'],
            [['outopenid', 'inopenid', 'fujian'], 'string', 'max' => 200],
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
            'inopenid' => 'Inopenid',
            'ctime' => 'Ctime',
            'title' => 'Title',
            'fujian' => 'Fujian',
            'type' => 'Type',
            'out_uid' => 'Out Uid',
            'in_uid' => 'In Uid',
        ];
    }
}
