<?php
$config = include __DIR__.'/pay/config.php';
// 参数数组
$url =getUrl();

function singlePostMsg($url,$data){
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



function getUrl($type = ''){
    $access_token = getAccessToken();
    $uri = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
    return $uri;
}

function getAccessToken(){
    global $config;
    $appId = $config['APPID'];
    $appSecret = $config['APPSECRET'];
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
    $mysql = getMysqli();
    $remy_token = $mysql->query($my_token)->fetch_assoc();

    if(!empty($remy_token)){
        if(($now - $remy_token['last_time']) < 1800 && !empty($remy_token['access_token'])){  //微信客户端认证access_token是否超过两小时，是就重新去微信抓去
            $acc_token = $remy_token['access_token'];
        }else{
            $json = file_get_contents($token_url);
            $result = json_decode($json);
            $acc_token = $result->access_token;

            $conid = $remy_token['id'];
            $upmy_token = "UPDATE wp_ischool_access_token SET access_token='".$acc_token."',last_time=".$now." WHERE id='$conid'";
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
    }

    return $acc_token;*/
	
}

function getMysqli()
{
    global $config;
    $mysql = new mysqli($config['DB_HOST'], $config['DB_USER'], $config['DB_PWD'], $config['DB_NAME']);
    return $mysql;
}

