    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">密码修改</h4>
        </div>
        <form class="form-horizontal Modify_pwd" action="/pastudent/updatepwd" method="post" onsubmit="return checkpwd()">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="pwd">当前密码:</label>
                <div class="col-sm-6">
                    <input class="form-control" type="password" name="pwd" id="pwd" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="newPwd">新密码:</label>
                <div class="col-sm-6">
                    <input class="form-control" type="password" name="newPwd" id="newPwd" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="Confirm_pwd">确认密码:</label>
                <div class="col-sm-6">
                    <input class="form-control" type="password" name="Confirm_pwd" id="Confirm_pwd" />
                </div>
            </div>
            <!--<div class="form-group">
                <label class="col-sm-2 control-label" for="phone">手机验证:</label>
                <div class="col-sm-6">
                    <input class="form-control" type="tel" name="phone" id="phone" />
                </div>
            </div>
            <div class="form-group form-inline">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-6 row" style="padding: 0;">
                    <div class="col-lg-5">
                        <button type="button" class="btn btn-block">获取短信验证码</button>
                    </div>
                    <div class="col-lg-5">
                        <input class="form-control" type="text" placeholder="请输入验证码" />
                    </div>
                </div>
            </div>-->
            <div class="form-group">
                <div class="col-sm-2 col-sm-offset-2">
                    <input class="form-control btn-success" type="submit" />
                </div>
            </div>
        </form>
    </div>
<!--    <div style="background-color: #ffffed;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">-->
<!--        <img src="/img/pc_dng.png" />-->
<!--        <span style="vertical-align: middle;padding-left: 5px;">密码修改成功。</span>-->
<!--    </div>-->
</div>
</div>
</div>

<script>
    function checkpwd(){
        var pwd = $("#pwd").val();
        var newpwd = $("#newPwd").val();
        var Confirm_pwd = $("#Confirm_pwd").val();
        if (pwd == "" || newpwd =="" || Confirm_pwd ==""){
            alert("请确认信息填写完整！");
            return false;
        }
        if (newpwd != Confirm_pwd){
            alert("确认密码填写不正确！");
            return false;
        }
        return true;
    }
</script>