<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "attachment".
 *
 * @property integer $id
 * @property string $openid
 * @property integer $create_time
 * @property integer $sid
 * @property integer $update_time
 * @property string $title
 * @property string $url
 * @property integer $grade_id
 * @property string $type
 * @property string $name
 */
class Attachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'sid', 'update_time', 'grade_id'], 'integer'],
            [['openid'], 'string', 'max' => 200],
            [['title'], 'string', 'max' => 80],
            [['url', 'name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 22],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'create_time' => 'Create Time',
            'sid' => 'Sid',
            'update_time' => 'Update Time',
            'title' => 'Title',
            'url' => 'Url',
            'grade_id' => 'Grade ID',
            'type' => 'Type',
            'name' => 'Name',
        ];
    }
}
