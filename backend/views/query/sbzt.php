<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\controllers\UtilsController;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '设备状态';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-ischool-class-index">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
<table class="table table-striped" id="zhuangtai"></table>
<script type="text/javascript">
    //setInterval("myInterval()",1000);//1000为1秒钟
    function myInterval()
    {
       var url = "/query/sbzt";
        var formdata = {};
                formdata = {
                            "056759":"柘城县学苑中学",
                            "056758":"舞钢市第一高级中学",
                			"056757":"获嘉县第一中学",
     //                       "056756":"新乡优行教育分校",
       //                     "056755":"濮阳县第一高级中学",
                            "056744":"正梵高级中学",
                            "056742":"临颍县窝城镇中心小学",
                            "056741":"漯河市邓襄镇第一初级中学  ",
                            "056740":"许昌新区实验学校",
                            "056739":"商水县新世纪学校",
                            "056738":"商水县第一高级中学",
                            "056736":"许昌市榆林乡柏庄学校",
                            "056732":"临颍县王孟中心小学",
                            "056731":"临颍县陈留中心小学",  
                            "056707":"西平县人和育才小学",
                            "056698":"王孟镇范庙小学",
                            "056689":"漯河市第五高级中学",
                            // "056685":"石桥乡中心小学",
                            "056684":"台陈一中",
                            "056683":"河南省临颍县职业教育中心",
                            "056682":"窝城二中",
                            "056681":"王岗二中",
                            "056675":"漯河市艺术学校",
                            "056673":"王洛中心社区小学",
                            "056670":"襄城县玉成学校",
                            "056666":"石桥一中",
                            "056665":"窝城一中",
                            "056664":"王孟一中",
                            "056654":"马庙小学",
                            "056653":"巨陵二中",
                            "056652":"巨陵一中",
                            "056650":"许昌市建安区第三高级中学",
                            "056649":"许昌市大同街小学",
                            "056623":"许昌市建安区实验中学",
                            "056762":"柘城县老王集中心中学"
                };
        // formdata.userid = {"56651","056739","055555"};
        // formdata.userid = "056739,055555,056655";
       $.post(url,formdata).done(function (data){
            // data = $.parseJSON( data );
            var leixin=JSON.parse(data);
                        console.log(data);

            var htmltrs;
            for(var k in leixin["bzc"]){
                htmltrs +="<tr>";
                       htmltrs +="<td>" +leixin["bzc"][k] + "</td>"+"<td>" +k + "</td>"+"<td>不正常</td>";
               htmltrs +="</tr>";
            }
            for(var k in leixin["zc"]){
                htmltrs +="<tr>";
                       htmltrs +="<td>" +leixin["zc"][k] + "</td>"+"<td>" +k + "</td>"+"<td>正常</td>";
               htmltrs +="</tr>";
            }
            $("#zhuangtai").html(htmltrs);
        })
       
    }
    $(function(){
        myInterval();
    })
</script>
