<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_user_role".
 *
 * @property string $id
 * @property string $openid
 * @property integer $rid
 * @property string $school
 * @property integer $sid
 * @property string $name
 * @property string $shenfen
 */
class WpIschoolUserRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_user_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['openid', 'rid', 'sid'], 'required'],
            [['rid', 'sid'], 'integer'],
            [['openid'], 'string', 'max' => 200],
            [['school'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 30],
            [['shenfen'], 'string', 'max' => 100],
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
            'rid' => 'Rid',
            'school' => 'School',
            'sid' => 'Sid',
            'name' => 'Name',
            'shenfen' => 'Shenfen',
        ];
    }
}
