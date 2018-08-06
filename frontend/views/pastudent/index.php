<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
?>
            <div style="background-color: white;padding: 20px;box-shadow: 0 0 2px #ccc;">
                <div class="media">
                    <a href="###" class="pull-left">
                        <img src="../img/pc_ren.png" />
                    </a>
                    <table class="media-body" style="line-height: 30px;">
                        <tr>
                            <td style="width: 250px;">你好，用户<span id="xiugaiyh2" class="xiugai"><?= $info['user'][0]['name']; ?></span>家长！</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>用户名：<p style="display: none"><?= $info['user'][0]['id']; ?></p><strong style="font-weight: 400"><?= $info['user'][0]['name']; ?></strong><a href="###"><span class="xiugai" id="xiugaiyh">点击修改</span></a></td>
                            <td>手机号：<p style="display: none"><?= $info['user'][0]['id']; ?></p><strong style="font-weight: 400"><?= $info['user'][0]['tel']; ?></strong><a href="###"><span class="xiugai" id="xiugaisj">点击修改</span></a></td>
                        </tr>
                        <tr>
                            <td>上次登录时间：<?= date("Y-m-d H:i:s",$info['user'][0]['last_login_time']); ?></td>
                            <td>上次登录IP：<?= $info['user'][0]['last_login_ip']; ?></td>
                        </tr>
                        <tr>
                            <td>我的学生：<span class="xiugai"><?= $info['countChild']; ?></span>人</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="background-color: #ffffed;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
                <img src="../img/pc_dng.png" />
                <span style="vertical-align: middle;padding-left: 5px;"><?php if(!empty($info['pastudent'][0]['stu_name'])){echo "您已关注学生，可关注多位学生信息。";}else{echo "你还没有关注学生，可以先关注学生";} ?></span>
            </div>
            <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
                <div class="clearfix">
                    <h4 class="pull-left">已关注学生</h4>
                    <input type="hidden" value="<?php echo $info['user'][0]['openid'];?>" id="openid">
                    <button class="btn btn-info pull-right" data-toggle="modal" data-target="#gzModal" id="gzbtn" onclick="checkOpenid()">关注学生</button>
                </div>
                <table class="table table-bordered text-center" style="margin-top: 20px;">
                    <tr style="background-color: #eee;">
                        <td>序号</td>
                        <td>姓名</td>
                        <td>学校</td>
                        <td>班级</td>
                        <td>审核</td>
                        <td>操作</td>
                    </tr>
                    <?php if(!empty($info['pastudent'])){ foreach($info['pastudent'] as $key=>$value){ ?>
                    <tr>
                        <td><?php echo $key; ?></td>
                        <td><?php echo $value['stu_name']; ?></td>
                        <td><?php echo $value['school']; ?></td>
                        <td><?php echo $value['class']; ?></td>
                        <td><?php echo ($value['ispass'] == "y")?"已审核":"未审核"; ?></td>
                        <td class="qxgz">
                            <a style="color: #e75d50;" href="###">取消关注</a>
                            <input type="hidden" id="qxid" name="<?php echo $value['stu_id'] ?>" value="<?php echo $value['id'] ?>" >
                        </td>
                    </tr>
                    <?php } } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<!--关注学生-->
<div class="modal fade" id="gzModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <h4 class="modal-header">
                关注学生
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </h4>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="province">省:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="province" id="province" onchange="changePro(this.value)">
                                <option value="">
                                    请选择
                                </option>
                                <?php foreach($info['pro'] as $value){?>
                                    <option><?php echo $value['pro']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="city">市:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="city" id="city" onchange="changeCity(this.value)">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="County">县区:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="County" id="County" onchange="changeSchool(this.value)">
                            </select>
                        </div>
                    </div>
<!--                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="School_type">学校类型:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="School_type" id="School_type" onchange="changeSchool(this.value)">
                            </select>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="School">学校:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="School" id="School" onchange="changeClass(this.value)">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="class">班级:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="class" id="class">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="student">姓名:</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="student" id="student" placeholder="请输入学生姓名" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="addOneChild()">
                    提交
                </button>
                <button class="btn btn-danger" data-dismiss="modal">
                    关闭
                </button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="<?php echo $info['path']?>" id="path">
</body>
</html>

<script type="application/javascript">
    var path = $("#path").val();
    $(function(){
        $(".qxgz").click(function(){
            var qxid = $(this).children('#qxid').val();
            var msg = "您真的要取消关注该学生吗？";
            var formData = {};
            var url = path+"/pastudent/delchild";
            formData.qxid = qxid;
            formData.stuid = $(this).children('#qxid').attr("name");
            var b = $(this).parent("tr").text();
            if (confirm(msg)==true){
                $.post(url,formData).done(function(data){
                    if (data == "0"){
                        alert("取消关注成功");
                        window.location.reload();
                    $(this).parent("tr").remove();
                    }
                    if (data == "1"){
                        alert("当前学生不能取消绑定");
                        return false;
                    }
                });

            }else{
                return false;
            }
        });
//        修改用户名
        $("#xiugaiyh").click(function(){
            var strong = $(this).parent().siblings('strong');
            var yhm = strong.text();
            var input = $("<input  size='10' type='text' value='" + yhm + "'/>");
            strong.html(input);
            $(this).hide();
            input.click(function () {
                return false;
            });
            input.trigger("focus");//获取焦点
            //文本框失去焦点后提交内容，重新变为文本
            input.blur(function(){
                var newyhm = $(this).val();
                //判断文本有没有修改
                if (newyhm != yhm){
                    var formData = {};
                    formData.usid = $.trim($(this).parents("td").children('p').text());
                    formData.newyhm =newyhm;
                    var url =path+"/pastudent/upname";
                    $.post(url,formData).done(function(data){
                        if (data == "0"){
                            alert("用户名修改成功");
                        }
                        if (data == "1"){
                            alert("用户名修改失败");
                        }
                    })
                    $("#xiugaiyh2").html(newyhm);
                    strong.html(newyhm);
                    $("#xiugaiyh").show();
                }else {
                    strong.html(newyhm);
                    $("#xiugaiyh").show();
                }
            })
        })
        $("#xiugaisj").click(function(){
            var strong = $(this).parent().siblings('strong');
            var tel = strong.text();
            var input = $("<input  size='10' type='text' value='" + tel + "'/>");
            strong.html(input);
            $(this).hide();
            input.click(function () {
                return false;
            });
            //获取焦点
            input.trigger("focus");
            //文本框失去焦点后提交内容，重新变为文本
            input.blur(function(){
                var newtel = $(this).val();
                //判断文本有没有修改
                if (newtel != tel){
                    var formData = {};
                    formData.usid = $.trim($(this).parents("td").children('p').text());
                    formData.newtel =newtel;
                    var url =path+"/pastudent/uptel";
//使用get()方法打开一个一般处理程序，data接受返回的参数（在一般处理程序中返回参数的方法 context.Response.Write("要返回的参数");）
                    //数据库的修改就在一般处理程序中完成
                    $.post(url,formData).done(function(data) {
                        if (data == "0"){
                            alert("手机号码修改成功");
                        }else if (data == "2")
                        {
                            alert("手机号码已经存在！");
                            window.location.reload();
                        }else{
                            alert("手机号码修改失败");
                            window.location.reload();
                        }
                    })
                    strong.html(newtel);
                    $("#xiugaisj").show();
                }else {
                    strong.html(tel);
                    $("#xiugaisj").show();
                }
            })
        })
    });
    function changePro(pro){
        $("#city").html("<option value=''>数据加载中...</option>");
        $("#County").html("<option value=''>请选择</option>");
        $("#School_type").html("<option value=''>请选择</option>");
        $("#School").html("<option value=''>请选择</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url= path+ "/pastudent/getcity";
        var formData = {};
        formData.pro = pro;
        $.post(url,formData).done(function(data){
            data = eval(data);
            var htmls="<option value=''>请选择</option>"
            for (var i = 0; i < data.length; i++) {
                htmls = htmls + "<option value="+data[i].city+">"+data[i].city+"</option>";
            }
            $("#city").html(htmls);
        });
    }
    function changeCity(city){
        $("#County").html("<option value=''>数据加载中...</option>");
        $("#School_type").html("<option value=''>请选择</option>");
        $("#School").html("<option value=''>请选择</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url= path+ "/pastudent/getcounty";
        var formData = {};
        formData.city = city;
        $.post(url,formData).done(function(data){
            data = eval(data);
            var htmls="<option value=''>请选择</option>"
            for (var i = 0; i < data.length; i++) {
                htmls = htmls + "<option value="+data[i].county+">"+data[i].county+"</option>";
            }
            $("#County").html(htmls);
        });
    }

    function changeType(county){
        $("#School_type").html("<option value=''>数据加载中...</option>");
        $("#School").html("<option value=''>请选择</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url= path+ "/pastudent/gettype";
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

    function changeSchool(schtype){
        $("#School").html("<option value=''>数据加载中...</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url= path+ "/pastudent/getschool";
        var formData = {};
        formData.schtype = schtype;
        formData.county = $("#County").val();
        $.post(url,formData).done(function(data){
            data = eval(data);
            var htmls="<option value=''>请选择</option>"
            for (var i = 0; i < data.length; i++) {
                htmls = htmls + "<option value="+data[i].id+">"+data[i].name+"</option>";
            }
            $("#School").html(htmls);
        });
    }
    function changeClass(sid){
        $("#class").html("<option value=''>数据加载中...</option>");
        var url= path+ "/pastudent/getclass";
        var formData = {};
        formData.sid = sid;
        $.post(url,formData).done(function(data){
            data = eval(data);
            console.log(data);
            var htmls="<option value=''>请选择</option>"
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
            var url=path+"/pastudent/addchild";
            var to_url=path+"/pastudent/index";
            formdata.openid = $("#openid").val();
            formdata.cid = $.trim($("#class option:selected").val());
            formdata.class = $.trim($("#class option:selected").text());
            formdata.school = $.trim($("#School option:selected").text());
            formdata.sid = $.trim($("#School option:selected").val());
            formdata.student = student;
            $.post(url,formdata).done(function(data){
                switch(data){
                    case "1":alert("学生信息尚未导入，请关闭当前页面，然后点击【我的服务】-》【人工客服】联系人工客服");break;
                    case "2":alert("保存失败");break;
                    case "3":alert("您已关注过该学生");break;
                    case "5":alert("绑定成功");window.location.href=to_url;break;
                    default:alert(data);break;
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
    function  checkOpenid(){
        $openid = $.trim($("#openid").val());
        if (!$openid){
            $('#gzbtn').attr('data-target','');
            alert("请先在手机端绑定学生");
        }else {
            $('#gzbtn').attr('data-target','#gzModal');
        }
    }
    //学生名字填写中去掉空格
    function removeAllSpace(str) {
        return str.replace(/\s+/g, "");
    }

</script>
