<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">学校微官网</h4>
        </div>
        <div style="background-color: #eee;padding: 10px; font-size: 18px;" class="text-center"><?=$school?></div>
        <div class="thumbnail">
            <img class="" src="<?php if(!empty($schoolpic)){echo $schoolpic;}else{ echo '/img/gw_1.jpg';}?>" />
        </div>
    </div>
    <ul class="list-group" style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
        <li style="border: none;line-height: 30px;" class="list-group-item">
            <span class="gg">公告</span><?= !empty($gonggao)?$gonggao[0]['title']:"暂无公告信息！";?><?php if(!empty($gonggao)){ ?><a class="pull-right" href="/guanli/schoolmin?id=<?=$gonggao[0]['id'];?>">>>查看详情</a><?php }?>
        </li>
        <li style="border: none;border-top: 1px solid #ddd;line-height: 30px;" class="list-group-item">
            <span style="background-color: #FF9900;" class="gg">动态</span><?= !empty($dongtai)?$dongtai[0]['title']:"暂无动态信息！";?><?php if(!empty($dongtai)){ ?><a class="pull-right" href="/guanli/dongtaimin?id=<?=$dongtai[0]['id'];?>">>>查看详情</a><?php }?>
        </li>
    </ul>
    <?php  if(count($schools) !=0) {?>
    <div style="background-color: white;padding: 10px 20px;margin-top: 5px;box-shadow: 0 0 2px #ccc;">
       <?php foreach($columns as $key=>$value){?>
        <div class="zhanshi">
            <div class="xgk clearfix">
                <span class="gg"><?=!empty($value['name'])?$value['name']:"暂无名称"?></span>
                <a href="/guanli/add?cid=<?php echo $value['cid']?>&type=<?php echo $value['name']?>&tem=zidingyi" style="line-height: 18px;margin: 7px 15px;" class="pull-right gg">发布</a>
                <?php if(!empty($value['id'])){?> <a href="/guanli/edit?id=<?php echo $value['id']?>&type=<?php echo $value['name']?>" style="line-height: 18px;margin: 7px 15px;" class="pull-right gg">编辑</a><?php } ?>
            </div>
            <div>
                    <img class="pull-left" style="max-width: 400px;" src="<?=!empty($value['toppicture'])?$value['toppicture']:'http://mobile.jxqwt.cn/img/zhengfan.png'?>" />
                    <div class="pull-left" style="margin-top: 10px;">
                        <h5 style="color: #FF8484;"><?=!empty($value['title'])?$value['title']:"标题暂时为空"?></h5>
                        <p><?=!empty($value['sketch'])?$value['sketch']:"简介暂时为空"?></p>
                    </div>
                <?php if(!empty($value['title'])){?>
                    <a class="xgk_xq" href="/guanli/websitemin?id=<?=$value['id']?>">>>查看详情</a><?php } ?>
            </div>
        </div>
        <?php } ?>
    </div><?php }?>
</div>
</div>
</div>
