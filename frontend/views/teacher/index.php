<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 20px;box-shadow: 0 0 2px #ccc;">
        <div class="media">
            <a href="###" class="pull-left">
                <img src="/img/pc_ren.png" />
            </a>
            <table class="media-body" style="line-height: 30px;">
                <tr>
                    <td style="width: 250px;">你好，用户<span class="xiugai" id="xiugaiyh2"><?=$info['user'][0]['name']?></span>老师！</td>
                    <td></td>
                </tr>
                <tr>
                    <td>用户名：<p style="display: none"><?= $info['user'][0]['id']; ?></p><strong style="font-weight: 400"><?=$info['user'][0]['name']?></strong><a href="###"><span class="xiugai" id="xiugaiyh">点击修改</span></a></td>
                    <td>手机号：<p style="display: none"><?= $info['user'][0]['id']; ?></p><strong style="font-weight: 400"><?=$info['user'][0]['tel']?></strong><a href="###"><span class="xiugai" id="xiugaisj">点击修改</span></a></td>
                </tr>
                <tr>
                    <td>上次登录时间：<?= date("Y-m-d H:i:s",$info['user'][0]['last_login_time']); ?></td>
                    <td>上次登录IP：<?= $info['user'][0]['last_login_ip']; ?></td>
                </tr>
                <tr>
                    <td>我的班级：<span class="xiugai"><?=count($info['teachers']);?></span>个</td>
                </tr>
            </table>
        </div>
    </div>
    <div style="background-color: #ffffed;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <img src="/img/pc_dng.png" />
        <span style="vertical-align: middle;padding-left: 5px;"><?php if(!empty($info['teachers'])){ echo "您已绑定班级";}else{echo "您还没有绑定班级，请先绑定班级";}?></span>
    </div>
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;height: auto;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">已绑定班级</h4>
            <input type="hidden" value="<?php echo $info['user'][0]['openid'];?>" id="openid">
            <button class="btn btn-info pull-right" data-toggle="modal" data-target="#gzModal" id="gzbtn" onclick="checkOpenid()">绑定班级</button>
        </div>
        <table class="table table-bordered text-center" style="margin-top: 20px;">
            <tr style="background-color: #eee;">
                <td>序号</td>
                <td>角色</td>
                <td>班级</td>
                <td>学校</td>
                <td>审核</td>
                <td>操作</td>
            </tr>
            <?php
            if (Yii::$app->session->hasFlash('info')) {
            $infos = Yii::$app->session->getFlash('info');
            ?>
                <script type="text/javascript">
                    var now = '<?php echo $infos; ?>';
                    alert(now);
                </script>
            <?php } ?>

            <?php foreach ($info['teacheres'] as $key=>$value){?>
            <tr>
                <td><?=$key; ?></td>
                <td><?=$value['role']?></td>
                <td><?=$value['class']?></td>
                <td><?=$value['school']?></td>
                <td><?=($value['ispass']=="y")?"已审核":"未审核"; ?></td>
                <td>
                    <a style="color: #e75d50;"  href="<?php echo yii\helpers\Url::to(['teacher/delclass', 'id' => $value['id'],'cid'=>$value['cid']]) ?>" <?php echo 'data-confirm = 确定要取消绑定吗？'; ?>>取消绑定</a>
                </td>
            </tr>
            <?php
            }?>
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
                绑定班级
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
                                <option value="">请选择</option>
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
                                <option value="">请选择</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="County">县区:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="County" id="County" onchange="changeSchool(this.value)">
                                <option value="">请选择</option>
                            </select>
                        </div>
                    </div>
<!--                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="School_type">学校类型:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="School_type" id="School_type" onchange="changeSchool(this.value)">
                                <option value="">请选择</option>
                            </select>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="School">学校:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="School" id="School" onchange="changeClass(this.value)">
                                <option value="">请选择</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="shenfens">身份:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="shenfens" id="shenfens" onchange="changeCla(this.value)">
                                <option value="">请选择</option>
                                <option value="1">教职工</option>
                                <option value="2">管理层</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="banjijl"  style="display: none;">
                        <label class="col-sm-3 control-label" for="class">班级/内部交流组:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="class" id="class">
                                <option value="">请选择</option>
                            </select>
                        </div>
                    </div>
<!--                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="class">身份:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="class" id="identity">
                                <option value="-1">请选择</option>
                                <option value="1">管理层</option>
                                <option value="2">教职工</option>
                            </select>
                        </div>
                    </div>-->

                    <div class="form-group" id="tea_role" style="display: none;">
                        <label class="col-sm-3 control-label" for="student">角色:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="class" id="tea_roles">
                                <option value="">请选择</option>
                                <?php  foreach($info['subject'] as $key=>$value){ ?>
                                <option value="<?=$key?>"><?=$value['name']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="guanli_role" style="display: none;">
                        <label class="col-sm-3 control-label" for="student">角色:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="class" id="guanli_roles">
                                <option value="">请选择</option>
                                <?php  foreach($info['manage'] as $key=>$value){ ?>
                                    <option value="<?=$key?>"><?=$value['name']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="addOneClass()">
                    提交
                </button>
                <button class="btn btn-danger" data-dismiss="modal">
                    取消
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $("#identity").change(function(){
            var ID=$("#identity").val();
            if(ID==1){
                $("#tea_role").hide();
            }else{
                $("#tea_role").show();
            }
        })
    })

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
                var url ="/teacher/upname";
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
//修改手机号
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
                var myreg = /^1[3|4|5|7|8][0-9]{9}$/;
                if(!myreg.test(newtel))
                {
                    alert('请输入有效的手机号码！');
                    return false;
                }
                var formData = {};
                formData.usid = $.trim($(this).parents("td").children('p').text());
                formData.newtel =newtel;
                var url ="/teacher/uptel";
//使用get()方法打开一个一般处理程序，data接受返回的参数（在一般处理程序中返回参数的方法 context.Response.Write("要返回的参数");）
                //数据库的修改就在一般处理程序中完成
                $.post(url,formData).done(function(data) {
                    if (data == "0"){
                        alert("手机号码修改成功");
                    }
                    if (data == "1"){
                        alert("手机号码修改失败");
                        window.location.reload();
                    }
                    if (data == "2"){
                        alert("该手机号已经存在");
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
//根据省份切换城市
    function changePro(pro){
        $("#city").html("<option value=''>数据加载中...</option>");
        $("#County").html("<option value=''>请选择</option>");
        $("#School_type").html("<option value=''>请选择</option>");
        $("#School").html("<option value=''>请选择</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url= "/teacher/getcity";
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
    //根据城市切换区县
    function changeCity(city){
        $("#County").html("<option value=''>数据加载中...</option>");
        $("#School_type").html("<option value=''>请选择</option>");
        $("#School").html("<option value=''>请选择</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url=  "/teacher/getcounty";
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
    //切换类别学校高中小学初中
    function changeType(county){
        $("#School_type").html("<option value=''>数据加载中...</option>");
        $("#School").html("<option value=''>请选择</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url= "/teacher/gettype";
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
        var url= "/teacher/getschool";
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

    //切换班级
    function changeCla(id){
        if(id == 2){
            $("#banjijl").hide();
            $("#guanli_role").show();
            $("#tea_role").hide();
        }else {
            $("#banjijl").show();
            $("#tea_role").show();
            $("#guanli_role").hide();
        }
    }

    //切换班级
    function changeClass(sid){
        $("#class").html("<option value=''>数据加载中...</option>");
        var url= "/teacher/getclass";
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

    //检查用户是否有openid信息
    function  checkOpenid(){
        $openid = $.trim($("#openid").val());
        if (!$openid){
            $('#gzbtn').attr('data-target','');
            alert("请先在手机端绑定");
        }else {
            $('#gzbtn').attr('data-target','#gzModal');
        }
    }
    //********************************保存添加绑定班级信息************************************//
    function addOneClass(){
        if(checkChildInfo()){
            var formdata={};
            var roles = $.trim($("#shenfens").val());
            if(roles=="1") {
                formdata.cid = $.trim($("#class option:selected").val());
                formdata.class = $.trim($("#class option:selected").text());
                var role = $.trim($("#tea_roles option:selected").text());
            }else {
                var role = $.trim($("#guanli_roles option:selected").text());
                formdata.cid = 0;
                formdata.class = "管理";
            }
            var url="/teacher/addclass";
            var to_url="/teacher/index";
            formdata.openid = $("#openid").val();
            formdata.school = $.trim($("#School option:selected").text());
            formdata.sid = $.trim($("#School option:selected").val());
            formdata.role = role;
            formdata.roles = roles;
            $.post(url,formdata).done(function(data){
                switch(data){
                    case "1":alert("班级信息尚未导入，请关闭当前页面，然后点击【我的服务】-》【人工客服】联系人工客服");break;
                    case "2":alert("保存失败");break;
                    case "3":alert("改班级已经有该职位教师存在，若需绑定，请联系人工客服解决！");break;
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
        if($.trim($("#shenfens").val())=="1"){
            if($.trim($("#class").val())==""){
                alert("班级名称不能为空");
                return false;
            }
            if($.trim($("#tea_roles").val())==""){
                alert("角色名不能为空");
                return false;
            }
        }else {
            if($.trim($("#guanli_roles").val())==""){
                alert("角色名不能为空");
                return false;
            }
        }

        return true;
    }
</script>