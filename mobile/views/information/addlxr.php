<div class="row heard-list-waper">

    <div class="col-xs-2 col-xs-offset-1 text-align-l">
        <div onclick="backto('<?php echo URL_PATH?>/information/lxr?cid=<?php echo $cid?>&sid=<?php echo $sid?>&openid=<?php echo $openid?>&tcid=<?php echo $tcid?>&stuid=<?php echo $stuid?>')">
            <i class="fa fa-reply"></i>
        </div>
    </div>

    <div class="col-xs-5 text-align-l">
        增加联系人信息
    </div>
    <div class="col-xs-4 text-align-l">         
      <span id="add_span" class="add-class" onclick="savelxr()">
        保存   
      </span>
    </div>

</div>
<input type="hidden" value="<?php echo $cid?>" id="cid"/>
<input type="hidden" value="<?php echo $sid?>" id="sid"/>
<input type="hidden" value="<?php echo $openid?>" id="openid"/>
<input type="hidden" value="<?php echo $tcid?>" id="tcid"/>
<input type="hidden" value="<?php echo $stuid?>" id="stuid"/>
<div class="row edit-user-row">
    <div class="col-xs-4 edit-user-top">

        <i class="icon-list-alt icon-margin"></i>姓名

    </div>

    <div class="col-xs-7 text-align-l">
        <input type="text" autocomplete="off" class="form-control" id="username"/>
    </div>

</div>
<div class="row edit-user-row">
    <div class="col-xs-4 edit-user-top">
        <i class="icon-list-alt icon-margin"></i>身份
    </div>
    <div class="col-xs-7 text-align-l">
        <input type="text" autocomplete="off" class="form-control" id="relation" placeholder="请输入联系人身份.."/>

    </div>
</div>
<div class="row edit-user-row">
    <div class="col-xs-4 edit-user-top">
        <i class="icon-list-alt icon-margin"></i>手机
    </div>
    <div class="col-xs-7 text-align-l">
        <input type="text" autocomplete="off" class="form-control" id="tel" placeholder="请输入正确的手机号.."/>

    </div>
</div>



