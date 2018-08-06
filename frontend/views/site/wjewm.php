<?php
use yii\helpers\Url;
?>
<style>
          .lg_nav a{
            font-size: 16px;
            padding-right: 20px;
          }
            .zc_li{
                margin: 15px auto;
            }
            .lg_cont{
                background: url('/img/lg_bj.png') no-repeat center center;
                width: 100%;
                min-height: 600px;
                background-size: 100% 100%;
                padding-top: 50px;
            }
            .ewm_yz{
                background-color: white;
                width: 300px;
                margin: 50px auto 0px;
                text-align: center;
                padding: 50px 10px;
            }
            .ewm_yz p{
                margin-top: 50px;
                color: #666;
            }
        </style>
    <div class="lg_cont">
        <div class="ewm_yz">
            <img width="200px" height="200px" src="/site/wjqrcode?userid=<?=$userid;?>&sid=<?=$sid?>&oldopid=<?=$oldopid;?>" />
            <p class="text-center">使用微信扫描二维码，验证身份信息进行密码重置，重置密码成功后，点击返回<a href="http://pc.jxqwt.cn">登录</a></p>
        </div>
    </div>

<script type="text/javascript">
//    setInterval("myInterval()",5000);//1000为1秒钟
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