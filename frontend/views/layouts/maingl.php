<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>正梵智慧校园</title>
        <link rel="shortcut icon" href="/img/0206_08.png">
        <script type="text/javascript" src="/rili/jedate/jedate.js"></script>
        <link rel="stylesheet" href="../css/bootstrap.css" />
        <link rel="stylesheet" href="../css/mystyle.css" />
        <script type="text/javascript" src="../js/jquery-1.12.3.js"></script>
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    </head>
    <body>
    <div id="tophead">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6">欢迎使用正梵智慧校园</div>
                <div class="col-xs-12 hidden-xs col-sm-6 text-right">服务热线：0371-55030687</div>
            </div>
        </div>
    </div>
    <div>
        <div class="container" style="line-height: 60px;">
            <img src="../img/0208_04.png" /><span style="font-size: 20px;margin: 0 10px;">|</span>
            <div class="dropdown" style="display: inline-block;">
                <div class="btn-group">
                    <button class="btn btn-default">切换身份</button>
                    <div class="btn-group">
                        <button class="btn btn-default" data-toggle="dropdown">
                            <?php $userifo = $this->params['user']; if($userifo[0]['shenfen'] == 'tea'){echo "老师";}elseif($userifo[0]['shenfen'] == 'jiazhang'){echo "家长";}else{echo "校长";}?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a onclick="changeShenfen(this)" href="###" id="jiazhang">家长</a></li>
                            <li><a onclick="changeShenfen(this)" href="###" id="tea">老师</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="dropdown pull-right">
                <div class="btn-group">
                    <button class="btn btn-default">当前学校</button>
                    <div class="btn-group">
                        <button class="btn btn-default" data-toggle="dropdown">
                            <?php  $userifo = $this->params['user']; $lastsid = $userifo[0]['last_sid']; $schools = $this->params['schools']; if(!empty($schools)){echo $schools[0]['school'];}else{echo "请在此处选择学校";} ?>
                        </button>
                       
                    </div>
                </div>
                <div class="pull-right" style="padding-left: 10px;">
                    <a style="color: black;font-size: 14px;padding-left: 10px;" href="###">帮助中心</a>
                    <a style="color: black;font-size: 14px;padding-left: 20px;" href="###">关于我们</a>
                    <a style="color: black;font-size: 14px;padding-left: 20px;" href="/site/loginout">退出</a>
                </div>
            </div>
        </div>
    </div>
    <div style="background-color: #f4f4f6;padding: 40px;min-height: 650px;">
        <div class="container">
            <ul class="pull-left list-group" style="width: 20%;min-width: 180px;">
                <li class="list-group-item text-center gz_pot">
                    <img class="gz_left" src="../img/pc_home.png" />
                    <span style="font-size: 16px;">个人中心</span>
                    <img class="gz_right" src="../img/pc_dw.png" />
                </li>
                <li class="list-group-item gz_xuanx">
                    <a href="/guanli/index"><h5 class="list-group-item-heading text-center">我的资料</h5></a>
                    <a href="/guanli/passwd"><h5 class="list-group-item-heading text-center">密码修改</h5></a>
                    <a data-toggle="modal" <?php $userifo = $this->params['user']; $typesf = $userifo[0]['label']; if(empty($typesf)){echo "href='javascript:alert(\"您还没有群组！\");'"; }else{ echo "href = '#' data-target='#wdModal'";} ?>> <h5 class="list-group-item-heading text-center">文档管理</h5></a>
                    <a href="/guanli/shenpi"><h5 class="list-group-item-heading text-center">计划审批</h5></a>
                </li>
                <li class="list-group-item text-center gz_pot">
                    <img class="gz_left" src="../img/pc_zl.png" />
                    <span style="font-size: 16px;">家校互动</span>
                    <img class="gz_right" src="../img/pc_dw.png" />
                </li>
                <li class="list-group-item gz_xuanx">
                    <a href="/guanli/safecard"><h5 class="list-group-item-heading text-center">平安通知</h5></a>
                </li>
                <li class="list-group-item text-center gz_pot">
                    <img class="gz_left" src="../img/class_mana.png" />
                    <span style="font-size: 16px;">班级管理</span>
                    <img class="gz_right" src="../img/pc_dw.png" />
                </li>
                <li class="list-group-item gz_xuanx">
                    <a href="/guanli/classlist"><h5 style="color: #ff9d11;" class="list-group-item-heading text-center">班级列表</h5></a>
                    <a href="/guanli/bjpz"><h5 class="list-group-item-heading text-center">配置班级</h5></a>
                    <a href="/guanli/kqdc"><h5 class="list-group-item-heading text-center">考勤导出</h5></a>
                </li>
                <li class="list-group-item text-center gz_pot">
                    <img class="gz_left" src="../img/teacher_mana.png" />
                    <span style="font-size: 16px;">教师管理</span>
                    <img class="gz_right" src="../img/pc_dw.png" />
                </li>
                <li class="list-group-item gz_xuanx">
                    <a href="/guanli/syjs"><h5 class="list-group-item-heading text-center">所有教师</h5></a>
                    <a href="/guanli/dshjs"><h5 class="list-group-item-heading text-center">待审核教师</h5></a>
                </li>
                <li class="list-group-item text-center gz_pot">
                    <img class="gz_left" src="../img/pc_jl.png" />
                    <span style="font-size: 16px;">掌上教育</span>
                    <img class="gz_right" src="../img/pc_dw.png" />
                </li>
                <li class="list-group-item gz_xuanx">
                    <a href="/guanli/schoolwebsite"><h5 class="list-group-item-heading text-center">学校微官网</h5></a>
                    <a href="/guanli/schoolnotice"><h5 class="list-group-item-heading text-center">校内公告</h5></a>
                    <a href="/guanli/classdynamics"><h5  class="list-group-item-heading text-center">班级动态</h5></a>
                    <a href="/guanli/internalcom"><h5 class="list-group-item-heading text-center">内部交流</h5></a>
                    <a data-toggle="modal" <?php $userifo = $this->params['user']; $typesf = $userifo[0]['label']; if(empty($typesf)){echo "href='javascript:alert(\"您还没有群组！\");'"; }else{ echo "href = '#' data-target='#zmModal'";} ?>> <h5 class="list-group-item-heading text-center">教师群组</h5></a>
                    <a href="/guanli/xxsysz"><h5 class="list-group-item-heading text-center">学校首页设置</h5></a>
                </li>
            </ul>
        <?= $content ?>
            <div class="text-center" style="line-height: 30px;color: #98999b;">
                Copyright @ 河南正梵通信技术有限公司 All rights reserved 豫ICP备13024673<br />
                <img src="../img/0206_88.png" />豫公网安备 41010502002379
            </div>
            <?php $this->endBody() ?>

            <!--文档管理组名-->
            <div class="modal fade" id="wdModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            请选择您的群组信息
                            <button class="close" data-dismiss="modal" >
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php $userifo = $this->params['user']; $typesf = $userifo[0]['label']; $qunzu=explode(",",$typesf); foreach($qunzu as $k=>$v){ ?>
                                <a href="/guanli/wdgl?type=<?=$v?>" class="btn btn-success"><?=$v?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>


            <!--组名-->
            <div class="modal fade" id="zmModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            请选择您的群组信息
                            <button class="close" data-dismiss="modal" >
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php $userifo = $this->params['user']; $typesf = $userifo[0]['label']; $qunzu=explode(",",$typesf); foreach($qunzu as $k=>$v){ ?>
                                <a href="/guanli/qunzu?type=<?=$v?>" class="btn btn-success"><?=$v?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
</body>
</html>
<script>
    //切换身份
    function changeShenfen(t){
        var formdata = {};
        formdata.shenfen = t.id;
        var url = "<?php echo $this->params['path'];?>/guanli/changeshenfen";
        var tourl = "<?php echo $this->params['path'];?>/site/loginout";
        if(confirm("温馨提示：切换身份成功后将退出系统重新登录！")) {
            $.post(url, formdata).done(function (data) {
                if (data == '0') {
                    // alert("身份切换成功");
                    window.location.href = tourl;
                } else {
                    alert("身份切换失败，请联系客服人员处理");
                }
            });
        }
    }

    function changeSchools(t){
        var formdata = {};
        formdata.sid = t.id;
        formdata.stuname = $(t).text();
        var url = "<?php echo $this->params['path'];?>/guanli/changeschool";
        var tourl = "<?php echo $this->params['path'];?>/guanli/index";
        $.post(url,formdata).done(function(data){
            if (data == '0'){
                alert("学校切换成功");
                window.location.href=tourl;
            }else {
                alert("学校切换失败，请重试或联系客服人员处理");
            }
        });
        $('.changestu').html(formdata.stuname);
    }

    $(function() {
        var lurl = location.href;
        if (lurl.indexOf("index") >= 0) {
            $(".list-group-item-heading").css("color", "#666");
            $(".list-group-item-heading").eq(0).css("color", "#FF9D11");
        }
        if(lurl.indexOf("passwd")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(1).css("color","#FF9D11");
        }
        if(lurl.indexOf("wdgl")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(2).css("color","#FF9D11");
        }
        if(lurl.indexOf("shenpi")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(3).css("color","#FF9D11");
        }
        if(lurl.indexOf("safecard")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(4).css("color","#FF9D11");
        }
        if(lurl.indexOf("classlist")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(5).css("color","#FF9D11");
        }
        if(lurl.indexOf("bjpz")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(6).css("color","#FF9D11");
        }
        if(lurl.indexOf("kqdc")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(7).css("color","#FF9D11");
        }
        if(lurl.indexOf("syjs")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(8).css("color","#FF9D11");
        }
        if(lurl.indexOf("dshjs")>=0 ){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(9).css("color","#FF9D11");
        }
//        if(lurl.indexOf("yshjs")>=0){
//            $(".list-group-item-heading").css("color","#666");
//            $(".list-group-item-heading").eq(8).css("color","#FF9D11");
//        }
        if(lurl.indexOf("schoolwebsite")>=0||lurl.indexOf("websitemin")>=0||lurl.indexOf("add")>=0||lurl.indexOf("edit")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(10).css("color","#FF9D11");
        }
        if(lurl.indexOf("schoolnotice")>=0 || lurl.indexOf("schoolmin")>=0|| lurl.indexOf("fabugg")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(11).css("color","#FF9D11");
        }
        if(lurl.indexOf("classdynamics")>=0  || lurl.indexOf("dongtaimin")>=0 || lurl.indexOf("fabudt")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(12).css("color","#FF9D11");
        }
        if(lurl.indexOf("internalcom")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(13).css("color","#FF9D11");
        }
        if(lurl.indexOf("qunzu")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(14).css("color","#FF9D11");
        }
        if(lurl.indexOf("xxsysz")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(15).css("color","#FF9D11");
        }
    })
</script>
