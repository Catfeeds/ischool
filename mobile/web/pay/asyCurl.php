<?php
/**
 * Created by PhpStorm.
 * User: hhb
 * Date: 2016/3/16 0016
 * Time: 上午 10:31
 * 异步通知服务器端的curl方法
 */
require_once 'log.php';
$logHandler= new CLogFileHandler(".".DIRECTORY_SEPARATOR."logs".DIRECTORY_SEPARATOR.date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

function asyCurl($url,$data){
    //初始化日志
    Log::DEBUG("saycurl---".json_encode($data));
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS , 100);
    curl_setopt($curl, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);  //http强制版本
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Expect:"));  //1024字节分批确认问题
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );  //设定ipv4
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($curl);
    if (curl_errno($curl)) {
        Log::DEBUG('错误号'.curl_errno($curl).'错误信息'.curl_error($curl));
    }
    curl_getinfo($curl);
    curl_close($curl);
    return 0;
}