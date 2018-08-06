<div class="row heard-list-waper">

    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/myallinfo?openid=<?php echo $openid?>&sid=<?php echo $sid?>')">
        <i class="fa fa-reply"></i>  
      </div>    
    </div>
   
    <div class="col-xs-5 text-align-l">         
      选择学校       
    </div>

</div>


<input type="hidden" value="<?php echo $schname?>" id="schname"/>

<div class="row edit-user-row" id="proe" style="display:block">
  <div class="col-xs-4 edit-user-top">   
      <i class="fa fa-map-marker"></i> 省
      </div>
   <div class="col-xs-7 text-align-l select-option">

    <select id="pro" onchange="changePro(this.value)" class="form-control">
          <option value="">
             请选择
          </option>
          <?php foreach($list_pro as $v ){?>
<!--          <foreach name="list_pro" item="vo">-->
            <option value="<?php echo $v['code']?>">
               <?php echo $v['name']?>
            </option>
<!--          </foreach>-->
          <?php } ?>   
    </select>

  </div>  
</div>


<div class="row edit-user-row" id="citye" style="display:block">
  <div class="col-xs-4 edit-user-top">   
      <i class="fa fa-map-marker"></i> 市
  </div>
   <div class="col-xs-7 text-align-l select-option">

    <select class="form-control" id="city" onchange="changeCity(this.value)">
                       
    </select>

  </div>  
</div>

<div class="row edit-user-row" id="areae" style="display:block">
  <div class="col-xs-4 edit-user-top">   
      <i class="fa fa-map-marker"></i> 县区
  </div>
   <div class="col-xs-7 text-align-l select-option">

    <select class="form-control" id="area">
                                               
    </select>

  </div>  
</div>


<div class="row edit-user-row" id="typee" style="display:none;">
  <div class="col-xs-4 edit-user-top">   
      <i class="fa fa-star"></i> 学校类型
  </div>
   <div class="col-xs-8 text-align-l select-option">


    <select class="form-control" id="sctype" onchange="getschoo(this.value)">
                         
          <option value="">
             请选择
          </option>
          <?php foreach($sctype as $v ){?>
<!--          <foreach name="sctype" item="v">-->
            <option value="<?php echo $v['name']?>">
               <?php echo $v['name']?>
            </option>
<!--         </foreach>-->
          <?php }?>
    </select>

  </div>  
</div>





<div class="row edit-user-row" id="sch" style="display:none;">
  <div class="col-xs-4 edit-user-top">   
      <i class="fa fa-star"></i> 学校
  </div>


  <div class="col-xs-5 text-align-l select-option" id="school-select" style="display:block">
    <select class="form-control" id="school" >
                                     
    </select>
  </div>
  <div class="col-xs-5 text-align-l select-option" id="school-input" style="display:none">
      <input type="text" class="form-control" id="schoolname" value="<?php echo $opd?>"  />
  </div>

  <div class="col-xs-3 text-align-l" onclick="retweet()" style="padding-top:5px; font-size:20px;">
<!--      <i class="fa fa-pencil-square-o"></i>-->
  </div>

</div>








<div class="row register-user-container times-display">
           <div class="col-xs-12 register-user-center-margin">

               <div class="row examine-user-list">
                     <div class="col-xs-5 col-xs-offset-1" onclick="display_back()" style="display:none" id="display-back">
                        <span class="data-card-op">上一步</span>
                     </div>
                     <div class="col-xs-5 col-xs-offset-7" onclick="display_next()" style="display:block" id="display-next">
                        <span class="data-card-add">下一步</span>
                     </div>                     
                     <div class="col-xs-5 col-xs-offset-1"  onclick="addOneschool()" style="display:none" id="display-end">
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
          如果您是校长，请选择您的学校信息。
          </div>
        </div>
 </div>


 <script>
 $(function(){
  var schname=$("#schname").val();
  if(schname!="")
  {
  var html="<option value="+schname+">"+schname+"</option>";
 $("#school").html(html);
 getclass();
    $("#proe").attr("style","display:none");
    $("#citye").attr("style","display:none");
    $("#areae").attr("style","display:none");
    $("#sch").attr("style","display:block");
    $("#clas").attr("style","display:block");
    $("#classna").attr("style","display:block");
      $("#display-back").attr("style","display:none");
    $("#display-next").attr("style","display:none");
    $("#display-end").attr("style","display:block");
      }
    });  
   function changePro(pro){
    
    var url= $("#path").val() + "/information/getcitybyprovince";
    if(pro!="")
    {

      $("#city").html("<option value=''>数据加载中...</option>");
      $("#area").html("<option value=''>请选择</option>");
      $("#type").html("<option value=''>请选择</option>");
      $("#school").html("<option value=''>请选择</option>");
      $("#class").html("<option value=''>请选择</option>");
      var para={code:pro};
      doGetReturnRes(url,para,function(data){
        var htmls="<option value=''>请选择</option>"

        for (var i = 0; i < data.length; i++) {
          htmls = htmls + "<option value="+data[i].code+">"+data[i].name+"</option>";
        }
        $("#city").html(htmls);
      });
    }
    else
    {
      $("#city").html("<option value=''>请选择</option>");
      $("#area").html("<option value=''>请选择</option>");
    }
   
  }

    function changeCity(city){
    
    var url=$("#path").val() + "/information/getcountybycity";
    if(city!="")
    {

      $("#area").html("<option value=''>数据加载中...</option>");
      $("#type").html("<option value=''>请选择</option>");
      $("#school").html("<option value=''>请选择</option>");
      $("#class").html("<option value=''>请选择</option>");
    var para={code:city};
    doGetReturnRes(url,para,function(data){
      var htmls="<option value=''>请选择</option>"
      for (var i = 0; i < data.length; i++) {
        htmls = htmls + "<option value="+data[i].name+">"+data[i].name+"</option>";
      }
      $("#area").html(htmls);
    });

    }
    else
    {
       $("#area").html("<option value=''>请选择</option>");
    }
  }


function gettype(area){
      
    var url= $("#path").val() + "/information/gettype";
    if(area!="")
    {
      $("#type").html("<option value=''>数据加载中...</option>");
      $("#school").html("<option value=''>请选择</option>");
      $("#class").html("<option value=''>请选择</option>");

    var para={code:area};
    doGetReturnRes(url,para,function(data){
      var htmls="<option value=''>请选择</option>"
      for (var i = 0; i < data.length; i++) {
        htmls = htmls + "<option value="+data[i].schtype+">"+data[i].schtype+"</option>";
      }
      $("#type").html(htmls);
    });
  }
  }


    function getschoo(type){
      
    var url=$("#path").val() + "/information/getsch";
    var area=$("#area").val();
    var county=$("#city").val();
    var pro=$("#pro").val();
    var para={code:type,area:area,county:county,pro:pro};
    if(type!="")
    {
      $("#school").html("<option value=''>数据加载中...</option>");
      $("#class").html("<option value=''>请选择</option>");
       doGetReturnRes(url,para,function(data){
      if(data==null)
      {
        $("#school").html("<option value=''>暂无学校数据</option>");
        alertDialog("暂无该地区学校数据，请点击右侧，然后填写学校名称");
      }
      else
      {
         var htmls="<option value=''>请选择</option>"
      for (var i = 0; i < data.length; i++) {
        htmls = htmls + "<option value="+data[i].name+">"+data[i].name+"</option>";
      }
      $("#school").html(htmls);
      }
    
      });

    }
   
  }

  function ok_school(){
    $("#school2").val($('#school').val());
    getclass();
  }




  function display_next(){    // 下一步按钮

  if ($("#proe").attr("style") == "display:block") {
    if($("#pro").val()=="")
  {
    alertDialog('请选择学校所在的省份');
   
   
  }else  if($("#city").val()=="")
  {
    alertDialog('请选择学校所在的市');
   
   
  }else  if($("#area").val()=="")
  {
    alertDialog('请选择学校所在的县区');
   
   
  }
  else
  {
    $("#proe").attr("style","display:none");
    $("#citye").attr("style","display:none");
    $("#areae").attr("style","display:none");
    $("#typee").attr("style","display:block");
    $("#sch").attr("style","display:block");
    $("#clas").attr("style","display:block");
    $("#classna").attr("style","display:block");
    
    $("#display-back").attr("style","display:block");
    $("#display-next").attr("style","display:none");
    $("#display-end").attr("style","display:block");
  }
    

  }
 
}



function display_back(){    // 返回上一步按钮

 if ($("#typee").attr("style") == "display:block") {
    $("#typee").attr("style","display:none");
    $("#sch").attr("style","display:none");
    $("#clas").attr("style","display:none");
    $("#classna").attr("style","display:none");
    $("#proe").attr("style","display:block");
    $("#citye").attr("style","display:block");
    $("#areae").attr("style","display:block");
    $("#display-end").attr("style","display:none");
    $("#display-back").attr("style","display:none");
    $("#display-next").attr("style","display:block");
    $("#display-next").attr("class","col-xs-5 col-xs-offset-7");
  

  }
}


function retweet(){
  if ($("#school-select").attr("style") == "display:block") {
      $("#school-select").attr("style","display:none");
      $("#school-input").attr("style","display:block");
  }else{
      $("#school-input").attr("style","display:none");
      $("#school-select").attr("style","display:block");
  }
}

</script>
