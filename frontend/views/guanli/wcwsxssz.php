<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;box-shadow: 0 0 2px #ccc;padding: 10px 20px 20px 20px;">
        <h4>检索</h4>
        <div class="form-group dropdown-toggle clearfix">
            <div class="input-group col-xs-4 pull-left">
                <input style="height: 40px;" class="form-control" type="text" placeholder="输入学生姓名" />
				        		<span style="background-color: #36ADFF;" class="input-group-addon">
					        	    <img src="../img/sou.png" />
					        	</span>
            </div>
            <button class="btn pull-left" style="background-color: #36ADFF;margin-left: 30px; color: white;line-height: 26px;">学生查询</button>
        </div>
    </div>
    <div style="background-color: white;box-shadow: 0 0 2px #ccc;padding: 10px 20px 20px 20px;">
        <h4>学校管理员配置</h4>
        <table class="table table-bordered text-center" style="margin-top: 20px;">
            <tr style="background-color: #eee;">
                <td>序号</td>
                <td>姓名</td>
                <td>学生班级</td>
                <td>进出状态</td>
                <td>操作</td>
            </tr>
            <tr>
                <td>01</td>
                <td>李依依</td>
                <td>一一班</td>
                <td>外宿</td>
                <td>
                    <button class="zd_btn4" data-toggle="modal" data-target="#changeModal">修改</button>
                </td>
            </tr>
            <tr>
                <td>02</td>
                <td>李依依</td>
                <td>一一班</td>
                <td>外宿</td>
                <td>
                    <button class="zd_btn4">修改</button>
                </td>
            </tr>
        </table>
        <div style="margin-top: 20px">
            <ul class="pagination" style="padding-left: 20%">
                <li>
                    <a href="###">记录：共4条信息</a>
                </li>
                <li class="previous">
                    <a href="###">首页</a>
                </li>
                <li>
                    <a href="###">上一页</a>
                </li>
                <li>
                    <a href="###">下一页</a>
                </li>
                <li class="next">
                    <a href="###">末页</a>
                </li>
                <li>
                    <a href="###">页数：1页</a>
                </li>
            </ul>
        </div>
    </div>
</div>
</div>
</div>
<div class="modal fade" id="changeModal">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content">
            <h4 class="modal-header">
                外餐外宿学生设置
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </h4>
            <div class="modal-body">
                <form class="form-horizontal" role='form'>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">学生姓名：</label>
                        <div class="col-sm-7">
                            <input type="text" value="张依依" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">学生班级：</label>
                        <div class="col-sm-7">
                            <input type="text" value="一一班" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">进出状态：</label>
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
            <div class="modal-footer">
                <button class="btn zd_btn3">
                    确定
                </button>
                <button class="btn btn-danger" data-dismiss="modal">
                    取消
                </button>
            </div>
        </div>
    </div>
</div>