<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
?>
<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left"><?=$classname?>考勤信息</h4>
            <div class="pull-right col-lg-5">
                <form id="formkq" name="formkq" method="get" action="/guanli/ckkq">
                <input type="hidden" name="name" value="<?=$classname?>"><input type="hidden" name="cid" value="<?=$cid?>">
                <input type="text" name="time" class="pull-right form-control" onfocus="(this.type='date')" placeholder="您要查看哪一天的考勤？" onblur="onblus()"/></form>
            </div>
        </div>
        <hr/>
<!--        <ul class="kaoqin">-->
<!--            <li style="background-color: #36ADFF;color: white;">今天迟到3人，早退1人。</li>-->
<!--        </ul>-->
        <ul class="kaoqin">
            <?php foreach($dklist as $key => $value){?>
            <li><?php echo isset($stuname[$value['stuid']])?$stuname[$value['stuid']]:"" ?>于<?=date("Y年m月d日 H时i分s秒",$value['ctime']);?>有一条<?=$value['info']?>信息</li>
            <?php } ?>
        </ul>
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
    function onblus(){
       $("#formkq").submit();
    }
</script>