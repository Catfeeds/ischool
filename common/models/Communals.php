<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Communals extends  \yii\db\ActiveRecord
{

    public function register_im($tel, $password) {
        \yii::trace(6666666);
        if (empty($tel) || empty($password) || !preg_match("/^1\d{10}$/", $tel)) {
            return false;
        }
        $posturl = "http://im.henanzhengfan.com:5281/api/register";
        $postData = [
            "user" => $tel,
            "host" => "im.henanzhengfan.com",
            "password" => $password,
        ];
        $postData = json_encode($postData);
        $user = \Yii::$app->params['IM_ADMIN_USER'];
        $pass = \Yii::$app->params['IM_ADMIN_PASSWORD'];;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $posturl); //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_USERPWD, "{$user}:{$pass}");
        $data = curl_exec($ch); //运行curl
        \yii::trace($data);
        if (strpos($data, 'successfully registered')) {
            \yii::trace(2222222);
            return true;
        } else {
            \yii::trace(3333333);
            return false;
        }

    }
   
}
