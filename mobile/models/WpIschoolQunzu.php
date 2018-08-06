<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_qunzu".
 *
 * @property string $id
 * @property string $content
 * @property string $outopenid
 * @property integer $grade_id
 * @property integer $sid
 * @property integer $ctime
 * @property string $title
 * @property string $fujian
 * @property integer $type
 */
class WpIschoolQunzu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_qunzu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['grade_id', 'sid', 'ctime'], 'integer'],
            [['outopenid', 'fujian'], 'string', 'max' => 200],
            [['title','type'], 'string', 'max' => 20],
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
            'grade_id' => 'Grade ID',
            'sid' => 'Sid',
            'ctime' => 'Ctime',
            'title' => 'Title',
            'fujian' => 'Fujian',
            'type' => 'Type',
        ];
    }
}
