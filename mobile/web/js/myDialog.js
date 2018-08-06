/*
* 二手dialog，基于artdialog
*/

/*  名称：删除框
*   说明：｛para:{name:value}型数据,url:链接,func:回调函数｝
*/
function del_dialog(options){
 
 var d = dialog({
    title: '提示',
    content: '您确定要删除吗?',
    okValue: '确定',

    ok: function () {
       $.post(options.url,options.para,
       function (data){
         options.func(data);
       });
    },

    cancelValue: '取消',
    cancel: function () {

    }

 });

 d.showModal();
}

/*  名称：提交框
*   说明：｛ele:被点击的元素，比如‘提交’按钮，此处传入jquery对象，比如$("#id")
*           para:{name:value}型数据,
*           sub_url:提交链接,
*           to_url:跳转链接，
*           status:[{code:0,content:标题不能重复}]状态吗及对应提示，可设置多个

*          ｝
*/
function sub_dialog(options){
   var first = options.ele;
   var para = options.para;        //参数数据
   var sub_url = options.sub_url;  //数据提交到的地址
   var to_url = options.to_url;    //提交后跳转的地址
   var status = options.status;
   var url_type = options.urltype;
   //是否已经跳转过，防止complete状态码200时和success重复跳转
   var isredirected = false;
   //关掉可点击发布按钮的开关，禁止重复发布
   first.attr('disabled','disabled');

     //构造一个对话框，等ajax执行后后再显示
  var d = dialog({
        title: '提示',
        content: '正在提交中...请稍等片刻'+'<span class="ui-dialog-loading" style="margin-top:10px">Loading..</span>'
     });

   $.ajax({
      url:sub_url,
      data:para,
      type:'post',
      success: function(data){       
        isredirected = true;
        var cont = getContOfArr(data,status);
        if(cont){
          d.remove();
     
          var td = dialog({
            title: '提示',
            content: cont,
            okValue: '确定',
      
            ok: function () {
              this.remove();
            }
    
          });
          td.showModal();                          
        }else{
          if(d != null){
//            d.content('提交成功！2秒后将自动跳转'); 
//            d.showModal();
        	  d.remove();
          }

    //这里直接跳转到to_url，无需再设置first
          setTimeout(function(){
            if(url_type==0){
              if(d != null){
                d.remove();
              }
              loadHtmlByUrl(to_url);
            }else{
              window.location.replace(to_url);
            }
          
          }, 2000); 
        }
      },
      complete:function(XHR, TS){

        //网络错误
        if(XHR.readyState == 0)
        {
          if (d != null){
            d.remove();
            }
          
          var errd = dialog({
            title: '警告',
            content: '网络连接错误,检查后重试！',
            okValue: '确定',
            ok: function () {
              this.remove();
            }

          });
          errd.showModal();
          first.removeAttr('disabled'); 
        }
        //网络传输完成
        else if (XHR.readyState == 4)
        {
          //发布按钮可点击开关打开，可以重新点击发布
          first.removeAttr('disabled');             
          if((XHR.status >= 200) && (XHR.status <300)){
              //服务器执行成功，但若因插件或服务器返回异常的不能解析的错误，则认为是执行成功
              if(isredirected == false){
                  setTimeout(function(){
                      if(url_type==0){
                          if(d != null){
                              d.remove();
                          }
                          loadHtmlByUrl(to_url);
                      }else{
                          window.location.replace(to_url);
                      }

                  }, 2000);
              }
          }
          //408:(SC_REQUEST_TIMEOUT)是指服务端等待客户端发送请求的时间过长。该状态码是新加入 HTTP 1.1中的
          else if (XHR.status == 408) 
          {
            //更新对话框提示超时
            if (d != null){
              d.content('服务器超时，请重试！'); 
              d.showModal();
            }
            
          }
          //其他网络错误
          else
         {
            //更新对话框提示执行失败
            if (d != null){
              var status=XHR.status;
              d.content('提交失败'+status+'，请重试！'); 
              d.showModal();        
            }
         }
        }
      }
    });

    //不用等ajax请求成功，显示模态对话框
    if (d != null){
      d.showModal();
    }        

}

/*  遍历辅助函数 */
function getContOfArr($code,arr){
  for (var i = 0; i < arr.length; i++) {
    if(arr[i].code==$code){
      return arr[i].content;
    }
  }

  return false;
}