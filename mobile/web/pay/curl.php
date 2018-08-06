<?php
/**
 * Created by PhpStorm.
 * User: hhb
 * Date: 2016/3/23 0023
 * Time: 下午 13:41
 */

function asyCurl($url,$data){
    //初始化日志
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);  //http强制版本
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Expect:"));  //1024字节分批确认问题
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );  //设定ipv4
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($curl);
    if (curl_errno($curl)) {
        echo '错误号'.curl_errno($curl).'错误信息'.curl_error($curl);
    }
    curl_getinfo($curl);
    curl_close($curl);
    return 0;
}

function sycurl($url,$data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS , 10);//1秒后立即执行
    curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);  //http强制版本
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));  //1024字节分批确认问题
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );  //设定ipv4
    curl_exec($ch);
    curl_close($ch);
    return 0;
}
$url = "http://www.henanzhengfan.com/ischool/index.php?s=/addon/Apiservice/Apiservice/sendSafeMsgBySid/sid/2/cid/C48E9EE373AFE4404037E36B/info/100/time/1456989009.html";

echo '开始.....'.microtime().' ...';
sycurl($url,array());
echo '结束.....'.microtime();

//echo phpinfo();

