<input type="hidden" value="<?php echo URL_PATH?>" id="path">
<main class="cd-main-content container-fluid" >

  <div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/myallinfo?openid=<?php echo $openid?>')">        
        <i class="icon-group"></i>

      </div>    
    </div>
   
    <div class="col-xs-5 text-align-l">         
     填写个人资料       
    </div>
    <div class="col-xs-4 text-align-l">
      <span id="add_span" class="add-class" onclick="saveuserinfo()">
        保存
      </span>          
    </div>
       
</div>

<div class="row edit-user-row">
  <div class="col-xs-4 edit-user-top">
      <i class="icon-list-alt icon-margin"></i>姓名
  </div>
  <div class="col-xs-7 text-align-l">
     <input type="text" autocomplete="off" class="form-control" id="username" value="" />
  </div>  
</div>

<div class="row edit-user-row">
  <div class="col-xs-4 edit-user-top">   
      <i class="icon-list-alt icon-margin"></i>手机
  </div>
   <div class="col-xs-7 text-align-l">

      <input type="text" autocomplete="off" class="form-control" id="tel" placeholder="请输入正确的手机号.." value=""/>

   </div>   

</div>

<div class="row edit-user-row">
  <div class="col-xs-4 edit-user-top">   
      <i class="icon-list-alt icon-margin"></i>身份
  </div>
   <div class="col-xs-7 text-align-l">

	<select id="shenfen" class="form-control">
    <option value="jiazhang">家长</option>
    <option value="tea">教师</option>
	  <option value="guanli">管理</option>
	
	</select>
	

   </div>   

</div>





<div class="row edit-user-row">
  <div class="col-xs-4 edit-user-top">   
      <i class="icon-list-alt icon-margin"></i>推荐人
  </div>
   <div class="col-xs-7 text-align-l">

      <input type="text" autocomplete="off" class="form-control" id="rec-tel" placeholder="请输入推荐人的手机号.." value="" />

   </div>   

</div>

<div class="row help-row">
        <div class="col-xs-12">
          <span class="badge">

            提示
          </span>
          <hr>
          <div class="help-row-text">
      若您是由其他用户推荐使用本应用，可以将推荐人的手机号写入推荐人输入框；若不是则可以不填
          </div>
        </div>
</div>

</main>


<input type="hidden" id="openid" value="<?php echo $openid?>" />

<?php echo $this->render('../layouts/footer')?>

<script type="text/javascript">   

function saveuserinfo(){
  var path=$("#path").val();
  if(checkUserInfo()){
    var username= $.trim($("#username").val());
    var tel=$.trim($("#tel").val());
    var openid=$("#openid").val();
    var rectel=$("#rec-tel").val();
    var shenfen = $("#shenfen").val();
    var data={openid:openid,username:username,tel:tel,rectel:rectel,shenfen:shenfen};
    var url=path+"/information/saveuserinfo";
    var to_url=path+"/information/index?openid="+openid;

    var options = {para:data,ele:$("#add_span"),sub_url:url,to_url:to_url,urltype:1,status:[{code:0,content:'该号码已被使用'}]};
    sub_dialog(options);
  }
}

function checkUserInfo(){

  var tel=$("#tel").val();
    var myreg = /^1[3|4|5|7|8][0-9]\d{4,8}$/;
        
  if($.trim($("#username").val())==""){
     $("#username").focus();
     return false;
   }
   if(!myreg.test(tel))
   {
    alert('请输入有效的手机号码！');
    return false;
   }

     
  return true;
}
</script>



