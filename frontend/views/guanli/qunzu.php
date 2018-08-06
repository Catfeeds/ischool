<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
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
                <h4 id="qunzjl" class="pull-left" style="margin-right: 40px;">群组交流</h4>
            </li>
            <li class="active" id="xiexinh" onclick="xiexinpa()">
                <a href="#xiexin" data-toggle="tab">写信</a>
            </li>
            <li id="shouxinh" onclick="shouxinpa()">
                <a href="#shouxin" data-toggle="tab">收信</a>
            </li>
            <li id="yifah" onclick="yifapa()">
                <a href="#yifa" data-toggle="tab">已发</a>
            </li>
            <li id="chengyuan">
                <a href="#chengyuan" data-toggle="modal" data-target="#cyModal">成员</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="xiexin" class="tab-pane active">
<!--                <div data-toggle="modal" data-target="#lxModal">-->
<!--                    <div class="form-group" style="padding-left: 10px;padding-bottom: 3px;padding-top: 3px;background-color: #ccc;">-->
<!--                        <div class="input-group col-xs-8">-->
<!--                            <input style="height: 40px;" class="form-control" type="text" id="form-control" />-->
<!--                            <input type="hidden" id="ids" value="" name="">-->
<!--				        		    		<span class="input-group-addon">-->
<!--					        	        		<img src="/img/ren.png" />-->
<!--					        	        	</span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div>
                    <script id="editor" type="text/plain" style="width:100%;height:350px;"></script>
                </div>
                <div class="clearfix" style="margin-top: 10px;">
                    <button class="btn btn-success pull-right" onclick="sendmsg()">发送</button>
                </div>
            </div>
            <div id="shouxin" style="min-height: 480px;" class="tab-pane">
                <div class="panel-group" id="accordion">
                    <?php if(!empty($inboxlist)) {
                        foreach ($inboxlist as $key => $value) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $key; ?>">
                                            <?= $value["title"]; ?><span
                                                class="pull-right"><?= date('Y-m-d H:i:s', $value["ctime"]); ?></span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse<?= $key; ?>" class="panel-collapse collapse <?php if ($key == 0) {
                                    echo ' in';
                                }; ?>">
                                    <div class="panel-body" id="tupian">
                                        <?= $value["content"]; ?>
                                        <?php $data= substr($value['fujian'],0,4); $data2 =substr($value["fujian"], 4);$fujian = explode('#',$data2); if (!empty($data) && $data == "one#") {foreach ($fujian as $key => $val) {
                                            ?>
                                            <div class="file-down" style="margin-top:10px;">
                                                <a href="<?php echo $val; ?>" target="_self">
                                                    <div class="down-popo btn btn-info">
                                                        下载附件
                                                    </div>
                                                </a>
                                            </div>
                                        <?php }} ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php
                        echo LinkPager::widget([
                            'pagination' => $pages,
                        ]);
                    }else{echo "您还没有收到信息！";}
                    ?>
                </div>
            </div>
            <div id="yifa" class="tab-pane">
                <div class="panel-group" id="Yfaccordion">
                    <?php if(!empty($outboxlist)){foreach($outboxlist as $key=>$value){ ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#Yfaccordion" href="#Yf<?=$key?>">
                                        <?=$value['title']?><span class="pull-right"><?=date('Y-m-d H:i:s',$value["ctime"]); ?></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="Yf<?=$key?>" class="panel-collapse collapse <?php if($key==0){echo ' in';}; ?>">
                                <div class="panel-body" id="tupian">
                                    <?=$value["content"]; ?>
                                    <?php $data= substr($value['fujian'],0,4); $data2 =substr($value["fujian"], 4);$fujian = explode('#',$data2); if (!empty($data) && $data == "one#") {foreach ($fujian as $key => $val) {
                                        ?>
                                        <div class="file-down" style="margin-top:10px;">
                                            <a href="<?php echo $val; ?>" target="_self">
                                                <div class="down-popo btn btn-info">
                                                    下载附件
                                                </div>
                                            </a>
                                        </div>
                                    <?php }} ?>
                                    <div style="margin: 20px;">
                                        <button class="btn zd_btn2" id="<?=$value['id']?>"><a href="javascript:void(0);"  onclick="delqunz(this)">删除</a></button>
                                        <div style="display: none" id="zhuanfat"><?= $value["content"]; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                        <?php
                        echo LinkPager::widget([
                            'pagination' => $pageso,
                        ]);}else{echo "您还没有发送过信息！";}
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

//群组成员
<div class="modal fade" id="cyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                群组成员
                <button class="close" data-dismiss="modal" >
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php foreach($chengyuan as $k=>$v){ ?>
                    <a class="btn btn-success" style="margin-top: 0.3em"><?=$v['name']?></a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!--联系人-->
<div class="modal fade" id="lxModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <h4 class="modal-header">
                选择联系人
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </h4>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-justified" id="table1">
                    <li>
                        <a href="#geren" data-toggle="tab">联系人</a>
                    </li>
                </ul>
                <div class="form-group" style="margin: 10px auto;height: 20px;">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="sousuo"  onblur="onblus()"/>
                        <span class="input-group-addon">搜索</span>
                    </div>
                </div>
                <div class="tab-content" style="max-height: 300px;overflow: auto;">
                    <div id="geren" class="tab-pane active">
                        <table class="table">
                            <?php foreach($teachers as $val){ ?>
                                <tr>
                                    <td style="width: 400px;"><?=$val['tname']; ?></td>
                                    <td style="width: 200px;"><input type="checkbox" value="<?=$val['openid']?>" name="<?=$val['tname']?>" class="lxr"/></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="quanxua()"> 全 选 </button>
                <button type="button" class="btn btn-success" onclick="fanxuan()"> 反 选 </button>
                <button class="btn btn-danger" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>
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
    $("#qunzjl").html(type);
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
        window.location.href = "/guanli/qunzu?type="+type+"&types=shouxin";
    }

    function yifapa(){
        var url = window.location.href;
        window.location.href = "/guanli/qunzu?type="+type+"&types=yifa";
    }
    function xiexinpa(){
        var url = window.location.href;
        window.location.href = "/guanli/qunzu?type="+type+"&types=xiexin";
    }

    function huifu(t)
    {
        var value = $(t).attr("name");
        $("#ids").val(value);
        var title = $(t).attr("id");
        if(title.length > 0){
            //如果获取到
            title = title.substring(2);
        }
        title = "回复"+title;
        $("#form-control").val(title);
        $("#ids").attr("name", "hf");
        $("#shouxinh").attr("class","");
        $("#xiexinh").attr("class","active");
        $("#yifah").attr("class","");
        $("#shouxin").attr("class","tab-pane");
        $("#xiexin").attr("class","tab-pane active");
        $("#yifa").attr("class","tab-pane");
    }
    function zhuanfa(t){
//        var content = $(t).attr("id");
        var content = $(t).prev("#zhuanfat").html();
        setContent(content);
        $("#shouxinh").attr("class","");
        $("#xiexinh").attr("class","active");
        $("#yifah").attr("class","");
        $("#shouxin").attr("class","tab-pane");
        $("#xiexin").attr("class","tab-pane active");
        $("#yifa").attr("class","tab-pane");
    }
    $(".btn-danger").click(function(){
        var chk_value =[];
        $('input[class="lxr"]:checked').each(function(){ //jquery获取复选框值
            chk_value.push($(this).val());
        });
        num = $('input:checked').length;
        $("#ids").val(chk_value);
        if ($("#ids").attr("name") == ""){
            $("#ids").attr("name", "ly");
        }
        var nameo =$('input[class="lxr"]:checked').attr("name");
        if(num == 0){
            nameo == "";
        }else if ($("#ids").attr("name") == "ly") {
            var nameo = "给"+nameo+"等"+num+"人留言";
        }
        $(".form-control").val(nameo);
    });
    function  checkinfo(){
        var title = $("#form-control").val();
        var content = getContent();
        if(title ==""){
            alert("收件人不能为空");
            return false;
        }
        if(content == ""){
            alert("发送内容不能为空");
            return false;
        }
        return true;
    }

    function sendmsg() {
        if (checkinfo()) {
            var formdata = {};
            var url = "/guanli/dointerqunzu";
            formdata.title = $("#form-control").val();
            formdata.content = getContent();
            formdata.type = '<?=$_GET['type'];?>';
            $.post(url, formdata).done(function (data) {
                data = $.parseJSON(data);
                if (data.status == 0) {
                    alert("发送成功");
                    window.location.reload();
                } else if (data.status == 2){
                    alert("您还不是校长，请先联系客服绑定为学校校长！");
                }else {
                    alert("发送失败");
                }
            });
        }
    }
    function onblus(){
        var url="/guanli/internalcom";
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


    var ue = UE.getEditor('editor');
    function isFocus(e) {
        alert(UE.getEditor('editor').isFocus());
        UE.dom.domUtils.preventDefault(e)
    }
    function setblur(e) {
        UE.getEditor('editor').blur();
        UE.dom.domUtils.preventDefault(e)
    }
    function insertHtml() {
        var value = prompt('插入html代码', '');
        UE.getEditor('editor').execCommand('insertHtml', value)
    }
    function createEditor() {
        enableBtn();
        UE.getEditor('editor');
    }
    function getAllHtml() {
        alert(UE.getEditor('editor').getAllHtml())
    }
    function getContent() {
        var arr = [];
//        arr.push("使用editor.getContent()方法可以获得编辑器的内容");
//        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getContent());
//        alert(arr.join("\n"));
        return arr.join("\n");
    }
    function getPlainTxt() {
        var arr = [];
        arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getPlainTxt());
        alert(arr.join('\n'))
    }
    function setContent(params,isAppendTo) {
        var arr = [];
        UE.getEditor('editor').setContent(params, isAppendTo);
    }
    //    function setContent(isAppendTo) {
    //        var arr = [];
    //        arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
    //        UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
    //        alert(arr.join("\n"));
    //    }
    function setDisabled() {
        UE.getEditor('editor').setDisabled('fullscreen');
        disableBtn("enable");
    }
    function setEnabled() {
        UE.getEditor('editor').setEnabled();
        enableBtn();
    }
    function getText() {
        //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
        var range = UE.getEditor('editor').selection.getRange();
        range.select();
        var txt = UE.getEditor('editor').selection.getText();
        alert(txt)
    }
    function getContentTxt() {
        var arr = [];
        arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
        arr.push("编辑器的纯文本内容为：");
        arr.push(UE.getEditor('editor').getContentTxt());
        alert(arr.join("\n"));
    }
    function hasContent() {
        var arr = [];
        arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
        arr.push("判断结果为：");
        arr.push(UE.getEditor('editor').hasContents());
        alert(arr.join("\n"));
    }
    function setFocus() {
        UE.getEditor('editor').focus();
    }
    function deleteEditor() {
        disableBtn();
        UE.getEditor('editor').destroy();
    }
    function disableBtn(str) {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for(var i = 0, btn; btn = btns[i++];) {
            if(btn.id == str) {
                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
            } else {
                btn.setAttribute("disabled", "true");
            }
        }
    }
    function enableBtn() {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for(var i = 0, btn; btn = btns[i++];) {
            UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
        }
    }
    function getLocalData() {
        alert(UE.getEditor('editor').execCommand("getlocaldata"));
    }
    function clearLocalData() {
        UE.getEditor('editor').execCommand("clearlocaldata");
        alert("已清空草稿箱")
    }


    function delqunz(t){
        var url = "/guanli/delqzjl";
        var formdata={};
        formdata.id = $(t).parents("button").attr("id");
        if(confirm("确认删除？")) {
            $.post(url, formdata).done(function (data) {
                data = $.parseJSON(data);
                if (data.status == 0) {
                    alert("删除成功");
                    window.location.reload();
//                    $(t).parents(".panel-default").remove();
                } else if(data.status == 2){
                    alert("信息不存在！");
                }else {
                    alert("删除失败");
                }
            }, "json");
        }
    }
</script>