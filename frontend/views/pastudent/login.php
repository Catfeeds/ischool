<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\controllers\PastudentController;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <script src="HTML/js/jquery-1.4.1.min.js" type="text/javascript"></script>
    <script type="text/javascript">

        /*-------------------------------------------*/
        var InterValObj; //timer变量，控制时间
        var count = 5; //间隔函数，1秒执行
        var curCount = 60;//当前剩余秒数
        var code = ""; //验证码
        var codeLength = 6;//验证码长度
        function sendMessage() {
            curCount = count;
            var dealType; //验证方式
            var uid=$("#uid").val();//用户uid
            if ($("#phone").attr("checked") == true) {
                dealType = "phone";
            }
            else {
                dealType = "email";
            }
            //产生验证码
            for (var i = 0; i < codeLength; i++) {
                code += parseInt(Math.random() * 9).toString();
            }
            //设置button效果，开始计时
            $("#btnSendCode").attr("disabled", "true");
            $("#btnSendCode").val("请在" + curCount + "秒内输入验证码");
            InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
//向后台发送处理数据
            $.ajax({
                type: "POST", //用POST方式传输
                dataType: "text", //数据格式:JSON
                url: 'Login.ashx', //目标地址
                data: "dealType=" + dealType +"&uid=" + uid + "&code=" + code,
                error: function (XMLHttpRequest, textStatus, errorThrown) { },
                success: function (msg){ }
            });
        }
        //timer处理函数
        function SetRemainTime() {
            if (curCount == 0) {
                window.clearInterval(InterValObj);//停止计时器
                $("#btnSendCode").removeAttr("disabled");//启用按钮
                $("#btnSendCode").val("重新发送验证码");
                code = ""; //清除验证码。如果不清除，过时间后，输入收到的验证码依然有效
            }
            else {
                curCount--;
                $("#btnSendCode").val("请在" + curCount + "秒内输入验证码");
            }
        }
    </script>
</head>
<body>
<input id="btnSendCode" type="button" value="发送验证码" onclick="sendMessage()" /></p>
</body>
</html>