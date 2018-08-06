<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
?>
<script type="text/javascript" charset="utf-8" src="../Text_editing/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="../Text_editing/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="../Text_editing/lang/zh-cn/zh-cn.js"></script>
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <ul class="nav nav-pills clearfix">
            <li>
                <h4 class="pull-left" style="margin-right: 40px;">家校沟通</h4>
            </li>
            <li class="active"  id="xiexinh" onclick="xiexinpa()">
                <a href="#xiexin" data-toggle="tab">写信</a>
            </li>
            <li id="shouxinh" onclick="shouxinpa()">
                <a href="#shouxin" data-toggle="tab" >收信</a>
            </li>
            <li id="yifah"  onclick="yifapa()">
                <a href="#yifa" data-toggle="tab">已发</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="xiexin" class="tab-pane active">
                <div class="dropdown">
                    <div class="form-group dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" style="padding-left: 10px;padding-bottom: 3px;padding-top: 3px;background-color: #ccc;">
                        <div class="input-group col-xs-8">
                            <input style="height: 40px;" class="form-control" type="text" name="<?=$is_time?>" readonly/>
                            <input type="hidden" id="ids" value="">
				        		    		<span class="input-group-addon">
					        	        		<img src="../img/ren.png" />
					        	        	</span>
                        </div>
                    </div>
                    <table class="dropdown-menu table" role="menu" aria-labelledby="dropdownMenu1">
                        <?php foreach($bzr as $value){ ?>
                        <tr>
                            <td style="width: 400px;"><?=$value['tname']?></td>
                            <td style="width: 200px;"><input type="checkbox" value="<?=$value['openid']?>" name="<?=$value['tname']?>" class="lxr" /></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td></td>
                            <td>
                                <button class="btn btn-primary">确定</button>
                            </td>
                        </tr>
                    </table>
                </div>
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
                                            <?= $value["title"]; ?> <span
                                                class="pull-right"><?= date('Y-m-d H:i:s', $value["ctime"]); ?></span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse<?= $key; ?>" class="panel-collapse collapse<?php if ($key == 0) {
                                    echo ' in';
                                }; ?> ">
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
                                        <div style="margin: 20px;">
                                            <button class="btn zd_btn2" id="<?=$value['id'];?>"><a href="/pastudent/delinbox?id=<?=$value['id'];?>&url=homeschool">删除</a></button>
                                            <button class="btn zd_btn2" value=""><a href="#####" name="<?=$value['outopenid'];?>" id="<?=$value["title"]?>" onclick="huifu(this)">回复</a></button>
                                            <div style="display: none" id="zhuanfat"><?= $value["content"]; ?></div>
                                            <button class="btn zd_btn2"  onclick="zhuanfa(this)">转发</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php
                        echo LinkPager::widget([
                            'pagination' => $pages,
                        ]);
                    }else{echo "您还没有收到过信息！";}?>
                </div>
            </div>
            <div id="yifa" class="tab-pane">
                <div class="panel-group" id="Yfaccordion">
                    <?php  if(!empty($outboxlist)) {
                        foreach ($outboxlist as $key => $value) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#Yfaccordion" href="#Yf<?= $key ?>">
                                            <?= $value['title'] ?><span
                                                class="pull-right"><?= date('Y-m-d H:i:s', $value["ctime"]); ?></span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="Yf<?= $key ?>" class="panel-collapse collapse<?php if ($key == 0) {
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
                                        <div style="margin: 20px;">
                                            <button class="btn zd_btn2"><a href="/pastudent/deloutbox?id=<?=$value['id'];?>&url=homeschool">删除</a></button>
                                             <div style="display: none" id="zhuanfat"><?= $value["content"]; ?></div>
                                            <button class="btn zd_btn2"  onclick="zhuanfa(this)">转发</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php
                        echo LinkPager::widget([
                            'pagination' => $pageso,
                        ]);
                    } else{echo "您还没有发送过信息！";}?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script type="text/javascript">
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

    $(".btn-primary").click(function(){
        var chk_value =[];
        $('input[class="lxr"]:checked').each(function(){ //jquery获取复选框值
            chk_value.push($(this).val());
        });
        num = $('input:checked').length;
        $("#ids").val(chk_value);
        var nameo =$('input[class="lxr"]:checked').attr("name");
        if(num == 0){
            nameo == "";
        }else {
            var nameo = "给"+nameo+"等"+num+"人留言";
        }
        $(".form-control").val(nameo);
    });
    function sendmsg(){
        if (checkinfo())
        {
            var formdata = {};
            var url = "/pastudent/inbox";
            formdata.title = $(".form-control").val();
            formdata.content = getContent();
            var idsstr = "";
            var isc = "";
            $('input[class="lxr"]:checked').each(function(){            //遍历table里的全部checkbox
                idsstr += $(this).val() + ",";                          //获取所有checkbox的值
            });
            if(idsstr.length > 0) //如果获取到
                idsstr = idsstr.substring(0, idsstr.length - 1);        //把最后一个逗号去掉
            formdata.toopenid = idsstr;
            $.post(url,formdata).done(function(data){
                    data = $.parseJSON(data);
                if (data.status == 0){
                    alert("发送成功");
                    window.location.reload();
                }else {
                    alert("发送失败");
                }
            });
        }
    }
    function  checkinfo(){
        var istime = $(".form-control").attr('name');
        if (istime =='guoqi'){
            alert("您的家校沟通已过期，请续费后使用！");
            return false;
        }
        var title = $(".form-control").val();
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

    function zhuanfa(t){
        var content = $(t).prev("#zhuanfat").html();
        setContent(content);
        $("#shouxinh").attr("class","");
        $("#xiexinh").attr("class","active");
        $("#yifah").attr("class","");
        $("#shouxin").attr("class","tab-pane");
        $("#xiexin").attr("class","tab-pane active");
        $("#yifa").attr("class","tab-pane");
    }

     function huifu(t){
        var value = $(t).attr("name");
        $("#ids").val(value);
        var title = $(t).attr("id");
        if(title.length > 0){
            //如果获取到
            title = title.substring(2);
        }
        title = "回复"+title;
        $(".form-control").val(title);
        $("#ids").attr("name", "hf");
            $("#shouxinh").attr("class","");
            $("#xiexinh").attr("class","active");
            $("#yifah").attr("class","");
            $("#shouxin").attr("class","tab-pane");
            $("#xiexin").attr("class","tab-pane active");
            $("#yifa").attr("class","tab-pane");
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
          window.location.href = "/pastudent/homeschool?type=shouxin";
    }

    function yifapa(){
       var url = window.location.href;
      window.location.href = "/pastudent/homeschool?type=yifa";
    }
    function xiexinpa(){
       var url = window.location.href;
      window.location.href = "/pastudent/homeschool?type=xiexin";
    }
</script>
<?php
if (Yii::$app->session->hasFlash('success')) {
    $infos = Yii::$app->session->getFlash('success');
    ?>
    <script type="text/javascript">
        var now = '<?php echo $infos; ?>';
        alert(now);
    </script>
<?php } ?>