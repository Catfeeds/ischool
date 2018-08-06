<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;box-shadow: 0 0 2px #ccc;padding: 10px 20px 20px 20px;">
        <h4>检索</h4>
        <div class="form-group dropdown-toggle clearfix">
            <form id="formsh" name="formsh" method="get" action="/guanli/classlist">
            <div class="input-group col-xs-4 pull-left">
                <input style="height: 40px;" class="form-control sousuo" type="text" placeholder="输入班级" name="class" value=""/>
				        		<span style="background-color: #36ADFF;cursor: pointer;" class="input-group-addon" onclick="shtname()">
					        	    <img src="../img/sou.png" />
					        	</span>
            </div></form>
<!--            <button class="btn pull-left" style="background-color: #36ADFF;margin-left: 30px; color: white;line-height: 26px;">班级查询</button>-->
        </div>
    </div>
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">所有班级列表</h4>
<!--            <button class="btn zd_btn3 pull-right" data-toggle="modal" data-target="#bjModal">新建班级/内部交流组</button>-->
        </div>
        <div class="panel-group" id="accordion">
            <?php if(count($info['schools']) !=0){ foreach($info['allclass'] as $key =>$value){ ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$key?>">
                            <div>班级<span class="pull-right"><?=$value['name']?></span></div>
                        </a>
                    </h4>
                </div>
                <div id="collapse<?=$key?>" class="panel-collapse collapse <?php if($key == '0') echo 'in'?>">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-5">班级：<?=$value['name']?></div>
                            <div class="col-xs-5">老师：<?=$value['role']?><?=$value['tname']?></div>
                        </div>
                        <div style="margin: 20px;" class="row">
<!--                            <button class="btn zd_btn3 col-xs-1" data-toggle="modal" data-target="#xxModal--><?//=$key?><!--">信息</button>-->
<!--                            <div class="col-xs-1"></div>-->
                            <a style="color: white;" href="/guanli/bjpz" class="btn zd_btn3 col-xs-1">配置</a>
                            <div class="col-xs-1"></div>
                            <a style="color: white;" href="/guanli/ckqj?cid=<?=$value['id']?>&name=<?=$value['name']?>" class="btn zd_btn3 col-xs-2">查看请假</a>
                            <div class="col-xs-1"></div>
                            <a style="color: white;" href="/guanli/ckkq?cid=<?=$value['id']?>&name=<?=$value['name']?>" class="btn zd_btn3 col-xs-2">查看考勤</a>
                            <div class="col-xs-1"></div>
                            <button class="btn btn-danger col-xs-1" id="<?=$value['id']?>"  onclick="delclass(this)">删除</button>
                        </div>
                    </div>
                </div>
                <!--信息弹窗-->
                <div class="modal fade" id="xxModal<?=$key?>">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <h4 class="modal-header">
                                一一班信息
                                <button class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </h4>
                            <div class="modal-body text-center">
                                <h4>发送公告：<span><?=$value['ggcount']?></span> 次</h4>
                                <h4>共发留言：<span><?=$value['lycount']?></span> 次</h4>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal">
                                    返回
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php }} ?>
        </div>
<?php if(count($info['schools']) !=0){ ?><div style="margin-top: 20px">
            <ul class="pagination" style="padding-left: 30%">
                <li class="previous">
                    <a href="<?=$info['start']?>">首页</a>
                </li>
                <li>
                    <a href="<?=$info['up']?>">上一页</a>
                </li>
                <li>
                    <a href="<?=$info['down']?>">下一页</a>
                </li>
                <li class="next">
                    <a href="<?=$info['end']?>">末页</a>
                </li>
            </ul>
        </div><?php } ?>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="bjModal">
    <div class="modal-dialog" style="width: 700px;">
        <div class="modal-content">
            <h4 class="modal-header">
                选择联系人
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </h4>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-justified" id="table1">
                    <li class="active">
                        <a href="#pil" data-toggle="tab">批量新建班级</a>
                    </li>
                    <li>
                        <a href="#zid" data-toggle="tab">自定义新建班级/学校内部群组</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="pil" class="tab-pane active">
                        <form class="form-horizontal" role='form' style="margin-top: 20px;">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">年级/组别：</label>
                                <div class="col-sm-7">
                                    <select class="form-control">
                                        <option>请选择</option>
                                        <option>一年级</option>
                                        <option>二年级</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">班级/组名称：</label>
                                <div class="col-sm-7">
                                    <select class="form-control">
                                        <option>请选择</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="zid" class="tab-pane">
                        <form class="form-horizontal" role='form' style="margin-top: 20px;">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">年级/组别：</label>
                                <div class="col-sm-7">
                                    <select class="form-control">
                                        <option>请选择</option>
                                        <option>一年级</option>
                                        <option>二年级</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bz" class="col-sm-3 control-label">班级/组名称：</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="bz" placeholder="请输入" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
    function delclass(t){
        var formdata = {};
        var url = "/guanli/delclass";
        formdata.cid = t.id;
        alert(formdata.cid);
        $.post(url, formdata).done(function (data) {
            data = $.parseJSON(data);
            if (data.status == 0) {
                alert("删除成功");
                window.location.reload();
            }else if(data.status ==2) {
                alert("改班级有学生，不能删除");
            }else {
                alert("删除失败");
            }
        });
    }

    function shtname(){
//        var class = $('.form-control.sousuo').val();
//        if(tname ==""){
//            alert("请输入要搜索的姓名！");
//            return false;
//        }
        $("#formsh").submit();
    }
</script>
