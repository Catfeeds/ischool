<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_role_purview".
 *
 * @property string $id
 * @property integer $rid
 * @property integer $pid
 */
class WpIschoolRolePurview extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_role_purview';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rid', 'pid'], 'required'],
            [['rid', 'pid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rid' => 'Rid',
            'pid' => 'Pid',
        ];
    }
}
