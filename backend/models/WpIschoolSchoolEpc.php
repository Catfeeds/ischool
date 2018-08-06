<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_school_epc".
 *
 * @property string $id
 * @property string $Name
 * @property string $Sex
 * @property string $EPC
 * @property integer $sid
 * @property string $stu_id
 * @property integer $LastTime
 * @property string $Class_name
 * @property string $ParentName
 * @property string $ParentMobile
 * @property string $Address
 * @property integer $LastStatus
 * @property string $outType
 * @property integer $type
 * @property string $carCode
 */
class WpIschoolSchoolEpc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_school_epc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'LastTime', 'LastStatus', 'type'], 'integer'],
            [['Name', 'stu_id', 'Class_name', 'ParentName', 'carCode'], 'string', 'max' => 20],
            [['Sex'], 'string', 'max' => 2],
            [['EPC'], 'string', 'max' => 24],
            [['ParentMobile'], 'string', 'max' => 11],
            [['Address'], 'string', 'max' => 200],
            [['outType'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'Name' => 'Name',
            'Sex' => 'Sex',
            'EPC' => 'Epc',
            'sid' => 'Sid',
            'stu_id' => 'Stu ID',
            'LastTime' => 'Last Time',
            'Class_name' => 'Class Name',
            'ParentName' => 'Parent Name',
            'ParentMobile' => 'Parent Mobile',
            'Address' => 'Address',
            'LastStatus' => 'Last Status',
            'outType' => 'Out Type',
            'type' => 'Type',
            'carCode' => 'Car Code',
        ];
    }
}
