<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>正梵智慧校园</title>
    <link rel="shortcut icon" href="/img/0206_08.png">
    <link rel="stylesheet" href="/css/bootstrap.css" />
    <script type="text/javascript" src="/js/jquery-1.12.3.js" ></script>
    <script type="text/javascript" src="/js/bootstrap.min.js" ></script>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        /*默认元素清零*/
        a{
            text-decoration: none;
        }
        a:hover{
            color: #050606;
            text-decoration: none;
        }
        body{
            background: url('/img/bg.png') no-repeat fixed center;
            background-size: cover;
        }
        #pc_top{
            width: 100%;
        }
        #pc_top h2{
            text-align: center;
            color: white;
        }
        #pc_top img{
            width: 100%;
            margin: 30px auto 100px;
        }
        #pc_main{
            margin-bottom: 30px;
            min-width: 1200px;
            padding-left: 15%;
            padding-right: 15%;
        }
        #pc_right{
            width: 25%;
            min-width: 400px;
            background-color: white;
        }
        #main_top{
            padding-left: 15px;
            padding-right: 15px;
        }
        #main_top>h5{
            padding: 20px 10px;
            font-weight: bold;
            font-size: 15px;
        }
        .form-horizontal .form-group label{
            line-height: 20px;
            text-align: left;
        }
        .form-group label span{
            line-height: 20px;
            padding: 0 5px;
        }
        #main_bottom{
            width: 100%;
            min-height: 320px;
            background-color: #eee;
            margin-top: 20px;
            padding-top: 15px;
            padding-bottom: 15px;
        }
        #ewm{
            background-color: white;
            width: 210px;
            height: 210px;
            line-height: 210px;
            margin: 10px auto 20px;
            text-align: center;
        }
        #ewm img{
            vertical-align: middle;
        }
        #main_bottom>.text-center{
            font-size: 14px;
            width: 80%;
            margin: 0 auto;
        }
        #main_bottom>.text-center>img{
            float: left;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
<div id="pc_top">
    <h2>正梵智慧校园</h2>
    <img src="/img/bt.png" />
</div>
<div id="pc_main">
    <div id="pc_left" class="pull-left">
        <img src="/img/lc.png" />
    </div>
    <div id="pc_right" class="pull-right">
        <div id="main_top">
            <h5>绑定学生（请填写学生的真实信息）</h5>
<!--            <form class="form-horizontal" role="form">-->
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-xs-3 control-label">
                        <span class="glyphicon glyphicon-map-marker"></span>省
                    </label>
                    <div class="col-xs-9">
                        <select class="form-control" name="province" id="province" onchange="changePro(this.value)">
                            <option value="">省份</option>
                            <?php foreach($info['pro'] as $value){?>
                                <option><?php echo $value['pro']; ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label">
                        <span class="glyphicon glyphicon-map-marker"></span>市
                    </label>
                    <div class="col-xs-9">
                        <select class="form-control" name="city" id="city" onchange="changeCity(this.value)">
                            <option value="">地级市</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label">
                        <span class="glyphicon glyphicon-map-marker"></span>县区
                    </label>
                    <div class="col-xs-9">
                        <select class="form-control" name="County" id="County" onchange="changeSchool(this.value)">
                            <option value="">县/区</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label">
                        <span class="glyphicon glyphicon-star"></span>学校
                    </label>
                    <div class="col-xs-9">
                        <select class="form-control" name="School" id="School" onchange="changeClass(this.value)">
                            <option value="">学校</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label">
                        <span class="glyphicon glyphicon-star"></span>班级
                    </label>
                    <div class="col-xs-9">
                        <select class="form-control" name="class" id="class">
                            <option value="">班级</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label">
                        <span class="glyphicon glyphicon-user"></span>姓名
                    </label>
                    <div class="col-xs-9">
                        <input class="form-control" type="text" name="student" id="student" placeholder="请输入学生姓名" />
                    </div>
                </div>
                <div class="text-center">
                    <input id="ewm_dl" style="width: 70%;" type="submit" class="form-control btn btn-primary" value="保存" onclick="addOneChild()"/>
                </div>
            </div>
<!--            </form>-->
        </div>
        <div id="main_bottom">
            <div id="ewm">
                <img src="/img/sys.png" />
            </div>
            <div class="text-center ewm_txt">请先填写学生信息，点击保存后，自动生成学生二维码，扫描即可绑定学生。</div>
            <div class="text-center ewm_txt hidden">
                <img src="/img/sys.png" />
                <div class="text-left">微信扫描二维码，绑定学生信息。</div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="text-center" style="line-height: 30px;color: white;margin-top: 10px;padding: 10px;">
		  Copyright @ 河南正梵通信技术有限公司 All rights reserved <a target="_blank" style="color:white" href="http://www.miitbeian.gov.cn/">豫ICP备13024673号-2</a><br />
			<img src="/img/0206_88.png" /><a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=41010502002679" target="_blank" style="color:white">豫公网安备 41010502002679号</a>
		</div>
</body>
<script>
    $(function(){
        $("#ewm_dl").click(function(){
            $('.ewm_txt').eq(1).removeClass("hidden");
            $('.ewm_txt').eq(0).addClass("hidden");
        })
    })

    //根据省份切换城市
    function changePro(pro){
        $("#city").html("<option value=''>数据加载中...</option>");
        $("#County").html("<option value=''>县/区</option>");
        $("#School_type").html("<option value=''></option>");
        $("#School").html("<option value=''>学校</option>");
        $("#class").html("<option value=''>班级</option>");
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
        $("#class").html("<option value=''>班级</option>");
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

    //切换学校
    function changeSchool(schtype){
        $("#School").html("<option value=''>数据加载中...</option>");
        $("#class").html("<option value=''>班级</option>");
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

    function changeClass(sid){
        $("#class").html("<option value=''>数据加载中...</option>");
        var url= "/site/getclass";
        var formData = {};
        formData.sid = sid;
        $.post(url,formData).done(function(data){
            data = eval(data);
            console.log(data);
            var htmls="<option value=''>班级</option>"
            for (var i = 0; i < data.length; i++) {
                htmls = htmls + "<option value="+data[i].id+">"+data[i].name+"</option>";
            }
            $("#class").html(htmls);
        });
    }

    //********************************保存添加关注学生信息************************************//
    function addOneChild(){
        if(checkChildInfo()){
            var formdata={};
            var student=removeAllSpace($("#student").val());
            var url= "/site/addchild";
            var to_url= "/site/index";
            formdata.openid = $("#openid").val();
            formdata.cid = $.trim($("#class option:selected").val());
            formdata.class = $.trim($("#class option:selected").text());
            formdata.school = $.trim($("#School option:selected").text());
            formdata.sid = $.trim($("#School option:selected").val());
            formdata.student = student;
            $.post(url,formdata).done(function(data){
                if(data == '1'){
                    alert("学生信息尚未导入，请关闭当前页面，然后点击【我的服务】-》【人工客服】联系人工客服");
                }else {
                    $("#ewm").html("<img src='/site/bdqrcode?stuno2="+data+"'/>")
                    $('.ewm_txt').eq(1).removeClass("hidden");
                    $('.ewm_txt').eq(0).addClass("hidden");

                }
            });
        }
    }

    //检验学校班级姓名是否为空
    function checkChildInfo(){
        if($.trim($("#School").val())==""){
            alert("学校名称不能为空");
            return false;
        }
        if($.trim($("#class").val())==""){
            alert("班级名称不能为空");
            return false;
        }
        if($.trim($("#student").val())==""){
            alert("学生姓名不能为空");
            return false;
        }
        return true;
    }

    //学生名字填写中去掉空格
    function removeAllSpace(str) {
        return str.replace(/\s+/g, "");
    }
</script>
</html>
