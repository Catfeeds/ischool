<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-04-17
 * Time: 16:02
 */
?>
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">学校微官网</h4>
        </div>
        <div style="background-color: #eee;padding: 10px; font-size: 18px;" class="text-center"><?=!(empty($students[0]['school']))?$students[0]['school']:""?></div>
        <div class="thumbnail">
            <img src="<?php if(!empty($schoolpic)){echo $schoolpic;}else{ echo '/img/gw_1.jpg';}?>" />
        </div>
    </div>
    <ul class="list-group" style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <li style="border: none;line-height: 30px;" class="list-group-item">
            <span class="gg">公告</span><?=$gonggao[0]['title'];?><a class="pull-right" href="/pastudent/schoolmin?id=<?=$gonggao[0]['id'];?>"><?php if(!empty($gonggao)){ echo ">>查看详情";} ?></a>
        </li>
        <li style="border: none;border-top: 1px solid #ddd;line-height: 30px;" class="list-group-item">
            <span style="background-color: #FF9900;" class="gg">动态</span>
            <?php if(!empty($dongtai)){;?>
            <?=$dongtai[0]['title'];?><a class="pull-right" href="/pastudent/dongtaimin?id=<?=$dongtai[0]['id'];?>">>>查看详情</a>
            <?php } ?>
        </li>
    </ul>
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <?php foreach($columns as $key=>$value){?>
            <div class="zhanshi">
                <div class="xgk clearfix">
                    <span class="gg"><?=!empty($value['name'])?$value['name']:"暂无名称"?></span>
                </div>
                <div>
                    <img class="pull-left" src="<?=!empty($value['toppicture'])?$value['toppicture']:'http://mobile.jxqwt.cn/img/zhengfan.png'?>" />
                    <div class="pull-left" style="margin-top: 10px;">
                        <h5 style="color: #FF8484;"><?=!empty($value['title'])?$value['title']:"标题暂时为空"?></h5>
                        <p><?=!empty($value['sketch'])?$value['sketch']:"简介暂时为空"?></p>
                    </div>
                    <?php if(!empty($value['title'])){?>
                    <a class="xgk_xq" href="/pastudent/websitemin?id=<?=$value['id']?>">>>查看详情</a><?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</div>
</div>
