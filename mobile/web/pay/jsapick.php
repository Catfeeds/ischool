<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once ".".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';
//
//初始化日志
$logHandler= new CLogFileHandler(".".DIRECTORY_SEPARATOR."logs".DIRECTORY_SEPARATOR.date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}
//payInfo由支付模块传递“学校|班级|姓名|学号|内部单号|总价|支付种类”
@$payInfo = $_GET['payInfo'];//用户下单前传来的自定义信息包括金额，商品信息等

//①、获取用户openid
$tools = new JsApiPay();
$opid = $tools->GetOpenid($payInfo);


// $log::DEBUG("second is ".time());
//②、统一下单
$state = $_GET['state']; //从auth传递而来
$newstate = explode("|",$state);
// $body  = $newstate[0].$newstate[1].$newstate[2];
$body  = $newstate[2];
$total_fee = $newstate[5]; //以元为单位
$input = new WxPayUnifiedOrder();
$input->SetBody($body);
$input->SetAttach($state); //通知信息中会带此参数，便于后续逻辑处理
$input->SetOut_trade_no($newstate[4]);
$log::DEBUG("trade_no---".$newstate[4]);
$input->SetTotal_fee($total_fee * 100); //下单界面以分为单位
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("餐卡充值");
$input->SetNotify_url(WxPayConfig::APP_PAY_URL."notifyck.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($opid);
$order = WxPayApi::unifiedOrder($input);

$log::DEBUG("third is ".time());
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

$log::DEBUG("forth is ".time());
////
////////获取共享收货地址js函数参数
//$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>餐卡充值中心</title>
    <style type="text/css">
        body{
            margin: 0;
            padding: 0;
            font-size:15px
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

        .wx-pay{
            margin-top: 50px;
        }
        #wx-pay{
            width: 50%;
            margin-left: 25%;
            text-align: center;
            line-height: 50px;
            background-color: #E74C3C;
            border-radius: 7px;
        }
    </style>
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $jsApiParameters; ?>,
                function(res){
                    WeixinJSBridge.log(res.err_msg);
                    if(res.err_msg == 'get_brand_wcpay_request:ok'){
                        alert("支付成功!");
                        window.location.href = "<?php echo WxPayConfig::REDIRECT_URLZ?>";
                    }else if(res.err_msg == "get_brand_wcpay_request:cancel"){
                        alert("您已取消支付");
                    }else{
                        alert("支付失败，错误信息：" + res.err_msg);
                    }
                }
            );
        }

        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }

    </script>
</head>
<body>
<div style="background-color:#6ec74f;padding:10px 10px">
    <a href="javascript:history.back(-1)" style="color: white;text-decoration: none;padding-top: 10px;margin-top:100px">&lt;返回</a>
</div>
<div class="pay-main">
    <div class="pay-wapper">
        <div class="pay-info">
            <div id="pay-wapper">
                <div class="pay-title">学生名称:</div>
                <div class="pay-name"><?php echo $newstate[2];?></div>
                <div class="clear"></div>
            </div>
            <div id="money-wapper">
                <div class="money-title">共计:</div>
                <div class="money-num"><?php echo $total_fee; ?>元</div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="wx-pay">
            <div id="wx-pay" onclick="callpay()">立即支付</div>
        </div>
    </div>
</div>
</body>
</html>

