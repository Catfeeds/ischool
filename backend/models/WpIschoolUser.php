<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_user".
 *
 * @property string $id
 * @property string $name
 * @property string $tel
 * @property string $openid
 * @property integer $last_sid
 * @property string $pwd
 * @property integer $ctime
 * @property integer $score
 * @property integer $level
 * @property string $login_ip
 * @property string $last_login_ip
 * @property integer $last_login_time
 * @property string $shenfen
 * @property integer $last_stuid
 * @property integer $last_cid
 * @property integer $login_time
 */
class WpIschoolUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['last_sid', 'ctime', 'score', 'level', 'last_login_time', 'last_stuid', 'last_cid', 'login_time'], 'integer'],
            [['shenfen'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['tel'], 'string', 'max' => 11],
            [['openid'], 'string', 'max' => 200],
            [['pwd'], 'string', 'max' => 50],
            [['login_ip', 'last_login_ip'], 'string', 'max' => 255],
            [['tel'], 'unique'],
            [['openid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户ID',
            'name' => '姓名',
            'tel' => '手机号',
            'openid' => 'Openid',
            'last_sid' => '学校ID',
            'pwd' => '密码',
            'ctime' => '注册时间',
            'score' => '积分',
            'level' => 'Level',
            'login_ip' => '登录IP',
            'last_login_ip' => '最后一次登录IP',
            'last_login_time' => '最后一次登录时间',
            'shenfen' => '当前身份',
            'last_stuid' => '当前孩子ID',
            'last_cid' => '当前班级ID',
            'login_time' => '本次登陆时间',
        ];
    }
}
