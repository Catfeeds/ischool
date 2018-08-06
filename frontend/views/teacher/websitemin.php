<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-04-18
 * Time: 16:10
 */
?>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
<div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
    <a href="#" style="letter-spacing: -5px;">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span style="letter-spacing:5px;padding: 0 10px" onclick="history.go(-1)">返回</span>
    </a>
    <div class="page-header" style="background-color: #ccc;padding:1px">
        <h4 class="text-center"><?=$info[0]['title']?></h4>
    </div>
    <p style="position: relative;z-index: 1">
        <span class="badge my_badge">简介</span>
    </p>
    <?=$info[0]['sketch']?>
    <div><img src="<?=$info[0]['toppicture']?>" alt="" /></div>
    <div style="padding-top: 20px">
        <p style="position: relative;z-index: 1">
            <span class="badge my_badge" style="background: dodgerblue;padding: 3px 10px">详情内容</span>
        </p>
    </div>
    <div id="tupian"><p><?=$info[0]['content']?></p></div>
</div>
</div>
</div>
</div>
