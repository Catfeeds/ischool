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
// $log_files ="/data/web/ischool/mobile/web/log5.php";

 //  参数以“|”分割，分别为“学校|班级|姓名|学号|内部订单号|总价|支付种类|微信单号|支付人openid”
$result = explode('|', $result);
try{
    $pdo=new PDO('mysql:host=127.0.0.1;dbname=card_water','root','hnzf123456');
    
 } catch (PDOException $e) {
     $a='连接失败';
     file_put_contents($log_files,$a);
 }
if($result[1]==56650 || $result[1]==56758 || $result[1]==56757){
	if(preg_match('/[a-zA-Z]/',$result[3])){
		$s_id = substr($result[3], 1, 5);
	    $result[3] = substr($result[3],6);
	}else{
		$s_id =$result[1];
		$result[3] = substr($result[3],2);
	}
}else{
	$s_id =$result[1];
}
// file_put_contents($log_files,$result[3].$s_id);
$sql = "select card_no,balance,created_by from zf_card_info where  user_no='".$result[3]."' and school_id = ".$s_id;
$resu=$pdo->query($sql);
if($resu){
    $resu = $resu->fetchAll();
//    file_put_contents($log_file,$resu[0]['balance']);
    $pee = $result[5]; //总价
  	
    $bhje = $resu[0]['balance']+$pee;//变化金额
    $utime = time();
   $trade_sql="select * from zf_recharge_detail where trade_no = '".$result[4]."'";
//   $trade_sql="select * from zf_recharge_detail where trade_no='2017071501219840665' ";
    $trade_no=$pdo->query($trade_sql);
    if(!$trade_no->rowCount()){
        $up_rechange_sql="insert into zf_recharge_detail (id,card_no,credit,type,balance,pos_no,created_by,time,note,is_active,school_id,trade_no) values ('','". $resu[0]['card_no']."',$result[5],'weixinchongzhi','0','0','".$resu[0]['created_by']."','$utime','0','0','".$s_id."','".$result[4]."') ";

        if($pdo->exec($up_rechange_sql)){
           $openid = $result[0];
            if(true){
                $content="水卡充值系统提醒!"."您已为学生".$result[2]."的卡号充值".$pee."元，谢谢您的使用！";
                $titie="充值成功";
                if($s_id==56650 || $s_id==56651){
		             SendMsgs::sendSHMsgToPa($openid,$title,$content,$url="http://mobile.jxqwt.cn/information/index?openid=".$openid."&sid=56650",$picurl="http://mobile.jxqwt.cn/upload/syspic/56650.jpg");  
		        }else{
		             SendMsgs::sendSHMsgToPa($openid,$title,$content,$url="",$picurl=""); 
		        }
            }

        }
        
    }else{
       
    }
    	
   

}
