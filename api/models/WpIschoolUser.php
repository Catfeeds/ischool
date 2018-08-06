<?php

namespace api\models;

use Yii;
use yii\base\Model;
use common\models\Apiuser;
use yii\db\Query;
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
 * @property string $label
 */
class WpIschoolUser extends \yii\db\ActiveRecord
{
//    public $tel;
//    public $pwd;
//    public $rememberMe = true;
//    private $_user;
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
            [['openid', 'label'], 'string', 'max' => 200],
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
            'id' => 'ID',
            'name' => 'Name',
            'tel' => 'Tel',
            'openid' => 'Openid',
            'last_sid' => 'Last Sid',
            'pwd' => 'Pwd',
            'ctime' => 'Ctime',
            'score' => 'Score',
            'level' => 'Level',
            'login_ip' => 'Login Ip',
            'last_login_ip' => 'Last Login Ip',
            'last_login_time' => 'Last Login Time',
            'shenfen' => 'Shenfen',
            'last_stuid' => 'Last Stuid',
            'last_cid' => 'Last Cid',
            'login_time' => 'Login Time',
            'label' => 'Label',
        ];
    }

}
