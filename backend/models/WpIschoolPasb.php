<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_pasb".
 *
 * @property integer $id
 * @property integer $sid
 * @property string $school
 * @property string $pa_id
 * @property string $pa_name
 * @property integer $ctime
 * @property integer $status
 */
class WpIschoolPasb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_pasb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'ctime', 'status'], 'integer'],
            [['school', 'pa_name'], 'string', 'max' => 255],
            [['pa_id'], 'string', 'max' => 12],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => 'Sid',
            'school' => 'School',
            'pa_id' => 'Pa ID',
            'pa_name' => 'Pa Name',
            'ctime' => 'Ctime',
            'status' => 'Status',
        ];
    }
}
