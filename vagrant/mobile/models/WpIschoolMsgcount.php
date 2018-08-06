<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_msgcount".
 *
 * @property string $id
 * @property integer $cid
 * @property integer $ym
 * @property integer $type
 * @property integer $num
 */
class WpIschoolMsgcount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_msgcount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid', 'ym', 'type', 'num'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'Cid',
            'ym' => 'Ym',
            'type' => 'Type',
            'num' => 'Num',
        ];
    }
}
