<div class="lg_cont">
    <div class="lg_fd">
        <div class="lg_left">
            <img src="/img/zhuc.png?r=<?php echo(rand(10,100)); ?>" />
        </div>
        <div class="lg_right">
            <ul class="nav nav-tabs nav-justified" id="table1">
                <li class="active" id="tabdenglu">
                    <a style="font-size: 16px;" href="#denglu" data-toggle="tab">登录账号</a>
                </li>
                <li id="tabzhuce">
                    <a style="font-size: 16px;" href="#zhuce" data-toggle="tab">注册账号</a>
                </li>
            </ul>
            <div class="tab-content" style="font-size: 14px;padding:30px 50px;">
                <form id="denglu" class="tab-pane active" method="post" action="/site/denglu" onsubmit="return checkdlinfo()">
                    <div class="input-group zc_li">
                <span class="input-group-addon">
                  <span class="	glyphicon glyphicon-user"></span>
                </span>
                        <input class="form-control" type="text" placeholder="手机号" id="telephonedl" name="telephone" />
                    </div>
                    <div class="input-group zc_li">
                <span class="input-group-addon">
                  <span class="	glyphicon glyphicon-lock"></span>
                </span>
                        <input class="form-control" type="password" placeholder="密码" id="passworddl" name="password"/>
                    </div>
                    <div>
                        <input class="btn btn-primary form-control dl" type="submit" value="登 录" />
                    </div>
                    <div class="row">
                        <div class="col-xs-8"></div>
                        <div class="col-xs-4"><a data-toggle="modal" data-target="#wjmodal" href="###">忘记密码？</a></div>
                    </div>
                </form>

                <div class="modal fade" id="wjmodal">
                    <div class="modal-dialog">
                        <form class="modal-content form-horizontal" id="form2" action="/site/wjewm" method="post" onsubmit="return checktel()">
                            <div class="modal-header">
                                请输入您的手机号码
                                <button class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                    <button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group text-center">
                                    <label for="lastname" class="col-sm-4 control-label">手机号：</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="userPhonwj" id="userPhonwj" class="form-control" placeholder="请输入正确的手机号...">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" >
                                    确定</button>
                                <button><button class="btn btn-danger" data-dismiss="modal">
                                        取消
                                        <button>
                            </div>
                        </form>
                    </div>
                </div>

                <form id="zhuce" class="tab-pane" method="post" action="/site/zhuce" onsubmit="return checkinfo()">
                    <div class="row">
                        <div class="col-xs-6">请选择用户类型</div>
                        <div class="col-xs-3">
                            <input id="Parent" type="radio" name="role" checked="checked" value="jiazhang" />
                            <label for="Parent">家长</label>
                        </div>
                        <div class="col-xs-3">
                            <input id="Teacher" type="radio" name="role" value="tea" />
                            <label for="Teacher">老师</label>
                        </div>
                    </div>
                    <div class="zc_cnt">
                        <div class="pull-left">选择学校</div>
                        <div class="pull-left">
                            <div class="lx">
                                <select class="form-control" name="province" id="province" onchange="changePro(this.value)">
                                    <option value="">省份</option>
                                    <?php foreach($info['pro'] as $value){?>
                                        <option><?php echo $value['pro']; ?></option>
                                    <?php }?>
                                </select>
                                <select class="form-control" name="city" id="city" onchange="changeCity(this.value)">
                                    <option value="">地级市</option>
                                </select>
                            </div>
                            <div class="lx">
                                <select class="form-control" name="County" id="County" onchange="changeSchool(this.value)">
                                    <option value="">县/区</option>
                                </select>
                                <select class="form-control"  name="School" id="School">
                                    <option value="">学校</option>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="input-group zc_li">
                <span class="input-group-addon">
                  <span class="	glyphicon glyphicon-user"></span>
                </span>
                        <input class="form-control" type="text" placeholder="请输入手机号"  id="telephone" name="telephone"/>
                    </div>
                    <div class="input-group zc_li">
                <span class="input-group-addon">
                  <span class="	glyphicon glyphicon-lock"></span>
                </span>
                        <input class="form-control" type="password" placeholder="请输入密码" id="password" name="password"/>
                    </div>
                    <div class="input-group zc_li">
                <span class="input-group-addon">
                  <span class="	glyphicon glyphicon-lock"></span>
                </span>
                        <input class="form-control" type="password" placeholder="请重复输入密码" id="passwordt" name="passwordt"/>
                    </div>
                    <div class="zh dl">
                        <input class="btn btn-primary form-control" type="submit" value="注册" />
                    </div>
                </form>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<script>
    function checkinfo(){
        var role = $("#zhuce").find('input:radio:checked').attr("id");
        var tel = $("#telephone").val();
        var password = $("#password").val();
        var passwordt = $("#passwordt").val();
        var school = $("#School").val();
        if (school ==""){
            alert("学校必须选择！");
            return false;
        }
        if (role =="" || tel =="" || password=="" || passwordt ==""){
            alert("信息必须填写完整");
            return false;
        }
        if (password != passwordt){
            alert("两次输入密码不一致,请重新输入！");
            return false;
        }
        var myreg = /^1[3|4|5|7|8][0-9]{9}$/;
        if(!myreg.test(tel)){
            alert('请输入有效的手机号码！');
            return false;
        }
        return true;
    }


    function checktel(){
        var tel = $("#userPhonwj").val();
        var myreg = /^1[3|4|5|7|8][0-9]{9}$/;
        if(!myreg.test(tel)){
            alert('请输入有效的手机号码！');
            return false;
        }
        return true;
    }
    function checkdlinfo(){
        var role = $("#denglurole").find('input:radio:checked').attr("id");
        var tel = $("#telephonedl").val();
        var password = $("#passworddl").val();
        if (role =="" || tel =="" || password==""){
            alert("信息必须填写完整");
            return false;
        }
        var myreg = /^1[3|4|5|7|8][0-9]{9}$/;
        if(!myreg.test(tel)){
            alert('请输入有效的手机号码！');
            return false;
        }
        return true;
    }

    (function($){
        $.getUrlParam = function(name)
        {
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r!=null) return unescape(r[2]); return null;
        }
    })(jQuery);

    //获取url中的参数
    var xx = $.getUrlParam('type');
    if (xx =="zhuce"){
        $("#tabdenglu").attr("class","");
        $("#denglu").attr("class","tab-pane");
        $("#zhuce").attr("class","tab-pane active");
        $("#tabzhuce").attr("class","active");
    }
//    alert(xx);

    //根据省份切换城市
    function changePro(pro){
        $("#city").html("<option value=''>数据加载中...</option>");
        $("#County").html("<option value=''>县/区</option>");
        $("#School_type").html("<option value=''></option>");
        $("#School").html("<option value=''>学校</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url= "/site/getcity";
        var formData = {};
        formData.pro = pro;
        $.post(url,formData).done(function(data){
            data = eval(data);
            var htmls="<option value=''>地级市</option>"
            for (var i = 0; i < data.length; i++) {
                htmls = htmls + "<option value="+data[i].city+">"+data[i].city+"</option>";
            }
            $("#city").html(htmls);
        });
    }
    //根据城市切换区县
    function changeCity(city){
        $("#County").html("<option value=''>数据加载中...</option>");
        $("#School_type").html("<option value=''>请选择</option>");
        $("#School").html("<option value=''>学校</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url=  "/site/getcounty";
        var formData = {};
        formData.city = city;
        $.post(url,formData).done(function(data){
            data = eval(data);
            var htmls="<option value=''>县/区</option>"
            for (var i = 0; i < data.length; i++) {
                htmls = htmls + "<option value="+data[i].county+">"+data[i].county+"</option>";
            }
            $("#County").html(htmls);
        });
    }
    //切换类别学校高中小学初中
    function changeType(county){
        $("#School_type").html("<option value=''>数据加载中...</option>");
        $("#School").html("<option value=''>请选择</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url= "/site/gettype";
        var formData = {};
        formData.county = county;
        $.post(url,formData).done(function(data){
            data = eval(data);
            var htmls="<option value=''>请选择</option>"
            for (var i = 0; i < data.length; i++) {
                htmls = htmls + "<option value="+data[i].schtype+">"+data[i].schtype+"</option>";
            }
            $("#School_type").html(htmls);
        });
    }
    //切换学校
    function changeSchool(schtype){
        $("#School").html("<option value=''>数据加载中...</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url= "/site/getschool";
        var formData = {};
        formData.schtype = schtype;
        formData.county = $("#County").val();
        $.post(url,formData).done(function(data){
            data = eval(data);
            var htmls="<option value=''>学校</option>"
            for (var i = 0; i < data.length; i++) {
                htmls = htmls + "<option value="+data[i].id+">"+data[i].name+"</option>";
            }
            $("#School").html(htmls);
        });
    }
</script>
