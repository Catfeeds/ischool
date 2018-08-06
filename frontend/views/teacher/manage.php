<style type="text/css">
.modal-content{width: 1200px;margin-left: -300px;}
</style>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;box-shadow: 0 0 2px #ccc;padding: 10px 20px 20px 20px;">
        <h4>成绩发送</h4>
        <form class="form-horizontal" id="formImg" method="post" action="/teacher/uploadcjd" enctype="multipart/form-data" target="hidden_frame" role="form">
            <div class="form-group">
                <label class="col-sm-1 control-label">学年</label>
                <div class="col-sm-6">
                    <select class="form-control" id="xueyear" name="xuenian">
                        <?php foreach($info['xuenian'] as $value){?>
                            <option value="<?=$value['year']?>"><?=$value['year']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">类型</label>
                <div class="col-sm-6">
                    <select class="form-control" onchange="showChildType(this)" id="examtype">
                        <?php foreach($info['type'] as $value){?>
                            <option value="<?=$value['name']?>"><?=$value['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-xs-3 edit-user-top">
                    <select id="childtype" style="display: none;">

                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">范围</label>
                <div class="col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="isopen" value="y"> <span style="vertical-align: middle;">是否在班级内公开可见</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">文件</label>
                <div class="col-sm-6">
                    <input class="form-control" type="file" name="ImportData[upload]" id="upfile"/>
                </div>
                <div>
                    <button type="button" class="btn btn-default"><a href="/docs/mb-chengjidan.xls" >模板</a></button><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<b style="color: red">温馨提示：</b>请选择.xls的文件格式或者直接下载模版文件使用！)
                </div>
            </div>
            <input type="hidden" value="<?=$info['cid'];?>" name="cid" />
            <input type="hidden" value="<?=$info['sid'];?>" name="sid" />
            <input type="hidden" value="<?=$info['openid'];?>" name="openid">
            <input type="hidden" value="" name="exam" id="examname"/>
            <div class="form-group">
                <label class="col-sm-1 control-label"></label>
                <div class="col-sm-6">
                    <button id="upload-ok" type="button" class="btn btn-success" onclick="file_input()">确认上传并发送</button>
                    <input type="reset" class="btn btn-default" />
                </div>
            </div>
        </form>
        <iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
    </div>

    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">成绩单删除</h4>
        </div>
        <form class="form-horizontal" method="post" action="/teacher/delcjd">
            <div class="form-group">
                <label class="col-sm-1 control-label">学年</label>
                <div class="col-sm-6">
                    <select name="cjdxxid" class="form-control" name="cjdxxid" id="cjdxxid">
                        <?php foreach($info['cjdxx'] as $key=>$value){?>
                            <option value="<?=$value['cjdid'] ?>"><?=$value['cjdname'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label"></label>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-success"  onclick="this.form.submit()">删除</button>
                </div>
            </div>
        </form>
    </div>

    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">成绩查询</h4>
        </div>
        <form class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-1 control-label">学年</label>
                <div class="col-sm-6">
                    <select class="form-control" id="cjdxx">
                        <?php foreach($info['cjdxx'] as $key=>$value){?>
                        <option value="<?=$value['cjdid'] ?>"><?=$value['cjdname'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label"></label>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-success" data-toggle="modal"  id="docjModal" onclick="querycj()">查询</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
<!--成绩查询-->
<div class="modal fade" id="cjModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                成绩查询
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered text-center" id="cjcx">
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal">
                    确定
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    function showChildType(ths){

        $this_value = $(ths).val();
        var content = "";
        var isshow = false;
        if($this_value == "周考"){

            for(var i=1;i<21;i++){
                content = content + "<option value='"+"第"+i+"周周考"+"'>"+"第"+i+"周周考"+"</option>";
            }
            isshow = true;

        }else if($this_value == "月考"){
            for(var j =1;j<13;j++){
                content = content + "<option value='"+j+"月月考"+"'>"+j+"月月考"+"</option>";
            }
            isshow = true;

        }else{
            isshow = false;
        }

        $("#childtype").html(content);

        if(isshow==true){
            $("#childtype").show();
        }else{
            $("#childtype").hide();
        }
    }
    function file_input(){
        var s=$('input[name="ImportData[upload]"]').val();
        if(s==""){
            alert("请选择文件");
            return false;
        }

        var ldot = s.lastIndexOf(".");
        var type = s.substring(ldot + 1);
        if(type != "xls") {
            alert("请选择.xls的文件格式或者直接下载模板文件使用！");
            //清除当前所选文件
            window.location.reload();
        }

        //提交前拼接考试名称
        var xuenian = $("#xueyear").val();

        if($("#childtype").is(":hidden")){
            $("#examname").val(xuenian+$("#examtype").val());
        }else{
            $("#examname").val(xuenian+$("#childtype").val());
        }

        //提交
        $("#formImg").submit();
    }
    function uploadCJDCallbak(retcode,retmsg)
    {
        if(retcode==0)
        {
            alert("上传成功！");
            window.clear;
            window.location ="/teacher/manage";
            $("#loading-main").hide();
        }else{
            alert(retmsg);
        }
        $("#upload-ok").attr('disabled',false);
        return 0;
    }

    function deletecj()
    {


    }

    function querycj(){
        var formdata={};
        var url="/teacher/querychengji";
        formdata.cjdid = $("#cjdxx").val();
        if(formdata.cjdid==null || formdata.cjdid==""){
            $('#docjModal').attr('data-target','');
            alert("请选择成绩种类");
            return false;
        }else {
            $('#docjModal').attr('data-target','#cjModal');
        }
        formdata.cid = $('input[name="cid"]').val();
        $.post(url,formdata).done(function(data){
            data = $.parseJSON(data);
            var myobj=eval(data);
//            alert(myobj[0]);
            var htmltr ="";
            var htmltrs="";
            htmltr += "<tr>";
            for (var j = 0; j < myobj[0].length; j++){
                htmltr +="<td>" + myobj[0][j] + "</td>"
             }
            htmltr +="</tr>";
            var myobjt = myobj[1];
            for(i=0;i<myobjt.length;i++){
                htmltrs +="<tr>";
                for ( a= 0; a < myobj[0].length; a++){
                        htmltrs +="<td>" + myobjt[i][a] + "</td>";
                    }
                htmltrs +="</tr>";
            }
            $("#cjcx").html(htmltr+htmltrs);
        })
    }
</script>