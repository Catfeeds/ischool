<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_news".
 *
 * @property string $id
 * @property integer $sid
 * @property string $title
 * @property string $content
 * @property integer $ctime
 * @property string $name
 * @property string $openid
 */
class News extends \app\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'title', 'content', 'ctime'], 'required'],
            [['sid', 'ctime'], 'integer'],
            [['title', 'name'], 'string', 'max' => 20],
            [['content'], 'string', 'max' => 3000],
            [['openid'], 'string', 'max' => 100],
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
            'title' => 'Title',
            'content' => 'Content',
            'ctime' => 'Ctime',
            'name' => 'Name',
            'openid' => 'Openid',
        ];
    }
}
