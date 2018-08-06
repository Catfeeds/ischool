<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
?>
<div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
    <ul class="nav nav-pills clearfix">
        <li>
            <h4 class="pull-left" style="margin-right: 40px;">平安通知</h4>
        </li>
        <li class="active" id="today" onclick="xiexinpa()">
            <a href="#xiexin" data-toggle="tab">今天</a>
        </li>
        <li id="week" onclick="shouxinpa()">
            <a href="#shouxin" data-toggle="tab">本周</a>
        </li>
        <li class="pull-right btn-group">
            <form name="formkq" method="post" id="formkq">
                <input type="hidden" name="type" value="" id="datatype">
                <?php  if (isset($info['is_time']) && $info['is_time']!='guoqi'){?>
                <button style="margin-right: 10px" class="btn btn-sm btn-success" onclick="kaoqinexcel(this)">导出考勤信息</button>
                <?php }?>
            </form>
<!--            <button style="margin-right: 10px" class="btn btn-sm btn-success" onclick="kaoqinexcel(this)">导出考勤信息</button>-->
        </li>
    </ul>
    <div class="tab-content">
        <?php  if (isset($info['is_time']) && $info['is_time']=='guoqi'){echo "<p style='margin-top: 20px'>您的学生平安通知已经欠费，请缴费后查询！</p>";} else{?>
        <div id="xiexin" class="tab-pane active">
            <table class="table table-bordered text-center" style="margin-top: 20px;">
                <tr style="background-color: #eee;">
                    <td>序号</td>
                    <td>姓名</td>
                    <td>时间</td>
                    <td>进校/出校</td>
                </tr>
                <tr> <?php if(!isset($model) || empty($model)){echo "<tr><td colspan='4'>暂无学生进出记录</td></tr>";}else{ foreach($model as $k=>$v){ ?>
                <tr>
                    <td><?=$k;?></td>
                    <td><?=$info['stuname'];?></td>
                    <td><?=date("Y-m-d H:i:s",$v['ctime']);?></td>
                    <td><?=$v['info']?></td>
                </tr>
                <?php } }?>
            </table>
            <?php
            if(!empty($pages)){
                echo LinkPager::widget([
                    'pagination' => $pages,
                ]);
            }
            ?>
        </div>

        <div id="shouxin" class="tab-pane">
        <table class="table table-bordered text-center" style="margin-top: 20px;">
            <tr style="background-color: #eee;">
                <td>序号</td>
                <td>姓名</td>
                <td>时间</td>
                <td>进校/出校</td>
            </tr>
            <tr><?php if(!isset($modelwk) || empty($modelwk)){echo "<tr><td colspan='4'>暂无学生进出记录</td></tr>";}else{ ?>
            <?php  foreach ($modelwk as $k => $v) { ?>
                    <tr>
                        <td><?= $k; ?></td>
                        <td><?= $info['stuname']; ?></td>
                        <td><?= date("Y-m-d H:i:s", $v['ctime']); ?></td>
                        <td><?= $v['info'] ?></td>
                    </tr>
                <?php } } ?>
            </table>
            <?php if(!empty($pages)) {
                echo LinkPager::widget([
                    'pagination' => $pageswk,
                ]);
            }
           ?>
        </div>
        <?php } ?>
    </div>
</div>
</div>
</div>
</div>
<script>

    function kaoqinexcel(){
        var type = $(".active").eq(0).attr("id");
        $("#datatype").val(type);
        $("#formkq").attr("action","/pastudent/export");
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
        $("#shouxin").attr("class","tab-pane active");
        $("#xiexin").attr("class","tab-pane");
    }else {
        $("#week").attr("class","");
        $("#today").attr("class","active");
        $("#shouxin").attr("class","tab-pane");
        $("#xiexin").attr("class","tab-pane active");
    }

    function shouxinpa(){
        var url = window.location.href;
        window.location.href = "/pastudent/security?type=shouxin";
    }

    function xiexinpa(){
        var url = window.location.href;
        window.location.href = "/pastudent/security?type=xiexin";
    }
</script>