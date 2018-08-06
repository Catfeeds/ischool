<?php
// session_start();
// $_SESSION['openid']=$openid;
// $openid = $_SESSION['openid'];
?>


<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
 <script type="text/javascript">
 $(function(){
      var code = <?php echo $at;?>;
     switch(code){
         case 0:alert('学校或班级信息有错误，请关闭当前页面，然后进入正梵智慧校园公众号，点击【我的服务】-》【人工客服】联系人工客服');
                 exit();
             window.location.href = "<?php echo URL_PATH;?>/information/index?openid=<?php echo $openid;?>&sid=<?php echo $sid;?>";
             break;
         case 1:
             alert('学生信息尚未导入，请关闭当前页面，然后进入正梵智慧校园公众号，点击【我的服务】-》【人工客服】联系人工客服');
             exit();
             window.location.href = "<?php echo URL_PATH;?>/information/index?openid=<?php echo $openid;?>&sid=<?php echo $sid;?>";break;
         case 2:alert('绑定失败，请关闭当前页面，然后进入正梵智慧校园公众号，点击【我的服务】-》【人工客服】联系人工客服');
             exit();
             window.location.href = "<?php echo URL_PATH;?>/information/index?openid=<?php echo $openid;?>&sid=<?php echo $sid;?>";break;
         case 3:alert('您已关注过该学生');
             url="<?php echo URL_PATH;?>/information/index?openid=<?php echo $openid;?>&sid=<?php echo $sid;?>";
             window.location.href = url;break;
         case 5:alert('绑定成功');
                 url="<?php echo URL_PATH;?>/information/index?openid=<?php echo $openid;?>&sid=<?php echo $sid;?>";
             window.location.href = url;break;
     }
 });
</script>