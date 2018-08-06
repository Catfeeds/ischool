
<div class="upload-wrapper">
<div id="upload-picture">上传图片</div>
<input id="upload-input" accept="image/*" capture="camera" type="file" name="upload-input" style="display:none">
</div>

<div class="picture-list-wrapper">

<?php if(empty($carousels)) {?>
<div class="empty-picture">暂未上传轮播图片</div>
<?php 
}
else{
foreach ($carousels as $key => $vo) {
?>
<div class="picture-list">
<div class="picture-narrow">
<div class="background-image" style="background-image: url(<?php echo $vo['picurl']?>);"></div>
</div>
<div class="picture-delete">
<div class="picture-delete-wrapper">
<div class="delete-picture" id="<?php echo $vo['id']?>"><a href="#0">删除</a></div>
</div>
</div>
</div>
<?php }}?>

</div>

<!-- JS代码区 -->
<script>
manage.setCarousel();
</script>
</body>
</html>
