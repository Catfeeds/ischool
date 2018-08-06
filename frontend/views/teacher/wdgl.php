<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
header("Content-Type: application/force-download");
?>
<style>
    .view{
        max-width: 100%;
    }
</style>
<script type="text/javascript" charset="utf-8" src="/Text_editing/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Text_editing/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Text_editing/lang/zh-cn/zh-cn.js"></script>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <ul class="nav nav-pills clearfix">
            <li>
                <h4 class="pull-left" style="margin-right: 40px;">文档管理</h4>
            </li>
            <li class="active" id="xiexinh" onclick="xiexinpa()">
                <a href="#xiexin" data-toggle="tab">文档上传</a>
            </li>
            <li id="shouxinh" onclick="shouxinpa()">
                <a href="#shouxin" data-toggle="tab">文档列表</a>
            </li>
            <li id="yifah" onclick="yifapa()">
                <a href="#yifa" data-toggle="tab">我的文档</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="xiexin" class="tab-pane active">
<!--                <div>
                    <script id="editor" type="text/plain" style="width:100%;height:350px;"></script>
                </div>
                <div class="clearfix" style="margin-top: 10px;">
                    <button class="btn btn-success pull-right" onclick="sendmsg()">发送</button>
                </div>-->
                <div >
                        <hr/>
                        <div style="margin-top: 10px;">
                            <style>
                                #w0{
                                    display: inline-block;
                                }
                            </style>
                            <?php $form = ActiveForm::begin([
                                'method'=>'post',
                                'options' => ['enctype' => 'multipart/form-data']]) ?>
                            <?= $form->field($model, 'title')->textInput(['maxlength' => 20])->label('标题（若不填写，则默认为您上传的文件名字！）') ?>
                            <?= $form->field($model, 'file')->fileInput()->label("请选择要上传的文件") ?>
                            <!--            --><?php //if(count($info['schools']) ==0){echo "上传图片功能需要绑定学校后才能使用！";}else{?>
                            <button class="btn zd_btn3">上传</button>
                            <!--            --><?php //}?>
                            <?php ActiveForm::end() ?>
                            <!--            <button class="btn zd_btn3">上传图片</button>-->
                            <?php if(count($info['schools']) ==0){echo "";}else{?>
                                <button style="display: inline-block; vertical-align: bottom" class="btn" onclick="delpic()">删除图片</button>
                            <?php }?>
                        </div>
                    </div>
            </div>
            <div id="shouxin" style="min-height: 480px;" class="tab-pane">
                <div class="panel-group" id="accordion">
                <table class="table table-bordered text-center" style="margin-top: 20px;">
                    <tr style="background-color: #eee;">
                        <td>姓名(上传人)</td>
                        <td>标题</td>
                        <td>上传时间</td>
                        <td>操作</td>
                    </tr>
                    <?php  if(!empty($info['inboxlist'])) {
                        foreach ($info['inboxlist'] as $key => $value) { ?>
                        <tr>
                            <td><?=$value['name']?></td>
                            <td><?=$value['title']?></td>
                            <td><?=date("Y-m-d H:i:s",$value['create_time']);?></td>
                            <td><a style="color: #36ADFF;" name="<?=$value['title']?>" id="<?=$value['url']?>" href="/teacher/upload?name=<?=$value['title']?>&url=<?=$value['url']?>&time=<?=time()?>">下载</a>                              </td>
                        </tr>
                        <?php  } ?></table>
                    <?php
                        echo LinkPager::widget([
                            'pagination' => $info['pages'],
                        ]);
                    }else{?> <tr><td colspan="4">您还没有收到过信息！</td></tr></table> <?php }?>
               </div>
            </div>
            <div id="yifa" class="tab-pane">
                <div class="panel-group" id="Yfaccordion">
                    <table class="table table-bordered text-center" style="margin-top: 20px;">
                        <tr style="background-color: #eee;">
                            <td>姓名(上传人)</td>
                            <td>标题</td>
                            <td>上传时间</td>
                            <td>操作</td>
                        </tr>
                        <?php  if(!empty($info['outboxlist'])) {
                        foreach ($info['outboxlist'] as $key => $value) { ?>
                            <tr>
                                <td><?=$value['name']?></td>
                                <td><?=$value['title']?></td>
                                <td><?=date("Y-m-d H:i:s",$value['create_time']);?></td>
                                <td id="<?=$value['id']?>"><a style="color: #36ADFF;" href="/teacher/upload?name=<?=$value['title']?>&url=<?=$value['url']?>&time=<?=time()?>">下载</a>
                                    <a style="color: #36ADFF; margin-left: 5px" href="javascript:void(0);" onclick="delwd(this)">删除</a></td>
                            </tr>
                        <?php  } ?></table>
                    <?php
                        echo LinkPager::widget([
                                'pagination' => $info['pageso'],
                            ]);}else{ ?>
                        <tr><td colspan="4">您还没有发送过信息！</td></tr> </table><?php }?>

                    </div>
             </div>
        </div>
</div>
</div>
</div></div>
<!--联系人-->
</body>
<script type="text/javascript" charset="gb2312">
    function quanxua(){
        $("input[class='lxr']").prop("checked",true);
    }

    function  fanxuan(){
        //$("input[name='table_records']").prop("checked",false);
        $("input[class='lxr']").each(function(){
            if($(this).prop("checked"))
            {
                $(this).prop("checked",false);
            }
            else
            {
                $(this).prop("checked",true);
            }
        })
    }

    (function($){
        $.getUrlParam = function(name)
        {
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
//            if (r!=null) return unescape(r[2]); return null;    //原始的中文出现乱码
            if (r!=null) return decodeURI(r[2]); return null;    // 中文不出现乱码
        }
    })(jQuery);

    //获取url中的参数
    var type = $.getUrlParam('type');
    var xx = $.getUrlParam('types');
    var test = window.location.href; //获取当前url地址
    //    alert(xx);
    if (xx =="shouxin"){
        $("#shouxinh").attr("class","active");
        $("#xiexinh").attr("class","");
        $("#yifah").attr("class","");
        $("#shouxin").attr("class","tab-pane active");
        $("#xiexin").attr("class","tab-pane");
        $("#yifa").attr("class","tab-pane");
    }else if (xx =="yifa"){
        $("#shouxinh").attr("class","");
        $("#xiexinh").attr("class","");
        $("#yifah").attr("class","active");
        $("#shouxin").attr("class","tab-pane");
        $("#xiexin").attr("class","tab-pane");
        $("#yifa").attr("class","tab-pane active");
    }else {
        $("#shouxinh").attr("class","");
        $("#xiexinh").attr("class","active");
        $("#yifah").attr("class","");
        $("#shouxin").attr("class","tab-pane");
        $("#xiexin").attr("class","tab-pane active");
        $("#yifa").attr("class","tab-pane");
    }

    function shouxinpa(){
        var url = window.location.href;
        window.location.href = "/teacher/wdgl?type="+type+"&types=shouxin";
    }

    function yifapa(){
        var url = window.location.href;
        window.location.href = "/teacher/wdgl?type="+type+"&types=yifa";
    }
    function xiexinpa(){
        var url = window.location.href;
        window.location.href = "/teacher/wdgl?type="+type+"&types=xiexin";
    }



    function sendmsg() {
        if (checkinfo()) {
            var formdata = {};
            var url = "/teacher/dointerqunzu";
            formdata.title = $("#form-control").val();
            formdata.content = getContent();
            formdata.type = '<?=$_GET['type'];?>';
            $.post(url, formdata).done(function (data) {
                data = $.parseJSON(data);
                if (data.status == 0) {
                    alert("发送成功");
                    window.location.reload();
                } else if (data.status == 2){
                    alert("您还没有绑定班级，请先绑定班级！");
                }else {
                    alert("发送失败");
                }
            });
        }
    }
    function onblus(){
        var url="/teacher/internalcom";
        var formdata = {};
        formdata.name = $("#sousuo").val();
        $.post(url,formdata).done(function(data){
            data = eval(data);
            var htmls = "";
            for (var i = 0; i < data.length; i++) {
                htmls +="<tr><td style='width: 400px;'>";
                htmls += data[i].tname+"</td><td style='width: 200px;'><input type='checkbox' value='"+data[i].tname+"'  name='"+data[i].tname+"' class='lxr'/></td></tr>";
            }
            $(".table tbody").html(htmls);
        });
    }

    function delwd(t){
        var url = "/teacher/delinwd";
        var formdata={};
        formdata.id = $(t).parents("td").attr("id");
        if(confirm("确认删除？")) {
            $.post(url, formdata).done(function (data) {
                data = $.parseJSON(data);
                if (data.status == 0) {
                    alert("删除成功");
                    $(t).parents("tr").remove();
                } else if(data.status == 2){
                    alert("文档不存在！");
                }else {
                    alert("删除失败");
                }
            }, "json");
        }
    }
</script>


