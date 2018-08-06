<?php
// session_start();
// $_SESSION['openid']=$openid;
// $openid = $_SESSION['openid'];
// setcookie("openid",$openid,time()+3600);
// var_dump($_cookie);exit();
?>

<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
<script type="text/javascript">
    $(function(){
        var code = <?php echo $at;?>;
        switch(code){
            case 2:alert('班主任信息已经绑定，请勿重复绑定！');
                window.location.href = "<?php echo URL_PATH;?>/information/index?openid=<?php echo $openid;?>&sid=<?php echo $sid;?>";
                break;
            case 1:
                alert('已经发送班主任绑定申请，请耐心等待');
                window.location.href = "<?php echo URL_PATH;?>/information/index?openid=<?php echo $openid;?>&sid=<?php echo $sid;?>";break;
            case 0:alert('绑定班主任成功');
                window.location.href = "<?php echo URL_PATH;?>/information/index?openid=<?php echo $openid;?>&sid=<?php echo $sid;?>";break;
        }
    });
</script>

