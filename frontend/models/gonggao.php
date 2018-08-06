<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_gonggao".
 *
 * @property string $id
 * @property string $school
 * @property integer $sid
 * @property string $title
 * @property string $content
 * @property integer $ctime
 * @property string $name
 */
class gonggao extends \app\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_gonggao';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'title'], 'required'],
            [['sid', 'ctime'], 'integer'],
            [['content'], 'string'],
            [['school'], 'string', 'max' => 20],
            [['title'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school' => 'School',
            'sid' => 'Sid',
            'title' => 'Title',
            'content' => 'Content',
            'ctime' => 'Ctime',
            'name' => 'Name',
        ];
    }
}
