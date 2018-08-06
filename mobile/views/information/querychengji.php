<div class="row heard-list-waper">
  
    <div class="col-xs-2 col-xs-offset-1 text-align-l"> 
      <div onclick="backto('<?php echo URL_PATH?>/information/chengjiindex?sid=<?php echo $sid?>&cid=<?php echo $cid?>&cname=<?php echo $cname?>&tcid=<?php echo $tcid?>&openid=<?php echo $openid?>')">
        <i class="fa fa-reply"></i> 
      </div>    
    </div>
   
    <div class="col-xs-6 text-align-l">         
      <?php echo $cname?>成绩查看
    </div>
       
</div>

<div id="wrapper" style="padding-top: 10px;">

      <div class="row edit-user-row">
        <div class="col-xs-4 col-xs-offset-2 edit-user-top">
          考试
        </div>
        <div class="col-xs-3 edit-user-top">
          <select id="examtype">
            <option value="">请选择</option>
             <?php foreach($cjds as $v){ ?>
<!--            <foreach name="cjds" item="vo" key="key">-->
              <option value="<?php echo $v['cjdid']?>"><?php echo $v['cjdname']?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <div class="row edit-user-row">
        <div class="col-xs-4 col-xs-offset-2 edit-user-top">
          学生
        </div>
        <div class="col-xs-3 edit-user-top">
          <select id="stuname">
            <option value="all">全部</option>
            <?php foreach($students as $v){ ?>
<!--            <foreach name="students" item="vo" key="key">-->
              <option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
            <?php } ?>
          </select>
        </div>
      </div>    
    
      <div id="query-button" onclick="query_button()">查询</div>
    
      <div id="query-warpper" style="display:none">
        <div id="cjd">

        </div>
        
        <div id="close-query" onclick="close_query()">关闭</div>
        
      </div>

      <div id="loading-main" style="text-align: center;">
        <div id="loading" class="ui-dialog-loading" style="margin-top:10px;display:none">Loading..</div>
      </div>
      <input type="hidden" value="<?php echo $cid?>" id="cid">
      <input type="hidden" value="<?php echo URL_PATH?>" id="path">
      <input type="hidden" value="<?php echo $openid?>" id="openid">
</div>

<!-- JS代码区 --> 
<script type="text/javascript">
    
    function query_button(){
      var examid = $("#examtype").val();
      if(examid == ""){
        alert("请选择成绩单");
        return 0;
      }
      var path=$("#path").val();
      var openid=$("#openid").val();
      var cid = $("#cid").val();
      var stuid =$("#stuname").val();

      var url =path+"/information/doquerychengji/";
      var para = {"openid":openid,"stuid":stuid,"examid":examid,cid:cid};
      $.getJSON(url,para,function(data){

        if(data != null){

            var htmls = "<div id='chengji-title'>";
            var title = data[0];  //第0行标题行
            var cols = title.length;
            var t = 0;
            for(t;t < cols;t++){
              htmls = htmls + "<div class='title-num'>"+title[t]+"</div>";
              if(t == cols-1){
                htmls = htmls + "<div style='clear:both'></div></div>";
              }
            }

            var content = data[1]; //内容行
            var rows = content.length;
            var i = 0;
            for(i;i < rows;i++){
              var j = 0;
              var row = content[i];

              if(i == 0){
                htmls = htmls + "<div id='chengji-info'>";
              }

              for(j;j < cols;j++){
                if(j == 0){
                  htmls = htmls + "<div class='chengji-row'>";
                }
                htmls = htmls + "<div class='title-num'>"+row[j]+"</div>";
                if(j == cols-1){
                  htmls = htmls + "<div style='clear:both'></div></div>";
                }
              }
              if(i == rows-1){
                htmls = htmls + "</div>";
              }

            }

            $("#cjd").html(htmls);
            addCss();

        }


      });

    }

    function addCss(){
        var title_num = $("#chengji-title").find(".title-num").length;
        var title_width = 100/title_num+"%";

        $(".title-num").css("width",title_width);
        $(".chengji-num").css("width",title_width);//设置表格宽度

        $("#loading-main").show();
        $("#query-warpper").show();
    }

    function close_query(){
        $("#query-warpper").hide();
    }
</script>


