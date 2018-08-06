 <?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once '.'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'WxPay.Api.php';
require_once '.'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'WxPay.Notify.php';
require_once 'log.php';
include  'SendMsgs.php';
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


$result = $result['attach'].'|'.$result['transaction_id'].'|'.$result['openid'];
$log_files ="/data/web/ischool/mobile/web/log10.php";

 //  参数以“|”分割，分别为“学校|班级|姓名|学号|内部订单号|总价|支付种类|微信单号|支付人openid”
$result = explode('|', $result);
try{
    $pdo=new PDO('mysql:host=127.0.0.1;dbname=ischool','root','hnzf123456');
    
 } catch (PDOException $e) {
     $a='连接失败';
     file_put_contents($log_files,$a);
 }
//本地订单处理逻辑
$sql = "select id,type from wp_ischool_orderjf where stuid='".$result[3]."' and trade_no='" .$result[4]. "' and issuccess=0";
$resu=$pdo->query($sql);

if($resu->rowCount()){
        // $resu = $resu->fetchAll();
        $pee = $result[5];
         $utime = time();
         // file_put_contents($log_files,$utime);
        $up_order_sql = "update wp_ischool_orderjf set issuccess=1,uptime=".$utime. ",zfopenid='".$result[9]."',trans_id=".$result[8]." where stuid='".$result[3]. "' and trade_no=".$result[4]." and issuccess=0";
        $up_order_sql =$pdo->exec($up_order_sql);
        // $syx = $mysql->affected_rows; //记录影响行数
        $openid = $result[6];
        if($up_order_sql){
            $content="补卡功能费提醒!"."您已为学生".$result[2]."提交学费缴费".$result[5]."元，谢谢您的使用！";
            $titie="充值成功";       
            if($result[1]==56650){
	             SendMsgs::sendSHMsgToPa($openid,$title,$content,$url="http://mobile.jxqwt.cn/information/index?openid=".$openid."&sid=56650",$picurl="");  
	         }else{
	             SendMsgs::sendSHMsgToPa($openid,$title,$content,$url="",$picurl=""); 
	         }
            // SendMsgs::sendSHMsgToPa($openid,$titie,$content);
        } 
}


