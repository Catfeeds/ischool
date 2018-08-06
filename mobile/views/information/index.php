<?php
/* @var $this yii\web\View */

$this->title = '正梵智慧校园';
?>
<main class="cd-main-content container-fluid" id="mycontainer">
</main>
<input type="hidden" id="openid" value="<?php echo \yii::$app->view->params['openid']?>" />
<input type="hidden" id="sid" value="<?php echo \yii::$app->view->params['sid']?>" />
<input type="hidden" id="path" value="<?php echo URL_PATH?>">
<div id="footer_na" style="display:block;">
<?php echo $this->render('../layouts/footer')?>
<div>
<script type="text/javascript">
$(function(){
  var path=$("#path").val();
  var openid=$("#openid").val();
  var sid =$("#sid").val();
  var url =path+"/information/myallinfo?openid="+openid+"&sid="+sid;
  loadHtmlByUrl(url);
});

function loadAllClass(){
  var path=$("#path").val();
  var openid=$("#openid").val();
  var sid =$("#sid").val();
  var url =path+"/information/myallclass?openid="+openid+"&sid="+sid;
   loadHtmlByUrl(url);
}

function loadAllStu(){
  var path=$("#path").val();
  var openid=$("#openid").val();
  var sid =$("#sid").val();
  var url =path+"/information/myallchild?openid="+openid+"&sid="+sid;
   loadHtmlByUrl(url);
}

function loadAllStudent(){
  var path=$("#path").val();
  var openid=$("#openid").val();
  var sid =$("#sid").val();
  var cid =$("#cid").val();
 var tcid =$("#tcid").val();
  var url =path+"/information/allstudent?openid="+openid+"&sid="+sid+"&cid="+cid+"&tcid="+tcid;
   loadHtmlByUrl(url);
}

function backto(url){
  $('#footer_na').css('display','block'); 
  loadHtmlByUrl(url);
}

function forwardTo(url){
  loadHtmlByUrl(url);
}

function refush(url){
  window.location.href = url;
}
//**********************新增资料学校班级的自动提醒*******************************//
function hideSchool(){

    $("#autoschool").hide();
}
function school(){

   getclass();
    $("#autoschool").hide();
}
function showSchool(){
  $("#autoschool").show();
}

function addSchoolEve(){
  $("#autoschool").hide();
  $("input[name='school']").attr("onblur","hideSchool()");
}
function addSchoolEv(){
  $("#autoschool").hide();
  $("input[name='school']").attr("onblur","getclass()");
}
function removeSchoolEve(){
  $("#autoschool").show();
  $("input[name='school']").attr("onblur","");
}

 function hideClass(){
    $("#autoClass").hide();
  }

  function showClass(){
    $("#autoClass").show();
  }

  function addClassEve(){
    $("#autoClass").hide();
    $("input[name='classname1']").attr("onblur","hideClass()");
  }

  function removeClassEve(){
    $("#autoClass").show();
    $("input[name='classname1']").attr("onblur","");
  }

  function autoGetSchool(){
    var path=$("#path").val();
      $.getJSON(path+"/information/getSchool",{school:$('#school').val()},function(data){
        if(data!=null){
          var i=0;
          var htmls="<ul>";
          for(i;i<data.length;i++){
            htmls=htmls+"<li style='margin:10px 0 10px 0'>"+ data[i].name+"</li>";
          }
          htmls=htmls+"</ul>";
          var school = $("#autoschool");
          $(school).html(htmls).show();
          $(school).find("li").on("click",function(){
             $("input[name='school']").val($(this).text()).focus();
             $(school).hide();
          });
          }
      })
  }

  function autoGetClass(){
    var path=$("#path").val();
   $.getJSON(path+"/information/getClass",{school:$('#school').val(),classname:$('#classname1').val()},function(data){
    if(data!=null){
        var i=0;
        var htmls="<ul>";
        for(i;i<data.length;i++){
          htmls=htmls+"<li style='margin:10px 0 10px 0'>"+ data[i].name+"</li>";
          //alert(data[i].name);
        }
        htmls=htmls+"</ul>";
         var classname = $("#autoClass");
         $(classname).html(htmls).show();
         $(classname).find("li").on("click",function(){
         $("input[name='classname1']").val($(this).text()).focus();
         $(classname).hide();
      });
       }
      })
  }

//********************************抓取所有班级************************************//
function chufa(){
  var reason=$("#reason").val();
  alert(reason)
}

//家长申请请假
function shenqing(id,name,type){
    var path=$("#path").val();
    var url=path+"/information/leave";
    var openid=$("#openid").val();
    //请假原因
    var reason=$("#reason").val();
    var id =id;
    var type=type;
    var stuname =name;
    var shenfen="jz";
    var reason=reason;
    var con="one";
    if(type=='0')
    {
      con="您确定要取消申请吗?";
    }
    else
    {
      con="您确定要申请请假吗?";
    }

    var to_url =path+"/information/oneChild&openid="+openid+"&stuid="+id+"/stuname/"+stuname;

    var d = dialog({
      title: '提示',
      content: con,
      okValue: '确定',

      ok: function () {
         $.post(url,{id:id,type:type,shenfen:shenfen,reason:reason},
         function (data){
           if(data=='success'){
             loadHtmlByUrl(to_url);
           }
         });
      },

      cancelValue: '取消',
      cancel: function () {
      }

   });

   d.showModal();
}

  function getclass(flag){
    var path=$("#path").val();
  var html=" <option value=''>数据加载中...</option>";
   $.post(path+"/information/getallclass",{school:$('#school').val(),flag:flag},function(data){
        if(data==null)
        {
          var html=" <option value=''>请选择</option>";
        }
        else
        {
             for(var i=0;i<data.length;i++)
          {
            html+="<option value='"+data[i]["id"]+"'>"+data[i]["name"]+"</option>";
          }

        }
         $('#class').html(html);

    })
  }

//********************************自动提醒结束************************************//
function addOneChild(){
  if(checkChildInfo()){
    var path=$("#path").val();
    var sid=$.trim($("#school").val());
    var school=$.trim($("#school option:selected").text());
    var cid= $.trim($("#class").val());
    var classname = $.trim($("#class option:selected").text());
    var student=removeAllSpace($("#student").val());
    var openid=$("#openid").val();
    var data={openid:openid,sid:sid,school:school,cid:cid,classname:classname,student:student};
    var url=path+"/information/doaddchild";
    var to_url=path+"/information/myallchild?openid="+openid;
    var options = {para:data,ele:$("#add_span"),sub_url:url,to_url:to_url,urltype:0,status:[{code:0,content:'学校或班级填写错误'},{code:1,content:'学生信息尚未导入，请关闭当前页面，然后点击【我的服务】-》【人工客服】联系人工客服'},{code:2,content:'保存失败'},{code:3,content:'您已关注过该学生'},{code:4,content:'请前往【瑞贝卡正祥公司】公众号->餐卡充值中绑定学生。'}]};
    sub_dialog(options);

  }
}
function saveclassinfo(){
    if(checkImginfo()){
        var path=$("#path").val();
        var sid=$.trim($("#school").val());
        var cid= $.trim($("#class").val());
        var classname = $.trim($("#class option:selected").text());
        var school=$.trim($("#school option:selected").text());
        // var student=removeAllSpace($("#student").val());
        // var data={sid:sid,cid:cid,classname:classname,school:school};
        var url =path+"/information/saveclassinfo?sid="+sid+"&cid="+cid+"&classname="+classname+"&school="+school;
        loadHtmlByUrl(url);
        // var to_url=path+"/information/saveclassinfo?sid="+sid+"&cid="+cid+"&student="+student+"&classname="+classname+"&school="+school;
        // var options = {para:data,ele:$("#add_span"),sub_url:url,to_url:to_url,urltype:0,status:[{code:1,content:'学生信息尚未导入'}]};
        // sub_dialog(options); 
    }
}
function removeAllSpace(str) {
  return str.replace(/\s+/g, "");
}
function addOneClass(){
  if(checkClassInfo()){
    var path=$("#path").val();
    var school =$.trim($("#school option:selected").text());
    //学校名称
    var sid=$.trim($("#school").val());
    //班级id
    var cid= $.trim($("#class").val());
    //班级名称
    var classname = $.trim($("#class option:selected").text());
    //角色
    var role=$.trim($("#role").val());
    //唯一表示
    var openid=$("#openid").val();
    //身份（必选项）
    var tea=$("#tea").val();
    //角色
    var rolel=$.trim($("#rolel").val());

    var data={rolel:rolel,tea:tea,openid:openid,sid:sid,school:school,cid:cid,classname:classname,role:role};
  var url=path+"/information/doaddclass";
    var to_url=path+"/information/myallclass?openid="+openid+"&sid="+sid;
    var options = {para:data,ele:$("#add_span"),sub_url:url,to_url:to_url,urltype:0,status:[{code:1,content:'学校不存在'},{code:2,content:'角色名重复，请勿重复申请同一角色'},{code:3,content:'该班已经有对应职位的老师，请关闭该页面，点击我的服务--》人工客服，进入我们的客服系统，我们将派专业人员为您处理。您也可直接拨打本产品的服务电话：0371-55030687，进行语音咨询'}]};
    sub_dialog(options);
  }
}
function checkImginfo(){
   if($.trim($("#school").val())==""){
        alertDialog("学校名称不能为空");
       return false;
    }
  if($.trim($("#class").val())==""){
      alertDialog("班级名称不能为空");
      return false;
   } 
   return true;
}
function checkChildInfo(){
  if($.trim($("#school").val())==""){
     alertDialog("学校名称不能为空");
    return false;
  }
  if($.trim($("#class").val())==""){
    alertDialog("班级名称不能为空");
    return false;
  }
  if($.trim($("#student").val())==""){
    alertDialog("学生姓名不能为空");
    return false;
  }
  return true;
}

function removeOneChild(stuid){
  var path=$("#path").val();
  var url=path+"/information/deleonechild";
  var stuid=stuid;
  var openid=$("#openid").val();
  var sid =$("#sid").val();
  var to_url =path+"/information/myallchild?openid="+openid+"&sid="+sid;

  var d = dialog({
    title: '提示',
    content: '您确定要取消关注吗?取消关注后将收不到学生的相关信息',
    okValue: '确定',

    ok: function () {
       $.post(url,{stuid:stuid,openid:openid},
       function (data){
         if(data=='success'){
             loadAllStu();
         }else if(data=='refuse'){
             alertDialog("您只绑定了一个孩子，无法取消关注！");
         }
       });
    },

    cancelValue: '取消',
    cancel: function () {
    }

 });

 d.showModal();
}


function removestudent(id){
  var path=$("#path").val();
  var url=path+"/information/deletestudent";

  var openid=$("#openid").val();
  var sid =$("#sid").val();
   var cid =$("#cid").val();
  var to_url =path+"/information/allstudent&openid="+openid+"&sid="+sid+"&cid="+cid;

  var d = dialog({
    title: '提示',
    content: '您确定要删除该学生信息吗?',
    okValue: '确定',

    ok: function () {
       $.post(url,{id:id,openid:openid,cid:cid},
       function (data){
         if(data=='success'){
           loadAllStudent();
         }else if(data == 'fail'){
           alert("无权限删除该学生");
         }
       });
    },

    cancelValue: '取消',
    cancel: function () {
    }

 });

 d.showModal();
}
//请假
function leave(id){
  var path=$("#path").val();
  var openid=$("#openid").val();
  var sid =$("#sid").val();
  var cid =$("#cid").val();
  var tcid = $("#tcid").val();
  var stuid=id;
  var url =path+"/information/onestuleave?openid="+openid+"&sid="+sid+"&cid="+cid+"&tcid="+tcid+"&id="+stuid;
  loadHtmlByUrl(url);
}
//销假
function cenleave(ths){
  var path=$("#path").val();
  var url=path+"/information/deleteoneleave";
  var id_epc = $(ths).attr("value");
  var sid =$("#sid").val();


  var d = dialog({
    title: '提示',
    content: '您确定要销假吗?',
    okValue: '确定',

    ok: function () {
       $.post(url,{id:id_epc,sid:sid},
       function (data){
         if(data==0){
           loadleave();
         }
       });
    },

    cancelValue: '取消',
    cancel: function () {
    }

 });

 d.showModal();
}
function loadleave()
{
  var path=$("#path").val();
  var openid=$("#openid").val();
  var sid =$("#sid").val();
  var cid =$("#cid").val();
 var tcid =$("#tcid").val();
  var url =path+"/information/leavestu?openid="+openid+"&sid="+sid+"&cid="+cid+"&tcid="+tcid;
   loadHtmlByUrl(url);
}
function access(id){
  var path=$("#path").val();
 var url=path+"/information/access";
  var stuid=stuid;


  var d = dialog({
    title: '提示',
    content: '您确定要通过该条信息吗?',
    okValue: '确定',

    ok: function () {
       $.post(url,{id:id},
       function (data){
         if(data=='success'){
           loaddaishenhe();
         }
       });
    },

    cancelValue: '取消',
    cancel: function () {
    }

 });

 d.showModal();
}
function refuse(id){
  var path=$("#path").val();
 var url=path+"/information/refuse";
  var stuid=stuid;


  var d = dialog({
    title: '提示',
    content: '您确定要拒绝该条信息吗?',
    okValue: '确定',

    ok: function () {
       $.post(url,{id:id},
       function (data){
         if(data=='success'){
           loaddaishenhe();
         }
       });
    },

    cancelValue: '取消',
    cancel: function () {
    }

 });

 d.showModal();
}
function loaddaishenhe(){
 var openid=$("#openid").val();
  var sid =$("#sid").val();
  var cid =$("#cid").val();
  var tcid =$("#tcid").val();
  var path=$("#path").val();
  var url =path+"/information/daishenhestu?openid="+openid+"&sid="+sid+"&cid="+cid+"&tcid="+tcid;
   loadHtmlByUrl(url);;
}
function addstudent(){
  if(checkstudent()){
    var name=$.trim($("#name").val());
    var cid=$.trim($("#cid").val());
    var openid=$("#openid").val();
    var sid=$("#sid").val();
    var stuno=$("#stuno").val();
     var tcid=$("#tcid").val();
    var address=$("#address").val();
    var path=$("#path").val();
    $.post(path+'/information/checkstuno', {stuno:stuno,cid:cid}, function(data) {
      if(data=="success")
      {
      var type="add";
      var data={stuno:stuno,openid:openid,name:name,cid:cid,sid:sid,address:address,type:type};
      var url=path+"/information/doaddstudent";
      var to_url=path+"/information/allstudent?openid="+openid+"&cid="+cid+"&sid="+sid+"&tcid="+tcid;
      var options = {para:data,ele:$("#add_span"),sub_url:url,to_url:to_url,urltype:0,status:[{code:1,content:'学号重复'}]};
      sub_dialog(options);

      }
      else
      {
      var type="save";
      var data={stuno:stuno,openid:openid,name:name,cid:cid,sid:sid,address:address,type:type};
      var url=path+"/information/doaddstudent";
      var to_url=path+"/information/allstudent?openid="+openid+"&cid="+cid+"&sid="+sid+"&tcid="+tcid;
      var options = {para:data,ele:$("#add_span"),sub_url:url,to_url:to_url,urltype:0,status:[{code:1,content:'学号重复'}]};

      var d = dialog({
        title: '提示',
        content: '当前学号已存在，请重新输入学号?',
        okValue: '确定',

//        ok: function () {
//            sub_dialog(options);
//        },

        cancelValue: '确定',
        cancel: function () {
        }

      });

     d.showModal();
      }

    });



  }
}

function checkstudent(){
  if($.trim($("#name").val())==""){
    alertDialog("学生姓名不能为空");
    $("#name").focus();
    return false;
  }
  if($.trim($("#stuno").val())==""){
    alertDialog("学号不能为空");
    $("#stuno").focus();
    return false;
  }

  return true;
}


function checkClassInfo(){

  if($("#tea").val()=="")
  {
    alertDialog('请选择您的身份！');
    $("#tea").focus();
    return false;
  }
  if($("#tea").val()==1)
  {

    if($.trim($("#school").val())==""){

    alertDialog('请选择您的学校！');
    $("#school").focus();
    return false;
    }

    if($.trim($("#class").val())==""){

    alertDialog('请选择您的班级！');
    $("#class").focus();
    return false;
    }

    if($.trim($("#role").val())==""){

    alertDialog('请选择您的角色！');
    $("#role").focus();
    return false;
    }

  }
  else
  {
     if($.trim($("#school").val())==""){

    alertDialog('请选择您的学校！');
    $("#school").focus();
    return false;
    }

    if($.trim($("#rolel").val())==""){

    alertDialog('请选择您的角色！');
    $("#rolel").focus();
    return false;
    }
  }
  return true;
}

/*  myallinfo页面用于保存编辑用户信息 */
function saveUserInfo(){
  if(checkUserInfo()){
    var username= $.trim($("#username").val());
    var tel=$.trim($("#tel").val());
    var openid=$("#openid").val();
    var path=$("#path").val();
    var data={openid:openid,username:username,tel:tel};
    var url=path+"/information/saveuser";
    var to_url=path+"/information/myallinfo?openid="+openid;
    var options = {para:data,ele:$("#add_span"),sub_url:url,to_url:to_url,urltype:0,status:[{code:0,content:'该号码已被使用'},{code:1,content:'编辑失败'}]};
    sub_dialog(options);
  }
}

function checkUserInfo(){
   var tel=$("#tel").val();
    var myreg = /^1[2|3|4|5|6|7|9|8][0-9]\d{4,8}$/;

  if($.trim($("#username").val())==""){
     $("#username").focus();
     return false;
   }
   if(!myreg.test(tel))
        {
            alertDialog('请输入有效的手机号码！');
            return false;
        }
  return true;
}

function removeOneClass(id){
  var path=$("#path").val();
 var url=path+"/information/deleoneclass";
  var id =id;
   var openid=$("#openid").val();
  var to_url =path+"/information/myallclass?openid="+openid;

  var d = dialog({
    title: '提示',
    content: '您确定要退出当前班级吗?退出当前<br/>班级后不能进行与该班相关的操作。',
    okValue: '确定',

    ok: function () {
       $.post(url,{id:id},
       function (data){
         if(data=='success'){
          loadAllClass();
         }
       });
    },

    cancelValue: '取消',
    cancel: function () {
    }

 });

 d.showModal();
}

function removeOneDepart(id){
  var path=$("#path").val();
 var url=path+"/information/deleoneclass";
  var id =id;
   var openid=$("#openid").val();
  var to_url =path+"/information/loadeducate&openid="+openid;

  var d = dialog({
    title: '提示',
    content: '您确定要退出当前部门吗?退出当前<br/>部门后不能进行与该部门相关的操作。',
    okValue: '确定',

    ok: function () {
       $.post(url,{id:id},
       function (data){
         if(data=='success'){
          loadeducate();
         }
       });
    },

    cancelValue: '取消',
    cancel: function () {
    }

 });

 d.showModal();
}

function gettea(){
  var sta=$("#tea").val();
  if(sta==1)
  {
  $("#proe").hide();
  $("#citye").hide();
  $("#areae").hide();
  $("#roll").hide();
  $("#sch").hide();
  $("#rol").hide();
  $("#typee").hide();
  $("#typee").show();
  $("#sch").show();
  $("#clas").show();
  $("#rol").show();
  $("#proe").show();
  $("#citye").show();
  $("#areae").show();
  }
  if(sta==2)
  {
  $("#proe").hide();
  $("#citye").hide();
  $("#areae").hide();
  $("#sch").hide();
  $("#clas").hide();
  $("#rol").hide();
  $("#rol").hide();
  $("#typee").hide();
  $("#typee").show();
  $("#sch").show();
  $("#roll").show();
  $("#proe").show();
  $("#citye").show();
  $("#areae").show();
  }
  if(sta=="")
  {
  $("#typee").hide();
  $("#sch").hide();
  $("#clas").hide();
  $("#rol").hide();
  $("#roll").hide();
  $("#proe").hide();
  $("#citye").hide();
  $("#areae").hide();
  }
}

function pchange(stuid){
var openid=$("#openid").val();
var path=$("#path").val();
var url=path+"/information/p_changeschool";
 $.post(url,{stuid:stuid,openid:openid},
       function (data){
         if(data["info"]=='success'){

           var d = dialog({
    title: '切换成功',
    content: '切换学校成功',
    okValue: '确定',

    ok: function () {
      window.location.replace(path+"/information/index?token=gh_1570853a2962&openid="+openid+"&sid="+data["sid"]+".html");

    },

 });

 d.showModal();

         }
       });
}

function tchange(sid){
  var openid=$("#openid").val();
  var path=$("#path").val();
  var url=path+"/information/t_changeschool";
  $.post(url,{sid:sid,openid:openid},
       function (data){
         if(data["info"]=='success'){
           var d = dialog({
    title: '切换成功',
    content: '切换学校成功',
    okValue: '确定',

    ok: function () {
    window.location.replace(path+"/information/index?token=gh_1570853a2962&openid="+openid+"&sid="+sid);
    },
 });

 d.showModal();
          }
         else
         {
          alertDialog("切换失败");
         }
       });
}

function tchangeEducate(sid){
  var openid=$("#openid").val();
  var path=$("#path").val();
  var url=path+"/information/t_changeSchool";
  $.post(url,{sid:sid,openid:openid},
       function (data){
         if(data["info"]=='success'){
           var d = dialog({
    title: '切换成功',
    content: '已切换到教育局',
    okValue: '确定',

    ok: function () {
    window.location.replace(path+"/information/index&token=gh_1570853a2962&openid="+openid+"&sid="+sid+".html");
    },
 });

 d.showModal();
          }
         else
         {
          alertDialog("切换失败");
         }
       });
}

function checklxr(){
   var tel=$("#tel").val();
   var email=$("#mail").val();
  var myreg = /^1[2|3|4|5|6|7|8|9][0-9]\d{4,8}$/;
  var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
  if(!myreg.test(tel))
   {
    alertDialog('请输入有效的手机号码！');
    return false;
   }

//  if(!reg.test(email))
//  {
//    alertDialog('请输入有效的邮箱地址！');
//    return false;
//  }
  return true;
}

function savelxr(){
  if(checklxr())
  {

    var username=$("#username").val();
    var relation=$("#relation").val();
    var tel=$("#tel").val();
    var email=$("#mail").val();
    var stuid=$("#stuid").val();
    var cid=$("#cid").val();
    var sid=$("#sid").val();
    var openid=$("#openid").val();
    var tcid=$("#tcid").val();
    var path=$("#path").val();
    if(username=="")
    {
      alertDialog("姓名不能为空");
    }else if(relation==""){
      alertDialog("联系人身份不能为空");  
    }
    else
    {
    var url=path+"/information/doaddlxr";
    var urlt=path+"/information/lxr?cid="+cid+"&sid="+sid+"&openid="+openid+"&tcid="+tcid+"&stuid="+stuid;
    $.post(url,{tel:tel,username:username,email:email,stuid:stuid,relation:relation},
       function (data){
         if(data==1){
    var d = dialog({
    title: '添加成功',
    content: '添加联系人成功',
    okValue: '确定',

    ok: function () {
      loadHtmlByUrl(urlt);
    },
    });
    d.showModal();
          }
         else
         {
          alertDialog("该用户已绑定这个学生");
         }
       });
    }
  }
}
function dellxr(id){
 var d = dialog({
    title: '提示',
    content: '您确定要删除该联系人吗？',
    okValue: '确定',
    ok: function () {
      var cid=$("#cid").val();
      var sid=$("#sid").val();
      var openid=$("#openid").val();
      var stuid=$("#stui").val();
      var tcid=$("#tcid").val();
      var path=$("#path").val();
    var url =path+"/information/dellxr";
      var tourl=path+"/information/lxr?cid="+cid+"&sid="+sid+"&openid="+openid+"&tcid="+tcid+"&stuid="+stuid;
      $.post(url,{id:id},
       function (data){
         if(data=='success'){
       loadHtmlByUrl(tourl);
         }
       });
    },
    cancelValue: '取消',
    cancel: function () {
    }
 });
 d.showModal();
}

//检查校长新建学校的时候信息是否填写完整
function checkschoolInfo(){
if($.trim($("#pro").val())==""){
     alertDialog("省份不能为空");
    return false;
  }
  if($.trim($("#city").val())==""){
    alertDialog("所在市不能为空");
    return false;
  }
  if($.trim($("#area").val())==""){
    alertDialog("所在县区不能为空");
    return false;
  }
  // if($.trim($("#sctype").val())==""){
  //   alertDialog("学校类型不能为空");
  //   return false;
  // }
  if ($("#school-select").attr("style") == "display:block") {
  if($.trim($("#school").val())==""){
    alertDialog("学校名称不能为空");
    return false;
  }
  }
else
{
  if($.trim($("#schoolname").val())==""){
    alertDialog("学校名称不能为空");
    return false;
  }
}
  return true;
}

//我是校长新建一个学校
function addOneschool(){
   if(checkschoolInfo()){
    var school
      var path=$("#path").val();
      if ($("#school-select").attr("style") == "display:block") {
        school=$.trim($("#school").val());
      }
      else
      {
        school=$.trim($("#schoolname").val());
      }

      var type= $.trim($("#sctype").val());
      var area=$.trim($("#area").val());
      var city=$.trim($("#city").val());
      var pro=$.trim($("#pro").val());
      var openid=$("#openid").val();
      var data={openid:openid,school:school,type:type,area:area,city:city,pro:pro};
      var url=path+"/information/doaddschool";

       $.post(url,data,
       function (data){
         if(data=="chongfu"){
          alertDialog('您已经是其他学校的校长不能在新建学校')
          return false;
         }

         if(data=="xiaozhang"){
alertDialog('您好，如果您是'+school+'的校长，请关闭该页面，点击我的服务--》人工客服，进入我们的客服系统，我们将派专业人员为您处理。您也可直接拨打本产品的服务电话：0371-55030687，进行语音咨询')
          return false;
         }

          window.location.href=path+"/manager/index?openid="+openid+"&sid="+data;
       });



    }
}

function loadschool(){
  var path=$("#path").val();
  var openid=$("#openid").val();
  var sid =$("#sid").val();
  var url =path+"/information/loadschool?openid="+openid+"&sid="+sid;
   loadHtmlByUrl(url);
}

function loadeducate(){
  var path=$("#path").val();
  var openid=$("#openid").val();
  var sid =$("#sid").val();
  var url =path+"/information/loadeducate?openid="+openid+"&sid="+sid;
   loadHtmlByUrl(url);
}

function uploadCJDCallbak(message,success)
{
  alert(message);
  if(success==false)
  {
    alert("地址错误"+message);
    return;
  }
  else{

    $("#img").show();
    $("#loading-main").hide();
  }
}
function uploadimages(){
    var path=$("#path").val();
    var openid=$("#openid").val();
    var sid =$("#sid").val();
    var url =path+"/information/uploadimgs?openid="+openid+"&sid="+sid;
   loadHtmlByUrl(url);
}
function adminner(){
    var path=$("#path").val();
    var openid=$("#openid").val();
    var sid =$("#sid").val(); 
    var url =path+"/information/adminner?openid="+openid+"&sid="+sid;
    loadHtmlByUrl(url);
}
//添加一个学校的超管
function add_admin_school(){
   if(checkschoolInfo()){
    var school
      var path=$("#path").val();
      if ($("#school-select").attr("style") == "display:block") {
        school=$.trim($("#school").val());
      }
      else
      {
        school=$.trim($("#schoolname").val());
      }
     
      var type= $.trim($("#sctype").val());
      var area=$.trim($("#area").val());
      var city=$.trim($("#city").val());
      var pro=$.trim($("#pro").val());
      var openid=$("#openid").val();
      var data={openid:openid,school:school,type:type,area:area,city:city,pro:pro};
      var url=path+"/information/add-admin-school";
      var to_url=path+"/information/adminner?openid="+openid+"&sid="+sid;
      var options = {para:data,ele:$("#add_span"),sub_url:url,to_url:to_url,urltype:0,status:[{code:0,content:'您已经绑定过该校了'}]};
      sub_dialog(options);

    }
}
</script>
