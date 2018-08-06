function ajaxLoad(htmlurl){
    doAjaxLoad(htmlurl,"mycontainer");
}

function ajaxLoadToOther(htmlpath,container_id){
    doAjaxLoad(htmlpath,container_id);
}

function doAjaxLoad(htmlpath,container){

    $.ajax({
        type: 'GET',
        url: htmlpath,
        data: '',
        dataType: 'html',
        cache: false,
        beforeSend:function(){
            loadingDialog('show');
        },
        success: function(response){
            loadingDialog('hide');
            var json = jsonEval(response);

            if (json.statusCode==mystatusCode.error){
                if (json.message) alert(json.message);
            } else {
                $("#"+container).html(response);
            }

            if (json.statusCode==mystatusCode.timeout){
                alert(json.message);
            }

        },
        error: function(xhr, ajaxOptions, thrownError){
            loadingDialog('hide');
            okDialog("Http status: " + xhr.status + " " + xhr.statusText);
        },
        statusCode: {
            503: function(xhr, ajaxOptions, thrownError) {
                loadingDialog('hide');
                okDialog("statusCode_503");
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

function forwardTo(url){
    ajaxLoad(url);
}

function backTo(url){
    ajaxLoad(url);
}

function loadHtmlToOther(url,container_id){
    ajaxLoadToOther(url,container_id);
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
        if(typeof func != "undefined" && typeof func == "function") {
            func(data);
        }
    });
}

function stopRepeatSubmit($ele,mins){
    $ele.attr('disabled','disabled');
    setTimeout(function(){reabled($ele);},mins);
}

function reabled(ele){
    ele.removeAttr('disabled');
}
