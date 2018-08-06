<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
use mobile\assets\Redis;

require_once '.'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'WxPay.Api.php';
require_once '.'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'WxPay.Notify.php';
require_once 'log.php';

$logHandler= new CLogFileHandler(".".DIRECTORY_SEPARATOR."logs".DIRECTORY_SEPARATOR.date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}

	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));

		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}

		return true;
	}
}

//
Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$result = $notify->Handle(true);  //返回false或订单数据
////本地订单处理逻辑
if($result){
    Log::DEBUG("redis begin");
    $trade_msg = $result['attach'].'|'.$result['transaction_id'].'|'.$result['openid'];
    try{
        $redis = new Redis();
        $redis->connect('127.0.0.1',6379,5); //本机6379端口，5秒超时
        $redis->auth('');  //鉴权
        $redis->select(8);  //1库
        $redis->set('order_'.$result['out_trade_no'],$trade_msg,3600*24*7);
        $redis->close();
    }catch (Exception $e){
        LOG::DEBUG('redis exception:exc_no-'.$e->getCode().'exc_msg-'.$e->getMessage().'trade_msg-'.$trade_msg);
    }

    LOG::DEBUG("redis end");
    exit;
}

