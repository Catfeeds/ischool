<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_hpage_colcontent".
 *
 * @property string $id
 * @property string $title
 * @property string $toppicture
 * @property string $content
 * @property string $sketch
 * @property integer $sid
 * @property string $openid
 * @property integer $cid
 */
class WpIschoolHpageColcontent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_hpage_colcontent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['sid', 'cid'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['toppicture'], 'string', 'max' => 100],
            [['sketch', 'openid'], 'string', 'max' => 200],
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
            'toppicture' => 'Toppicture',
            'content' => 'Content',
            'sketch' => 'Sketch',
            'sid' => 'Sid',
            'openid' => 'Openid',
            'cid' => 'Cid',
        ];
    }
}
