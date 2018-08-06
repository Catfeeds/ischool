<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\controllers\PastudentController;
?>
<style type="text/css">
.modal-content{width: 1200px;margin-left: -300px;}
</style>
    <div style="background-color: white;box-shadow: 0 0 2px #ccc;padding: 10px 20px 20px 20px;">
        <h4>班级信息</h4>
        <div class="media" style="border: 1px solid #ccc;padding: 20px;">
            <a href="###" class="pull-left">
                <img src="../img/pc_ren.png" />
            </a>
            <table class="media-body" style="line-height: 30px;padding-left: 30px;">
                <tr>
                    <td>学生：</td>
                    <td><?=$info['name'];?></td>
                </tr>
                <tr>
                    <td>学校：</td>
                    <td><?=$info['school'];?></td>
                </tr>
                <tr>
                    <td>班级：</td>
                    <td><?=$info['class'];?></td>
                </tr>
                <tr>
                    <td>班主任：</td>
                    <td><?=$info['teaname'];?></td>
                </tr>
                <tr>
                    <td>手机号：</td>
                    <td><?=$info['tel'];?></td>
                </tr>
            </table>
        </div>
    </div>
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">添加亲情号码</h4>
            <button class="btn btn-info pull-right" data-toggle="modal" data-target="#maModal" id="mybtn">新增</button>
        </div>
        <table class="table table-bordered text-center" style="margin-top: 20px;">
            <tr style="background-color: #eee;">
                <td>序号</td>
                <td>姓名</td>
                <td>身份</td>
                <td>电话</td>
                <td>操作</td>
            </tr>
            <?php foreach($info['notel'] as $key => $value){?>
                <tr>
                    <td><?=$key?></td>
                    <td class="stuname"><?=$value['name']?></td>
                    <td><?=$value['Relation']?></td>
                    <td><?=$value['tel']?></td>
                    <td id="<?=$value['id']?>">不可删除
                    </td>
                </tr>
            <?php     }?>
            <?php foreach($info['paqq'] as $key => $value){?>
            <tr>
                <td><?=$key+count($info['notel'])?></td>
                <td class="stuname"><?=$value['name']?></td>
                <td><?=$value['Relation']?></td>
                <td><?=$value['tel']?></td>
                <td id="<?=$value['id']?>">
                    <a href="###" data-toggle="modal" data-target="#chModal" onclick="upqqh(this)">修改</a>
                    <a style="color: #e75d50;" onclick="delqqh(this)" href="###" id="delqqh">删除</a>
                </td>
            </tr>
            <?php     }?>
        </table>
    </div>
    <form style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;" method="post" action="/pastudent/qingjia">
        <h4>申请请假</h4>
        <table class="table" border="0">
            <tr>
                <td>开始时间：</td>
                <td>
                    <input type="hidden" value="<?=$painfo['user'][0]['openid'];?>" name="openid">
                    <input type="hidden" value="<?=$info['stuid'];?>" name="stuid">
                    <input class="form-control"  id="kq_date_from" type="text" placeholder="请选择" name="statime" value="" readonly />
                </td>
                <td>结束时间：</td>
                <td>
                    <input class="form-control"  id="testy2" type="text" placeholder="请选择" name="endtime" value="<?php echo date('Y-m-d 23:59:59', time());?>" readonly />
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

    <?php if(isset($info['stuleave']) && ($info['stuleave']!="")){ foreach($info['stuleave'] as $list){  ?>
    <div style="background-color: #ffffed;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <img src="../img/pc_dng.png" />
        <span style="vertical-align: middle;padding-left: 5px;">
            <?php if($list['flag'] ==2){echo $info['name']."申请".date("Y-m-d H:i:s",$list['begin_time'])."至".date("Y-m-d H:i:s",$list['stop_time'])."请假信息待审核！";}if($list['flag'] ==1){echo $info['name']."申请的".date("Y-m-d H:i:s",$list['begin_time'])."至".date("Y-m-d H:i:s",$list['stop_time'])."请假信息已通过！";} ?>
        <?php if($list['flag'] ==3){echo $info['name']."申请的".date("Y-m-d H:i:s",$list['begin_time'])."至".date("Y-m-d H:i:s",$list['stop_time'])."请假信息已被拒绝！";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/pastudent/qqbxs?id=<?=$list['id']?>" >不再显示</a><?php } ?>
        </span>
</div>
    <?php  }}    ?>

    <form style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <h4>成绩查询</h4>
        <table class="table">
            <tr>
                <td style="vertical-align: middle;">考&nbsp;&nbsp;&nbsp;&nbsp;试：</td>
                <td>
                    <select class="form-control" name="cjd">
                        <?php foreach($info['chengji'] as $chegnji){?>
                        <option value="<?=$chegnji['cjdid']?>" id="<?=$chegnji['isopen']?>"><?=$chegnji['cjdname']?></option>
                        <?php } ?>
                    </select>
                </td>
                <td style="vertical-align: middle; width: 60px;text-align: right;">学&nbsp;&nbsp;&nbsp;&nbsp;生：</td>
                <td>
                    <select class="form-control" name="stu_name">
                        <option value="all">全部</option>
                        <option value="<?=$info['stuid']?>"><?=$info['name'];?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input class="btn btn-success" type="button" data-toggle="modal" data-target="#cjModal" id="" value="提交" onclick="return chengji(this)"/>
                </td>
            </tr>
        </table>
    </form>
</div>
</div>
</div>
<?php
require(__DIR__ . '/../layouts/pastudent_foot.php');
?>
<!--新增亲情号-->
<div class="modal fade" id="maModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                添加亲情号
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="form2" action="/pastudent/addqqh" method="post">
            <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="userID">身份:</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="userID" id="userName" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="userName">姓名:</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="userName" id="userName" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="userPhon">电话:</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="userPhon" id="userName" />
                            <input class="form-control" type="hidden" name="stuid" id="stuid" value="<?=$info['stuid'];?>" />
                            <input class="form-control" type="hidden" name="sid" id="sid" value="<?=$info['sid'];?>"/>
                            <input class="form-control" type="hidden" name="cid" id="cid" value="<?=$info['cid'];?>"/>
                            <input class="form-control" type="hidden" name="class" id="class" value="<?=$info['class'];?>"/>
                            <input class="form-control" type="hidden" name="school" id="school" value="<?=$info['school'];?>"/>
                            <input class="form-control" type="hidden" name="stuname" id="stuname" value="<?=$info['name'];?>"/>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">
                    提交
                </button>
                <button class="btn btn-danger" data-dismiss="modal">
                    关闭
                </button>
            </div>
        </div>
        </form>
    </div>
</div>
<!--修改亲情号-->
<div class="modal fade" id="chModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                修改亲情号
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form class="form-horizontal" action="/pastudent/upqqh" method="post" id="form1">
            <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="userName">身份:</label>
                        <div class="col-sm-6">
                            <input type="hidden" name="id">
                            <input class="form-control" type="text" name="userID" id="userName" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="userPwd">姓名:</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="userName" id="userName" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="userPwd">电话:</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="userPhon" id="userName" />
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">
                    提交
                </button>
<!--                <input type="submit" value="确定">-->
                <button class="btn btn-danger" data-dismiss="modal">
                    关闭
                </button>
            </div>
            </form>
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
                <table class="table table-striped">
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
<input type="hidden" value="<?php echo $painfo['path']?>" id="path">
</body>
</html>
<script type="text/javascript">
    var path = $("#path").val();
    function upqqh(t){
        var id = $(t).parents("td").attr("id");
        var name = $(t).parents("tr").find("td").eq(1).text();
        var shenfen = $(t).parents("tr").find("td").eq(2).text();
        var tel = $(t).parents("tr").find("td").eq(3).text();
        $("#chModal").find('input[name="userID"]').val(name);
        $("#chModal").find('input[name="userName"]').val(shenfen);
        $("#chModal").find('input[name="userPhon"]').val(tel);
        $("#chModal").find('input[name="id"]').val(id);
       /* alert(id)*/
    }

    function delqqh(t){
        var url = path+"/pastudent/delqqh";
        var formdata={};
        formdata.id = $(t).parents("td").attr("id");
        if(confirm("确认删除？")) {
            $.post(url, formdata).done(function (data) {
                data = $.parseJSON(data);
                if (data.status == 0) {
                    alert("删除成功");
                    $(t).parents("tr").remove();
                } else {
                    alert("删除失败");
                }
            }, "json");
        }
    }

    function qingjia(t){
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
    function chengji(t){
        var url = path+"/pastudent/doscorequery";
        var formdata={};
        formdata.cjdid = $('select[name="cjd"] option:selected').val();
        formdata.stuid = $('select[name="stu_name"] option:selected').val();
        formdata.isopen = $('select[name="cjd"] option:selected').attr("id");
        formdata.cid = <?php echo $info['cid'];?>;
        $(t).attr("data-toggle","modal");
        if (formdata.isopen == "n" && formdata.stuid =="all"){
            alert("该班级成绩没有公开，不能查询全部成绩");
            $(t).attr("data-toggle","");
            return false;
        }
        if (formdata.cjdid == undefined || formdata.cjdid == ""){
            alert("请选择成绩单");
            $(t).attr("data-toggle","");
            return false;
        }else {
            $.post(url,formdata).done(function(data){
                data = $.parseJSON(data);
                if(data != null){
                    var htmls = "<tr>";
                    var title = data[0];  //第0行标题行
                    var cols = title.length;
                    var t = 0;
                    for(t;t < cols;t++){
                        htmls = htmls + "<td>"+title[t]+"</td>";
                        if(t == cols-1){
                            htmls = htmls + "</tr>";
                        }
                    }

                    var content = data[1]; //内容行
                    var rows = content.length;
                    var i = 0;
                    for(i;i < rows;i++){
                        var j = 0;
                        var row = content[i];

//                        if(i == 0){
//                            htmls = htmls + "<tr>";
//                        }

                        for(j;j < cols;j++)
                        {
                            if(j == 0){
                                htmls = htmls + "<tr>";
                            }
                            htmls = htmls + "<td>"+row[j]+"</td>";
                            if(j == cols-1){
                                htmls = htmls + "</tr>";
                            }
                        }
//                        if(i == rows-1){
//                            htmls = htmls + "</tr>";
//                        }
                    }

                    $(".table-striped").html(htmls);
                }
            });
        }
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