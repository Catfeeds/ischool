<?php
use mobile\assets\Mysqli;
/**
 * Created by PhpStorm.
 * User: hhb
 * 用于发送微信信息的静态类
 * Date: 2016/2/24 0024
 * Time: 下午 14:33
 */
$config = include 'weixin/config.php';

class SendMsg{
    static $COM_KF_URL = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=";
//  static $COM_KF_URL = "https://140.206.160.101/cgi-bin/message/custom/send?access_token=";
    static $COM_MB_RUL = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=";
    static $COM_PIC_URL = "/upload/syspic/msg.jpg";

    function getMysqli()
    {
        global $config;
        $mysql = new Mysqli($config['MYSQL_HOST'], $config['MYSQL_USER'], $config['MYSQL_PASS'], $config['ZF_MYSQL_DB']);
        return $mysql;
    }


    /**
     * @param $type [客服消息|模版消息]
     * @return string 返回发消息的url
     */
    static function getUrl($type){
        $access_token = self::getAccessToken();
        if ($type == 'kf') {
            $url = self::$COM_KF_URL.$access_token;
        }else{
            $url = self::$COM_MB_RUL.$access_token;
        }
        return $url;
    }

    /**
     * @return mixed 返回ACCESS_TOKEN
     */
    static function getAccessToken(){
        $appId     = 'wx8c6755d40004036d';
        $appSecret ='22f68f4da5b36641ed492c596406b75f';
        $token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appSecret;
                $redis = new \redis();
                $result = $redis->connect('127.0.0.1', 6379);
                if ($result) {
                        $acc_token = $redis->get("my_access_token");
                        if (!$acc_token) {
                                $json = file_get_contents($token_url);
                                $result = json_decode($json);
                                $acc_token = $result->access_token;
                                $redis->set("my_access_token", $acc_token, 7100);
                        }

                } else {
                        $json = file_get_contents($token_url);
                        $result = json_decode($json);
                        $acc_token = $result->access_token;
                }

                return $acc_token;


	/*
        $my_token = "select * FROM wp_ischool_access_token LIMIT 0,1";
        $now = time();
        $mysql = getMysql();
        if(!empty($my_token)){
            if(($now-$my_token[0]['last_time']) < 1800 && !empty($my_token[0]['access_token'])){  //微信客户端认证access_token是否超过两小时，是就重新去微信抓去
                $acc_token = $my_token[0]['access_token'];
            }else{
                $json   = file_get_contents($token_url);
                $result = json_decode($json);
                $acc_token = $result->access_token;
                $conid = $my_token[0]['id'];
//                $con['id'] = $my_token[0]['id'];
//                $data['access_token'] = $acc_token;
//                $data['last_time'] = $now;
//                $d->where($con)->save($data);
                $upmy_token = "UPDATE wp_ischool_access_token SET access_token=".$acc_token.",last_time=".$now." WHERE id='$conid'";
                $re_upmy_token = $mysql->query($upmy_token);
            }
        }else{
            $json   = file_get_contents($token_url);
            $result = json_decode($json);
            $acc_token = $result->access_token;

            $data['access_token'] = $acc_token;
            $data['last_time']    = $now;
            $add_my_token = "insert into  wp_ischool_access_token(`access_token` ,`last_time`) values ($acc_token,$now)";
            $readd_my_token =$mysql->query($add_my_token);
//            $d->add($data);
        }

        return $acc_token;*/
    }

    static function singlePostMsg($url,$data){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置header
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return array("errcode"=>-1,"errmsg"=>'发送错误号'.curl_errno($curl).'错误信息'.curl_error($curl));
        }

        curl_close($curl);
        return $result;
    }

    /**
     * @param $url
     * @param $data
     * @return array|mixed
     * 执行http_post请求的公用方法
     */
    static public function https_post($url,$data){
        return self::singlePostMsg($url,$data);
    }

}
