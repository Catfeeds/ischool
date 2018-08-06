<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_user".
 *
 * @property integer $id
 * @property string $name
 * @property string $tel
 * @property string $openid
 * @property integer $last_sid
 * @property integer $last_stuid
 * @property integer $last_cid
 * @property string $pwd
 * @property integer $ctime
 * @property integer $score
 * @property integer $level
 */
class WpIschoolUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'tel', 'openid'], 'required'],
            [['last_sid', 'ctime', 'score', 'level','last_stuid','last_cid'], 'integer'],
            [['name'], 'string', 'max' => 10],
            [['tel'], 'string', 'max' => 11],
            [['openid','label'], 'string', 'max' => 200],
            [['pwd'], 'string', 'max' => 50],
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
            'tel' => 'Tel',
            'openid' => 'Openid',
            'last_sid' => 'Last Sid',
            'pwd' => 'Pwd',
            'ctime' => 'Ctime',
            'score' => 'Score',
            'level' => 'Level',
            'last_stuid'=>'Last Stuid',
            'label'=>'Label',
        ];
    }
}
