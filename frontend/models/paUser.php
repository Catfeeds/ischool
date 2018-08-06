<?php

namespace app\models;

use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\IdentityInterface;


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
class paUser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
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
/**
根据手机号码获取家长的身份信息
 */
/*    public function getParinfo($params,$role){
        return self::find()->where(["tel"=> $params,'shenfen'=>$role])->asArray()->all();
    }*/
    public function getParinfo($params){
        return self::find()->where(["tel"=> $params])->asArray()->all();
    }

    public function setPassword($password)
    {
        $this->pwd = md5($password);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
        /*foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;*/
    }

    public static function findByUsername($username)
    {
        $user = paUser::find()
            ->where(['name' => $username])
            ->asArray()
            ->one();

        if($user){
            return new static($user);
        }

        return null;
        /*foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;*/
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->pwd === md5($password);
    }

}
