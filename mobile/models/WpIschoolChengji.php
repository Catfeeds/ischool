<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_chengji".
 *
 * @property string $id
 * @property integer $stuid
 * @property string $stuname
 * @property integer $cid
 * @property integer $cjdid
 * @property integer $kmid
 * @property string $kmname
 * @property double $score
 * @property integer $ctime
 */
class WpIschoolChengji extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_chengji';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stuid', 'cid', 'cjdid', 'kmid', 'ctime'], 'integer'],
            [['score'], 'number'],
            [['stuname', 'kmname'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stuid' => 'Stuid',
            'stuname' => 'Stuname',
            'cid' => 'Cid',
            'cjdid' => 'Cjdid',
            'kmid' => 'Kmid',
            'kmname' => 'Kmname',
            'score' => 'Score',
            'ctime' => 'Ctime',
        ];
    }
}
