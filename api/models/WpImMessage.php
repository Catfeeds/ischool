<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "wp_im_message".
 *
 * @property int $id
 * @property string $content 内容
 * @property string $converType 会话类型
 * @property string $targetId 目标人id
 * @property int $sendtime
 * @property string $url
 */
class WpImMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wp_im_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['sendtime'], 'string','max'=>20],
            [['converType'], 'string', 'max' => 20],
            [['targetId', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'converType' => 'Conver Type',
            'targetId' => 'Target ID',
            'sendtime' => 'Sendtime',
            'url' => 'Url',
        ];
    }
}
