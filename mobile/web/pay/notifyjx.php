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
function caltime($endtime){
    $time=$endtime-time();
    //week
    $week=floor($time/(3600*24*7));
    //days
    $daytime=$time-(3600*24*7*$week);
    $days=floor($daytime/(3600*24));
    //hours
    $hourstime= $daytime-(3600*24*$days);
    $hours=floor($hourstime/3600);
    //seconds
    $seconds=$hourstime-($hours*3600);
    return "+$week week $days days $hours hours $seconds seconds";
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$result = $notify->Handle(true);  //返回false或订单数据
// $log_file ="/data/web/ischool/mobile/web/log2.php";
$result = $result['attach'].'|'.$result['transaction_id'].'|'.$result['openid'];
 // file_put_contents($log_file,$result);
 //  参数以“|”分割，分别为“学校|班级|姓名|学号|内部订单号|总价|支付种类|微信单号|支付人openid”
$result = explode('|', $result);
try{
    $pdo=new PDO('mysql:host=127.0.0.1;dbname=ischool','root','hnzf123456');
    
} catch (PDOException $e) {
    $a='连接失败';
    // file_put_contents($log_file,$a);
}
$sql = "select * from wp_ischool_orderjx where stuid=".$result[3]." and trade_no='" .$result[4]. "' and ispass=0";
$resu=$pdo->query($sql);
if($resu) { 
    $num = 0;
    $pee = $result[6];
    $xqxn = explode('-', $pee);
    $pdxq =$xqxn[4];//获取一学期还是一学年或一个月的值进行判断
    if ("yxqi" == $pdxq) {
        $num = "+6 month";
    } elseif ("yxni" == $pdxq) {
        $num = "+12 month";
    } elseif ("ygyu" == $pdxq) {
        $num = "+1 month";
    } elseif("specialday"== $pdxq){
        $num = caltime(1535644800);
    }else if("onemonth"== $pdxq){
        $num = caltime(1523721600);
    }
    $end = array();
    $end['pa'] = (($xqxn[0] == "pa") ? $num : 0);
    $end["jx"] = (($xqxn[1] == "jx") ? $num : 0);
    $end["qq"] = (($xqxn[2] == "qq") ? $num : 0);
    $end["ck"] = (($xqxn[3] == "ck") ? $num : 0);
    $sql_student = "select enddatepa,enddatejx,enddateqq,enddateck,upendtimepa,upendtimejx,upendtimeqq,upendtimeck from wp_ischool_student where id=". $result[3];
    $old_end=$pdo->query($sql_student);
    $old_enddate = $old_end->fetchAll();
    $old_enddate[0]['enddatepa']=($old_enddate[0]['enddatepa']==NULL) ? 0 : $old_enddate[0]['enddatepa'];
    $old_enddate[0]['enddatejx']=($old_enddate[0]['enddatejx']==NULL) ? 0 : $old_enddate[0]['enddatejx'];
    $old_enddate[0]['enddateqq']=($old_enddate[0]['enddateqq']==NULL) ? 0 : $old_enddate[0]['enddateqq'];
    $old_enddate[0]['enddateck']=($old_enddate[0]['enddateck']==NULL) ? 0 : $old_enddate[0]['enddateck'];
    $old_enddate[0]['upendtimepa']=($old_enddate[0]['upendtimepa']==NULL) ? 0 : $old_enddate[0]['upendtimepa'];
    $old_enddate[0]['upendtimejx']=($old_enddate[0]['upendtimejx']==NULL) ? 0 : $old_enddate[0]['upendtimejx'];
    $old_enddate[0]['upendtimeqq']=($old_enddate[0]['upendtimeqq']==NULL) ? 0 : $old_enddate[0]['upendtimeqq'];
    $old_enddate[0]['upendtimeck']=($old_enddate[0]['upendtimeck']==NULL) ? 0 : $old_enddate[0]['upendtimeck'];
    if($pdxq=="specialday" || $pdxq=="onemonth"){
       $enddatepa = ($end['pa'] == 0) ? $old_enddate[0]['enddatepa']: strtotime($end['pa']);//有效期的时间
       $enddatejx = ($end['jx'] == 0) ? $old_enddate[0]['enddatejx']: strtotime($end['jx']);
       $enddateqq = ($end['qq'] == 0) ? $old_enddate[0]['enddateqq']: strtotime($end['qq']);
       $enddateck = ($end['ck'] == 0) ? $old_enddate[0]['enddateck']: strtotime($end['ck']); 
    } else{
       $enddatepa = ($end['pa'] == 0) ? $old_enddate[0]['enddatepa']: ((!$old_enddate || $old_enddate[0]['enddatepa'] < time())?strtotime($end['pa']):strtotime($end['pa'],$old_enddate[0]['enddatepa']));//有效期的时间
       $enddatejx = ($end['jx'] == 0) ? $old_enddate[0]['enddatejx']: ((!$old_enddate || $old_enddate[0]['enddatejx'] < time())?strtotime($end['jx']):strtotime($end['jx'],$old_enddate[0]['enddatejx']));
       $enddateqq = ($end['qq'] == 0) ? $old_enddate[0]['enddateqq']: ((!$old_enddate || $old_enddate[0]['enddateqq'] < time())?strtotime($end['qq']):strtotime($end['qq'],$old_enddate[0]['enddateqq']));
       $enddateck = ($end['ck'] == 0) ? $old_enddate[0]['enddateck']: ((!$old_enddate || $old_enddate[0]['enddateck'] < time())?strtotime($end['ck']):strtotime($end['ck'],$old_enddate[0]['enddateck']));
    } 
    $untimepa = ($end['pa'] == 0) ? $old_enddate[0]['upendtimepa']:time(); //更新有效期的时间
    $untimejx = ($end['jx'] == 0) ? $old_enddate[0]['upendtimejx']:time();
    $untimeqq = ($end['qq'] == 0) ? $old_enddate[0]['upendtimeqq']:time();
    $untimeck = ($end['ck'] == 0) ? $old_enddate[0]['upendtimeck']:time();
    //三高走截止日期型
    switch($pdxq){
      case 'oneyear':
        $enddatejx=$enddateqq=$enddatepa=$enddateck=1535644800;
        break;
      case 'twoyear':
        $enddatejx=$enddateqq=$enddatepa=$enddateck=1567180800;
        break;
      case 'threeyear':
        $enddatejx=$enddateqq=$enddatepa=$enddateck=1598803200;
        break;
    }
    $up_student_sql = "update wp_ischool_student set enddatepa=".$enddatepa.",enddatejx=".$enddatejx.", enddateqq=".$enddateqq.", enddateck=".$enddateck.",upendtimepa=".$untimepa.",upendtimejx=".$untimejx.",upendtimeqq=".$untimeqq.",upendtimeck=".$untimeck." where id=".$result[3];
 
    if($pdo->query($up_student_sql))
    {
        $ispa = (($xqxn[0] == "pa") ? 1 : 0); //根据支付传参确定是给哪一项产品支付
        $isjx = (($xqxn[1] == "jx") ? 1 : 0);
        $isqq = (($xqxn[2] == "qq") ? 1 : 0);
        $isck = (($xqxn[3] == "ck") ? 1 : 0);
        foreach ($resu as $row) {
           $uid=$row['uid'];   
        }
        $up_order_sql = "update wp_ischool_orderjx set ispasspa=".$ispa.",ispassjx=".$isjx.",ispassqq=".$isqq.",ispassck=".$isck.",utime=".time().",zfopenid='".$result[0]."',trans_id=".$result[7].",zfuid=".$uid." where stuid=".$result[3].
            " and trade_no=".$result[4]." and ispass=0";
        $up_order_sql =$pdo->query($up_order_sql);
        // $syx = $mysql->affected_rows; //记录影响行数
        $openid = $result[0];
        if($up_order_sql){
            $conpa = ($xqxn[0] == "pa") ? "平安通知有效期更新至".date("Y年m月d日",$enddatepa)."。": "";
            $conjx = ($xqxn[1] == "jx") ? "家校沟通有效期更新至".date("Y年m月d日",$enddatejx)."。": "";
            $conqq = ($xqxn[2] == "qq") ? "亲情电话有效期更新至".date("Y年m月d日",$enddateqq)."。": "";
            $conck = ($xqxn[3] == "ck") ? "一卡通充值微信充值有效期更新至".date("Y年m月d日",$enddateck).",非常感谢您对正梵公司的支持。": "";
            $content = "尊敬的家长您好!"."您已为学生".$result[2]."缴费".$result[5]."元，".$conpa.$conjx.$conqq.$conck;
            $title="开通成功";
       
        if($result[1]==56650){
             SendMsgs::sendSHMsgToPa($openid,$title,$content,$url="http://mobile.jxqwt.cn/information/index?openid=".$openid."&sid=56650",$picurl="");  
         }else{
             SendMsgs::sendSHMsgToPa($openid,$title,$content,$url="http://mobile.jxqwt.cn/information/index?openid=".$openid."&sid=".$result[1],$picurl=""); 
         }
        }

    }
} 
 




