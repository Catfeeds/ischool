/*
*  manage 学校管理模块脚本文件
*  2015.5.5 hhb
*/
function alertObj(obj){
    var output ="";
    for(var i in obj){
        var property=obj[i];  
        output+=i+" = "+property+"\n";
    }  
    alert(output);
}
var manage = {
   
  //首页设置页面脚本
  hpageSetting:function(){
    //轮播设置按钮点击事件
    $("#cycle-picture").click(function(){  
      var path=$("#pathurl").val();
      var openid=$("#hidden_openid").val();
      var sid =$("#hidden_sid").val();
      var url ="/manager/listcarousel?sid="+sid+"&openid="+openid;
      loadHtmlByUrl(url);
    });

    //栏目设置按钮点击事件
    $("#columns").click(function(){
      var path=$("#pathurl").val();
      var openid=$("#hidden_openid").val();
      var sid =$("#hidden_sid").val();
      var url ="/manager/setcolumn?sid="+sid+"&openid="+openid;
      loadHtmlByUrl(url);
    });
  },

  //栏目设置页面
  setColumn:function(){
    //"点击新增栏目"按钮事件
    $(".edit-calumns").click(function(){
        var read_calumns = $(this).parent().css("display","none");
        var edit_calumns = $(this).parent().prev().css("display","block");
    });

    //编辑保存事件
    $(".save-calumns").click(function(){
       
        var path=$("#pathurl").val();
        var openid=$("#hidden_openid").val();
        var sid =$("#hidden_sid").val();
        var url ="/manager/doeditcolumn";
        $.get(url,{columnId:$(this).attr('name'),columnName:$('#c'+ $(this).attr('name')).val(),openid:openid,sid:sid},function(data){
            if(data=='dupname'){
              alert('重名，请重新输入！');
            }else if(data=='success'){
              alert("修改成功");
              var url2 ="/manager/setcolumn";
              loadHtmlByUrl(url2);
            }else{
              alert("修改失败");
            }
        });
    });

    //删除事件
    $(".delete-calumns").click(function(){
       
        var path=$("#pathurl").val();
        var openid=$("#hidden_openid").val();
        var sid =$("#hidden_sid").val();
        var url ="/manager/dodelcolumn";

        $.get(url,{columnId:$(this).attr('name')},function(data){
            if(data=='success'){
              alert("删除成功");
              var url2 ="/manager/setcolumn";
              loadHtmlByUrl(url2);
            }else{
              alert("修改失败");
            }
        });
    }); 

      //新增事件
    $("#addcolumn-new-button").click(function(){
        if($.trim($("#columnName").val())==""){
          alert('请输入栏目名称');
          return false;
        }
        var path=$("#pathurl").val();
        var openid=$("#hidden_openid").val();
        var sid =$("#hidden_sid").val();
        var url ="/manager/doaddcolumn";
        $.get(url,{columnName:$("#columnName").val(),sid:sid},function(data){
            if(data == 'dupname'){
              alert("该栏目重名");
            }
            else if(data=='success'){
              alert("新增成功");              
              var url2 ="/manager/setcolumn";
              loadHtmlByUrl(url2);
            }else{
              alert("新增失败");
            }
        });
    });          
      
  },

  //轮播设置页面
  setCarousel:function(){
    //点击图片显示删除按钮
    $(".picture-narrow").click(function(){
        var read_calumns = $(this).next().fadeToggle(200);
    });
    //上传按钮事件
    $("#upload-picture").click(function(){
        $("#upload-input").click();
    });
    
    $('#upload-input').localResizeIMG({
   	 width: 400,
   	 quality: 1,
   	 before:function(ths,b,f){
   		 //这里可以在上传前做一些操作，比如压缩的大小等
   		 //掌握一下h5接口，分析一下源码，争取完全无缝的融入到编辑器里
//   		 alert(this.width);
//   		 this.width = 500;
   	 },
   	 success: function (result) {
   		 var submitData={
   		 	data:result.clearBase64,
   	 	 };
   		 $("#dimmer-loader").show();
   		 $("#prevImg").attr("src",result.base64);
   		 $.ajax({
   			 type: "POST",
   			 url: '/utils/uploadimg',
   			 data: submitData,
   			 dataType:"json",
   			 success: function(data){
   				 if(data){  					 
   					 manage.uploadimg(data.file_path, data.success);
   				 }else{
   					 alert("上传错误，请重试");
   				 }
   			 }
   			
   		 });
   	 }
   });
  
    //上传格式验证
    $("#upload-input2").on('change',function(){
      var names=$("#upload-input").val().split("."); 
      if(names[1]!="JPEG"&&names[1]!="jpeg"&&names[1]!="gif"&&names[1]!="GIF"&&names[1]!="jpg"&&names[1]!="JPG"&&names[1]!="png"&&names[1]!="PNG"&&names[1]!="BMP") { 
        alert("上传的图片格式错误!"); 
        return; 
      }else{
          $("#formImg").submit();//提交上传
          $("#dimmer-loader").show(); //显示上传动画      
      }
        
    });

    //删除图片
    $(".delete-picture").click(function(){
        
        var cid =$(this).attr("id");
        var url ="/manager/dodeletecarousel";
        var remove_pic = $(this).parents(".picture-list");
        
        $.get(url,{cid:cid},function(data){
            if(data == 'success'){
                alert("删除成功");
                $(remove_pic).remove();
            }else{
                alert("删除失败");
            }
        });
        
    });
      
  },
   //图片上传页面
  setUploadimages:function(){
    //点击图片显示删除按钮
    $(".picture-narrow").click(function(){
        var read_calumns = $(this).next().fadeToggle(200);
    });
    //上传按钮事件
    $("#upload-picture").click(function(){
        $("#upload-input").click();
    });
    
    $('#upload-input').localResizeIMG({
         width:1200,
   	 quality:1,
   	 before:function(ths,b,f){
   		 //这里可以在上传前做一些操作，比如压缩的大小等
   		 //掌握一下h5接口，分析一下源码，争取完全无缝的融入到编辑器里
//   		 alert(this.width);
//   		 this.width = 500;
   	 },
   	 success: function (result) {
   		 var submitData={
   		 	data:result.clearBase64,
   	 	 };
                 var sid=$("#hidden_sid").val();
                 var cid=$("#hidden_cid").val();
   		 $("#dimmer-loader").show();
   		 $("#prevImg").attr("src",result.base64);
   		 $.ajax({
   			 type: "POST",
   			 url: '/utils/uploadclassimgs?sid='+sid+'&cid='+cid,
   			 data: submitData,
   			 dataType:"json",
   			 success: function(data){
   				 if(data){  					 
   					 manage.uploadimages(data.file_path, data.success);
   				 }else{
   					 alert("上传错误，请重试");
   				 }
   			 }
   			
   		 });
   	 }
   });
  
    //上传格式验证
    $("#upload-input2").on('change',function(){
      var names=$("#upload-input").val().split("."); 
      if(names[1]!="JPEG"&&names[1]!="jpeg"&&names[1]!="gif"&&names[1]!="GIF"&&names[1]!="jpg"&&names[1]!="JPG"&&names[1]!="png"&&names[1]!="PNG"&&names[1]!="BMP") { 
        alert("上传的图片格式错误!"); 
        return; 
      }else{
          $("#formImg").submit();//提交上传
          $("#dimmer-loader").show(); //显示上传动画      
      }
        
    });

    //删除图片
    $(".delete-picture").click(function(){
        
        var cid =$(this).attr("id");
        var url ="/manager/dodeletecarousel";
        var remove_pic = $(this).parents(".picture-list");
        
        $.get(url,{cid:cid},function(data){
            if(data == 'success'){
                alert("删除成功");
                $(remove_pic).remove();
            }else{
                alert("删除失败");
            }
        });
        
    });
      
  },
  //上传轮播图片的回调函数
  uploadimg:function(message,success){
    if(success==false) 
    { 
      alert("地址错误"+message);
      return;
    } 
    else{ 
        var path=$("#pathurl").val();
        var openid=$("#hidden_openid").val();
        var sid =$("#hidden_sid").val();     
        var url ="/manager/doaddcarousel";
        $.get(url,{ sid:sid,picurl:message}, function(data){
          if(data == 'success'){
            alert("上传图片成功!");
            //上传完后 刷新页面，并关闭 上传提示动画
            var listcarousel ="/manager/listcarousel?sid="+sid+"&openid="+openid;
            loadHtmlByUrl(listcarousel);            

          }else{
            alert("上传失败!");
          }
          $("#dimmer-loader").hide();  
        });
    } 
  },
//上传照片的回调函数
  uploadimages:function(message,success){
    if(success==false) 
    { 
      alert("地址错误"+message);
      return;
    } 
    else{ 
        var path=$("#path").val();
        var openid=$("#openid").val();
        var sid =$("#hidden_sid").val();     
        var cid =$("#hidden_cid").val();  
        var url ="/information/doaddimages";
        $.get(url,{ sid:sid,picurl:message,cid:cid}, function(data){
          if(data == 'success'){
            alert("上传图片成功!");
            if(confirm('是否继续上传?')){
                //上传完后 刷新页面，并关闭 上传提示动画
                var listcarousel ="/information/saveclassinfo?sid="+sid+"&cid="+cid;
                loadHtmlByUrl(listcarousel);  
            }else{
                var listcarousel ="/information/myallinfo?sid="+sid+"&openid="+openid;
                loadHtmlByUrl(listcarousel); 
            }
                      

          }else{
            alert("上传失败!");
          }
          $("#dimmer-loader").hide();  
        });
    } 
  }
 
}
