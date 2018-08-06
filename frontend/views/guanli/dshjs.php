<?php
use yii\widgets\LinkPager;
?>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;box-shadow: 0 0 2px #ccc;padding: 10px 20px 20px 20px;">
        <h4>待审核教师</h4> <?php  if(count($schools) !=0) {?>
        <div class="panel-group" id="dai">
            <?php if(empty($model)){echo "暂无待审核教师！";}; foreach($model as $key =>$value){?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#dai" href="#collapse<?=$key?>">
                            <div>姓名<span class="pull-right"><?=$value['tname']?></span></div>
                        </a>
                    </h4>
                </div>
                <div id="collapse<?=$key?>" class="panel-collapse collapse <?php if($key == 0) echo 'in'; ?>">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">姓名：<?=$value['tname']?></div>
                            <div class="col-xs-4">手机：<?=$value['tel']?></div>
                            <div class="col-xs-4">
                                申请角色：<?=$value['class'].$value['role']?>
                            </div>
                        </div>
                        <div class="pull-right" style="margin-top: 10px;">
                            <button class="btn zd_btn3" id="<?=$value['id']?>"  onclick="tongguo(this)">通过</button>
                            <button class="btn btn-danger" id="<?=$value['id']?>" onclick="jujue(this)">拒绝</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div> <?php }?>
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
<script>
    function tongguo(t){
        var formdata = {};
        formdata.id = $(t).attr("id");
        formdata.ispass = "y";
//        console.log(formdata);
        var url = "/guanli/dshjs";
        $.post(url,formdata).done(function (data){
            console.log(data);
            if (data == 0){
                alert('已经通过审核');
                $(t).parents('.panel-default').remove();
            }else{
                alert("审核失败");
            }
        });
    }
    function jujue(t){
        var formdata = {};
        formdata.id = $(t).attr("id");
        formdata.ispass = "n";
        var url = "/guanli/dshjs";
        $.post(url,formdata).done(function (data){
            console.log(data);
            if (data == 0){
                alert('已经拒绝审核');
                $(t).parents('.panel-default').remove();
            }else{
                alert("拒绝审核失败");
            }
        });
    }
</script>