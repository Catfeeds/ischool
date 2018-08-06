<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/chengjiindex?sid=<?php echo $sid?>&cid=<?php echo $cid?>&canme=<?php echo $cname?>&tcid=<?php echo $tcid?>&openid=<?php echo $openid?>')">
        <i class="fa fa-reply"></i> 
      </div>    
    </div>
   
    <div class="col-xs-6 text-align-l">         
     <?php echo $cname?>成绩单发送
    </div>
    <div class="col-xs-3 text-align-l">
      <a href="/upload/template/mb-chengjidan.xls" target="_self"><div>模版</div></a>
    </div>
</div>


	<div id="wrapper" style="padding-top: 10px;">
<div id="upload-main">

      <form  action="/information/uploadchengjidan" method="post" target="hidden_frame" enctype="multipart/form-data" id="formImg" role="form">

        <div class="row edit-user-row">
          <div class="col-xs-4 col-xs-offset-2 edit-user-top">
            学年
          </div>
          <div class="col-xs-6 edit-user-top">
            <select id="xueyear">
            <?php  foreach($xuenian as $v){?>    
<!--              <foreach name="xuenian" item="vo" key="key">-->
                <option value=" <?php echo $v['year']?>"> <?php echo $v['year']?></option>
             <?php } ?>
            </select>
          </div>
        </div>

        <div class="row edit-user-row">
          <div class="col-xs-4 col-xs-offset-2 edit-user-top">
            类型
          </div>
          <div class="col-xs-3 edit-user-top">
            <select id="examtype" onchange="showChildType(this)">
             <?php  foreach($examtype as $v){?>   
<!--              <foreach name="examtype" item="vo" key="key">-->
                <option value=" <?php echo $v['name']?>"> <?php echo $v['name']?></option>
             <?php } ?>
            </select>
          </div>
          <div class="col-xs-3 edit-user-top">
            <select id="childtype" style="display: none;">

            </select>
          </div>
        </div>

        <div class="row edit-user-row">
          <div class="col-xs-4 col-xs-offset-2 edit-user-top">
            范围
          </div>
          <div class="col-xs-6 edit-user-top">
            <input type="checkbox" name="isopen" value="y">是否班级内公开可见
          </div>
        </div>

        <div id="file-name-wapper" style="display:none;">
          <div class="file-name-text" style="width:30%;line-height: 50px;float: left;text-align: center;">所选文件：</div>
          <div id="file-name" style="width:70%;line-height: 50px;float: left;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"></div>
          <div class="clear" style="clear:both"></div>
        </div>
        <div id="upload-score" style="background-color: #e74c3c;line-height: 50px;text-align: center;border-radius: 5px;color: #FFFFFF;" onclick="upload_button_block()">上传成绩单</div>
      
       
          <input id="upload-score-but"  type="file" name="ImportData[upload]" onchange="upload_name()" style="display:block;width:0px;">
          <input type="hidden" value="<?php echo $cid?>" name="cid" />
          <input type="hidden" value="<?php echo $cname?>" name="cname" />
          <input type="hidden" value="<?php echo $sid?>" name="sid" />
          <input type="hidden" value="<?php echo $openid?>" name="openid">
          <input type="hidden" value="" name="exam" id="examname"/>
      </form>
      <iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
            
        <div id="upload-button-wapper" style="display:none;margin-bottom: 150px;">
            <div id="upload-ok" style="background-color: #e74c3c;line-height: 50px;text-align: center;border-radius: 5px;color: #FFFFFF;width: 49%;float: left;" onclick="file_input()">确认上传并发送</div>
            <div id="upload-close" style="background-color: #f1c40f;line-height: 50px;text-align: center;border-radius: 5px;color: #FFFFFF;width: 49%;float: right;" onclick="file_input_close()">取消发送</div>            
        </div>   
         <div class="row help-row">
            <div class="col-xs-12">
              <span class="badge">
                温馨提示:
              </span>
              <hr>
              <div class="help-row-text">请下载模板文件，或者使用.xls格式文件上传！</div>
            </div>
        </div>

</div>
        
        <div id="loading-main" style="text-align: center;">
            <div id="loading" class="ui-dialog-loading" style="margin-top:10px;display:none">Loading..</div>
        </div>
          
	</div>

<!-- JS代码区 --> 
<script>
    function showChildType(ths){
        
        $this_value = $(ths).val();       
        var content = "";
        var isshow = false;   
        var this_value=Trim($this_value);           
        if(this_value == "周考"){      
            for(var i=1;i<21;i++){
               content = content + "<option value='"+"第"+i+"周周考"+"'>"+"第"+i+"周周考"+"</option>";
            }
            isshow = true;

        }else if(this_value == "月考"){
            for(var j =1;j<13;j++){
              content = content + "<option value='"+j+"月月考"+"'>"+j+"月月考"+"</option>";
            }
            isshow = true;

        }else{
          isshow = false;
        }

        $("#childtype").html(content);
       
        if(isshow==true){
            $("#childtype").show();
        }else{
            $("#childtype").hide();
        }
    }
     function Trim(str){ 
             return str.replace(/(^\s*)|(\s*$)/g, ""); 
     }
    function upload_button_block(){
        $("#upload-score-but").click();
       
    }
    function file_input_close(){
        $("#upload-score").show();
        $("#upload-button-wapper").hide();  
        $("#file-name-wapper").hide();
        
        var file = $("#upload-score-but");
        file.after(file.clone().val("")); 
        file.remove(); 
    }
    
    function file_input(){
        //提交前拼接考试名称
         var s=$('input[name="ImportData[upload]"]').val();
        if(s==""){
            alert("请选择文件");
            return false;
        }
        //提交前拼接考试名称
        var xuenian = $("#xueyear").val();

        if($("#childtype").is(":hidden")){
            $("#examname").val(xuenian+$("#examtype").val());
        }else{
            $("#examname").val(xuenian+$("#childtype").val());
        }

        //提交
        $("#formImg").submit();
    }
    function upload_name()
    {
        var fileName="";
        fileName = $("#upload-score-but").val().split("\\").pop();
//        fileName = fileName.substring(0, fileName.lastIndexOf("."));
        $("#file-name-wapper").show();
        $("#file-name").text(fileName);
        var  text= $("#file-name").text();
        if(text){
             var k = text.substr(text.indexOf("."));
             if(k!='.xls'){
                  alert('请上传.xls格式的文本');
                  file_input_close();
                  die;
             }          
        }             
        $("#upload-score").hide();
        $("#upload-button-wapper").show();

    }
    function uploadCJDCallbak(retcode,retmsg)
    {
        if(retcode==0)
        {
            alert("上传成功！");
            file_input_close();
            $("#loading-main").hide();

        }else{
            alert(retmsg);
        }
        $("#upload-ok").attr('disabled',false);
        return 0;
    }
</script>


