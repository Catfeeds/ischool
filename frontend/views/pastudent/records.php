<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

function config($sid, $pos_no) {
    $config = [
            '56758' => [
                '241' => "超市",
                '242'=>"超市",
                '251' => "医务室",
            ],
            '56650' => [
                '71' => "超市",
                '72' => "超市",
                '81' => "面包房",
                '91' => "超市",
                '92' => "超市",
                '155' => "超市",
                '219' => "超市",
                '151' => "超市",
                '221' => "超市",
                '220' => "超市",
                '152' => "超市",
                '222' => "超市",
                '154' => "超市",
                '153' => "超市",
                '223' => "面包房",
                '224' => "面包房",
            ],
            '56651' => [
                '155' => "超市",
                '219' => "超市",
                '151' => "超市",
                '221' => "超市",
                '220' => "超市",
                '152' => "超市",
                '222' => "超市",
                '154' => "超市",
                '153' => "超市",
                '223' => "面包房",
                '224' => "面包房",
           ],
        ];
    if (isset($config[$sid]) && isset($config[$sid][$pos_no])) {
        return $config[$sid][$pos_no];
    } else {
        return "餐厅刷卡";
    }

}
?>
<div style="background-color: white;padding: 10px 20px;height: 400px;box-shadow: 0 0 2px #ccc;height: auto">
    <ul class="nav nav-pills clearfix">
        <li>
            <h4 class="pull-left" style="margin-right: 40px;">消费记录</h4>
        </li>
        <li class="active" id="today" onclick="xiexinpa()">
            <a href="#xiexin" data-toggle="tab">今天</a>
        </li>
        <li id="week" onclick="shouxinpa()">
            <a href="#shouxin" data-toggle="tab">本周</a>
        </li>
        <li id="month" onclick="yifapa()">
            <a href="#yifa" data-toggle="tab">本月</a>
        </li>
    </ul>
    <div class="tab-content">
        <?php if (isset($is_time) && $is_time=='guoqi'){echo "<p style='margin-top: 20px'>您的学生餐卡服务已经欠费，请缴费后查询！</p>";} else{?>
        <div id="xiexin" class="tab-pane active">
            <table class="table table-bordered text-center" style="margin-top: 20px;">
                <tr style="background-color: #eee;">
                    <!--                    <td>序号</td>-->
                    <td>消费时间</td>
                    <td>消费地点</td>
                    <td>消费金额</td>
                    <td>余额</td>
                </tr>
                <?php  if(!empty($today)){ foreach($today['model'] as $key=>$value){
                    ?>
                    <tr>
                        <td><?php echo date("Y-m-d H:i:s",$value['created']); ?></td>
                        <td><?php
                            echo  config($value['school_id'],$value['pos_sn']);
                            ?></td>
                        <td>
                            <?=$value['amount']?>
                        </td>
                        <td>
                            <?=$value['balance']?>
                        </td>
                    </tr>
                <?php }} ; ?>
            </table>
            <?php  if(!empty($today)){
                echo LinkPager::widget([
                    'pagination' => $today['pages'],
                ]);
            } ?>
        </div>
        <div id="shouxin" class="tab-pane">
            <table class="table table-bordered text-center" style="margin-top: 20px;">
                <tr style="background-color: #eee;">
                    <td>序号</td>
                    <td>姓名</td>
                    <td>消费类型</td>
                    <td>消费时间</td>
                    <td>消费金额</td>
                </tr>
                <?php if(!empty($week)){
                    foreach($week['model'] as $key=>$value){ ?>
                    <tr>
                        <td><?=$key?></td>
                        <td><?=$stuname?></td>
                        <td><?php
                            echo  config($value['school_id'],$value['pos_sn']);
                            ?></td>
                        <td><?php echo date("Y-m-d H:i:s",$value['created']); ?></td>
                        <td>
                            <?=$value['amount']?>
                        </td>
                    </tr>
                <?php }}; ?>
            </table>
            <?php  if(!empty($week)){
                echo LinkPager::widget([
                    'pagination' => $week['pages'],
                ]);
            } ?>
        </div>
        <div id="yifa" class="tab-pane">
            <table class="table table-bordered text-center" style="margin-top: 20px;">
                <tr style="background-color: #eee;">
                    <td>序号</td>
                    <td>姓名</td>
                    <td>消费类型</td>
                    <td>消费时间</td>
                    <td>消费金额</td>
                </tr>
                <?php if(!empty($month)){
                    foreach ($month['model'] as $key => $value) { ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= $stuname ?></td>
                            <td><?php
                            echo  config($value['school_id'],$value['pos_sn']);
                            ?></td>
                            <td><?php echo date("Y-m-d H:i:s", $value['created']); ?></td>
                            <td>
                                <?= $value['amount'] ?>
                            </td>
                        </tr>
                    <?php }}; ?>
            </table>
            <?php if(!empty($month)){
                echo LinkPager::widget([
                    'pagination' => $month['pages'],
                ]);
            } ?>
        </div>
        <?php } ?>
    </div>
</div>
</div>
</div>
</div>

<script>

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
        $("#shouxin").attr("class","tab-pane active");
        $("#xiexin").attr("class","tab-pane");
        $("#yifa").attr("class","tab-pane");
    }else if (xx =="yifa"){
        $("#week").attr("class","");
        $("#today").attr("class","");
        $("#month").attr("class","active");
        $("#shouxin").attr("class","tab-pane");
        $("#xiexin").attr("class","tab-pane");
        $("#yifa").attr("class","tab-pane active");
    }else {
        $("#week").attr("class","");
        $("#today").attr("class","active");
        $("#month").attr("class","");
        $("#shouxin").attr("class","tab-pane");
        $("#xiexin").attr("class","tab-pane active");
        $("#yifa").attr("class","tab-pane");
    }

    function shouxinpa(){
        var url = window.location.href;
        window.location.href = "/pastudent/records?type=shouxin";
    }
    function yifapa(){
        var url = window.location.href;
        window.location.href = "/pastudent/records?type=yifa";
    }
    function xiexinpa(){
        var url = window.location.href;
        window.location.href = "/pastudent/records?type=xiexin";
    }

</script>