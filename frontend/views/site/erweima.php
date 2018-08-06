<?php
use yii\helpers\Url;
?>
<div style="margin: 150px 0 100px 0; text-align: center;">
<img width="200px" height="200px" src="/site/qrcode?userid=<?=$userid;?>&sid=<?=$sid?>" />
    <p>请用微信扫一扫二维码完成登录！</p>
    <p>(温馨提示：一个微信号只能注册一个身份，不能重复注册多个身份！)</p>
</div>

<script type="text/javascript">
    setInterval("myInterval()",5000);//1000为1秒钟
    function myInterval()
    {
       var url = "/site/checkopid";
        var formdata = {};
        formdata.userid = <?=$userid;?>;
        $.post(url,formdata).done(function (data){
            if (data == 0){
                window.location = "/site/denglu";
            }
        })
    }
</script>