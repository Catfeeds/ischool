<script>
    //发送验证码时添加cookie
    function addCookie(name,value,expiresHours){
        var cookieString=name+"="+escape(value);
        //判断是否设置过期时间,0代表关闭浏览器时失效
        if(expiresHours>0){
            var date=new Date();
            date.setTime(date.getTime()+expiresHours*1000);
            cookieString=cookieString+";expires=" + date.toUTCString();
        }    document.cookie=cookieString;
    }
    //修改cookie的值
    function editCookie(name,value,expiresHours){
        var cookieString=name+"="+escape(value);
        if(expiresHours>0){
            var date=new Date();
            date.setTime(date.getTime()+expiresHours*1000); //单位毫秒
            cookieString=cookieString+";expires=" + date.toGMTString();
        }    document.cookie=cookieString;
    }
    //根据名字获取cookie的值
    function getCookieValue(name){
        var strCookie=document.cookie;
        var arrCookie=strCookie.split("; ");
        for(var i=0;i<arrCookie.length;i++){
            var arr=arrCookie[i].split("=");
            if(arr[0]==name){
                return unescape(arr[1]);
                break;
            }
        }

    }

    $(function(){
        $("#second").click(function (){
            sendCode($("#second"));
        });
        v = getCookieValue("secondsremained_login") ? getCookieValue("secondsremained_login") : 0;//获取cookie值
        if(v>0){
            settime($("#second"));//开始倒计时
        }
    })

    //发送验证码
    function sendCode(obj){
        var site = 'http://xxxx.com.cn/';
        var mobile = $("#loginform-mobile").val();

        //检查手机是否合法
        var result = isPhoneNum();
        if(result){
            //检查手机号码是否存在
            var exists_result = dbCheckMobileExists(site+'site/check-mobile-exists',{"mobile":mobile});
            if(exists_result){
                doPostBack(site+'sms/send-login-code',{"mobile":mobile});
                addCookie("secondsremained_login",60,60);//添加cookie记录,有效时间60s
                settime(obj);//开始倒计时
            }
        }
    }
    //检查手机号码是否存在
    function dbCheckMobileExists(url,queryParam){
        $.ajax({        async : false,
            cache : false,
            type : 'POST',
            url : url,// 请求的action路径
            data:queryParam,
            error : function() {// 请求失败处理函数
            },
            success:function(result){
                if(result=='Success'){
                    return true;
                }
                else{
                    alert('该手机号码不存在！');
                    return false;
                }
            }
        });
    }
    //将手机利用ajax提交到后台的发短信接口
    function doPostBack(url,queryParam) {
        $.ajax({        async : false,
            cache : false,
            type : 'POST',
            url : url,// 请求的action路径
            data:queryParam,
            error : function() {// 请求失败处理函数
            },
            success:function(result){
                if(result=='Success'){
                    alert('短信发送成功，验证码10分钟内有效,请注意查看手机短信。如果未收到短信，请在60秒后重试！');
                }
                else{
                    alert('短信发送失败，请和网站客服联系！');
                    return false;
                }
            }
        });
    }
    //开始倒计时
    var countdown;
    function settime(obj) {
        countdown=getCookieValue("secondsremained_login") ? getCookieValue("secondsremained_login") : 0;
        if (countdown ==0) {
            obj.removeAttr("disabled");
            obj.val("获取验证码");
            return;
        } else {
            obj.attr("disabled", true);
            obj.val(countdown + "秒后重发");
            countdown--;
            editCookie("secondsremained_login",countdown,countdown+1);
        }
        setTimeout(function() { settime(obj) },1000) //每1000毫秒执行一次}

//校验手机号是否合法
        function isPhoneNum(){
            var phonenum = $("#loginform-mobile").val();
            var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
            if(!myreg.test(phonenum)){
                alert('请输入有效的手机号码！');
                $("#loginform-mobile").focus();
                return false;
            }else{
                return true;
            }
        }
</script>