<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_class_chengjidan".
 *
 * @property string $id
 * @property integer $cid
 * @property integer $cjdid
 * @property string $cjdname
 * @property string $isopen
 * @property string $creater
 * @property integer $ctime
 */
class WpIschoolClassChengjidan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_class_chengjidan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid', 'cjdid', 'ctime'], 'integer'],
            [['cjdname', 'creater'], 'string', 'max' => 50],
            [['isopen'], 'string', 'max' => 1],
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
            'cjdid' => 'Cjdid',
            'cjdname' => 'Cjdname',
            'isopen' => 'Isopen',
            'creater' => 'Creater',
            'ctime' => 'Ctime',
        ];
    }
}
