<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_student".
 *
 * @property string $id
 * @property string $name
 * @property string $stuno2
 * @property string $sex
 * @property string $school
 * @property string $class
 * @property string $address
 * @property integer $ctime
 * @property integer $cid
 * @property string $cardid
 * @property string $stuno
 * @property string $outType
 * @property integer $type
 * @property string $carCode
 * @property integer $sid
 * @property integer $LastTime
 * @property integer $LastStatus
 * @property integer $enddate
 * @property integer $upendtime
 * @property integer $enddatejx
 * @property integer $upendtimejx
 * @property integer $enddateqq
 * @property integer $upendtimeqq
 * @property integer $enddateck
 * @property integer $upendtimeck
 * @property integer $enddatepa
 * @property integer $upendtimepa
 * @property integer $is_deleted
 * @property string $avatar
 * @property integer $is_linshi
 * @property string $img
 */
class WpIschoolStudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctime', 'cid', 'type', 'sid', 'LastTime', 'LastStatus', 'enddate', 'upendtime', 'enddatejx', 'upendtimejx', 'enddateqq', 'upendtimeqq', 'enddateck', 'upendtimeck', 'enddatepa', 'upendtimepa', 'is_deleted', 'is_linshi'], 'integer'],
            [['name', 'outType'], 'string', 'max' => 10],
            [['stuno2', 'school', 'carCode'], 'string', 'max' => 20],
            [['sex'], 'string', 'max' => 2],
            [['class'], 'string', 'max' => 15],
            [['address'], 'string', 'max' => 50],
            [['cardid'], 'string', 'max' => 32],
            [['stuno'], 'string', 'max' => 25],
            [['avatar', 'img'], 'string', 'max' => 255],
            [['stuno2'], 'unique'],
            [['cardid'], 'unique'],
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
            'stuno2' => 'Stuno2',
            'sex' => 'Sex',
            'school' => 'School',
            'class' => 'Class',
            'address' => 'Address',
            'ctime' => 'Ctime',
            'cid' => 'Cid',
            'cardid' => 'Cardid',
            'stuno' => 'Stuno',
            'outType' => 'Out Type',
            'type' => 'Type',
            'carCode' => 'Car Code',
            'sid' => 'Sid',
            'LastTime' => 'Last Time',
            'LastStatus' => 'Last Status',
            'enddate' => 'Enddate',
            'upendtime' => 'Upendtime',
            'enddatejx' => 'Enddatejx',
            'upendtimejx' => 'Upendtimejx',
            'enddateqq' => 'Enddateqq',
            'upendtimeqq' => 'Upendtimeqq',
            'enddateck' => 'Enddateck',
            'upendtimeck' => 'Upendtimeck',
            'enddatepa' => 'Enddatepa',
            'upendtimepa' => 'Upendtimepa',
            'is_deleted' => 'Is Deleted',
            'avatar' => 'Avatar',
            'is_linshi' => 'Is Linshi',
            'img' => 'Img',
        ];
    }
}
