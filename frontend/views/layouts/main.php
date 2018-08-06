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
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>正梵智慧校园</title>
    <link rel="shortcut icon" href="/img/0206_08.png">
    <script type="text/javascript" src="/rili/jedate/jedate.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.css" />
    <?= Html::csrfMetaTags() ?>
    <link rel="stylesheet" href="/css/mystyle.css" />
    <script type="text/javascript" src="/js/jquery-1.12.3.js" ></script>
    <script type="text/javascript" src="/js/bootstrap.min.js" ></script>
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
                        <?php $userifo = $this->params['user']; if($userifo[0]['shenfen'] == 'tea'){echo "教师";}elseif($userifo[0]['shenfen'] == 'jiazhang'){echo "家长";}else{echo "校长";}?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a onclick="changeShenfen(this)" href="###" id="tea">老师</a></li>
                        <li><a onclick="changeShenfen(this)" href="###" id="guanli">校长</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="dropdown pull-right">
            <div class="btn-group">
                <button class="btn btn-default">当前学生</button>
                <div class="btn-group">
                    <button class="btn btn-default changestu" data-toggle="dropdown">
                        <?php $userifo = $this->params['user']; $laststuid = !empty($userifo)?$userifo[0]['last_stuid']:""; $pinfo = $this->params['key'];   $last_names = !empty($pinfo)?array_column($pinfo, 'stu_name', 'stu_id'):""; if(empty($laststuid) || empty($userifo[0]['openid'])){echo "请在此处选择学生";}else{echo !empty($last_names)?$last_names[$laststuid]:"";}?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <?php if(!empty($last_names[$laststuid]) ){ unset($last_names[$laststuid]);} if(!empty($last_names) && !empty($userifo[0]['openid'])){foreach($last_names as $key=> $value){?>
                        <li><a onclick="changeStu(this)" href="###" id="<?=$key;?>"><?=$value;?></a></li>
                        <?php }}?>
                    </ul>
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
                <a href="/pastudent/index"><h5 class="list-group-item-heading text-center">我的资料</h5></a>
                <a href="/pastudent/child"><h5 class="list-group-item-heading text-center">学生信息</h5></a>
                <a href="/pastudent/password"><h5 class="list-group-item-heading text-center">密码修改</h5></a>
            </li>
            <li class="list-group-item text-center gz_pot">
                <img class="gz_left" src="../img/pc_zl.png" />
                <span style="font-size: 16px;">家校互动</span>
                <img class="gz_right" src="../img/pc_dw.png" />
            </li>
            <li class="list-group-item gz_xuanx">
                <a href="/pastudent/homeschool"><h5 class="list-group-item-heading text-center">家校沟通</h5></a>
                <a href="/pastudent/security"><h5 class="list-group-item-heading text-center">平安通知</h5></a>
                <!--<a href="###"><h5 class="list-group-item-heading text-center">亲情电话</h5></a>-->
            </li>
            <li class="list-group-item text-center gz_pot">
                <img class="gz_left" src="../img/pc_ck.png" />
                <span style="font-size: 16px;">餐卡</span>
                <img class="gz_right" src="../img/pc_dw.png" />
            </li>
            <li class="list-group-item gz_xuanx">
                <!--<a href="###"><h5 class="list-group-item-heading text-center">余额查询</h5></a>-->
                <a href="/pastudent/records"><h5 class="list-group-item-heading text-center">消费记录</h5></a>
            </li>
            <li class="list-group-item text-center gz_pot">
                <img class="gz_left" src="../img/pc_jl.png" />
                <span style="font-size: 16px;">掌上教育</span>
                <img class="gz_right" src="../img/pc_dw.png" />
            </li>
            <li class="list-group-item gz_xuanx">
                <a href="/pastudent/website"><h5 class="list-group-item-heading text-center">学校微官网</h5></a>
                <a href="/pastudent/notice"><h5 class="list-group-item-heading text-center">校内公告</h5></a>
                <a href="/pastudent/dynamics"><h5 class="list-group-item-heading text-center">班级动态</h5></a>
            </li>
        </ul>
        <div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
            <?= $content ?>

<!--<footer class="footer">

</footer>-->
            <div class="text-center" style="line-height: 30px;color: #98999b;">
                Copyright @ 河南正梵通信技术有限公司 All rights reserved 豫ICP备13024673<br />
                <img src="/img/0206_88.png" />豫公网安备 41010502002379
            </div>
</body>
</html>
<?php $this->endPage() ?>

<script type="text/javascript">
    //切换身份
    function changeShenfen(t){
        var formdata = {};
        formdata.shenfen = t.id;
        var url = "<?php echo $this->params['path'];?>/pastudent/changeshenfen";
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
    //切换学生
    function changeStu(t){
        var formdata = {};
         formdata.stuid = t.id;
         formdata.stuname = $(t).text();
        var url = "<?php echo $this->params['path'];?>/pastudent/chcld";
        var tourl = "<?php echo $this->params['path'];?>/pastudent/child";
        $.post(url,formdata).done(function(data){
            if (data == '0'){
                alert("选择学生切换成功");
                window.location.href=tourl;
            }else {
                alert("学生切换失败，请联系客服人员处理");
            }
        });
        $('.changestu').html(formdata.stuname);
    }

//    $(".pull-left").find("a").length();
    $(function() {
        var lurl = location.href;
        if (lurl.indexOf("index") >= 0) {
            $(".list-group-item-heading").css("color", "#666");
            $(".list-group-item-heading").eq(0).css("color", "#FF9D11");
        }
        if(lurl.indexOf("child")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(1).css("color","#FF9D11");
        }
        if(lurl.indexOf("password")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(2).css("color","#FF9D11");
        }
        if(lurl.indexOf("homeschool")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(3).css("color","#FF9D11");
        }
        if(lurl.indexOf("security")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(4).css("color","#FF9D11");
        }
        if(lurl.indexOf("records")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(5).css("color","#FF9D11");
        }
        if(lurl.indexOf("website")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(6).css("color","#FF9D11");
        }
        if(lurl.indexOf("notice")>=0 || lurl.indexOf("schoolmin")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(7).css("color","#FF9D11");
        }
        if(lurl.indexOf("dynamics")>=0||lurl.indexOf("dongtaimin")>=0){
            $(".list-group-item-heading").css("color","#666");
            $(".list-group-item-heading").eq(8).css("color","#FF9D11");
        }
    })
</script>