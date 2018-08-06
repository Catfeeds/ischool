<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/myallinfo?openid=<?php echo \yii::$app->view->params['openid']?>')">        
        <i class="fa fa-reply"></i>  
      </div>    
    </div>
   
    <div class="col-xs-5 text-align-l">         
      编辑个人信息       
    </div>
    <div class="col-xs-4 text-align-l">         
      <span id="add_span" class="add-class" onclick="saveUserInfo()">
        保存   
      </span>          
    </div>
       
</div>

<div class="row edit-user-row">
  <div class="col-xs-4 edit-user-top">
   
      <i class="fa fa-user"></i> 姓名
   
  </div>

   <div class="col-xs-7 text-align-l">
     <input type="text" autocomplete="off" class="form-control" id="username" value="<?php echo $the_user[0]['name']?>" />
   </div> 

</div>

<div class="row edit-user-row">
  <div class="col-xs-4 edit-user-top">   
      <i class="fa fa-phone"></i> 手机   
  </div>
   <div class="col-xs-7 text-align-l">
      <input type="text" autocomplete="off" class="form-control" id="tel" placeholder="请输入正确的手机号.." value="<?php echo $the_user[0]['tel']?>" />

  </div>  
</div>

<div class="row edit-user-row">
  <div class="col-xs-4 edit-user-top">   
      <i class="fa fa-phone"></i> 推荐人   
  </div>
   <div class="col-xs-7 text-align-l">
      <input type="text" autocomplete="off" class="form-control" id="references-tel" value="<?php echo $utel?>" disabled />

  </div>  
</div>

<div class="row help-row">
        <div class="col-xs-12">
          <span class="badge">

            提示
          </span>
          <hr>
          <div class="help-row-text">推荐给更多的人使用：您推荐的每个人，注册资料时候只要在“推荐人”栏内填写您的手机号，就可以为您增加1000学分。
          </div>
          <div class="help-row-text">推荐给您认识的校长：您的亲朋好友中如果有人是幼儿园园长，中小学、高中的校长，您可以推荐给他/她使用，对方只要注册学校使用成功，您不但能成为我们的VIP用户免费使用所有学习资源，还可以在人工客服中联系我们，有优厚的礼品赠送哦~~
          </div>          
        </div>
</div>

