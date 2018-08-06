 <div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/myallinfo?openid=<?php echo \yii::$app->view->params['openid']?>')">        
        <i class="fa fa-reply"></i> 

      </div>
    </div>
   
    <div class="col-xs-5 text-align-l">         
           我的学校      
    </div>

    <div class="col-xs-4 text-align-l">         
      <span class="add-class" onclick="forwardTo('<?php echo URL_PATH?>/information/admin-school?openid=<?php echo \yii::$app->view->params['openid']?>')">
        <i class="fa fa-plus"></i>  
      </span>   
    </div>  
</div>
<div id="adminner">
<?php if(empty($list_school)){?>
     <div class="row edit-user-row">
          <div class="col-xs-10 col-xs-offset-2 edit-user-top">
            暂未绑定学校
          </div>          
      </div>
<?php }else{?>
 <?php foreach($list_school as $k=>$v ){?>    
    <div class="row edit-user-row xz-counts aaaaa" >
        <div class="col-xs-10" style="margin-top:5px;" onclick="change_power('<?= $v['openid']?>','<?= $v['sid']?>','<?= $v['school_name']?>','<?= $v['name']?>','<?= $v['role']?>')">
          
           
           <i class="fa fa-user"></i>
             <?php echo $v['school_name'] ?> 
              <?php if($v['role']=='1'){ ?>
            &nbsp; &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" style="color:green;"></span> 
          <?php } ?>                
        </div>
        
        <div id="del" class="col-xs-2 btn-group-sm" style="padding: 0;">
           <button  class="btn btn-danger delete" data-id="<?php echo $v['id']?>">删除</button>
        </div>
    </div> 
    
   <?php } ?>
</div>
<?php } ?>
 <div class="row help-row">
        <div class="col-xs-12">
          <span class="badge">
            帮助
          </span>
          <hr>
          <div class="help-row-text">
          如果您想申请成为新的校长，点击右上角【＋】绑定相关学校。<br/>
          若您已经绑定学校，点击相关学校的名称，可以将校长身份切换至该校，同时您将失去原来校长的身份。
          </div>
        </div>
      </div>
<script type="text/javascript"> 

     $("#adminner").on("click",".delete",function(){
            if(confirm("确认删除？")){
             
            var row_id = $(this).data("id");
            var _this = $(this);
             $.getJSON("/information/deladminer?id=" + row_id).done(function(data){
                 if(data=='success'){
                     alert('操作成功！');
                     location.reload();
                 }
             })
         }
    })

     
 function change_power(openid,sid,school_name,name,role){
     if(role==1){
         alert("无需切换！");
         return  false;
     }
     if(confirm("您要将校长身份切换到当前学校？")){                    
       var url="/information/change-power";
       $.post(url,{openid:openid,sid:sid,school_name:school_name,name:name},
       function (data){
         if(data['status']=='success'){
            var d = dialog({
            title: '切换成功',
            content: '切换学校成功',
            okValue: '确定',
                ok: function () {
                    var tourl ="/manager/index?openid="+data['openid']+"&sid="+data['sid'];
                    window.location.replace(tourl);

                },

            });

           d.showModal();

         }
       });
     
    } 
} 
 
</script>

