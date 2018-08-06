<?php 
use yii\helpers\Url;
?>
<header>
  <div class="container-fluid">
    <div class="row header-menu" id="header-menu1">

      <div class="col-xs-12">
         <div class="col-xs-3 jxt_op_btnol" id="back-btn">
              <a href="<?php echo URL_PATH?>/homepage/index?openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo \yii::$app->view->params['sid']?>"><span class="fa fa-reply"></span>返回</a>
         </div>
        <div class="col-xs-3 pull-right">
            <div name="<?php echo URL_PATH?>/exchange/sendmsg?openid=<?php echo \yii::$app->view->params['openid']?>" class="jxt_op_btn" id="send_btn">写信</div>
        </div>
        <div class="col-xs-3 pull-right">
          <div name="<?php echo URL_PATH?>/exchange/inbox?openid=<?php echo \yii::$app->view->params['openid']?>" class="jxt_op_btn jxt_current_btn" id="receive_btn">收信</div>
        </div>
        <div class="col-xs-3 pull-right">
          <div name="<?php echo URL_PATH?>/exchange/outbox?openid=<?php echo \yii::$app->view->params['openid']?>" class="jxt_op_btn" id="sent_btn">已发</div>
        </div>
      </div>
    </div>
  </div>
</header> <!-- 顶部菜单 结束 -->

