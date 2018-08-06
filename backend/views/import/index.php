<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpIschoolSchoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '数据导入';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
span{display:inline-block}
</style>
<div class="wp-ischool-school-index">

<form action="/import/student" method="post" enctype="multipart/form-data">
<div>
<input type="hidden" name="<?= \Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken()?>" />
<span >学生信息导入：</span><span><input type="file" name="ImportData[upload]"></span><span><button type="button" class="import">导入</button></span><span> <a href="/document/stu.xls"> 模板 </a> </span>
</div>
</form>


<form action="/import/phones" method="post" enctype="multipart/form-data">
<div>
<input type="hidden" name="<?= \Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken()?>" />
<span >电话数据导入：</span><span><input type="file" name="ImportData[upload]"></span><span><button type="button" class="import">导入</button></span><span> <a href="/document/tel.xlsx"> 模板 </a> </span>
</div>
</form>

<form action="/import/epc" method="post" enctype="multipart/form-data">
<div>
<input type="hidden" name="<?= \Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken()?>" />
<span >EPC数据导入：</span><span><input type="file" name="ImportData[upload]" ></span><span><button type="button" class="import">导入</button></span><span> <a href="/document/epc.xlsx"> 模板 </a> </span>
</div>
</form>

<form action="/import/parents" method="post" enctype="multipart/form-data">
<div>
<input type="hidden" name="<?= \Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken()?>" />
<span >家长信息导入：</span><span><input type="file" name="ImportData[upload]"></span><span><button type="button" class="import">导入</button></span><span> <a href="/document/fam.xlsx"> 模板 </a> </span>
</div>
</form>
</div>

<form action="/import/kaku" method="post" enctype="multipart/form-data">
<div>
<input type="hidden" name="<?= \Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken()?>" />
<span >卡库信息导入：</span><span><input type="file" name="ImportData[upload]"></span><span><button type="button" class="import">导入</button></span><span> <a href="/document/kaku.xlsx"> 模板 </a> </span>
</div>
</form>

<form action="/import/userinfo" method="post" enctype="multipart/form-data">
    <div>
        <input type="hidden" name="<?= \Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken()?>" />
        <span >用户信息导入：</span><span><input type="file" name="ImportData[upload]"></span><span><button type="button" class="import">导入</button></span><span> <a href="/document/user.xlsx"> 模板 </a> </span>
    </div>
</form>

<script>
$(".import").click(
        function(){
            var s=$(this).parents("form").find('input[name="ImportData[upload]"]').val();
            if(s==""){
                alert("请选择文件");
                return false;
            }
            $(this).parents("form").submit();
        }
    );
</script>
