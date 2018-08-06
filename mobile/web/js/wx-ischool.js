/**
 * Created by hhb on 2016/1/8 0008.
 * ischool weixin common included js file
 */
/*this is ajaxload.min.js*///
function common_callback(data,status,model){
    //status type as[{code:1,content:'success'}....]
    if(Object.prototype.toString.call(status) === '[object Array]'){
        var content = "";
        for (var i = 0; i < status.length; i++) {
            if(status[i].code==data){
                content = status[i].content;
                break;
            }
        }
        if(model==null || model=='show'){
            okDialog(content);
        }
    }
    return data;
}
function okDialog(content){
    //bootstrap的模态框改装成确认框
    var tcontent = $.trim(content);
    if(tcontent == ""){
        tcontent = "操作成功!";
    }
    $("#okContent").html(tcontent);
    $("#okModal").modal('show');
}
function okDialogCancel(){
    $("#okModal").modal('hide');
}
function loadingDialog(option){
    if(option == 'show' || option==null){
        $("#loadingModal").modal('show');
    }else{
        $("#loadingModal").modal('hide');
    }
}
function showDelDialog(url,param,eleid){
    bindDelDialogClick(url,param,eleid);
    $("#delModal").modal('show');
}
function hideDelDialog(){
    $("#delModal").modal('hide');
}
function bindDelDialogClick(url,param,eleid){
    $("#submitDelBtn").on("click",function(){
        hideDelDialog();
        var result_arr = [{'code':0,'content':'删除成功!'},{'code':1,'content':'删除失败，请重试!'},{'code':2,'content':'操作无效，请重试'}];
        doGetReturnRes(url,param,function(data){
            if(common_callback(data,result_arr)==0){
                if(eleid!="" && eleid!=null){
                    $("#"+eleid).remove();
                }
            }
        });
    });
}

var wx_ischool ={
    sid:null,
    school:null,
    openid:null,
    path:null,
    init:function(){
        this.sid    = $("#sid").val();
        this.school = $("#school").val();
        this.openid = $("#openid").val();
        this.path   = $("#path").val();
        this.init_footer_menu();
    },
    /********manage module**********/
    manage_index:function(){
        this.init();
        var url = this.path+"/index.php?s=/addon/Manage/Manage/mainPage/openid/"+this.openid+"/sid/"+this.sid;
        loadHtmlByUrl(url);
    },
    manage_mainPage:function(){
        this.bind_click_load_html();
    },
    manage_classManage:function(){
        this.bind_click_load_html();
    },
    manage_addClass:function(){
        this.bind_click_load_html();
        //自定义建班级
        $("#addClassCustomer").on("click",function(){
            var classname = $.trim($("#classnamet").val());
            if(classname == ""){
                okDialog("请输入班级名称！");
            }else{
                var url = wx_ischool.path + "/index.php?s=/addon/Manage/Manage/doAddClassCustomer";
                var result_arr = [{'code':0,'content':'保存成功!'},{'code':1,'content':'班级名字重复请重新输入'},{'code':2,'content':'保存失败，请重试'}];
                doGetReturnRes(url,{'classname':classname,'grade':$.trim($("#grade").val()),
                    'sid':wx_ischool.sid,'school':wx_ischool.school},function(data){common_callback(data,result_arr)});
            }
        });
        //批量建班级
        $("#addClassBatch").on("click",function(){
            var classname = $.trim($("#classname").val());
            if(classname == ""){
                okDialog("请输入班级名称！");
            }else{
                var url = wx_ischool.path + "/index.php?s=/addon/Manage/Manage/doAddClass";
                var result_arr = [{'code':0,'content':'保存成功!'},{'code':1,'content':'班级名字重复请重新输入'},{'code':2,'content':'保存失败，请重试'}];
                doGetReturnRes(url,{'classname':classname,'grade':$.trim($("#grade").val()),
                    'sid':wx_ischool.sid},function(data){common_callback(data,result_arr)});
            }
        });
    },
    manage_allClass:function(){
        this.bind_click_load_html();
        $(".updown_div").on("click",function(){
            var divid = "#with-"+$(this).attr("id");
            if($(divid).hasClass("on")){
                $(divid).removeClass("on").addClass('off').slideToggle(200);
            }else{
                $(".on").hide(200).addClass("off").removeClass("on");
                $(divid).addClass("on").removeClass('off').slideToggle(200);
            }
        });
        $(".btn_del_class").on("click",function(){
            var cid = $(this).attr("data-id");
            var url= wx_ischool.path + '/index.php?s=/addon/Manage/Manage/deleteClass2/cid/'+cid;
            showDelDialog(url,{},"s"+cid);
        });
        $(".btn_config_class").on("click",function(){
            var cid = $(this).attr("data-id");
            var url= wx_ischool.path + '/index.php?s=/addon/Manage/Manage/configClass2/cid/'+cid;
            loadHtmlByUrl(url);
        });
    },
    manage_configClass:function(){
        this.bind_click_load_html();
        $("#btn_add_tea").on("click",function(){
            var cid = $("#sel_list_class").val();
            if(cid == ""){
                okDialog("请选择一个班级！");
            }else if($("#list_teacher").val()==""){
                okDialog("请选择一位老师！");
            }else{
                var param = {'cid':cid,'classname':$.trim($("#sel_list_class option:selected").text()),tname:$("#list_teacher option:selected").text(),sid:wx_ischool.sid,openid:$("#list_teacher").val(),school:wx_ischool.school,role:$("#role").val()};
                var url = wx_ischool.path + "/index.php?s=/addon/Manage/Manage/saveConfigClass";
                doGetReturnRes(url,param,function(data){
                    if(data.flag == "success"){
                        $("#zwxx").hide();
                        $("#list_tc").append(wx_ischool.createClassTeaHtml(data.data));
                    }else if(data.flag == "fail"){
                        okDialog("配置保存失败，请重试！");
                    }else{
                        okDialog("不能重复绑定请重新选择");
                    }
                });
            }
        });
        $("#list_tc").on("click",".btn_del_tea",function(){
            var url = wx_ischool.path + "/index.php?s=/addon/Manage/Manage/deleteConfigClass";
            var ths = $(this);
            var para = {tcid:ths.attr("data-id")};
            doGetReturnRes(url,para,function(data){
                if(data.result=='success'){
                    ths.parents(".tc").eq(0).remove();
                    if($("#list_tc .tc").length==0){
                        $("#zwxx").show();
                    }
                }else{
                    okDialog('移除失败，请重试');
                }
            });
        });
        $("#sel_list_class").on("change",function(){
            var url = wx_ischool.path + "/index.php?s=/addon/Manage/Manage/getTeaClass";
            var para = {cid:$(this).val()};
            doGetReturnRes(url,para,function (data){
                if(data.result=='success'){
                    var htmls = "";
                    if(data.data!=null){
                        $("#zwxx").hide();
                        var res2 = data.data;
                        for (var i = 0; i < res2.length; i++) {
                            htmls = htmls + wx_ischool.createClassTeaHtml(res2[i]);
                        }
                    }
                    $("#list_tc").html(htmls);

                }else{
                    okDialog('获取当前班级老师失败，请重试');
                }
            });
        });
    },
    /**********information module **********/
    information:function(){

    },
    bind_click_load_html:function(){
        //按钮或链接点击进入各自子菜单
        $(".manage_div").on("click",function(){
            var url = wx_ischool.path+"/index.php?s=/addon/Manage/Manage/"+$(this).attr("id")+"/openid/"+wx_ischool.openid+"/sid/"+wx_ischool.sid;
            loadHtmlByUrl(url);
        });
    },
    init_footer_menu:function(){
        $(".footer-menu").on('click',function(){
            var thisid = $(this).attr("id");
            $(".mynav").each(function() {
                if($(this).hasClass(thisid)){
                    if($(this).hasClass("on")){
                        $(this).slideUp(300);
                        $(this).removeClass("on").addClass("off");
                    }else{
                        $("."+thisid).slideDown(300);
                        $(this).removeClass("off").addClass("on");
                    }
                }else{
                    $(this).slideUp(300);
                    $(this).removeClass("on").addClass("off");
                }
            });
        });
    },
    createClassTeaHtml:function (obj){
        if(obj != null){
            return "<div class='row class-root-Paging tc'>"
                    +  "<div class='col-xs-8 text-omit'>"
                    +  obj.tname  +"  " + obj.role
                    +  "</div><div class='col-xs-4'>"
                    +  "<span class='data-card-del btn_del_tea' data-id="+obj.id+">删除</span>"
                    +  "</div></div>";
        }else{
            return "";
        }


    }
};
