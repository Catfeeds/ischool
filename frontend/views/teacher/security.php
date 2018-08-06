<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
?>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <ul class="nav nav-pills clearfix">
            <li>
                <h4 class="pull-left" style="margin-right: 40px;">平安通知</h4>
            </li>
            <li class="active"  id="today" onclick="xiexinpa()">
                <a href="#tian" data-toggle="tab">今天</a>
            </li>
            <li id="week" onclick="shouxinpa()">
                <a href="#zhou" data-toggle="tab">本周</a>
            </li>
            <li  id="month" onclick="yifapa()">
                <a href="#yue" data-toggle="tab">本月</a>
            </li>
            <li class="pull-right btn-group">
                <form name="formkq" method="post" id="formkq">
                    <input type="hidden" name="type" value="" id="datatype">
                <button style="margin-right: 10px" class="btn btn-sm btn-success" onclick="kaoqinexcel(this)">导出考勤信息明细</button>
                    <button style="margin-right: 10px" class="btn btn-sm btn-success" onclick="kaoqinexcelhz(this)">导出考勤信息汇总</button>
                </form>
            </li>
        </ul>
        <div class="tab-content">
            <div id="tian" class="tab-pane active">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div id="tianOne" class="panel-collapse collapse in">
                            <div class="panel-body">
                                    <table class="table table-bordered text-center">
                                    <tr style="background-color: #eee;">
                                        <td>序号</td>
                                        <td>姓名</td>
                                        <td>时间</td>
                                        <td>进校/出校</td>
                                    </tr>
                                    <?php foreach($model as $k=>$v){ ?>
                                    <tr>
                                        <td><?=$k;?></td>
                                        <td><?php echo isset($info[$v['stuid']]) ?$info[$v['stuid']]:"" ?></td>
                                        <td><?=date("Y-m-d H:i:s",$v['ctime']);?></td>
                                        <td><?=$v['info']?></td>
                                    </tr>
                                    <?php }?>
                                    </table>
                                <?php
                                echo LinkPager::widget([
                                    'pagination' => $pages,
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="zhou" class="tab-pane">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div id="collapseOne" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <table class="table table-bordered text-center">
                                    <tr style="background-color: #eee;">
                                        <td>序号</td>
                                        <td>姓名</td>
                                        <td>时间</td>
                                        <td>进校/出校</td>
                                    </tr>
                                    <?php foreach($modelwk as $k=>$v){ ?>
                                        <tr>
                                            <td><?=$k;?></td>
                                            <td><?php echo isset($info[$v['stuid']]) ?$info[$v['stuid']]:"" ?></td>
                                            <td><?=date("Y-m-d H:i:s",$v['ctime']);?></td>
                                            <td><?=$v['info']?></td>
                                        </tr>
                                    <?php }?>
                                </table><?php
                                echo LinkPager::widget([
                                    'pagination' => $pageswk,
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="yue" class="tab-pane">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div id="yueOne" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <table class="table table-bordered text-center">
                                    <tr style="background-color: #eee;">
                                        <td>序号</td>
                                        <td>姓名</td>
                                        <td>时间</td>
                                        <td>进校/出校</td>
                                    </tr>
                                    <?php foreach($modelmh as $k=>$v){ ?>
                                        <tr>
                                            <td><?=$k;?></td>
                                            <td><?php echo isset($info[$v['stuid']]) ?$info[$v['stuid']]:"" ?></td>
                                            <td><?=date("Y-m-d H:i:s",$v['ctime']);?></td>
                                            <td><?=$v['info']?></td>
                                        </tr>
                                    <?php }?>
                                </table><?php
                                echo LinkPager::widget([
                                    'pagination' => $pagesmh,
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function kaoqinexcel(){
        var type = $(".active").eq(0).attr("id");
        $("#datatype").val(type);
        $("#formkq").attr("action","/teacher/export");
        $("#formkq").submit();
    }
    function kaoqinexcelhz(){
        var type = $(".active").eq(0).attr("id");
        $("#datatype").val(type);
        $("#formkq").attr("action","/teacher/exportkqhz");
        $("#formkq").submit();
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
    if (xx =="shouxin"){
        $("#week").attr("class","active");
        $("#today").attr("class","");
        $("#month").attr("class","");
        $("#zhou").attr("class","tab-pane active");
        $("#tian").attr("class","tab-pane");
        $("#yue").attr("class","tab-pane");
    }else if (xx =="yifa"){
        $("#week").attr("class","");
        $("#today").attr("class","");
        $("#month").attr("class","active");
        $("#zhou").attr("class","tab-pane");
        $("#tian").attr("class","tab-pane");
        $("#yue").attr("class","tab-pane active");
    }else {
        $("#week").attr("class","");
        $("#today").attr("class","active");
        $("#month").attr("class","");
        $("#zhou").attr("class","tab-pane");
        $("#tian").attr("class","tab-pane active");
        $("#yue").attr("class","tab-pane");
    }

    function shouxinpa(){
        var url = window.location.href;
        window.location.href = "/teacher/security?type=shouxin";
//            window.location.reload();
    }

    function yifapa(){
        var url = window.location.href;
        window.location.href = "/teacher/security?type=yifa";
//            window.location.reload();
    }
    function xiexinpa(){
        var url = window.location.href;
        window.location.href = "/teacher/security?type=xiexin";
//            window.location.reload();
    }
</script>