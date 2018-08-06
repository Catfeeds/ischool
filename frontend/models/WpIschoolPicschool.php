<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_picschool".
 *
 * @property integer $id
 * @property string $pic
 * @property string $schoolid
 * @property string $toppic
 */
class WpIschoolPicschool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_picschool';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pic', 'schoolid', 'toppic'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pic' => 'Pic',
            'schoolid' => 'Schoolid',
            'toppic' => 'Toppic',
        ];
    }
}
