<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once '.'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'WxPay.Api.php';
require_once '.'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'WxPay.Notify.php';
include  'SendMsgs.php';
$config = require_once('.'.DIRECTORY_SEPARATOR.'weixin'.DIRECTORY_SEPARATOR.'config.php');
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

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$result = $notify->Handle(true);  //返回false或订单数据
$result = $result['attach'].'|'.$result['transaction_id'].'|'.$result['openid'];
$log_files ="/data/web/ischool/mobile/web/log5.php";
//参数以“|”分割，分别为“学校|班级|姓名|学号|内部订单号|总价|支付种类|微信单号|支付人openid”
$result = explode('|', $result);
if(in_array($result[1],$config['cooperative.school'])){
	 // $requst_info=[
  //       'actionStr'=>'YKT_AUTHENTICATION',
  //       'version'=>'200',
  //       'thirdCode'=>'',
  //       'serialType'=>'1',
  //       'serialValue'=>$result[3],
  //       'name'=>$result[2]
  //   ];
  //   $jsonInfo=getPostInfo($requst_info);
  //   if($jsonInfo['resultCode']=='0000'){

    	$send_info=[
	        'actionStr'=>'YKT_RECHARGE',
	        'version'=>'200',
	        'thirdCode'=>'',
	        'tradeNo'=>$result[4],
	        'name'=>$result[2],
	        'custNo'=>$result[3],
	        'phone'=>'',
	        'money'=>$result[5]*100,
	        'tradeTime'=>date("Ymd",time())
	    ];
	    $jsonInfo1=getPostInfo($send_info);
	    if($jsonInfo1['resultCode']=='0000'){	    	
	    	$openid = $result[0];
	    	$content="餐卡充值系统提醒!"."您已为学生".$result[2]."的卡号充值".$result[5]."元，谢谢您的使用！";
		    $title="充值成功";
		    SendMsgs::sendSHMsgToPa($openid,$title,$content,$url="",$picurl="");
		    file_put_contents($log_files,json_encode($jsonInfo1 , JSON_UNESCAPED_UNICODE));
	    	
	    }else{
	    	file_put_contents($log_files,json_encode($jsonInfo1 , JSON_UNESCAPED_UNICODE));
	    }
    // }else{
    // 	file_put_contents($log_files,json_encode($jsonInfo , JSON_UNESCAPED_UNICODE));
    // }			    	       
}else{
	try{
        $pdo=new PDO('mysql:host=127.0.0.1;dbname=card','root','hnzf123456');  
    } catch (PDOException $e) {
        $a='连接失败';
        file_put_contents($log_files,$a);
    }
	if(in_array($result[1],$config['zfend.school'])){
		if(preg_match('/[a-zA-Z]/',$result[3])){
			$s_id = substr($result[3], 1, 5);
		    $result[3] = substr($result[3],6);
		}else{
			$s_id ='56651';
			$result[3] = substr($result[3],2);
		}
	}else{
		$s_id =$result[1];
	}
	$sql = "select card_no,balance,created_by from zf_card_info where  user_no='".$result[3]."' and school_id = ".$s_id;
	$resu=$pdo->query($sql);
	if($resu){
	    $resu = $resu->fetchAll();
	    $pee = $result[5]; //总价 	
	    $bhje = $resu[0]['balance']+$pee;//变化金额
	    $utime = time();
	    $trade_sql="select * from zf_recharge_detail where trade_no = '".$result[4]."'";
	    $trade_no=$pdo->query($trade_sql);
	    if(!$trade_no->rowCount()){
	        $up_rechange_sql="insert into zf_recharge_detail (card_no,credit,type,balance,pos_no,created_by,time,note,is_active,school_id,trade_no) values ('". $resu[0]['card_no']."',$result[5],'weixinchongzhi','0','0','".$resu[0]['created_by']."','$utime','0','0','".$s_id."','".$result[4]."') ";
	        if($pdo->exec($up_rechange_sql)){
	           $openid = $result[0];
	            if(true){
	                $content="餐卡充值系统提醒!"."您已为学生".$result[2]."的卡号充值".$pee."元，谢谢您的使用！";
	                $titie="充值成功";
	                if($s_id==56650 || $s_id==56651){
			             SendMsgs::sendSHMsgToPa($openid,$title,$content,$url="http://mobile.jxqwt.cn/information/index?openid='".$openid."'&sid=56650",$picurl="http://mobile.jxqwt.cn/upload/syspic/56650.jpg");  
			        }else{
			             SendMsgs::sendSHMsgToPa($openid,$title,$content,$url="http://mobile.jxqwt.cn/information/index?openid='".$openid."'&sid=".$s_id,$picurl=""); 
			        }
			        $pdo=null;
	            }

	        }
	        
	    }

	}
}

function PostCurl($url,$data){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置header
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($data)));
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        return array("errcode"=>-1,"errmsg"=>'发送错误号'.curl_errno($curl).'错误信息'.curl_error($curl));
    }

    curl_close($curl);
    return json_decode($result, true);
}

function getPostInfo($arrInfo){
    $url="http://60.205.148.191:8080/yqsh_third/api/onecard/message";
    //申请商户的时候生成的key
    $key="44EC6C5C17BA74D1759AB0AEB782E4EE";
    //第三方代码
    $thirdCode='510102180306020203';
    $arrInfo['thirdCode']=$thirdCode;
    $new_array=$arrInfo;
    if($new_array['name']!=''){
        unset($new_array['name']);
    }
    $mac=strtoupper(md5(implode('',$new_array).$key));          
    $arrInfo['mac']=$mac;       
    $data=json_encode($arrInfo , JSON_UNESCAPED_UNICODE);
    $result=PostCurl($url,$data);
    return $result;
}