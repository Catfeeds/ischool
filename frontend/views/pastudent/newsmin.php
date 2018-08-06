<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-04-18
 * Time: 9:03
 */
?>
<div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
    <a href="#" style="letter-spacing: -5px;">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span style="letter-spacing:5px;padding: 0 10px" onclick="history.go(-1)">返回</span>
    </a>
    <hr/>
    <p class="text-primary"> <span class="badge" style="background: red;padding: 3px 10px">动态</span><span style="padding: 0 0 0 15px"><?php echo $info[0]['title'];?></span> <span class="text-right pull-right"><?=date("Y-m-d H:i:s",$info[0]['ctime']);?></span></p>
    <div class="panel panel-body" style="border: 1px solid #ccc">
        <!--        <p class="text-center">平安通知使用方法</p>-->
        <!--        <div><img src="/img/gw_2.jpg" alt="" /></div>-->
        <!--        <p style="line-height: 36px">班级动态的发布与校内公告的发布方法是一样的</p>-->
        <?php echo $info[0]['content'];?>
    </div>
</div>
</div>
</div>
</div>
</div>