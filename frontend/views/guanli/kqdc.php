<div class="pull-left" style="margin-left: 10px;width: 70%;min-width: 600px;">
    <div style="background-color: white;padding: 10px 20px;box-shadow: 0 0 2px #ccc;">
        <div class="clearfix">
            <h4 class="pull-left">考勤导出</h4>
        </div>
        <hr/>
        <?php if(count($schools) !=0){ ?>
        <form class="form-horizontal Modify_pwd" id="formkq" method="post" >
            <div class="form-group">
                <label style="line-height:10px" class="col-sm-2 col-lg-1 control-label text-left" for="kq_date_from">日期</label>
                <div class="col-sm-6">
                    <input class="form-control" style="width: 80%" name="kq_date_from" id="kq_date_from" type="text" placeholder="请选择"  readonly/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-lg-1 control-label" for="kq_date_to"></label>
                <div class="col-sm-6">
                    <input class="form-control" style="width: 80%" name="kq_date_to" id="kq_date_to" type="text" placeholder="请选择" value="" readonly/>
                </div>
            </div>
            <div class="form-group form-inline">
                <label class="col-sm-2 col-lg-1 control-label"></label>
                <div class="col-sm-6 row" style="padding: 0;">
                    <div class="col-md-5">
                        <button type="button" class="btn btn-block" style="background-color: #36ADFF;color: white" onclick="doDownKQ()" >导出考勤明细</button>
                    </div>
                    <div class="col-md-5">
                        <button type="button" class="btn btn-block" style="background-color: #36ADFF;color: white" onclick="doDownKQHZ()">导出考勤汇总</button>
                    </div>
                </div>
            </div>
        </form>
        <?php }?>
        <div style="margin-top:50px ;">
            <hr/>
            <span class="badge" style="margin-right: 20px">帮助</span> 点击【导出考勤】，默认下载所选日期当天全部出校信息
        </div>
    </div>

</div>
</div>
</div>

<script>
    function doDownKQ(){
        if (check()){
            var formdata = {};
            formdata.downtime = $("#kq_date_from").val();
            formdata.endtTime = $("#kq_date_to").val();
            formdata.type = "kaoqin";
            $("#formkq").attr('action','/guanli/exportkq');$("#formkq").submit();
        }
    }
    function doDownKQHZ(){
        if (check()){
            var formdata = {};
            formdata.downtime = $("#kq_date_from").val();
            formdata.endtTime = $("#kq_date_to").val();
            formdata.type = "kaoqin";
            $("#formkq").attr('action','/guanli/exportkqhz');$("#formkq").submit();
        }
    }
    function check(){
        var downtime = $("#kq_date_from").val();    //导出考勤开始时间
        var endtTime = $("#kq_date_to").val();  //导出考勤结束时间
        if(downtime==""){
            alert("请先选择考勤开始日期");
            return false;
        }
        if(endtTime==""){
            alert("请先选择考勤结束日期");
            return false;
        }
        return true;
    }

</script>
<script type="text/javascript">
    jeDate({
        dateCell:"#kq_date_from",
        format:"YYYY-MM-DD",
        isinitVal:true,
        isTime:true, //isClear:false,
        minDate:"2011-09-19 00:00:00",
        okfun:function(val){}
    })
    jeDate({
        dateCell:"#kq_date_to",
        format:"YYYY-MM-DD",
        isinitVal:true,
        isTime:true, //isClear:false,
        minDate:"2011-09-19 00:00:00",
        okfun:function(val){}
    })
</script>