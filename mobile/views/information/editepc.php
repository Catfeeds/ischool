<div class="row heard-list-waper">
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
        <div onclick="backto('<?php echo URL_PATH?>/information/onechild?stuid=<?php echo $stuid?>&openid=<?php echo $openid?>&_=<?php echo $time?>')">
        <i class="fa fa-reply"></i>
      </div>    
    </div>
    <div class="col-xs-5 text-align-l">         
      一卡通卡号变更
    </div>
    
</div>

<div id="qinqinghao">
<!--    <volist name="arr" id="vo">-->
<div class="row edit-user-row xz-counts aaaaa">
    <div class="col-xs-8" id="rowid">
        <span class="shenfen">卡号：</span>
        <span class="xinming"><?php echo $stuinfo[0]['stuno2']?></span>
    </div>
    <div id="del" class="col-xs-4 btn-group-sm" style="padding: 0;">
        <button  class="btn btn-info edit" id="<?php echo $v['id']?>">修改</button>   
    </div>
</div>
<!--    </volist>-->
<div class="row help-row">
        <div class="col-xs-12">
          <span class="badge">
            温馨提示：
          </span>
          <hr>
          <div class="help-row-text">
           该卡号需与一卡通上的【编号】对应。</br>
          </div>
        </div>
</div>
</div>


<!--修改卡号-->
<form id="change-model" style="display: none;" action="<?php echo URL_PATH?>/information/updateepc?stuid=<?php echo $stuid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>" method="post">
<!--<form id="change-model" style="display: none;">-->
<div class="row edit-user-row">
  <div class="col-xs-10" style="margin: 0;">
    卡号：
    <input name="xingming" id="change_m" style="width: 80%;display: inline-block;" class="form-control text"type="text">
  </div>
</div>

<div class="row edit-user-row">
  <div class="col-xs-6"></div>
  <div class="col-xs-3">
      <input type="button" name="submit" id="btn2" value="提交" class="btn btn-success">
  </div>
  <div class="col-xs-3">
    <div class="btn btn-danger quxiao">取消</div>
  </div>
</div>
</form>
<input type="hidden" id="path" value="<?php echo URL_PATH?>">
<input type="hidden" id="openid" value="<?php echo $openid?>">
<input type="hidden" id="stuid" value="<?php echo $stuid?>">

<link href="/css/DateTimePicker.css" rel="stylesheet" type="text/css">
<script src="/js/DateTimePicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $('.aaaaa').each(function(){
            $(this).click(function(){
                $('#change_m').val('');                         
                var $textxm = $(this).find('.xinming').text();                   
                $('#change_m').val($textxm);
            })
        })
        $("#qinqinghao").on("click",".edit",function(e){
            var dividx = $("#rowid span ").text();
            $("#qinqinghao").hide();
            $("#change-model").show();
        })         
        $(".quxiao").click(function(){
        	$("#add_s").val('');
          	$("#add_m").val('');
         	$("#add_p").val('');
      	    $("#add-model").hide();
      	    $("#change-model").hide();
        	$("#qinqinghao").show();
        })
    });
    function A(i){
      	$("#qinqinghao").hide();
      	$("#change-model").show();     
      	var m=$(".xinming").eq(i).html();    
      	$("#change_m").val(m);

    }
   

    $("#btn2").on("click",function(){     
        var name=$("#change_m").val();  
        if(name==''){
            alertDialog('请输入卡号！');
            return false;
        } 
        var url ="<?php echo URL_PATH?>/information/updateepc?stuid=<?php echo $stuid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>";
        var para = {"name":name};
        $.getJSON(url,para,function(data){
            if (data == 0){
                alert("修改成功");
                backto('<?php echo URL_PATH?>/information/editepc?id=<?php echo $stuid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>&_=<?php echo $time?>');
            }else if(data == 1){
                alert("卡号无法正确匹配，请重新填写！");
            }else{
                alert("修改失败！");
            }  
        })
    })

//    function checkChangeInfo(){
//    	var role=$("#change_s").val();
//    	var name=$("#change_m").val();
//        var tel=$("#change_p").val();
//        var myreg = /^1[2|3|4|5|6|7|9|8][0-9]\d{4,8}$/;
//        if(role==''){
//        	alertDialog('请输入身份！');
//            return false;
//        }
//        if(name==''){
//        	alertDialog('请输入姓名！');
//            return false;
//        }
//        if(!myreg.test(tel)||tel==''){
//            alertDialog('请输入有效的手机号码！');
//            return false;
//        }
//        return true;
//    }
</script>



