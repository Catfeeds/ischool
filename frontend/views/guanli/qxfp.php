<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">角色权限编辑</h4>
            <button class="btn zd_btn3 pull-right" data-toggle="modal" data-target="#roleModal">添加用户角色</button>
        </div>
        <hr/>
        <div class="permission_bg">
            <div class="row">
                <div class="col-sm-8">角色名：首页管理员</div>
                <div class="col-sm-4">
                    <button type="button" class="zd_btn4 btn" data-toggle="modal" data-target="#gnModal">添加功能</button>
                    <button class="zd_btn5 btn">删除角色</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">角色名：总管理员</div>
                <div class="col-sm-4">
                    <button type="button" class="zd_btn4 btn" data-toggle="modal" data-target="#gnModal">添加功能</button>
                    <button class="zd_btn5 btn">删除角色</button>
                </div>
            </div>
        </div>
    </div>
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">用户角色分配</h4>
        </div>
        <hr/>
        <form class="form-horizontal Modify_pwd">
            <div class="form-group">
                <label class="col-sm-2 control-label">用户名</label>
                <div class="col-sm-6">
                    <select class="form-control" id="province">
                        <option value="">请选择</option>
                    </select>
                </div>
            </div>
            <div class="permission_bg">
                <div class="row">
                    <div class="col-sm-10">角色名：首页管理员</div>
                    <div class="col-sm-2">
                        <input type="checkbox" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10">角色名：总管理员</div>
                    <div class="col-sm-2">
                        <input type="checkbox" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10">角色名：动态管理员</div>
                    <div class="col-sm-2">
                        <input type="checkbox" />
                    </div>
                </div>
            </div>
            <div class="form-group form-inline" style="margin-top: 10px;">
                <label class="col-sm-4 control-label"></label>
                <div class="col-sm-8 row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-block" style="background-color: #36ADFF;color: white" >全选</button>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-danger" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
<!--添加角色弹窗-->
<div class="modal fade" id="roleModal">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content">
            <h4 class="modal-header">
                新增角色
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </h4>
            <div class="modal-body">
                <form>
                    <input style="width: 60%;margin: 20px auto;" type="text" class="form-control" placeholder="请填写角色名称" />
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
<!--添加功能弹窗-->
<div class="modal fade" id="gnModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <h4 class="modal-header">
                新增角色
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </h4>
            <div class="modal-body">
                <form style="line-height: 40px;">
                    <div class="row">
                        <div class="col-sm-10">首页管理</div>
                        <div class="col-sm-2">
                            <input type="checkbox" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">公告管理</div>
                        <div class="col-sm-2">
                            <input type="checkbox" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">动态管理</div>
                        <div class="col-sm-2">
                            <input type="checkbox" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">平安通知管理</div>
                        <div class="col-sm-2">
                            <input type="checkbox" />
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