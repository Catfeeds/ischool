<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_student_card".
 *
 * @property integer $id
 * @property integer $stu_id
 * @property string $card_no
 * @property integer $flag
 * @property integer $ctime
 */
class WpIschoolStudentCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_student_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stu_id', 'flag', 'ctime'], 'integer'],
            [['card_no'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stu_id' => 'Stu ID',
            'card_no' => 'Card No',
            'flag' => 'Flag',
            'ctime' => 'Ctime',
        ];
    }
}
