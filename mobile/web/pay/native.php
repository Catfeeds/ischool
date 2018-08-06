<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once ".".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."WxPay.Api.php";
require_once "WxPay.NativePay.php";
require_once 'log.php';

//payInfo由支付模块传递“学校|班级|姓名|学号|内部单号|总价”
$state = $_GET['payInfo'];//用户下单前传来的自定义信息包括金额，商品信息等
$newstate = explode("|",$state);
$body  = $newstate[0].$newstate[1].$newstate[2];
$total_fee = $newstate[5]; //以元为单位

$notify = new NativePay();
//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$input = new WxPayUnifiedOrder();
$input->SetBody($body);
$input->SetAttach($state);
$input->SetOut_trade_no($newstate[4]);
$input->SetTotal_fee($total_fee * 100);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("平安通知功能费");
$input->SetNotify_url(WxPayConfig::APP_PAY_URL."notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id($newstate[4]);
$result = $notify->GetPayUrl($input);
$url2 = $result["code_url"];
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>平安通知功能费支付</title>
    <style type="text/css">
        body{
            margin: 0;
            padding: 0;
            font-size:15px;
        }
        .clear{
            clear: both;
        }
        .pay-main{
            margin-top: 20px;
            font-family: "微软雅黑";
            font-size: 1rem;
        }
        .pay-wapper{
            background-color: #ECF0F1;
            color: #FFFFFF;
            width: 100%;
            text-align: center;
            padding: 50px 0 50px 0;
        }
        .pay-info{
            background-color: #2C3E50;
        }
        .pay-wapper{
            margin: 10px 0 10px 0;
        }
        .pay-title{
            float: left;
            width: 30%;
            text-align: right;
            line-height: 50px;
        }
        .pay-name{
            float: left;
            width: 70%;
            line-height: 50px;
        }
        .money-title{
            float: left;
            width: 30%;
            text-align: right;
            line-height: 50px;
        }
        .money-num{
            float: left;
            width: 70%;
            line-height: 50px;
            color: #E74C3C;
        }

        .co-pay{
            margin-top: 30px;
        }
        #co-pay{
            text-align: center;
        }
        #co-pay img{
            width: 50%;
        }
        .pay-text{
            line-height: 50px;
            color: #E74C3C;
            font-family: "微软雅黑";
        }
    </style>

</head>

<body>
<div class="pay-main">
    <div class="pay-wapper">
        <div class="pay-info">
            <div id="pay-wapper">
                <div class="pay-title">学生名称:</div>
                <div class="pay-name"><?php echo $body; ?></div>
                <div class="clear"></div>
            </div>
            <div id="money-wapper">
                <div class="money-title">共计:</div>
                <div class="money-num"><?php echo $total_fee ?>元</div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="co-pay">
            <div id="co-pay" onclick="co_pay()">
                <img alt="扫码支付" src="<?php echo WxPayConfig::APP_PAY_URL; ?>qrcode.php?data=<?php echo urlencode($url2);?>" style="width:150px;height:150px;"/>
            </div>
        </div>
        <div class="pay-text">长按或扫描上图二维码完成支付</div>
    </div>
</div>
<body>
</html>