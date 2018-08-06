<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_order".
 *
 * @property string $id
 * @property string $openid
 * @property double $money
 * @property string $trade_no
 * @property string $trade_name
 * @property string $paytype
 * @property integer $ispass
 * @property integer $ctime
 * @property integer $utime
 * @property string $zfopenid
 * @property integer $stuid
 * @property string $trans_id
 */
class WpIschoolOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $school,$class,$name,$stuno2,$enddate;
    public static function tableName()
    {
        return 'wp_ischool_orderjx';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money'], 'number'],
            [['ispass', 'ctime', 'utime', 'stuid'], 'integer'],
            [['openid', 'trade_name', 'zfopenid', 'trans_id'], 'string', 'max' => 100],
            [['trade_no'], 'string', 'max' => 32],
            [['paytype'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'money' => '金额',
            'ispass' => '是否缴费',
            'ctime' => '创建时间',
			'school' => "学校",
        	'class' => "班级",
        	'name' => "姓名",
        	'stuno2' => "学生号",
        	'enddate' => "结束时间"
        ];
    }
    public function getStudentinfo()
    {
    	return $this->hasOne(WpIschoolStudent::className(), ['id' => 'stuid']);
    }
}
