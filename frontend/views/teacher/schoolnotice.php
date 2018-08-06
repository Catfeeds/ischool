<?php
use yii\widgets\LinkPager;
?>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;box-shadow: 0 0 2px #ccc;">
        <div class="panel panel-body">
            <ul class="list-group">
                <li class="list-group-item clearfix">
                    <h4 class="pull-left">校内公告</h4>
<!--                    <a href="/teacher/fabugg" class="btn btn-success pull-right">发布</a>-->
                </li>
                <?php foreach($dataprovider as $key=>$value){ ?>
                <li class="list-group-item">
                    <a href="/teacher/schoolmin?id=<?=$value['id']?>" class="text-primary"> <span class="badge" style="background: red;padding: 3px 10px">公告</span><span style="padding: 0 0 0 15px"><?=$value['title']?></span> <span class="text-right pull-right"><?=date("Y-m-d",$value['ctime'])?></span></a>
                </li>
                <?php } ?>
            </ul>
            <div class="panel-footer" style="margin-top: 10%">
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
</div>