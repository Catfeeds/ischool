<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_userschool".
 *
 * @property integer $id
 * @property string $openid
 * @property string $schoolid
 */
class WpIschoolUserschool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_userschool';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['openid'], 'string', 'max' => 30],
            [['schoolid'], 'string', 'max' => 20],
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
            'schoolid' => 'Schoolid',
        ];
    }
}
