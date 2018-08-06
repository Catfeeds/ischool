<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;box-shadow: 0 0 2px #ccc;padding: 10px 20px 20px 20px;">
        <h4>检索</h4>
        <form id="formnm" name="form" method="post">
            <div class="form-group dropdown-toggle">
                <div class="input-group col-xs-4">
                    <input name="stuname" style="height: 40px;"  class="form-control" type="text" placeholder="输入学生名字" />
                                    <span style="background-color: #36ADFF;" class="input-group-addon" onclick="formnm()">
                                        <img src="/img/sou.png" />
                                    </span>
                </div>
            </div>
        </form>
    </div>
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">学生信息</h4>
        </div>
        <table class="table table-bordered text-center" style="margin-top: 20px;">
            <tr style="background-color: #eee;">
                <td>序号</td>
                <td>姓名</td>
                <td>学号</td>
                <td>班级</td>
                <td>是否绑定</td>
                <td>操作</td>
            </tr>
            <?php foreach($info['info'] as $key =>$value){?>
            <tr>
                <td><?php echo $key+1;?></td>
                <td><?=$value['name']?></td>
                <td><?=$value['stuno2']?></td>
                <td><?=$value['class']?></td>
                <td><?=($value['isqqtel']=='0')?"已绑定":"未绑定" ?></td>
                <td>
                    <input type="hidden" value="<?=$value['id']?>" id="stuid">
                    <a href="###" data-toggle="modal" data-target="#tjlxModal<?=$key?>">添加联系人</a>
                    <!--添加联系人-->
                    <div class="modal fade" id="tjlxModal<?=$key?>">
                        <div class="modal-dialog">
                            <form class="modal-content form-horizontal" id="form2" action="/teacher/addqqh" method="post">
                                <div class="modal-header">
                                    添加联系人
                                    <button class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                        <button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group text-center">
                                        <label for="userID" class="col-sm-4 control-label">身份：</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" placeholder="请输入身份..."  name="userID" id="userName" >
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <label for="firstname" class="col-sm-4 control-label">姓名：</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" placeholder="请输入姓名..."  name="userName" id="userName" >
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <label for="lastname" class="col-sm-4 control-label">手机号：</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="userPhon" id="userName" class="form-control" placeholder="请输入正确的手机号...">
                                            <input class="form-control" type="hidden" name="stuid" id="stuid" value="<?=$value['id']?>" />
                                            <input class="form-control" type="hidden" name="sid" id="sid" value="<?=$info['sid'];?>"/>
                                            <input class="form-control" type="hidden" name="cid" id="cid" value="<?=$info['cid'];?>"/>
                                            <input class="form-control" type="hidden" name="class" id="class" value="<?=$value['class']?>"/>
                                            <input class="form-control" type="hidden" name="school" id="school" value="<?=$value['school'];?>"/>
                                            <input class="form-control" type="hidden" name="stuname" id="stuname" value="<?=$value['name'];?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" >
                                        确定</button>
                                        <button><button class="btn btn-danger" data-dismiss="modal">
                                                取消
                                                <button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <a href="###" data-toggle="modal" data-target="#lxModal" id="mybtn" class="<?=$value['id']?>" onclick="lxrcx(this)">联系人</a>
                    <a style="color: #e75d50;" href="###" data-toggle="modal" data-target="#qjModal" onclick="doqingjia(this)">请假</a>
                </td>
            </tr>
            <?php }?>
        </table>
    </div>
</div>
</div>
</div>

<!--联系人-->
<div class="modal fade" id="lxModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                联系人
                <button class="close" data-dismiss="modal" >
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered text-center" style="margin-top: 20px;" id="lxrxx">
                    <tr style="background-color: #eee;">
                        <td>身份</td>
                        <td>姓名</td>
                        <td>电话</td>
<!--                        <td>邮箱</td>-->
<!--                        <td>绑定方式</td>-->
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal" >
                    确定
                </button>
            </div>
        </div>
    </div>
</div>
<!--请假-->
<div class="modal fade" id="qjModal">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="/teacher/doleave" id="formqj">
            <div id="formqjhd"></div>
            <div class="modal-header">
                请假
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <for class="modal-body">
                <table class="table">
                    <tr>
                        <td>开始时间：</td>
                        <td>
                            <input type="hidden" value="<?=$info['openid'];?>" name="openid">
                            <input class="form-control"  id="kq_date_from" type="text" placeholder="请选择" name="statime" value="" readonly />
                        </td>
                        <td>结束时间：</td>
                        <td>
                            <input class="form-control"  id="testy2" type="text" placeholder="请选择" name="endtime" value="" readonly />
<!--                            <input type="datetime-local" />-->
                        </td>
                    </tr>
                    <tr>
                        <td>请假原因：</td>
                        <td colspan="4">
                            <textarea cols="60" rows="6" name="reason"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input class="btn btn-success" type="submit" onclick="return qingjia(this)"/>
                        </td>
                    </tr>
                </table>
        </form>
    </div>
</div>
</div>
<script type="text/javascript">
    nuber=10;
    function  lxrcx(t){
        var formdata={};
        formdata .id =$(t).attr("class");
        var url = "/teacher/lxr";
        $.post(url,formdata).done(function(data){
                var t2 = data;
                var myobj=eval(t2);
                var html = "<tr style='background-color: #eee;'><td>身份</td><td>姓名</td><td>电话</td></tr>";
                for(var i=0;i<myobj.length;i++){
                    html +="<tr id='lxrid'><td>"+myobj[i]['Relation']+"</td><td>"+myobj[i]['name']+"</td><td>"+myobj[i]['tel']+"</td></tr>"
                }
            $("#lxrxx").html(html);
        });
    }
    function doqingjia(t){
        var stuid = $(t).siblings("#stuid").val();
        $("#formqjhd").html("<input type='hidden' name='stuidid' value='"+stuid+"'>");
    }
    //移除隐藏的学生ID的信息
    function remove(){
        $('input[name="stuidid"]').remove();
    }
    //移除当前的学生的联系人家长的信息
    function removejz(){
        $("*").remove("#lxrid");
    }
    function qingjia(t){
        $(t).parents("table").find('input[name="stuidid"]').remove();
        var formdata={};
        formdata.statime = $(t).parents("tbody").find('input[name="statime"]').val();
        formdata.endtime = $(t).parents("tbody").find('input[name="endtime"]').val();
        formdata.reason = $(t).parents("tbody").find('textarea[name="reason"]').val();
        statime= Date.parse(new Date(formdata.statime));
        endtime = Date.parse(new Date(formdata.endtime));
        if(statime>=endtime){
            alert("开始时间不能大于或等于结束时间");
            return false;
        }
        var reason = $.trim(formdata.reason);
        if(reason ==""){
            alert("请假原因不能为空");
            return false;
        }
    }
    function formnm(){
        $("#formnm").attr("action","/teacher/child");
        $('#formnm').submit();
    }
</script>

<script type="text/javascript">
    jeDate({
        dateCell:"#kq_date_from",
        format:"YYYY-MM-DD hh:mm:ss",
        isinitVal:true,
        isTime:true, //isClear:false,
        minDate:"2011-09-19 00:00:00",
        okfun:function(val){}
    })
    jeDate({
        dateCell:"#testy2",
        format:"YYYY-MM-DD hh:mm:ss",
        isinitVal:true,
        isTime:true, //isClear:false,
        minDate:"2011-09-19 00:00:00",
        okfun:function(val){}
    })
</script>