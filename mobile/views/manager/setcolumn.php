<div id="addcolumn-wrapper">
<div id="addcolumn-input">
<input type="text" id="columnName" placeholder="请输入新增的栏目标题...">
</div>
<div id="addcolumn-new-wrapper">
<div id="addcolumn-new-button">新增</div>
</div>
<div class="clear"></div>
</div>

<div id="allcalumns">

<?php foreach ($columns as $key => $vo) {?>
<div class="calumns-list" id="div<?php echo $vo['id']?>">
<div class="calumns-edit-status" style="display:none">
<div class="calumns-title"><input type="text" class="columnName" id="c<?php echo $vo['id']?>" value="<?php echo $vo['name']?>"></div>
<div class="save-calumns" name="<?php echo $vo['id']?>">保存</div>
</div>
<div class="calumns-read-status" style="display:block">
<div class="calumns-title-text"><?php echo $vo['name']?></div>
<div class="edit-calumns" >编辑</div>
</div>
<div class="delete-calumns" name="<?php echo $vo['id']?>">删除</div>
<div class="clear"></div>
</div>
<?php }?>
</div>

<script type="text/javascript">
manage.setColumn();
</script>
