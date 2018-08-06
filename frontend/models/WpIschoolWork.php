<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_work".
 *
 * @property integer $id
 * @property string $name
 * @property integer $grade
 * @property string $class
 * @property integer $ctime
 * @property integer $oktime
 * @property integer $flag
 * @property integer $flag1
 * @property string $fjurl
 * @property string $title
 * @property string $content
 * @property integer $tid
 * @property integer $is_deleted
 * @property integer $sid
 */
class WpIschoolWork extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_work';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'grade', 'ctime', 'oktime', 'flag', 'flag1', 'tid','is_deleted'], 'integer'],
            [['name', 'class', 'fjurl', 'title', 'content'], 'string', 'max' => 255],
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
            'grade' => 'Grade',
            'class' => 'Class',
            'ctime' => 'Ctime',
            'oktime' => 'Oktime',
            'flag' => 'Flag',
            'flag1' => 'Flag1',
            'fjurl' => 'Fjurl',
            'title' => 'Title',
            'content' => 'Content',
            'tid' => 'Tid',
        ];
    }
}
