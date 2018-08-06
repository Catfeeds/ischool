<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_kaku".
 *
 * @property string $id
 * @property string $stuno2
 * @property string $epc
 * @property string $telid
 */
class WpIschoolKaku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_kaku';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stuno2'], 'string', 'max' => 20],
            [['epc'], 'string', 'max' => 32],
            [['telid'], 'string', 'max' => 30],
            [['stuno2'], 'unique'],
            [['epc'], 'unique'],
            [['telid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stuno2' => 'Stuno2',
            'epc' => 'Epc',
            'telid' => 'Telid',
        ];
    }
}
