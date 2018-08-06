<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_teacher".
 *
 * @property string $id
 * @property string $tname
 * @property integer $sid
 * @property string $school
 * @property string $tel
 * @property string $openid
 * @property string $ispass
 * @property integer $ctime
 * @property string $epc
 * @property integer $is_deleted
 */
class WpIschoolTeacher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_teacher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'ctime', 'is_deleted'], 'integer'],
            [['tname'], 'string', 'max' => 50],
            [['school'], 'string', 'max' => 20],
            [['tel'], 'string', 'max' => 13],
            [['openid'], 'string', 'max' => 200],
            [['ispass'], 'string', 'max' => 1],
            [['epc'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tname' => 'Tname',
            'sid' => 'Sid',
            'school' => 'School',
            'tel' => 'Tel',
            'openid' => 'Openid',
            'ispass' => 'Ispass',
            'ctime' => 'Ctime',
            'epc' => 'Epc',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
