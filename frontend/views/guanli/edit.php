<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
?>
<script type="text/javascript" src="/js/LocalResizeIMG.js"></script>
<script type="text/javascript" charset="utf-8" src="/Text_editing/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Text_editing/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Text_editing/lang/zh-cn/zh-cn.js"></script>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left"><?= $ischool ?></h4>
            <button type="button" class="btn btn-success pull-right" onclick="sub()">保存</button>
        </div>
        <!--<a href="#" style="letter-spacing: -5px;">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span style="letter-spacing:5px;padding: 0 10px">返回</span>
            <button type="button" class="btn btn-success pull-right">保存</button>
        </a>-->
        <hr/>
        <input type='hidden' name='id' id="id" value="<?php echo $id?>">
        <input type="hidden" id="imghidden" name="img" value="<?= $toppicture ?>"/>
        <input type='hidden' name='sid' id="sid" value="<?php echo $sid?>">
        <input type='hidden' name='type' id="type" value="<?php echo $type?>">
        <input type='hidden' name='tem' id="tem" value="<?php echo isset($tem)?$tem:"" ?>">
        <p class="text-primary row" style="margin-bottom: 10px;">
            <span class="badge col-lg-1" style="background: red;padding: 3px 10px">标题</span>
            <span class="col-lg-1"></span>
							<span class="col-lg-10">
								<input style="margin-top: -10px;margin-bottom: 10px;" class="form-control" placeholder="请输入主题信息" name="title" id="title" value="<?php echo $title?>"/>
							</span>
        </p>
        <p class="text-primary row">
            <span class="badge col-lg-1" style="background: red;padding: 3px 10px">简介</span>
            <span class="col-lg-1"></span>
							<span class="col-lg-10">
								<input style="margin-top: -10px;margin-bottom: 10px;" class="form-control" placeholder="请输入主题信息" id="sketch" value="<?php echo $sketch?>"/>
							</span>
        </p>
        <div>
            <script id="editor" type="text/plain" style="width:100%;height:350px;"></script>
        </div>
    </div>
    <div class="clearfix" style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <div class="zt_img pull-left" >
            <div id="loading" class="ui-dialog-loading" style="margin-top:10px;display:none">Loading..</div>
            <img style="max-width: 300px;" src="<?php echo $toppicture?:"/img/add_img.png"?>" id="img"/>
        </div>
        <button style="background-color: #36ADFF;margin-left: 20px;color: white;" class="btn" onclick="file_input()">上传封面</button>
        <input id="upload-input" accept="image/*" capture="camera" type="file" name="upload-input" style="display:none">
    </div>
</div>
</div>
</div>

<script>

    $('#upload-input').localResizeIMG({
        width: 400,
        quality: 1,
        before:function(ths,b,f){
            var names=$(ths).val().split(".");
            if(names[1]!="gif"&&names[1]!="GIF"&&names[1]!="jpg"&&names[1]!="JPG"&&names[1]!="png"&&names[1]!="PNG"&&names[1]!="jpeg"&&names[1]!="JPEG"&&names[1]!="bmp"&&names[1]!="BMP")
            {
                alert("图片必须为gif,jpg,png,bmp,jpeg格式!!");
                return;
            }
        },
        success: function (result) {
            var submitData={
                data:result.clearBase64,
            };
            $("#img").hide();
            $("#loading").show();
            $("#dimmer-loader").show();
            $("#prevImg").attr("src",result.base64);
            $.ajax({
                type: "POST",
                url: '/utils/uploadimg',
                data: submitData,
                dataType:"json",
                success: function(data){
                    if(data){
                        $("#img").attr("src",data.file_path);
                        $("#imghidden").attr("value",data.file_path);
                        $("#img").show();
                        $("#loading").hide();
                    }else{
                        alert("上传错误，请重试");
                    }
                }

            });
        }
    });
    function file_input(){
        $("#upload-input").click();
    };

    function sub() {
        if (checkinput())
        {
            var formdata = {};
            formdata.id = $("#id").val();
            formdata.img = $("#imghidden").val();
            formdata.title = $.trim($("#title").val());
            formdata.content = getContent();
            formdata.sketch = $("#sketch").val();
            formdata.type = $("#type").val();
            formdata.tem = $("#tem").val();
            var url = "/guanli/doedit";
            $.post(url,formdata).done(function (data){
                data = $.parseJSON(data);
                if (data.status == 0) {
                    alert("发送成功");
                    window.location.reload();
                } else {
                    alert("发送失败");
                }
            });
        }
    }

    function checkinput(){
        var imghidden = $("#imghidden").val();
        var title = $.trim($("#title").val());
        var content = getContent();
        var sketch = $("#sketch").val();
        if (imghidden ==""){
            alert("请至少上传一张图片");
            return false;
        }
        if (title ==""){
            alert("标题不能为空");
            return false;
        }
        if (sketch ==""){
            alert("主题不能为空");
            return false;
        }
        if (content ==""){
            alert("内容不能为空");
            return false;
        }
        return true;
    }
</script>
<script>
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
//        function setContent(isAppendTo) {
//            var arr = [];
//            arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
//            UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
//            alert(arr.join("\n"));
//        }
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
   $(function (){
      ue.addListener("ready",function(){
          var content = '<?php echo $content ?>';
//          alert(content);
          setContent(content);
      });
   })

</script>
