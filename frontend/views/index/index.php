<?php
use yii\helpers\Html;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>正梵智慧校园</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <link rel="shortcut icon" href="img/0206_08.png">
    <link rel="stylesheet" href="css/zhanbootstrap.min.css">
    <!--    <link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">-->
    <!--    <link rel="stylesheet" href="css/font-awesome.min.css">-->
    <link rel="stylesheet" href="css/mystyle.css"/>
    <!--    <script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>-->
    <script src="js/zhandjq.min.js"></script>
    <script src="js/zhanbootstrap.min.js"></script>
    <!--    <script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
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
<div class="container hidden"id="xp_nav_dh">
    <div class="row" style="max-width:1100px">
        <ul class="">
            <li class="active"><a href="#tophead">首页</a></li>
            <li><a href="#Produt_Introduction">产品介绍</a></li>
            <li><a href="#Produt_Function">产品功能</a></li>
            <li><a href="#Registration_Process">注册流程</a></li>
            <li><a href="#Recruitment_Agents">诚招代理</a></li>
            <li><a href="#Elite_Wanted">人才招聘</a></li>
        </ul>
    </div>
</div>
<!--以上是网页的最顶部-->
<div class="navbar navbar-default" id="fix-top" role="navigation" style="margin: 0">
    <div class="container" style="height: 100px">
        <div class="row" style="max-width:1100px">
            <div class="navbar-header" >
                <button type="button" class="navbar-toggle col-xs-1 col-xs-pull-2" data-toggle="collapse"style="margin-top: 25px"
                        data-target="#example-navbar-collapse">
                    <span class="sr-only">切换导航</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand my_brand" href="#">
                    <img src="img/0208_04.png" alt=""/>
                </a>
            </div>
            <div class="collapse navbar-collapse xiaop"id="example-navbar-collapse">
                <ul class="nav navbar-nav text-justify col-sm-8 col-md-8 col-md-push-2 col-lg-push-2 dixian">
                    <li class="active"><a href="#tophead">首页</a></li>
                    <li><a href="#Produt_Introduction">产品介绍</a></li>
                    <li><a href="#Produt_Function">产品功能</a></li>
                    <li><a href="#Registration_Process">注册流程</a></li>
                    <li><a href="#Recruitment_Agents">诚招代理</a></li>
                    <li><a href="#Elite_Wanted">人才招聘</a></li>
                </ul>
            </div>
            <ul class="sign_in_stay pull-right col-xs-3 col-md-2 col-lg-2">
                <?php  $session = Yii::$app->session;  if(!empty($session->get('name')) && !empty($session->get('tel')) && $session['lifetime']>time()){ ?>
                    <li class="btn pull-right"><a class="sign_stay"">欢迎您<?php echo $session->get('name');?>!</a></li>
                    <li class="btn btn-primary pull-right"><a class="sign_in" style="color:white" href="<?php echo $session->get('url');?>">个人信息</a></li>
<?php }else{ ?>
                <li class="btn pull-right"><a class="sign_stay" href="/site/denglu">登录</a></li>
                <li class="btn btn-primary pull-right"><a class="sign_in" style="color:white" href="/site/denglu?type=zhuce">注册</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<!--以上是导航-->
<div id="my_banner">
    <div class="container" >
        <div class="row">
            <h2 class="col-xs-8 col-sm-8 col-md-5 col-xs-push-3 my_banner_p1">为了学生的健康和安全</h2>
        </div>
        <div class="row">
            <h3 class="col-xs-8 col-sm-8 col-md-5 col-xs-push-4 my_banner_p2">扫描二维码关注正梵智慧校园</h3>
        </div>
        <div class="row">
            <ul class="col-xs-4 col-md-2 col-md-push-4 col-xs-push-2 my_banner_school">
                <li >正梵智慧校园 操作简单</li>
                <li>学校家长 畅快沟通</li>
                <li>学生动态 一手掌控</li>
            </ul>
            <div class="col-xs-6 col-md-push-4 col-xs-push-2 my_banner_erweim">
                <img src="img/0206_55.png" alt=""/>
            </div>
        </div>
    </div>
</div>
<!--以上是banner部分-->
<div class="gaod1" style="visibility: hidden"></div>
<div class="container" id="Produt_Introduction">
    <div class="row">
        <h1 class="text-center">Produt <span> Introduction</span></h1>
        <h2 class="text-center p_i"><span>产品介绍</span></h2>
    </div>
</div>
<div id="Produt_Introduction_intr">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 Produt_Introduction_intr_img">
                <img src="img/0206_18.png" alt=""/>
            </div>
            <div class="col-xs-6 Produt_Introduction_intr_right">
                <h2 class="Produt_Introduction_intr_title">正梵智慧校园</h2>

                <p><strong>是</strong> 基于物联网和互联网技术在微信品台上开发的一款针对学生安全、</p>

                <p>学校与家长、学生与家长相互沟通的家校服务系统品台。主要功能包括学校管理、家校沟通、平安通知、亲情电话、电子请假和餐卡管理系统等功能。</p>
                <div class="btn Produt_Introduction_join">快来加入我们吧>></div>
            </div>
        </div>
    </div>
</div>
<!--以上是产品介绍-->
<div class="gaod2"style="visibility: hidden"></div>
<div class="container" id="Produt_Function">
    <div class="row">
        <h1 class="text-center">Produt <span> Function</span></h1>
        <h2 class="text-center p_i"><span>产品功能</span></h2>
    </div>
</div>
<div class="container" id="Produt_Function_func">
    <div class="row" style="padding-top: 70px">
        <div class="col-xs-12 col-sm-4 Produt_Function_func-left">
            <div class="Produt_Function_zong" style="padding-bottom: 130px;">
                <div class="col-xs-9">
                    <h4 class="text-right">学校管理</h4>
                    <p>学校微官网、校内交流、校园动态、班级管理、学生请假、成绩发布、会议通知等管理功能。</p>
                </div>
                <div class="col-xs-3 Produt_Function_func_font">
                    <img src="img/0206_25.png" alt=""/>
                </div>
            </div>
            <div class="Produt_Function_zong" style="padding-bottom: 130px;">
                <div class="col-xs-9">
                    <h4 class="text-right">亲情电话</h4>
                    <p>我们在学校安装有专用话机，学生使用学生证可在亲情电话机上无限量免费和家长通话。该卡不限制手机号码，即移动、联通、电信均可，家长可提供5个亲情号码。</p>
                </div>
                <div class="col-xs-3 center Produt_Function_func_font">
                    <img src="img/0206_32.png" alt=""/>
                </div>
            </div>
            <div class="Produt_Function_zong">
                <div class="col-xs-9">
                    <h4 class="text-right">平安通知</h4>
                    <p>家长和相关老师可实时收到学生进出学校、进出宿舍通知。</p>
                </div>
                <div class="col-xs-3 center Produt_Function_func_font">
                    <img src="img/0206_36.png" alt=""/>
                </div>
            </div>
        </div>
        <div class="hidden-xs col-sm-4 tu"  style="padding-top: -70px">
            <img src="img/0206_22.png" alt=""/>
        </div>
        <div class="col-xs-12 col-sm-4 Produt_Function_func-left">
            <div class="Produt_Function_zong" style="padding-bottom: 130px;">
                <div class="col-xs-3 hidden-xs center Produt_Function_func_font">
                    <img src="img/0206_28.png" alt=""/>
                </div>
                <div class="col-xs-9">
                    <h4 class="text-left hidden-xs">家校沟通</h4>
                    <h4 class="text-right show-xs hidden-sm hidden-md hidden-lg">家校沟通</h4>
                    <p>通过手机、互联网等手段，快捷方便的实现家长和老师的实时在线沟通。它支持文字、图片、语音、视频、文档等多样化内容。</p>
                </div>
                <div class="col-xs-3 show-xs hidden-sm hidden-md hidden-lg center Produt_Function_func_font">
                    <img src="img/0206_28.png" alt=""/>
                </div>
            </div>
            <div class="Produt_Function_zong">
                <div class="col-xs-3 hidden-xs center Produt_Function_func_font">
                    <img src="img/0206_33.png" alt=""/>
                </div>
                <div class="col-xs-9">
                    <h4 class="text-left hidden-xs">餐卡系统</h4>
                    <h4 class="text-right show-xs hidden-sm hidden-md hidden-lg">餐卡系统</h4>
                    <p>家</span>长可通过该平台或学校管理员给学生充值。充值后，学生凭学生卡可在学校刷卡就餐、购物等。家长可以实时收到学生的消费情况，也可查询学生的消费记录和余额。</p>
                </div>
                <div class="col-xs-3 show-xs hidden-sm hidden-md hidden-lg center Produt_Function_func_font">
                    <img src="img/0206_28.png" alt=""/>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="Produt_Function_next" style="margin-top: 105px">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4 Produt_Function_next_wen">
                <div class="wen_p text-center">
                    <h2><img src="img/0206_43.png" alt=""/>家长功能</h2>
                    <p>接收学生进出校或宿舍的信息</p>
                    <p>可实时在线和老师校长发送信息</p>
                    <p>学生用学生证可免费与家长通话</p>
                    <p>家长可通过本平台和开始请假</p>
                    <p>家长可在本平台查询学生成绩</p>
                    <p style="border-bottom: 0">可收到学生在校的消费记录并查询</p>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 Produt_Function_next_wen">
                <div class="wen_p text-center">
                    <h2><img src="img/0206_40.png" alt=""/>老师功能</h2>
                    <p>管理班级学生信息</p>
                    <p>可实时在线和老师校长发送信息</p>
                    <p>老师可通过本平台查看已请假学生</p>
                    <p>老师可在本平台发布学生成绩单</p>
                    <p>可在校内公告和班级动态发布信息</p>
                    <p style="border-bottom: 0"> </p>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 Produt_Function_next_wen">
                <div class="wen_p text-center">
                    <h2><img src="img/0206_46.png" alt=""/>校长功能</h2>
                    <p>查看学校学生的考勤</p>
                    <p>可收到家长和老师发送的信息</p>
                    <p>管理学校老师</p>
                    <p>编辑学校微官网</p>
                    <p>设置老师权限</p>
                    <p style="border-bottom: 0;"> </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--以上是产品功能-->
<div class="gaod3"style="visibility:hidden"></div>
<div id="Registration_Process">
    <div class="container">
        <div class="row">
            <h1 class="text-center">Registration <span> Process</span></h1>
            <h2 class="text-center p_i"><span>注册流程</span></h2>
        </div>
    </div>
</div>
<div class="container">
    <div class="row" style="background: rgba(29, 161, 244, 0.99) ; padding: 30px 0">
        <div class="col-xs-12 col-sm-6">
            <div class="text-center Registration_Process_bj">
                <img class="" src="img/0206_59.png" alt=""/>
                <video class="hidden" width="90%" controls class="radio_hang" id="dd" autoplay="autoplay">
                    <source src="zhengfanradio.mp4" type="video/mp4">
                </video>
            </div>
            <div class="Registration_Process_zclc">正梵智慧校园注册流程</div>
        </div>
        <div class="col-xs-12 col-sm-6 Registration_Process_right">
            <h3 class="btn btn-primary ">注册步骤</h3>
            <ol>
                <li>扫描二维码 <p>扫描右侧二维码，关注我们</p></li>
                <li>进入我的资料 <p>在公众号右下角点击“我的资料”，点击进入我的资料。</p></li>
                <li>注册家长信息 <p>按照提示填写家长真实信息，推荐人可不填写。</p></li>
                <li>绑定我的学生 <p>选择无视家长，选择“+”按钮，按步骤填写学生信息。</p></li>
                <li>确认信息无误，保存 <p>保存后，学生姓名旁显示“已审核”即绑定成功。</p></li>
            </ol>
            <hr style="width: 70%">
            <p>如有疑问，微信或电话0371-55030687联系我们在线客服。</p>

            <div class="Registration_Process_img"><img src="img/0206_55.png" alt=""/></div>
        </div>
    </div>
</div>
<!--以上是注册流程-->
<div class="gaod4"style="visibility:hidden"></div>
<div class="container" id="Recruitment_Agents">
    <div class="row">
        <h1 class="text-center">Recruitment <span> Agents</span></h1>
        <h2 class="text-center p_i"><span>诚招代理</span></h2>
    </div>
</div>
<div class="container">
    <div class="row daili">
        <div class="col-lg-6 Recruitment_Agents_row">
            <a href="###" class="pull-left">
                <img src="img/0208_07.jpg" />
            </a>
            <div class="media-body" style="color: #98999b;background-color: #eee;height: 75px;padding-left: 20px;padding-top: 10px;">
                <h4 class="media-heading" style="color: black;">
                    第一步：咨询交流
                </h4>
                可咨询客服电话0371-55030687，了解正梵智慧校园相关信息。
            </div>
        </div>
        <div class="col-lg-6 Recruitment_Agents_row">
            <a href="###" class="pull-left">
                <img src="img/0208_09.jpg" />
            </a>
            <div class="media-body" style="color: #98999b;background-color: #eee;height: 75px;padding-left: 20px;padding-top: 10px;">
                <h4 class="media-heading" style="color: black;">
                    第二步：申请代理
                </h4>
                同公司代理人员并申请代理，公司有义务对您的资料进行保密。
            </div>
        </div>
    </div>
    <div class="row daili">
        <div class="col-lg-6 Recruitment_Agents_row">
            <a href="###" class="pull-left">
                <img src="img/0208_13.jpg" />
            </a>
            <div class="media-body" style="color: #98999b;background-color: #eee;height: 75px;padding-left: 20px;padding-top: 10px;">
                <h4 class="media-heading" style="color: black;">
                    第三步：资格审查
                </h4>
                符合代理条件，进入资格审查。
            </div>
        </div>
        <div class="col-lg-6 Recruitment_Agents_row">
            <a href="###" class="pull-left">
                <img src="img/0208_14.jpg" />
            </a>
            <div class="media-body" style="color: #98999b;background-color: #eee;height: 75px;padding-left: 20px;padding-top: 10px;">
                <h4 class="media-heading" style="color: black;">
                    第四步：洽谈细节
                </h4>
                双方进行细节方面的沟通，达成初步合作意向。
            </div>
        </div>
    </div>
    <div class="row daili">
        <div class="col-lg-6 Recruitment_Agents_row">
            <a href="###" class="pull-left">
                <img src="img/0208_17.jpg" />
            </a>
            <div class="media-body" style="color: #98999b;background-color: #eee;height: 75px;padding-left: 20px;padding-top: 10px;">
                <h4 class="media-heading" style="color: black;">
                    第五步：技术培训
                </h4>
                公司安排专人对公司产品及操作进行培训。
            </div>
        </div>
        <div class="col-lg-6 Recruitment_Agents_row">
            <a href="###" class="pull-left">
                <img src="img/0208_18.jpg" />
            </a>
            <div class="media-body" style="color: #98999b;background-color: #eee;height: 75px;padding-left: 20px;padding-top: 10px;">
                <h4 class="media-heading" style="color: black;">
                    第六步：签订合同
                </h4>
                培训合格并达到公司相关要求后双方签订合同。
            </div>
        </div>
    </div>
</div>
<!--以上是诚招代理-->
<div class="gaod5"style="visibility: hidden"></div>
<div class="container" id="Elite_Wanted">
    <div class="row">
        <h1 class="text-center">Elite <span> Wanted</span></h1>
        <h2 class="text-center p_i"><span>人才招聘</span></h2>
    </div>
</div>
<div class="container">
    <div class="row">
        <ul class="nav nav-tabs nav-justified" id="table1">
            <li class="active">
                <a href="#ruanjian" data-toggle="tab">软件</a>
            </li>
            <li>
                <a href="#yunying" data-toggle="tab">运营</a>
            </li>
            <li>
                <a href="#yinjian" data-toggle="tab">硬件</a>
            </li>
        </ul>
        <div class="tab-content" style="height: 200px;">
            <div id="ruanjian" class="tab-pane active">
                <h4>PHP/JAVA开发工程师（中高）</h4>
                <p>
                    1、本科及以上学历，身体健康；<br />
                    2、熟练掌握php/JAVA编程、ThinkPHP开发框架；<br />
                    3、掌握MySql数据库开发、架构及设计；<br />
                    4、有3年以上工作经历，有较强的独立工作能力和解决问题的能力；<br />
                    5、喜爱专研新技术，热爱研发工作，较强学习能力；<br />
                    6、薪酬面议。
                </p>
            </div>
            <div id="yunying" class="tab-pane">
                <h4>分公司总经理</h4>
                <p>
                    1、35（含）以下，男女不限；<br />
                    2、身体健康，大专以上学历；<br />
                    3、沟通能力强，有一定的社会关系的优先；<br />
                    4、原则上工作地址，户口所在地或常住地；<br />
                    5、薪酬面议。
                </p>
            </div>
            <div id="yinjian" class="tab-pane">
                <h4>硬件工程师（中高）</h4>
                <p>
                    1、本科及以上学历，通信、电子、计算机等相关专业；<br />
                    2、熟悉通信各方面的知识，熟悉单片机，监控，GPS等方面的知识优先；<br />
                    3、两年以上工作经验；<br />
                    4、身体健康，年龄在35周岁以下；<br />
                    5、薪酬面议。
                </p>
            </div>
        </div>
    </div>
</div>
<!--以上是人才招聘-->
<div id="" style="background-color: #363b3f; padding: 50px;">
    <div class="container">
        <div class="row">
            <div class="col-xs-4 text-center">
                <img style="max-width: 100%;" src="img/0206_80.png" />
            </div>
            <div class="col-xs-4" style="color: #98999b; line-height: 40px;font-size: 14px">
                <p>公司地址：郑州市金水区东风路28号世玺中心1313室</p>
                <p>公司邮箱：hnzhengfan@163.com</p>
                <p>客服电话：0371-55030687</p>
                <p>客服时间：8:30-19:30</p>
            </div>
            <div class="col-xs-4 text-center">
                <img src="img/0206_14.png" />
                <div style="color: #98999b; font-size: 14px">
                    <p style="line-height: 36px">欢迎关注</p>
                    <p>正梵智慧校园公众号</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-center" style="line-height: 30px;margin-top: 20px;color: #98999b;">
    Copyright @ 河南正梵通信技术有限公司 All rights reserved 豫ICP备13024673<br />
    <img class="oo" src="img/0206_88.png" />豫公网安备 41010502002379
</div>
<script>
    $('.sign_in_stay li').each(function(){
        $(this).mouseover(function(){
            $('.sign_in_stay li').removeClass('btn-primary').find('a').css({'color':'#050606'})
            $(this).addClass('btn-primary').find('a').css({'color':'#fff'})
        })
    })
    $(function(){
        $(window).scroll(function(){
            var t=window.scrollY||document.documentElement.scrollTop;
            if(t>=35){
                $("#fix-top").addClass("navbar-fixed-top");
            }else{
                $("#fix-top").removeClass("navbar-fixed-top");
            }
        })
    })
    $('.Registration_Process_bj img').click(function(){
        $(this).addClass('hidden')
        $('#dd').removeClass('hidden').parent('.Registration_Process_bj').css({"background":"#ccc"})
    })
    var audio = document.getElementById("dd");
    audio.loop = false;
    audio.addEventListener('ended', function() {
        $('.Registration_Process_bj img').removeClass('hidden')
        $("#dd").addClass('hidden').parent('.Registration_Process_bj').css({"background":""})
    }, false);
    var ft = $('#tophead').offset().top
    var pi = $('#Produt_Introduction').offset().top
    var pf = $('#Produt_Function').offset().top
    var rp = $('#Registration_Process').offset().top
    var ra = $('#Recruitment_Agents').offset().top
    var ew = $('#Elite_Wanted').offset().top
    var ew1 = $('.oo').offset().top
    $(".dixian li").eq(0).click(function(){
        $('body,html').animate({scrollTop:ft},500); //点击按钮让其到相应页面
    });
    $(".dixian li").eq(1).click(function(){
        $('body,html').animate({scrollTop:pi-100},500); //点击按钮让其到相应页面
    });
    $(".dixian li").eq(2).click(function(){
        $('body,html').animate({scrollTop:pf-100},500); //点击按钮让其到相应页面
    });
    $(".dixian li").eq(3).click(function(){
        $('body,html').animate({scrollTop:rp-100},500); //点击按钮让其到相应页面
    });
    $(".dixian li").eq(4).click(function(){
        $('body,html').animate({scrollTop:ra-100},500); //点击按钮让其到相应页面
    });
    $(".dixian li").eq(5).click(function(){
        $('body,html').animate({scrollTop:ew-100},500); //点击按钮让其到相应页面
    });
    var ft1 = $('.gaod1').offset().top
    var ft2 = $('.gaod2').offset().top
    var ft3 = $('.gaod3').offset().top
    var ft4 = $('.gaod4').offset().top
    var ft5 = $('.gaod5').offset().top
    $(window).scroll(function(){
        var bh=window.scrollY||document.documentElement.scrollTop;
        if(bh<ft1-150){
            $(".dixian li").eq(0).addClass('active').siblings('li').removeClass('active')
        }else{
            if(bh>=ft1-150 && bh<ft2-150){
                $(".dixian li").eq(1).addClass('active').siblings('li').removeClass('active')
            }else{
                if(bh>=ft2-150 && bh<ft3-150){
                    $(".dixian li").eq(2).addClass('active').siblings('li').removeClass('active')
                }else{
                    if(bh>=ft3-150 && bh<ft4-150){
                        $(".dixian li").eq(3).addClass('active').siblings('li').removeClass('active')
                    }else{
                        if(bh>=ft4-150 && bh<ft5-150){
                            $(".dixian li").eq(4).addClass('active').siblings('li').removeClass('active')
                        }else{
                            if(bh>=ft5-150 ){
                                $(".dixian li").eq(5).addClass('active').siblings('li').removeClass('active')
                            }
                        }
                    }
                }
            }
        }
    })
</script>
<script type="text/javascript">
    function browserRedirect() {
        var sUserAgent = navigator.userAgent.toLowerCase();
        /*     var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";*/
        var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
        var bIsMidp = sUserAgent.match(/midp/i) == "midp";
        var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
        var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
        var bIsAndroid = sUserAgent.match(/android/i) == "android";
        var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
        var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
        if ((/*bIsIpad ||*/ bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) ){
            $('.xiaop').html('<button class="btn btn-default pull-right xp_aniu" style="border: 1px solid #ccc;position: relative;z-index: 999;top: 20px;right: 10%;width: 80px;height: 40px;padding: 5px 10px"><span style="font-size: 16px" class="glyphicon glyphicon-list"></span></button>')
            $('.dixian').addClass('hidden')
            $(function(){
                $(window).scroll(function(){
                    var t=window.scrollY||document.documentElement.scrollTop;
                    if(t>=35){
                        $("#fix-top").addClass("navbar-fixed-top");
                        $('.xp_aniu').click(function(){
                            $('#xp_nav_dh').addClass('visible').removeClass('hidden').css({
                                'position':'fixed',
                                'top':'105px',
                                'left':'100px',
                                'background':'rgba(0,180,255,.2)',
                                'margin':'0 auto','z-index':'9999'
                            }).find('li').css({
                                'margin':'20px'
                            }).find('a').css({
                                'color':'#666'
                            })
                        })
                    }else{
                        $("#fix-top").removeClass("navbar-fixed-top");
                        $('.xp_aniu').click(function(){
                            $('#xp_nav_dh').addClass('visible').removeClass('hidden').css({
                                'position':'fixed',
                                'top':'140px',
                                'left':'100px',
                                'background':'rgba(0,180,255,.2)',
                                'margin':'0 auto','z-index':'9999'
                            }).find('li').css({
                                'margin':'20px'
                            }).find('a').css({
                                'color':'#666'
                            })
                        })
                    }
                })
            })
        }
    }
    browserRedirect();
</script>
</body>
</html>