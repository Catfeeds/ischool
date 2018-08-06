<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">请假待审核学生</h4>
        </div>
        <table class="table table-bordered text-center" style="margin-top: 20px;">
            <tr style="background-color: #eee;">
                <td>姓名</td>
                <td>学号</td>
                <td>请假时间</td>
                <td>操作</td>
            </tr>
            <?php if($info['rolesf'] == 'n'){echo "<tr><td  colspan='4'>只有班主任才能查看请假信息！</td></tr>";}else{ foreach($info['dsh'] as $key=>$value){ ?>
            <tr>
                <td><?=$value['name']?></td>
                <td><?=$value['stuno2']?></td>
                <td><?=date("m-d H:i",$value['begin_time'])."至".date("m-d H:i",$value['stop_time'])?></td>
                <td>
                    <a style="color: #999;" href="###" data-toggle="modal" data-target="#qjModal" name="<?=$value['name']."家长";?>" id="<?=$value['reason']?>" onclick="reason(this)">请假原因</a>
                    <a style="color: #36ADFF;" href="###" id="<?=$value['id']?>" onclick="pizhun(this)">批准</a>
                    <a style="color: #e75d50;" href="###" id="<?=$value['id']?>" onclick="jujue(this)" >拒绝</a>
                </td>
            </tr>
            <?php  } }?>
        </table>
    </div>
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">已请假学生</h4>
        </div>
        <table class="table table-bordered text-center" style="margin-top: 20px;">
            <tr style="background-color: #eee;">
                <td>姓名</td>
                <td>学号</td>
                <td>请假时间</td>
                <td>操作</td>
            </tr>
            <?php if($info['rolesf'] == 'n'){echo "<tr><td  colspan='4'>只有班主任才能查看请假信息！</td></tr>";}else{ foreach($info['yqj'] as $key=>$value){ ?>
            <tr>
                <td><?=$value['name']?></td>
                <td><?=$value['stuno2']?></td>
                <td><?=date("m-d H:i",$value['begin_time'])."至".date("m-d H:i",$value['stop_time'])?></td>
                <td>
                    <a style="color: #999;" href="###" data-toggle="modal" data-target="#qjModal" name="<?=$value['name']."家长";?>" id="<?=$value['reason']?>" onclick="reason(this)">请假原因</a>
                    <a style="color: #e75d50;" href="###" id="<?=$value['id']?>" onclick="xiaojia(this)">销假</a>
                </td>
            </tr>
            <?php } }?>
        </table>
    </div>
</div>
</div>
</div>
<!--请假原因-->
<div class="modal fade" id="qjModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <h4 class="modal-header">
                请假原因
                <button class="close" data-dismiss="modal" >
                    <span>&times;</span>
                </button>
            </h4>
            <div class="modal-body" id="modalbodyyy">
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal" >
                    确定
                </button>
            </div>
        </div>
    </div>
</div>
<!--销假-->
<div class="modal fade" id="xjModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <h4 class="modal-header">
                已请假学生
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </h4>
            <div class="modal-body">
                <h4 style="line-height: 100px;" class="text-center">您确定要销假吗？</h4>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">
                    确定
                </button>
                <button class="btn btn-danger" data-dismiss="modal">
                    取消
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function reason(t){
        var reason = t.id;
        var reasonpeople = t.name;
        $("#modalbodyyy").html("<h5>"+reason+"</h5>"+"<h4 class='text-right'>"+reasonpeople+"</h4>");
    }
    function remove(){
        $('.modal-body').find("h5").remove();
        $('.modal-body').find("h4").remove();
    }
    function xiaojia(t){
        if(confirm("您确定要销假吗？")){
            var formdata= {};
            formdata.type = 'xiaojia';
            formdata.id = t.id;
            var url = "/teacher/leave";
            $.post(url,formdata).done(function(data){
                data = $.parseJSON(data);
                if (data.status == 0) {
                    alert("销假成功");
                    $(t).parents("tr").remove();
                } else {
                    alert("销假失败");
                }
            })
        }
    }
    function pizhun(t){
            var formdata= {};
            formdata.id = t.id;
            formdata.type = 'pizhun';
            var url = "/teacher/leave";
            $.post(url,formdata).done(function(data){
                data = $.parseJSON(data);
                if (data.status == 0) {
                    alert("批准成功");
                    window.location.reload();
                } else {
                    alert("批准失败");
                }
            })
    }
    function jujue(t){
        if(confirm("您确定要拒绝吗？")){
            var formdata= {};
            formdata.id = t.id;
            formdata.type = 'jujue';
            var url = "/teacher/leave";
            $.post(url,formdata).done(function(data){
                data = $.parseJSON(data);
                if (data.status == 0) {
                    alert("拒绝成功");
                    window.location.reload();
                } else {
                    alert("拒绝失败");
                }
            })
        }
    }
</script>