<script type="text/javascript" charset="utf-8" src="/Text_editing/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Text_editing/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Text_editing/lang/zh-cn/zh-cn.js"></script>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <a href="#" style="letter-spacing: -5px;">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span style="letter-spacing:5px;padding: 0 10px" onclick="javascript:history.go(-1)">返回</span>
            <button type="button" class="btn btn-success pull-right" name="<?php echo !empty($info[0]['id'])?$info[0]['id']:"" ?>" onclick="tijiao(this)">保存</button>
        </a>
        <hr/>
        <p class="text-primary row">
            <span class="badge col-lg-1" style="background: red;padding: 3px 10px">动态</span>
            <span class="col-lg-1"></span>
							<span class="col-lg-10">
								<input style="margin-top: -10px;margin-bottom: 10px;" class="form-control" placeholder="请输入主题信息" name="title" value="<?php echo !empty($info[0]['title'])?$info[0]['title']:"" ?>"/>
							</span>
        </p>
        <div>
            <script id="editor" type="text/plain" style="width:100%;height:350px;"></script>
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
        arr.push("使用editor.getContent()方法可以获得编辑器的内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getContent());
        alert(arr.join("\n"));
    }
    function getPlainTxt() {
        var arr = [];
        arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getPlainTxt());
        alert(arr.join('\n'))
    }
    function setContent(isAppendTo) {
        var arr = [];
        arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
        UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
        alert(arr.join("\n"));
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
    function getContent() {
        var arr = [];
//        arr.push("使用editor.getContent()方法可以获得编辑器的内容");
//        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getContent());
//        alert(arr.join("\n"));
        return arr.join("\n");
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
    function tijiao(t)
    {
        if (checkinfo())
        {
            var url = "/teacher/fabudt";
            var formdata = {};
            var id = $(t).attr("name");
            if(id != ""){
                formdata.id = id;
            }
            formdata.title = $(".form-control").val();
            formdata.content = getContent();
            console.log(formdata);
            $.post(url, formdata).done(function (data) {
                if (data == 0){
                    alert("发布成功");
                    window.location = "/teacher/classdynamics";
                }else {
                    alert("发布失败");
                }
            });
        }
    }
    function  checkinfo(){
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
    <?php if(!empty($info[0]['content'])){?>
    $(document).ready(function(){
        var ue = UE.getEditor('editor');
        var  proinfo='<?php echo $info[0]['content'] ?>';
        ue.ready(function() {//编辑器初始化完成再赋值
            ue.setContent(proinfo);  //赋值给UEditor
        });
    });
    <?php } ?>
</script>