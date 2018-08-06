<!-- <link rel="stylesheet" href="/im/im.css">
<div id="rcs-app"></div>
<script src="//cdn.ronghub.com/RongIMLib-2.3.0.js"></script>
<script src="//cdn.ronghub.com/RongEmoji-2.2.6.min.js"></script> 
<script src="/im/libs/utils.js"></script>
<script src="/im/libs/qiniu-upload.js"></script>
<script src="/im/template.js"></script>
<script src="/im/emoji.js"></script>
<script src="/im/im.js"></script> -->

<!-- 实例化 -->
<script>
(function(){
    var appKey = "<?= $appkey?>";
    var token = "<?= $token?>";
    RCS.init({
        appKey: appKey,
        token: token,
        target: document.getElementById('rcs-app'),
        showConversitionList: true,
        templates: {
            button: ['<div class="rongcloud-consult rongcloud-im-consult">',
                    '   <button onclick="RCS.showCommon()"><span class="rongcloud-im-icon">会话列表</span></button>',
                    '</div>',
                    '<div class="customer-service" ></div>'].join('')//"templates/button.html",
        },
        extraInfo: {
            // 当前登陆用户信息
            userInfo: {
                name: "游客",
                grade: "VIP"
            },
            // 产品信息
            requestInfo: {
                productId: "123",
                referrer: "10001",
                define: "" // 自定义信息
            }
        }
    });
})()


</script>

</html>