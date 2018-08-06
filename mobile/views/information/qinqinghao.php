<div class="row heard-list-waper">
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
        <div onclick="backto('<?php echo URL_PATH?>/information/onechild?stuid=<?php echo $stuid?>&openid=<?php echo $openid?>&_=<?php echo $time?>')">
        <i class="fa fa-reply"></i>
      </div>    
    </div>
    <div class="col-xs-5 text-align-l">         
      添加亲情号
    </div>
    <div class="col-xs-3">         
        <span class="add-class" id="add-btn">
                               增加  
        </span>   
    </div> 
</div>

<div id="qinqinghao">
   <?php foreach( $arr as $v){?> 
<!--    <volist name="arr" id="vo">-->
<div class="row edit-user-row xz-counts aaaaa">
    <div class="col-xs-8" id="rowid">
        <span class="shenfen"><?php echo $v['Relation']?></span>-
        <span class="xinming"><?php echo $v['name']?></span>-
        <span class="haoma"><?php echo $v['tel']?></span>
    </div>
    <div id="del" class="col-xs-4 btn-group-sm" style="padding: 0;">
        <button  class="btn btn-info edit" id="<?php echo $v['id']?>">修改</button>
        <?php if($v['isqqtel']==1){?>
        <button  class="btn btn-danger delete" data-id="<?php echo $v['id']?>">删除</button>
        <?php }?>
    </div>
</div>
   <?php } ?> 
<!--    </volist>-->
</div>
<!--增加亲情号-->
<form id="add-model" style="display: none;" action="<?php echo URL_PATH?>/information/addqqh?stuid=<?php echo $stuid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>" method = "post">
<div class="row edit-user-row">
  <div class="col-xs-10" style="margin: 0;">
    称呼：
    <input id="add_s" name="shenfen" style="width: 80%;display: inline-block;" class="form-control text" type="text">
  </div>
</div>  
<div class="row edit-user-row">  
  <div class="col-xs-10" style="margin: 0;">
    姓名：
    <input id="add_m" name="xingming" style="width: 80%;display: inline-block;" class="form-control text"type="text">
  </div>
</div>
<div class="row edit-user-row">
  <div class="col-xs-10" style="margin: 0;">
    电话：
    <input id="add_p" name="dianhua" style="width: 80%;display: inline-block;" class="form-control text" type="text">
  </div>
</div>
<div class="row edit-user-row">
  <div class="col-xs-6"></div>
  <div class="col-xs-3">
      <input type="button" name="submit" id="btn3" value="提交" class="btn btn-success">
  </div>
  <div class="col-xs-3">
    <div class="btn btn-danger quxiao">取消</div>
  </div>
</div>
</form>
<!--修改亲情号-->
<form id="change-model" style="display: none;" action="<?php echo URL_PATH?>/information/updateqqh?stuid=<?php echo $stuid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>" method="post">
<!--<form id="change-model" style="display: none;">-->
<div class="row edit-user-row">
  <div class="col-xs-10" style="margin: 0;" >
      <!--<input  name="id" value="{$id}" style="width: 80%;display: inline-block;" class="form-control text" type="hidden">-->
      <input  name="id" id="change_id" style="width: 80%;display: inline-block;" class="form-control text" type="hidden">
      称呼：
    <input name="shenfen"  id="change_s" style="width: 80%;display: inline-block;" class="form-control text" type="text">
  </div>
</div>
<div class="row edit-user-row">
  <div class="col-xs-10" style="margin: 0;">
    姓名：
    <input name="xingming" id="change_m" style="width: 80%;display: inline-block;" class="form-control text"type="text">
  </div>
</div>
<div class="row edit-user-row">
  <div class="col-xs-10" style="margin: 0;">
    电话：
    <input name="dianhua" id="change_p" style="width: 80%;display: inline-block;" class="form-control text" type="text">
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
<input type="hidden" id="schoolid" value="<?php echo \yii::$app->view->params['sid']?>">

<link href="/css/DateTimePicker.css" rel="stylesheet" type="text/css">
<script src="/js/DateTimePicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $('.aaaaa').each(function(){
            $(this).click(function(){
                $('#change_s').val('');
                $('#change_m').val('');
                $('#change_p').val('');
                var textid = $(this).find('.edit').attr("id");
                var textsff = $(this).find('.shenfen').text();
                var $textxm = $(this).find('.xinming').text();
                var $texthm =  $(this).find('.haoma').text();
                $('#change_id').val(textid);
                $('#change_s').val(textsff);
                $('#change_m').val($textxm);
                $('#change_p').val($texthm)
            })
        })
        $("#qinqinghao").on("click",".edit",function(e){
            var dividx = $("#rowid span ").text();
            $("#qinqinghao").hide();
            $("#change-model").show();
        })

        $("#qinqinghao").on("click",".delete",function(){
            if(confirm("确认删除？")){
            var row_id = $(this).data("id");
            var _this = $(this);
             $.getJSON("<?php echo URL_PATH?>/information/delqqh?id=" + row_id + ".html").done(function(data){
                 if(data.status == 0){
                     alert("该亲情号已绑定学生，不能删除");
                 }else if(data.status == 1)
                {
                    _this.parents(".row").remove();
                    alert("删除成功");
                }else{
                    alert("删除失败");
                }
             })
         }
    })
        $("#add-btn").click(function(){
                var schoolid=$("#schoolid").val();
                if(schoolid=='56757' || schoolid=='56670'){
                   if($(".xz-counts").length>=3){
        		alertDialog("最多只能添加3个！")
                    }else{
                            $("#qinqinghao").hide();
                            $("#add-model").show();
                    } 
                }else{
                    if($(".xz-counts").length>=5){
        		alertDialog("最多只能添加5个！")
                    }else{
                            $("#qinqinghao").hide();
                            $("#add-model").show();
                    } 
                }      	
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
      	var s=$(".shenfen").eq(i).html();
      	var m=$(".xinming").eq(i).html();
      	var p=$(".haoma").eq(i).html();
      	$("#change_s").val(s);
      	$("#change_m").val(m);
      	$("#change_p").val(p);
    }
    function B(k){
    	if(confirm("确认删除？")){
    		alert(k);
    	}
    }

    $("#btn2").on("click",function(){
        var role=$("#change_s").val();
        var name=$("#change_m").val();
        var tel=$("#change_p").val();
        var myreg = /^1[2|3|4|5|6|7|9|8][0-9]\d{4,8}$/;
        var myreg = /^1[3|4|5|7|8][0-9]{9}$/;
        if(role==''){
            alertDialog('请输入称呼！');
            return false;
        }
        if(name==''){
            alertDialog('请输入姓名！');
            return false;
        }
        if(!myreg.test(tel)||tel==''){
            alertDialog('请输入有效的手机号码！');
            return false;
        }
        var url ="<?php echo URL_PATH?>/information/updateqqh?stuid=<?php echo $stuid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>";
        var Relation = $("#change_s").val();
        var name = $("#change_m").val();
        var tel = $("#change_p").val();
        var id = $("#change_id").val();
        var para = {"Relation":Relation,"name":name,"tel":tel,"id":id};
        $.getJSON(url,para,function(data){
            if (data == 0){
                alert("修改成功");
                backto('<?php echo URL_PATH?>/information/qinqinghao?id=<?php echo $stuid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>&_=<?php echo $time?>');
            }else {
                alert("修改失败");
            }
        })
    })
    $("#btn3").on("click",function(){
        var role=$("#add_s").val();
        var name=$("#add_m").val();
        var tel=$("#add_p").val();
//        var myreg = /^1[2|3|4|5|6|7|9|8][0-9]\d{4,8}$/;
        var myreg = /^1[3|4|5|7|8][0-9]{9}$/;
        if(role==''){
            alertDialog('请输入称呼！');
            return false;
        }
        if(name==''){
            alertDialog('请输入姓名！');
            return false;
        }
        if(!myreg.test(tel)||tel==''){
            alertDialog('请输入有效的手机号码！');
            return false;
        }
        var url ="<?php echo URL_PATH?>/information/addqqh?stuid=<?php echo $stuid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>";
        var Relation = $("#add_s").val();
        var name = $("#add_m").val();
        var tel = $("#add_p").val();
        var para = {"Relation":Relation,"name":name,"tel":tel};
        $.getJSON(url,para,function(data){
            if (data == 0){
                alert("添加成功");
                backto('<?php echo URL_PATH?>/information/qinqinghao?id=<?php echo $stuid?>&openid=<?php echo $openid?>&sid=<?php echo $sid?>&_=<?php echo $time?>');
            }else {
                alert("添加失败");
            }
        })
    })
    function checkAddInfo(){
        var role=$("#add_s").val();
        var name=$("#add_m").val();
        var tel=$("#add_p").val();
        var myreg = /^1[2|3|4|5|6|7|9|8][0-9]\d{4,8}$/;
        if(role==''){
            alertDialog('请输入称呼！');
            return false;
        }
        if(name==''){
            alertDialog('请输入姓名！');
            return false;
        }
        if(!myreg.test(tel)||tel==''){
            alertDialog('请输入有效的手机号码！');
            return false;
        }
    }
    function checkChangeInfo(){
    	var role=$("#change_s").val();
    	var name=$("#change_m").val();
        var tel=$("#change_p").val();
        var myreg = /^1[2|3|4|5|6|7|9|8][0-9]\d{4,8}$/;
        if(role==''){
        	alertDialog('请输入称呼！');
            return false;
        }
        if(name==''){
        	alertDialog('请输入姓名！');
            return false;
        }
        if(!myreg.test(tel)||tel==''){
            alertDialog('请输入有效的手机号码！');
            return false;
        }
        return true;
    }
</script>

