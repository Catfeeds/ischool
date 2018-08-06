<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">班级配置</h4>
        </div>
        <hr/>
        <?php  if(count($schools) !=0) {?>
        <form class="form-horizontal Modify_pwd">
            <div class="form-group">
                <label class="col-sm-2 col-lg-1 control-label" for="clas">班级</label>
                <div class="col-sm-6">
                    <select class="form-control" name="clas" id="clas">
                        <option value="">请选择</option>
                        <?php foreach($listclass as $key=>$value){?>
                            <option value="<?=$value['id']?>"><?=$value['name']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-lg-1 control-label" for="rol">角色</label>
                <div class="col-sm-6">
                    <select class="form-control" name="rol" id="rol">
                        <option class="select-option" value="班主任">班主任</option>
                        <option class="select-option" value="语文老师">语文老师</option>
                        <option class="select-option" value="数学老师">数学老师</option>
                        <option class="select-option" value="英语老师">英语老师</option>
                        <option class="select-option" value="体育老师">体育老师</option>
                        <option class="select-option" value="音乐老师">音乐老师</option>
                        <option class="select-option" value="物理老师">物理老师</option>
                        <option class="select-option" value="化学老师">化学老师</option>
                        <option class="select-option" value="生物老师">生物老师</option>
                        <option class="select-option" value="历史老师">历史老师</option>
                        <option class="select-option" value="地理老师">地理老师</option>
                        <option class="select-option" value="计算机老师">计算机老师</option>
                        <option class="select-option" value="自定义">自定义</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-lg-1 control-label" for="teacher">老师</label>
                <div class="col-sm-6">
                    <select class="form-control" name="teacher" id="teacher">
                        <option value="">请选择</option>
                        <?php foreach($teainfo as $key=>$value){?>
                            <option value="<?=$value['openid']?>" id="<?=$value['tel']?>"><?=$value['tname']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group form-inline">
                <label class="col-sm-2 col-lg-1 control-label"></label>
                <div class="col-sm-10 row" style="padding: 0;">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-block" style="background-color: #36ADFF;color: white" onclick="addOneClass()">确认配置老师</button>
                    </div>
                    <div class="col-md-3">
                        <button type="reset" class="btn btn-block" value="重置" name="reset">重置</button>
                    </div>
                    <span class="col-md-5" style="color: red;line-height: 40px">* 为班级安排一位老师</span>
                </div>
            </div>
        </form>
        <?php } ?>
    </div>
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">班级老师</h4>
        </div>
        <hr/>
        <ul id="teachers">
        </ul>
    </div>
</div>
</div>
</div>

<script>
    $("#clas").on("change",function(){
        var url = "/guanli/getteas";
        var para = {cid:$(this).val()};
        $.getJSON(url,para,function (data){
            if(data.result=='success'){
                var htmls = "";
                if(data.data!=null){
                    var res2 = data.data;
                    for (var i = 0; i < res2.length; i++) {
                        htmls = htmls + getHtml(res2[i]);
                    }
                }else{
                    htmls="<li>暂无信息</li>";
                }
                $("#teachers").html(htmls);
            }else{
                alert('获取当前班级老师失败，请重试');
            }
        });
    })

    function getHtml(obj){
        var str = "<li class='row text-center'style='background-color: #e8e8e8;line-height: 35px;margin-top: 5px;padding: 5px 0;border: 1px solid #ccc' ><div class='col-sm-5'>老师："
            +  obj.tname  +" </div> <div class='col-sm-5'>角色：" + obj.role
            +  "</div>"
            +  "<div class='col-sm-1 btn btn-danger pull-right col-sm-pull-1'  onclick='deleteTeaClass("+obj.id+",this)'>删除</div></li>";
        return str;
    }

    function deleteTeaClass(tcid,ths){
        var url = "/guanli/deletetea";
        var para = {tcid:tcid};
        $.getJSON(url,para,function(data){
            if(data.result=='success'){
                alert("删除成功");
                $(ths).parents(".text-center").hide().remove();
            }else{
                alert('删除失败，请重试');
            }
        });
    }
    //检验学校班级姓名是否为空
    function checkChildInfo(){
        if($.trim($("#clas").val())==""){
            alert("班级不能为空");
            return false;
        }
        if($.trim($("#teacher").val())==""){
            alert("教师不能为空");
            return false;
        }
        if($.trim($("#rol").val())==""){
            alert("角色名不能为空");
            return false;
        }
        return true;
    }
    function addOneClass(){
        if(checkChildInfo()){
            var formdata={};
            var url="/guanli/dopzbj";
            var to_url="/guanli/bjpz";
            formdata.openid = $("#teacher").val();
            formdata.cid = $.trim($("#clas option:selected").val());
            formdata.class = $.trim($("#clas option:selected").text());
            formdata.role = $.trim($("#rol option:selected").text());
            formdata.teaname = $.trim($("#teacher option:selected").text());
            formdata.tel = $("#teacher option:selected").attr("id");

            $.post(url,formdata).done(function(data){
                data =$.parseJSON(data);
                if(data.id == 2){
                    alert("保存失败");return;
                }else if(data.id == 3){
                    alert("角色名重复");
                }else {
                    alert("添加成功");
                    $("#teachers").append(getHtml(data.data));
                }
            });
        }
    }
</script>