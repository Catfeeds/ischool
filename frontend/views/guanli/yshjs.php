<?php
use yii\widgets\LinkPager;
?>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">已审核教师</h4>
        </div>
        <div class="panel-group" id="accordion">
            <?php foreach($model as $key =>$value){?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$key?>">
                            <div>姓名<span class="pull-right"><?=$value['tname']?></span></div>
                        </a>
                    </h4>
                </div>
                <div id="collapse<?=$key?>" class="panel-collapse collapse <?php if($key == 0) echo 'in'; ?>">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-5">姓名：<?=$value['tname']?></div>
                            <div class="col-xs-5">手机：<?=$value['tel']?></div>
                            <div class="col-xs-2">
                                <button class="btn btn-danger" id="<?=$value['id']?>"  onclick="shanchu(this)">删除</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div style="margin-top: 20px">
            <?php
            echo LinkPager::widget([
                'pagination' => $pages,
            ]);
            ?>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function shanchu(t){
        var formdata = {};
        formdata.id = $(t).attr("id");
        formdata.ispass = "n";
        var url = "/guanli/yshjs";
        $.post(url,formdata).done(function (data){
            if (data == 0){
                alert('删除成功');
                $(t).parents('.panel-default').remove();
            }else{
                alert("删除失败");
            }
        });
    }
</script>