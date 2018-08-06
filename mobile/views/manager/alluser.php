    <div class="container-fluid register-user-margin">
       <div class="row register-user-container">

           <div class="col-xs-12 register-user-title">
               <span class="badge">所有教师</span>
           </div>

			<?php if(empty($list_user)) {?>
               <div class="col-xs-12 text-center list-location">暂无相关信息</div>
           <?php }else{?>
           		
		    <div class="row inout-with-list" style="padding-top:60px;">
		        <div class="col-xs-3 col-xs-offset-1">姓名:</div>
		        <div class="col-xs-8">
		          <input id='username' value="<?php echo $uname?>">
		          <span class="data-card-del"  onclick="searchTeacher()">搜索</span>
		        </div>
		    </div>

			<?php 
			foreach ($list_user as $key=>$vo) { ?>
               <div id="s<?php echo $vo['id']?>" class="col-xs-12 register-user-center-margin">

                   <div class="row register-user-title data-card" onmouseover="register_user_over(this)" onmouseout="register_user_out(this)" id="list<?php echo $vo['id'] ?>" onclick="witchInfo(this)">
                     <div class="col-xs-6 text-center"><?php echo $vo['role']?></div>
                     <div class="col-xs-6 text-center"><?php echo $vo['tname']?></div>
                   </div>

                   <div id="with-list<?php echo $vo['id']?>" style="display:none;margin-top:15px;">
                   
                        <div class="row inout-with-list">
                            <div class="col-xs-4">姓名:</div>
                            <div class="col-xs-7"><?php echo $vo['tname']?></div>
                        </div>                      
                        
						<div class="row inout-with-list">
                            <div class="col-xs-4">手机:</div>
                            <div class="col-xs-7"><?php echo $vo['tel']?></div>
                        </div> 
                        <div class="row inout-with-list">
                        <hr>
                            <div class="col-xs-4 col-xs-offset-7 text-right"><span class="data-card-del badge_oprate" onclick="delete_span(<?php echo $vo['id']?>,'<?php echo $vo['openid']?>')" >删除</span></div>
                        </div>

                   </div>

               </div>                                             
			<?php }}?>
       </div>

       <div class="row register-user-container">

           <div class="col-xs-12 register-user-center-margin">
               <div class="row register-user-Paging">
                 <div class="col-sm-2 list-display">记录：共<?php echo $count?>条信息</div>
                 <a href="<?php echo $start?>" onclick="loadHtml(this,event)">
                    <div class="col-sm-2 col-xs-3">首页</div>
                 </a>
                 <a href="<?php echo $up?>" onclick="loadHtml(this,event)">
                    <div class="col-sm-2 col-xs-3">上一页</div>
                 </a>
                 <a href="<?php echo $down?>" onclick="loadHtml(this,event)">
                    <div class="col-sm-2 col-xs-3">下一页</div>
                 </a>
                 <a href="<?php echo $end?>" onclick="loadHtml(this,event)">
                    <div class="col-sm-2 col-xs-3">末页</div>
                 </a>
                 <div class="col-sm-2 list-display">页数：<?php echo $totalPage?>页</div>
               </div>
           </div>

       </div>


    </div> <!-- container-fluid -->

<!-- container-fluid DIV -->  
<script type="text/javascript" src="/js/bootstrap-confirmation.js"></script>
<!-- JS代码区 --> 
<script type='text/javascript'>

    function delete_span(id,openid){
          var openid = openid;
          var sid = $("#sid").val();  
          var url= '/manager/deleteteacher';
          var para={openid:openid,sid:sid};
          var ths = $(this);
          var d = dialog({
            title: '提示',
            content: '您确定要删除吗?',
            okValue: '确定',

            ok: function () {
               $.post(url,para,function (data){
                 if(data=='success'){
                  $("#s"+id).remove();
                 }else if(data="xiaozhang"){
                    alert("无权删除该用户");
                 }else{
                    alert("删除失败");
                 }
               });
            },

            cancelValue: '取消',
            cancel: function () {

            }

         });

         d.showModal();      
    }
    
    function searchTeacher(){
    	var username = $.trim($("#username").val());
    	var url= '/manager/alluser?uname='+username;
    	loadHtmlByUrl(url);
    }

</script>
