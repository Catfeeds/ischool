<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-01-02
 * Time: 11:43
 */
use yii\widgets\LinkPager;
use yii\grid\GridView;

?>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">

<div style="background-color: white;padding: 10px 20px;margin-top: 5px;height: auto;box-shadow: 0 0 2px #ccc;">
    <div class="clearfix">
        <ul class="nav nav-pills clearfix">
            <li>
                <h4 class="pull-left" style="margin-right: 40px;">计划审批</h4>
            </li>
            <li class="active" id="xiexinh" onclick="xiexinpa()">
                <a href="#xiexin" data-toggle="tab">我发起的</a>
            </li>
            <li id="shouxinh" onclick="shouxinpa()">
                <a href="#shouxin" data-toggle="tab">我收到的</a>
            </li>
        </ul>
<div class="tab-content">
    <div id="xiexin" class="tab-pane active">
        <div class="btn-group" data-toggle="buttons-radio" style="margin-top: 10px">
            <form style="display: inline-block" action = "/teacher/shenpi" method="post">
                <input type="hidden" value="0" name="flag">
            <button class="btn btn-primary spll" onclick="this.form.submit()">全部</button>
            </form>
            <form style="display: inline-block" action = "/teacher/shenpi" method="post">
                <input type="hidden" value="1" name="flag">
            <button class="btn btn-primary spll" onclick="this.form.submit()">审批完成</button>
            </form>
            <form style="display: inline-block" action = "/teacher/shenpi" method="post">
                <input type="hidden" value="2" name="flag">
                <button class="btn btn-primary spll" onclick="this.form.submit()">审批中</button>
            </form>
        </div>
        <input type="hidden" value="<?php echo $info['user'][0]['openid'];?>" id="openid">
        <button class="btn btn-info pull-right" data-toggle="modal" data-target="#gzModal" id="gzbtn">提交申请</button>
    <table class="table table-bordered text-center" style="margin-top: 20px;">
        <tr style="background-color: #eee;">
            <td>序号</td>
            <td>标题</td>
            <td>简述</td>
            <td>提交时间</td>
            <td>附件</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        <?php
        if (Yii::$app->session->hasFlash('info')) {
            $infos = Yii::$app->session->getFlash('info');
            ?>
            <script type="text/javascript">
                var now = '<?php echo $infos; ?>';
                alert(now);
            </script>
        <?php } ?>

        <?php $index = 1; if(!empty($models['dataprovider'])) { foreach ($models['dataprovider'] as $key=>$value){?>
            <tr>
                <td><?=!empty($_GET['page'])?(15*($_GET['page'] - 1)+$key):$key  ?></td>
                <td><?=$value['title']?></td>
                <td><?=mb_substr($value['content'],0,20)?>......</td>
                <td><?=date("Y-m-d H:i:s",$value['ctime'])?></td>
                <td>
                    <?php if(!empty($value['fjurl'])){ ?>
                    <a style="color: #36ADFF;"  href="/teacher/uploadsp?id=<?=$value['id']?>&tid=<?=$value['tid']?>&sid=<?=$value['sid']?>">下载</a><?php }else{ echo "空";}?></td>
                <td><?PHP if($value['flag']==0){echo "待审核";}elseif($value['flag']==1){echo "已通过";}elseif($value['flag']==2){echo "已拒绝";} ?></td>
                <td>
                    <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle btn-xs" type="button"> 查看 <span class="caret"></span> </button>
                        <ul style="min-width: 25px;" class="dropdown-menu">
                            <li>
                                <a href="#" data-toggle="modal" data-target="<?='.xq'.$value['id']?>" id="<?=$value['id']?>" onclick="xiangqing(this.id)">详情</a>
                            </li>
                            <?PHP if($value['flag']==0){?>
                                <li>
                                    <a href="#" onclick="delshenpi(<?=$value['id']?>)">删除</a>
                                </li>
                            <?php }?>
                        </ul>
                    </div>

                    <div class="modal fade xq<?=$value['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">信息详情</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="" style="padding: 5px 20px;">
                                        <div class="row">
                                            <div class="col-lg-2 text-right">提交人：</div>
                                            <div class="col-lg-9 text-left"><?=$value['name']?></div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;" class="row">
                                            <div class="col-lg-2 text-right">标&nbsp;&nbsp;&nbsp;题：</div>
                                            <div class="col-lg-9 text-left"><?=$value['title']?></div>
                                        </div>
                                        <div style="margin-top: 10px;" class="row">
                                            <div class="col-lg-2 text-right">事&nbsp;&nbsp;&nbsp;由：</div>
                                            <div class="col-lg-9 text-left"><?=$value['content']?></div>
                                        </div>
                                        <div style="margin-top: 10px;" class="row">
                                            <div class="col-lg-2 text-right">状&nbsp;&nbsp;&nbsp;态：</div>
                                            <div class="col-lg-9 text-left zhuangtais" id="zhuangtais<?=$value['id']?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php
        }?>
    </table>

    <?php
                        echo LinkPager::widget([
                            'pagination' => $models['pages'],
                        ]);
                    }else{?> <tr><td colspan="7">您还没有发送过信息！</td></tr></table> <?php }?>
    </div>
    <div id="shouxin" class="tab-pane">
        <div class="btn-group" data-toggle="buttons-radio" style="margin-top: 10px">
            <form style="display: inline-block" action = "/teacher/shenpi" method="post">
                <input type="hidden" value="0" name="flag">
                <input type="hidden" value="shouxin" name="type">
                <button class="btn btn-primary splc" onclick="this.form.submit()">全部</button>
            </form>
            <form style="display: inline-block" action = "/teacher/shenpi" method="post">
                <input type="hidden" value="1" name="flag">
                <input type="hidden" value="shouxin" name="type">
                <button class="btn btn-primary splc" onclick="this.form.submit()">审批完成</button>
            </form>
            <form style="display: inline-block" action = "/teacher/shenpi" method="post">
                <input type="hidden" value="2" name="flag">
                <input type="hidden" value="shouxin" name="type">
                <button class="btn btn-primary splc" onclick="this.form.submit()">审批中</button>
            </form>
        </div>

        <input type="hidden" value="<?php echo $info['user'][0]['openid'];?>" id="openid">
        <table class="table table-bordered text-center" style="margin-top: 20px;">
            <tr style="background-color: #eee;">
                <td>序号</td>
                <td>提交人</td>
                <td>标题</td>
                <td>简述</td>
                <td>提交时间</td>
                <td>附件</td>
                <td>状态</td>
                <td>操作</td>
            </tr>
            <?php
            if (Yii::$app->session->hasFlash('info')) {
                $infos = Yii::$app->session->getFlash('info');
                ?>
                <script type="text/javascript">
                    var now = '<?php echo $infos; ?>';
                    alert(now);
                </script>
            <?php } ?>

            <?php $index = 1; if(!empty($sjxmodels['dataprovider'])) { foreach ($sjxmodels['dataprovider'] as $key=>$value){?>
                <tr>
                    <td><?=!empty($_GET['page'])?(15*($_GET['page'] - 1)+$key):$key  ?></td>
                    <td><?=$value['work']['name']?></td>
                    <td><?=$value['work']['title']?></td>
                    <td><?=mb_substr($value['work']['content'],0,20)?>......</td>
                    <td><?=date("Y-m-d H:i:s",$value['work']['ctime'])?></td>
                    <td><?php if(!empty($value['work']['fjurl'])){ ?><a style="color: #36ADFF;"  href="/teacher/uploadsp?id=<?=$value['work_id']?>&tid=<?=$value['work']['tid']?>&sid=<?=$value['work']['sid']?>">下载</a><?php }else{ echo "空";}?></td>
                    <td><?PHP if($value['status']==0){echo "待审核";}elseif($value['status']==1){echo "已通过";}elseif($value['status']==2){echo "已拒绝";} ?></td>
                    <td>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle btn-xs" type="button"> 查看 <span class="caret"></span> </button>
                            <ul style="min-width: 25px;" class="dropdown-menu">
                                <li>
                                    <a href="#" data-toggle="modal" data-target="<?='.'.$value['work_id']?>" onclick="xiangqing(this.id)" id="<?=$value['work_id']?>" class="xqbtn">详情</a>
                                </li>
                                <?PHP if($value['status'] == 0){?>
                                    <li>
                                        <a href="#" data-toggle="modal" data-target=".ty<?=$value['id']?>">同意</a>
                                    </li>
                                    <li>
                                        <a href="#" data-toggle="modal" data-target=".jj<?=$value['id']?>">拒绝</a>
                                    </li>
                                <?php }?>
                            </ul>
                        </div>
                        <!--同意审批弹窗-->
                        <div class="modal fade ty<?=$value['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel">审批同意意见</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div style="margin-top: 10px;" class="row">
                                            <textarea class="form-control"  id="tyly<?=$value['id']?>" name="descr" cols="20" rows="5" placeholder="请输入同意理由"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary"  onclick="agree(<?=$value['id']?>)">确定</button>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--拒绝请假弹窗-->
                        <div class="modal fade jj<?=$value['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel">审批拒绝意见</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div style="margin-top: 10px;" class="row">
                                            <textarea class="form-control"  id="jjly<?=$value['id']?>" name="descr" cols="20" rows="5" placeholder="请输入拒绝理由"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary"  onclick="refuse(<?=$value['id']?>)">确定</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--请假详情弹窗-->
                        <div class="modal fade <?=$value['work_id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel">请假信息详情</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="" style="padding: 5px 20px;">
                                            <div class="row">
                                                <div class="col-lg-2 text-right">提交人：</div>
                                                <div class="col-lg-9 text-left"><?=$value['work']['name']?></div>
                                            </div>
                                            <div class="row" style="margin-top: 10px;" class="row">
                                                <div class="col-lg-2 text-right">标&nbsp;&nbsp;&nbsp;题：</div>
                                                <div class="col-lg-9 text-left"><?=$value['work']['title']?></div>
                                            </div>
                                            <div style="margin-top: 10px;" class="row">
                                                <div class="col-lg-2 text-right">事&nbsp;&nbsp;&nbsp;由：</div>
                                                <div class="col-lg-9 text-left"><?=$value['work']['content']?></div>
                                            </div>
                                            <div style="margin-top: 10px;" class="row">
                                                <div class="col-lg-2 text-right">状&nbsp;&nbsp;&nbsp;态：</div>
                                                <div class="col-lg-9 text-left zhuangtai" id="zhuangtai<?=$value['work_id']?>" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php
            }?>
        </table>

        <?php
        echo LinkPager::widget([
            'pagination' => $sjxmodels['pages'],
        ]);
        }else{?> <tr><td colspan="8">您还没有收到过信息！</td></tr></table> <?php }?>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>

<!--提交申请-->
<div class="modal fade" id="gzModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <h4 class="modal-header">
                提交申请
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </h4>
            <div class="modal-body">
        <form  id="antoform" class="form-horizontal calender" role="form" action="/teacher/shenpi" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="province">标&nbsp;&nbsp;&nbsp;&nbsp;题:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="zhuti" id="zhuti">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="city">简&nbsp;&nbsp;&nbsp;&nbsp;述:</label>
                        <div class="col-sm-6">
                            <textarea class="form-control"  id="descr" name="descr" cols="20" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="County">文档上传:</label>
                        <div class="col-sm-6">
                            <input type="file" class="form-control" name="UploadForm[file]" id="file1">
                        </div>
                    </div>
                    <div class="form-group">
                         <label class="col-sm-3 control-label">添加审批人:</label>
                         <div class="col-sm-3">
                             <select class="form-control" onchange="changeFenzu(this.value)">
                                 <option value="">请选择分组</option>
                                 <?php foreach ($info['fenzu'] as $v){?>
                                    <option value="<?=$v?>"><?=$v;?></option>
                                 <?php } ?>
                             </select>
                          </div>
                        <div class="col-sm-3" >
                            <select class="form-control" id="renyuan" onchange="changeShenpiren(this.value,this.options[this.selectedIndex].text)">
                                <option value="">请选择审批人</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">审批人:</label>
                        <div class="col-sm-9" id="shenpiren">

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="tianjia()">
                    提交
                </button>
                <button class="btn btn-danger" data-dismiss="modal">
                    取消
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    (function($){
        $.getUrlParam = function(name)
        {
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r!=null) return unescape(r[2]); return null;
        }
    })(jQuery);



    //获取url中的参数
    var ss = $.getUrlParam('type');
    var hh = "<?php echo $type;?>";
    if (ss == null){
        var xx = hh;
    }else {
        var xx = ss;
    }
    var flags = "<?php echo $flags;?>";
    if(flags == 'all'){
        $(".splc").eq(0).removeClass('btn-primary');
        $(".spll").eq(0).removeClass('btn-primary');
    }else if(flags == 'spwc'){
        $(".splc").eq(1).removeClass('btn-primary');
        $(".spll").eq(1).removeClass('btn-primary');
    }else if(flags == 'spz'){
        $(".splc").eq(2).removeClass('btn-primary');
        $(".spll").eq(2).removeClass('btn-primary');
    }
// alert(xx);
    if (xx =="shouxin"){
        $("#shouxinh").attr("class","active");
        $("#xiexinh").attr("class","");
        $("#shouxin").attr("class","tab-pane active");
        $("#xiexin").attr("class","tab-pane");
    }else {
        $("#shouxinh").attr("class","");
        $("#xiexinh").attr("class","active");
        $("#shouxin").attr("class","tab-pane");
        $("#xiexin").attr("class","tab-pane active");
    }
    function xiexinpa(){
        var url = window.location.href;
        window.location.href = "/teacher/shenpi?type=xiexin";
    }
    function shouxinpa(){
        var url = window.location.href;
        window.location.href = "/teacher/shenpi?type=shouxin";
    }
    //上传报表信息
    function tianjia(){
        var title = $("#zhuti").val();
        var con = $("#descr").val();
        var content = con.replace(/<[^>]+>/g,"");
        var spr = $("#shenpiren").find(".spdy").length;
        if(title ==""){
            alert("标题不能为空");
            return false;
        }
        if(content == ""){
            alert("发送内容不能为空");
            return false;
        }
        if(spr == 0){
            alert("审批人不能为空");
            return false;
        }
        var s=$('input[name="UploadForm[file]"]').val();
        if(s !=""){
            var size = $("#file1")[0].files[0].size;
            if(size >5242880){
                alert("上传文件大小不能大于5M!");
                return false;
            }
        }
/*        var s=$('input[name="ImportData[upload]"]').val();
        if(s==""){
            alert("请选择文件");
            return false;
        }*/
        //提交
        $("#antoform").submit();
    }

    //删除计划
    function delshenpi(pro){
        if(window.confirm('是否删除？')){
            $.ajax({
                url: '/teacher/delshenpi',
                data:'id='+pro,
                dataType:'json',
                type:'post',
                success:function(data){
                    if(data==0){
                        alert('删除成功');
                        window.location.reload();
                    }
                }
            })
        }
    }


    //根据分组切换分组联系人
    function changeFenzu(pro){
        $("#renyuan").html("<option value=''>数据加载中...</option>");
        var url= "/teacher/getrenyuan";
        var formData = {};
        formData.fenzu = pro;
        $.post(url,formData).done(function(data){
            data = eval(data);
            console.log(data);
            var htmls="<option value=''>请选择审批人</option>"
            for (var i = 0; i < data.length; i++) {
                htmls = htmls + "<option value="+data[i].id+">"+data[i].name+"</option>";
            }
            $("#renyuan").html(htmls);
        });
    }

    function changeShenpiren(sprvalue,spr){
        var openid = sprvalue;
        var i=$("#shenpiren").children().length;
        if(i !=0 ) {
            var preOpenid = $("input[name='spdy[]']").eq(i-1).val();
        }
        var shenpiren = "<span class='spdy'><input type='hidden' name='spdy[]' value='"+sprvalue+"'><span class='shenpiren'><span name='spdy'>"+spr+"</span><span class='scr'>×</span>"+"</span>"+"</span>";
        var shenpiren1 = "<span class='spdy'><input type='hidden' name='spdy[]' value='"+sprvalue+"'><span class='gd'>→</span>"+"<span class='shenpiren'>"+spr+"<span class='scr'>×</span>"+"</span>"+"</span>";
        if (openid !=""){
            var m=$("#shenpiren").children().length;
            if(i==0){
                if(m>=1){
                    $("#shenpiren").append(shenpiren1);
                }else{
                    $("#shenpiren").append(shenpiren);
                }
            }else{
                if(openid != preOpenid) {
                    if(m>=1){
                        $("#shenpiren").append(shenpiren1);
                    }else{
                        $("#shenpiren").append(shenpiren);
                    }
                }else {
                    alert("不能连续选择同一个审批人！");
                    return false;
                }
            }
        }
    }
    $(document).on('click','.scr',function () {
        var y=$('.scr').index(this);
        $(".spdy").eq(y).remove();
        var z=$(".spdy").length;
        var j=$(".gd").length;
        if(z==j){
            $(".gd").eq(0).remove();
        }else {
            return false;
        }
    })

    function xiangqing(id) {
        // $('.xqbtn').attr('data-target','');
        var url= "/teacher/getstatus";
        var formData = {};
        formData.id = id;
        $.post(url,formData).done(function(data){
            data = eval(data);
            // alert(data[1].okt)
            var htmls = "";
            for (var i = 0; i < data.length; i++) {
                var time= timestampToTime(data[i].oktime);
                console.log(time);
                if (data[i].status ==0){
                    htmls+= "<p><span class='jg'>"+data[i].name+"</span>待审核</p>";
                }else if(data[i].status ==1){
                        htmls+="<p><span class='jg'>"+data[i].name+"</span><span class='jg'>已通过</span><span>"+time+"</span></p><p style='color: #000;'>"+data[i].reason+"</p>";
                }else if(data[i].status ==2){
                        htmls+="<p><span class='jg'>"+data[i].name+"</span><span class='jg'>已拒绝</span><span>"+time+"</span></p><p style='color: #000;'>"+data[i].reason+"</p>";
                }else if(data[i].status ==3){
                        htmls+="<p>"+data[i].name+"</p>";
                }
            }
            $("#zhuangtai"+id).html(htmls);
            $("#zhuangtais"+id).html(htmls);
        });
    }


    function timestampToTime(timestamp) {
        var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
        Y = date.getFullYear() + '-';
        M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
        D = date.getDate() + ' ';
        h = date.getHours() + ':';
        m = date.getMinutes() + ':';
        s = date.getSeconds();
        return Y+M+D+h+m+s;
    }

    //同意计划
    function agree(pro){
        // var tyly = "#tyly"+pro;
        var tyly = $("#tyly"+pro).val();
        var reason = tyly.replace(/<[^>]+>/g,"");   //理由
        if (reason.length >200){
            alert("输入内容不能超过200个字！");
            return false;
        }
        // if(window.confirm('确定通过？')){
            $.ajax({
                url: '/teacher/agree',
                data:'id='+pro+'&reason='+reason,
                dataType:'json',
                type:'post',
                success:function(data){
                    if(data==0){
                        alert('已经通过！');
                        window.location.reload();
                    }
                }
            })
        // }
    }

    //拒绝计划
    function refuse(pro){
        var tyly = $("#jjly"+pro).val();
        var reason = tyly.replace(/<[^>]+>/g,"");   //理由
        if (reason.length >200){
            alert("输入内容不能超过200个字！");
            return false;
        }
        // if(window.confirm('是否拒绝？')){
            $.ajax({
                url: '/teacher/refuse',
                data:'id='+pro+'&reason='+reason,
                dataType:'json',
                type:'post',
                success:function(data){
                    if(data==0){
                        alert('已经拒绝！');
                        window.location.reload();
                    }
                }
            })
        // }
    }

</script>
<style>
    .gd{
        vertical-align: middle;
    }
    .shenpiren{
        border: 1px solid #ccc;
        padding: 5px;
        display: inline-block;
        width: 60px;
        vertical-align: middle;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .scr{
        position: absolute;
        top: -4px;
        right: 0;
        cursor: pointer;
    }
    .zhuangtais>p{
        line-height: 20px;
        margin: 0;
        padding: 0;
    }
    .zhuangtais .jg{
        display: inline-block;
        width: 80px;
    }

    .zhuangtai>p{
        line-height: 20px;
        margin: 0;
        padding: 0;
    }
    .zhuangtai .jg{
        display: inline-block;
        width: 80px;
    }

</style>