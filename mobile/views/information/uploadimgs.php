<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/myallinfo?openid=<?php echo \yii::$app->view->params['openid']?>&sid=<?php echo $sid?>')">
        <i class="fa fa-reply"></i>  
      </div>    
    </div>
   
    <div class="col-xs-5 text-align-l">         
      1.填写班级信息       
    </div>

</div>


<input type="hidden" value="<?php echo $schname?>" class="<?php echo $schid?>" id="schname"/>



<!-- 第一页 -->
<div class="page" id="page-one">
    <div class="row edit-user-row" id="proe">
      <div class="col-xs-4 edit-user-top">   
          <i class="fa fa-map-marker"></i> 省
          </div>
       <div class="col-xs-7 text-align-l select-option">

        <select id="pro" onchange="changePro(this.value)" class="form-control">
              <option value="">
                 请选择
              </option>
<!--              <foreach name="list_pro" item="vo">-->
     <?php foreach($list_pro as $v){?>       
                <option value="<?php echo $v['pro']?>">
                   <?php echo $v['pro']?>
                </option>
<!--              </foreach>-->
     <?php }?> 
        </select>

      </div>  
    </div>
    <div class="row edit-user-row" id="citye">
      <div class="col-xs-4 edit-user-top">   
          <i class="fa fa-map-marker"></i> 市
      </div>
       <div class="col-xs-7 text-align-l select-option">

        <select class="form-control" id="city" onchange="changeCity(this.value)">

        </select>

      </div>  
    </div>

    <div class="row edit-user-row" id="areae">
      <div class="col-xs-4 edit-user-top">   
          <i class="fa fa-map-marker"></i> 县区
      </div>
       <div class="col-xs-7 text-align-l select-option">

        <select class="form-control" id="area" onchange="getSchoo(this.value)">

        </select>

      </div>  
    </div>
    <!-- <div class="row edit-user-row" id="typee">
      <div class="col-xs-4 edit-user-top">   
          <i class="fa fa-star"></i> 学校类型
      </div>
       <div class="col-xs-7 text-align-l select-option">

        <select class="form-control" onchange="getSchoo(this.value)" id="type">

        </select>

      </div>  
    </div>  -->   
</div>

<!-- 第二页 -->
<div class="page-off" id="page-two">
    <div class="row edit-user-row" id="sch">
      <div class="col-xs-4 edit-user-top">   
          <i class="fa fa-star"></i> 学校
      </div>
       <div class="col-xs-7 text-align-l select-option">

        <select class="form-control" id="school" onchange="getclass(1)">

        </select>

      </div>  
    </div>
    <div class="row edit-user-row" id="clas">
      <div class="col-xs-4 edit-user-top">   
          <i class="fa fa-star"></i> 班级 
      </div>
       <div class="col-xs-7 text-align-l select-option">

        <select id="class">
       <option value="">
        请填写学校信息
       </option>
       </select>
          <div name="community" class="form-control" style="z-index:1000;position:fixed;display:none;width:auto;background:#FFF;height:auto;position:absolute;" id="autoClass" onmousemove="removeClassEve()" onmouseout="addClassEve()"></div>

      </div>  
    </div>
   
</div>
<div class="row register-user-container times-display">
           <div class="col-xs-12 register-user-center-margin">

               <div class="row examine-user-list">
                     <div class="col-xs-5 col-xs-offset-1 back-page off">
                        <span class="data-card-op">上一步</span>
                     </div>
                     <div class="col-xs-5 col-xs-offset-1 next-page on">
                        <span class="data-card-add">下一步</span>
                     </div>                     
                     <div class="col-xs-5 col-xs-offset-1 save-page off" onclick="saveclassinfo()">
                        <span class="data-card-add">保存</span>
                     </div>
               </div>

           </div>
</div>

 <div class="row help-row">
        <div class="col-xs-12">
          <span class="badge">
            帮助
          </span>
          <hr>
          <div class="help-row-text">
          1.绑定上传班级信息 2.上传图片
          </div>
        </div>
 </div>
 <script type="text/javascript">
 $(function(){
     var schname=$("#schname").val();
     if(schname!=""){
        var html="<option value="+$("#schname").attr("class")+">"+schname+"</option>";
        $("#school").html(html);
        getclass(1);
         
        $("#page-one").addClass("page-off").removeClass("page");
        $("#page-two").addClass("page").removeClass("page-off");
         
        $(".next-page").addClass("off").removeClass("on");          
        $(".back-page").addClass("on").removeClass("off");
        $(".save-page").addClass("on").removeClass("off");
   
      }
});  

   function changePro(pro){
      $("#city").html("<option value=''>数据加载中...</option>");
      $("#area").html("<option value=''>请选择</option>");
      $("#type").html("<option value=''>请选择</option>");
      $("#school").html("<option value=''>请选择</option>");
      $("#class").html("<option value=''>请选择</option>");
    var url= $("#path").val() + "/information/getcity";
    var para={code:pro};
    doGetReturnRes(url,para,function(data){
      var htmls="<option value=''>请选择</option>"

      for (var i = 0; i < data.length; i++) {
        htmls = htmls + "<option value="+data[i].city+">"+data[i].city+"</option>";
      }
      $("#city").html(htmls);
    });
  }

    function changeCity(city){
      $("#area").html("<option value=''>数据加载中...</option>");
      $("#type").html("<option value=''>请选择</option>");
      $("#school").html("<option value=''>请选择</option>");
      $("#class").html("<option value=''>请选择</option>");
    var url= $("#path").val() + "/information/getarea";
    var para={code:city};
    doGetReturnRes(url,para,function(data){
      var htmls="<option value=''>请选择</option>"
      for (var i = 0; i < data.length; i++) {
        htmls = htmls + "<option value="+data[i].county+">"+data[i].county+"</option>";
      }
      $("#area").html(htmls);
    });
  }

function gettype(area){
      $("#type").html("<option value=''>数据加载中...</option>");
      $("#school").html("<option value=''>请选择</option>");
      $("#class").html("<option value=''>请选择</option>");
    var url= $("#path").val() + "/information/gettype";
    var para={code:area};
    doGetReturnRes(url,para,function(data){
      var htmls="<option value=''>请选择</option>"
      for (var i = 0; i < data.length; i++) {
        htmls = htmls + "<option value="+data[i].schtype+">"+data[i].schtype+"</option>";
      }
      $("#type").html(htmls);
    });
  }
    function getSchoo(area){
      $("#school").html("<option value=''>数据加载中...</option>");
      $("#class").html("<option value=''>请选择</option>");
    var url= $("#path").val() + "/information/getschoo";
    var area=$("#area").val();
    var para={area:area};
    doGetReturnRes(url,para,function(data){
      var htmls="<option value=''>请选择</option>";
      for (var i = 0; i < data.length; i++) {
        htmls = htmls + "<option value="+data[i].id+">"+data[i].name+"</option>";
      }
      $("#school").html(htmls);
    });
  }

  function ok_school(){
    $("#school2").val($('#school').val());
    getclass(1);
  }
// 翻页JS
$(".next-page").click(function(){
  // 继续 下一页 按钮
  if ($(".page-off").last().prev(".page").length == 1 ) {
    $(".page").first().next(".page-off").addClass("page").removeClass("page-off");   //改变page元素后的page-off元素，为显示状态(page状态)
      $(".page").first().addClass("page-off").removeClass("page");
      $(".back-page").addClass("on").removeClass("off");
      $(".next-page").addClass("off").removeClass("on");      // 如果最后一个page-off前的元素为page，那么把执行完继续后(先执行翻页)，然后把“继续”改为“完成”
      $(".save-page").addClass("on").removeClass("off");
    }else{
    $(".page").first().next(".page-off").addClass("page").removeClass("page-off");  //否则一直可以翻页
      $(".page").first().addClass("page-off").removeClass("page");
      $(".back-page").addClass("on").removeClass("off");
    };
});

$(".back-page").click(function(){
  // 返回 上一页 按钮
  if ($(".page-off").first().next(".page").length == 1 ) {
    $(".back-page").addClass("off").removeClass("on");
      $(".save-page").addClass("off").removeClass("on");
      $(".next-page").addClass("on").removeClass("off");          
      $(".page").prev(".page-off").addClass("page").removeClass("page-off");
      $(".page").last().addClass("page-off").removeClass("page");
    }else if($(".page-off").last().next(".page").length == 1 ) {
      $(".save-page").addClass("off").removeClass("on");
      $(".next-page").addClass("on").removeClass("off");
      $(".page").prev(".page-off").addClass("page").removeClass("page-off");
      $(".page").last().addClass("page-off").removeClass("page");
    }else{
      $(".page").prev(".page-off").addClass("page").removeClass("page-off");
      $(".page").last().addClass("page-off").removeClass("page");
    };
}); 
// 翻页JS END
</script>

