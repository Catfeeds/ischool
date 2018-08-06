<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attachment".
 *
 * @property integer $id
 * @property string $name
 * @property integer $create_time
 * @property integer $sid
 * @property integer $cid
 * @property string $kemu
 * @property integer $update_time
 * @property string $title
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
            [['id'], 'required'],
            [['id', 'create_time', 'sid', 'cid', 'update_time'], 'integer'],
            [['name', 'kemu'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 30],
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
            'create_time' => 'Create Time',
            'sid' => 'Sid',
            'cid' => 'Cid',
            'kemu' => 'Kemu',
            'update_time' => 'Update Time',
            'title' => 'Title',
        ];
    }
}
