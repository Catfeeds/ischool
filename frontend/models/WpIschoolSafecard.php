<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_safecard".
 *
 * @property string $id
 * @property integer $stuid
 * @property string $info
 * @property integer $ctime
 * @property integer $yearmonth
 * @property integer $yearweek
 * @property integer $weekday
 * @property integer $receivetime
 * @property integer $late
 */
class WpIschoolSafecard extends \app\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_safecard';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stuid', 'info', 'ctime'], 'required'],
            [['stuid', 'ctime', 'yearmonth', 'yearweek', 'weekday', 'receivetime', 'late'], 'integer'],
            [['info'], 'string', 'max' => 10],
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
            'info' => 'Info',
            'ctime' => 'Ctime',
            'yearmonth' => 'Yearmonth',
            'yearweek' => 'Yearweek',
            'weekday' => 'Weekday',
            'receivetime' => 'Receivetime',
            'late' => 'Late',
        ];
    }

    public function export($stuid,$begin){
        $models = self::find()->where(['and',['in', 'stuid', $stuid],['>','ctime',$begin],['<>','info','未到']])->orderBy('ctime desc')->all();
        return $models;
    }
}
