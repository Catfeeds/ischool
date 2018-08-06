<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">

    <div style="background-color: white;box-shadow: 0 0 2px #ccc;padding: 10px 20px 20px 20px;">
        <h4>检索</h4>
        <div class="form-group dropdown-toggle clearfix">
            <form id="formsh" name="formsh" method="get" action="/guanli/syjs">
            <div class="input-group col-xs-4 pull-left">
                <input style="height: 40px;" class="form-control" type="text" placeholder="输入老师姓名" name="tname" value=""/>
				        		<span style="background-color: #36ADFF;cursor: pointer;" class="input-group-addon" onclick="shtname()">
					        	    <img  src="../img/sou.png" />
					        	</span>

            </div> </form>
<!--            <button class="btn pull-left" style="background-color: #36ADFF;margin-left: 30px; color: white;line-height: 26px;">老师查询</button>-->
        </div>
    </div>
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">所有教师列表</h4>
        </div> <?php  if(count($schools) !=0) {?>
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
<!--                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">-->
                    <table style="text-align: center;width: 100%" class="panel-title"><tbody>
                        <tr ><td style="width: 15%">角色</td><td style="width: 20%">姓名</td><td style="width: 15%">班级</td><td style="width: 25%">手机号</td><td style="width: 25%">操作</td></tr>
                        </tbody>
                    </table>
<!--                        </a>-->
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <table style="text-align: center;width: 100%" class="panel-title"><tbody>
                                <?php foreach($list as $key=>$value){?>
                                <tr style="line-height: 36px"><td style="width: 15%"><?=$value['role']?></td><td style="width: 20%"><?=$value['tname']?></td><td style="width: 15%"><?=$value['class']?></td><td style="width: 25%"><?=$value['tel']?></td><td  class="btn btn-danger" id="<?=$value['id']?>" onclick="delteacher(this)">删除</td></tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><?php }?>
        <div style="margin-top: 20px">
            <ul class="pagination" style="padding-left: 30%">
                <li class="previous">
                    <a href="<?=$start?>">首页</a>
                </li>
                <li>
                    <a href="<?=$up?>">上一页</a>
                </li>
                <li>
                    <a href="<?=$down?>">下一页</a>
                </li>
                <li class="next">
                    <a href="<?=$end?>">末页</a>
                </li>
            </ul>
        </div>
    </div>

</div>
</div>
</div>

<script>
    function delteacher(t){
        if (confirm("确认删除？")){
            var id = $(t).attr("id");
            var formdata={};
            var url ="/guanli/delteacher";
            formdata.id = id;
            $.post(url,formdata).done(function (data) {
                data = $.parseJSON(data);
                if (data.status == 0) {
                    alert("删除成功");
                    $(t).parents("tr").remove();
                }else {
                    alert("删除失败");
                }
            })
        };
    }

    function shtname(){
        var tname = $('.form-control').val();
//        if(tname ==""){
//            alert("请输入要搜索的姓名！");
//            return false;
//        }
        $("#formsh").submit();
    }

</script>
