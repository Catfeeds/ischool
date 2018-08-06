<div class="container-fluid register-user-margin">
<div class="row register-user-container">

<div class="col-xs-12 register-user-title">
<div onclick="loadHtmlByUrl('/manager/gotoallclass')">
<i style="color: white" class="fa fa-reply"></i>
&nbsp;<span style="margin-left: 20px;" class="badge">班级配置</span>
</div>
<span class="badge"><?php echo $cname ?>请假学生</span>
</div>

<?php if(empty($list_stu)) {?>
<div class="col-xs-12 text-center list-location">暂无请假信息</div>
<?php }{?>

<?php foreach ( $list_stu as $vo) {?>
<div id="s{$vo.id}" class="col-xs-12">

<div class="row register-user-title data-card">
<div class="col-xs-12 text-center"><?php echo $vo['name']?></div>
<div class="col-xs-12 text-center"><?php echo date("m月d日 H点i分",strtotime($vo['begin_time']))?> ~ <?php echo date("m月d日 H点i分",strtotime($vo['stop_time']))?></div>
</div>

</div>
<?php }}?>
</div>
</div>


