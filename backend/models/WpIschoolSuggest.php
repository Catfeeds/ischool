<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_suggest".
 *
 * @property integer $id
 * @property string $content
 * @property string $outopenid
 * @property integer $sid
 * @property integer $ctime
 * @property string $title
 * @property string $fujian
 * @property string $school
 */
class WpIschoolSuggest extends \yii\db\ActiveRecord
{
	public $pmobile;
	public $pcontent;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_suggest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['sid', 'ctime'], 'integer'],
            [['outopenid', 'fujian', 'school'], 'string', 'max' => 200],
            [['title'], 'string', 'max' => 20],
	    [['note'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'outopenid' => 'Openid',
            'sid' => '学校ID',
            'ctime' => '时间',
            'title' => '标题',
            'fujian' => '附件',
            'school' => 'School',
        	'pmobile'=>'家长信息',
        	'pcontent'=>'学生信息',
	    'note'=>'备注'
        ];
    }
}
