function ajaxLoad(htmlurl){
    var d = dialog({
      title: '提示',
      content: '数据加载中，请稍候...'+'<span class="ui-dialog-loading" style="margin-top:10px">Loading..</span>',
    });
    $.ajax({
        type: 'GET',
        url: htmlurl,
        data: '',
        dataType: 'html',
        cache: false,
        beforeSend:function(){         

           if (d != null){
             d.showModal();
           } 
          //$("#mycontainer").html("<div style=''><span class='ui-dialog-loading' style='margin-top:10px'>Loading..</span></div>");
        },
        success: function(response){
          d.remove();
          var json = jsonEval(response);
          
          if (json.statusCode==mystatusCode.error){
            if (json.message) alert(json.message);
          } else {
            $("#mycontainer").html(response);
           
          }
          
          if (json.statusCode==mystatusCode.timeout){           
            alert(json.message);              
          } 
          
        },
        error: function(xhr, ajaxOptions, thrownError){
   
          //d.remove();
          d.content("Http status: " + xhr.status + " " + xhr.statusText + "\najaxOptions: " + ajaxOptions + "\nthrownError:"+thrownError + "\n" +xhr.responseText); 
          d.showModal();
          //alert("Http status: " + xhr.status + " " + xhr.statusText + "\najaxOptions: " + ajaxOptions + "\nthrownError:"+thrownError + "\n" +xhr.responseText);
    
        },
        statusCode: {
          503: function(xhr, ajaxOptions, thrownError) {
            alert("statusCode_503");
          }
        }
      });


}

function loadHtml(ths,e){

  _preventDefault(e);  
  var htmlurl = $(ths).attr('href');
 
  ajaxLoad(htmlurl);
}

function loadHtmlByUrl(url){
  ajaxLoad(url);
}

function _preventDefault(event){
  
  var e = event||window.event;
  e.preventDefault();
}

function jsonEval(data) {
    try{
      if ($.type(data) == 'string')
        return eval('(' + data + ')');
      else return data;
    } catch (e){
      return {};
    }
}

var mystatusCode = {ok:200, error:300, timeout:301};

function doGetReturnRes(url,para,func){
  $.getJSON(url,para,function (data){
    
    func(data);

  });
}

function stopRepeatSubmit($ele,mins){
  $ele.attr('disabled','disabled');
  setTimeout(function(){reabled($ele);},mins);
}

function reabled(ele){
  ele.removeAttr('disabled');
}

function alertDialog(cont){
   var d = dialog({
      content: cont,
      cancel: false,
      okValue: '确定',
      ok: function () {}
   });
   d.showModal();
}
