<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 20px;box-shadow: 0 0 2px #ccc;">
        <div class="media">
            <a href="###" class="pull-left">
                <img src="../img/pc_ren.png" />
            </a>
            <table class="media-body" style="line-height: 30px;">
                <tr>
                    <td style="width: 250px;">你好，用户<span class="xiugai"  id="xiugaiyh2"><?=$info['user'][0]['name']?></span>校长！</td>
                    <td></td>
                </tr>
                <tr>
                    <td>用户名：<p style="display: none"><?= $info['user'][0]['id']; ?></p><strong style="font-weight: 400; "><?=$info['user'][0]['name']?></strong><a href="###"><span class="xiugai" id="xiugaiyh">点击修改</span></a></td>
                    <td>手机号：<p style="display: none"><?= $info['user'][0]['id']; ?></p><strong style="font-weight: 400"><?=$info['user'][0]['tel']?></strong><a href="###"><span class="xiugai" id="xiugaisj">点击修改</span></a></td>
                </tr>
                <tr>
                    <td>上次登录时间：<?= date("Y-m-d H:i:s",$info['user'][0]['last_login_time']); ?></td>
                    <td>上次登录IP：<?= $info['user'][0]['last_login_ip']; ?></td>
                </tr>
                <tr>
                    <td>我的学校：<span class="xiugai"><?=count($info['school']);?></span>个</td>
                </tr>
            </table>
        </div>
    </div>
    <div style="background-color: #ffffed;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <img src="../img/pc_dng.png" />
        <span style="vertical-align: middle;padding-left: 5px;"><?php if(!empty($info['school'][0]['school'])){echo "您已绑定学校！";}else{echo "你还没有绑定学校！，可以先绑定学校！";} ?></span>
    </div>
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;height: auto;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">已绑定学校</h4>
            <input type="hidden" value="<?php echo $info['user'][0]['openid'];?>" id="openid">
            <input type="hidden" value="<?php echo count($info['school']);?>" id="schoolnum">
            <button class="btn btn-info pull-right" data-toggle="modal" data-target="#gzModal" id="gzbtn" onclick="checkOpenid()" >绑定学校</button>
        </div>
        <table class="table table-bordered text-center" style="margin-top: 20px;">
            <tr style="background-color: #eee;">
                <td>序号</td>
                <td>学校</td>
                <td>类型</td>
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
        <?php if(!empty($info['schools'] )){foreach($info['schools'] as $key=>$value){ ?>
            <tr>
                <td><?=$key?></td>
                <td><?=$value['school']?></td>
                <td><?=$info['snames'][$value['sid']]["schtype"];?></td>
                <td><?=$value['ispass']=="y"?"已审核":"未审核";?></td>
                <td>
                    <a style="color: #e75d50;"  href="<?php echo yii\helpers\Url::to(['guanli/delschool', 'id' => $value['id'],'sid'=>$value['sid']]) ?>" <?php echo 'data-confirm = 确定要取消绑定吗？'; ?>>取消绑定</a>
                </td>
            </tr>
            <?php }} ?>
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
                绑定学校
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
                            <select class="form-control" name="County" id="County" onchange="changeType(this.value)">
                                <option value="">请选择</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="School_type">学校类型:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="School_type" id="School_type" onchange="changeSchool(this.value)">
                                <option value="">请选择</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="School">学校:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="School" id="School">
                                <option value="">请选择</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary"  onclick="addOneClass()">
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
                var url ="/guanli/upname";
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
        var input = $("<input  size='16' type='text' value='" + tel + "'/>");
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
                var url ="/guanli/uptel";
//使用get()方法打开一个一般处理程序，data接受返回的参数（在一般处理程序中返回参数的方法 context.Response.Write("要返回的参数");）
                //数据库的修改就在一般处理程序中完成
                $.post(url,formData).done(function(data) {
                    if (data == "0"){
                        alert("手机号码修改成功");
                    }
                    if (data == "1"){
                        alert("手机号码修改失败");
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

    //检查用户是否有openid信息
    function  checkOpenid(){
        var schoolnum = $.trim($("#schoolnum").val());
        if (schoolnum>0){
            $('#gzbtn').attr('data-target','');
            alert("您已经绑定过学校！");
            return false;
        }else{
            $('#gzbtn').attr('data-target','');
            alert("绑定学校功能已取消，如果您确实是该校校长，请您联系人工客服0371--55030687进行处理解决，谢谢！");
            return false;
            }
    }
    // function  checkOpenid(){

    //     var schoolnum = $.trim($("#schoolnum").val());
    //     if (schoolnum>0){
    //         $('#gzbtn').attr('data-target','');
    //         alert("您已经绑定过学校！");
    //         return false;
    //     }
    //     $openid = $.trim($("#openid").val());
    //     if (!$openid){
    //         $('#gzbtn').attr('data-target','');
    //         alert("请先在手机端绑定学校");
    //         return false;
    //     }else {
    //         $('#gzbtn').attr('data-target','#gzModal');
    //     }
    // }
    //根据省份切换城市
    function changePro(pro){
        $("#city").html("<option value=''>数据加载中...</option>");
        $("#County").html("<option value=''>请选择</option>");
        $("#School_type").html("<option value=''>请选择</option>");
        $("#School").html("<option value=''>请选择</option>");
        $("#class").html("<option value=''>请选择</option>");
        var url= "/guanli/getcity";
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
        var url=  "/guanli/getcounty";
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
        var url= "/guanli/gettype";
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
        var url= "/guanli/getschool";
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

    //********************************保存添加绑定班级信息************************************//
    function addOneClass(){
        if(checkChildInfo()){
            var formdata={};
            var role=$("#role").val();
            var url="/guanli/addschool";
            var to_url="/guanli/index";
            formdata.openid = $("#openid").val();
            formdata.school = $.trim($("#School option:selected").text());
            formdata.sid = $.trim($("#School option:selected").val());
            $.post(url,formdata).done(function(data){
                switch(data){
                    case "2":alert("保存失败！");break;
                    case "3":alert("您已绑定过请勿重复绑定！");break;
                    case "4":alert("该学校已经存在校长！");break;
                    case "5":alert("绑定成功！");window.location.href=to_url;break;
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
        return true;
    }
</script>