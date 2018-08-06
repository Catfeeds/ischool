<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_group_message".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $paramers
 * @property string $send_role
 * @property string $title
 * @property string $content
 * @property string $created
 */
class WpIschoolGroupMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_group_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'paramers', 'send_role', 'title', 'content'], 'required'],
            [['user_id'], 'integer'],
            [['content'], 'string'],
            [['created'], 'safe'],
            [['paramers'], 'string', 'max' => 500],
            [['send_role'], 'string', 'max' => 30],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'paramers' => 'Paramers',
            'send_role' => 'Send Role',
            'title' => 'Title',
            'content' => 'Content',
            'created' => 'Created',
        ];
    }
}
