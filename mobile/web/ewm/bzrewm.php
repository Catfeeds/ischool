<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>班主任绑定班级生成一个学校的二维码专用PHP脚本文件</title>
</head>
<body>
<?php
header("Content-Type: text/html; charset=utf-8");
//引入核心库文件
include "phpqrcode/phpqrcode.php";
//定义生成内容
$content="微信公众平台：思维与逻辑;公众号:siweiyuluoji";
try {
    $dbh = new PDO("mysql:host=127.0.0.1;dbname=ischool", "root", 'hnzf123456',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    function mkdirs($path, $mode = 0777)
    {
        if (is_dir($path) || @mkdir($path, $mode)) return TRUE;
        if (!mkdirs(dirname($path), $mode)) return FALSE;
        return @mkdir($path,$mode);
    }

    // 学校
    $bs =$dbh->prepare('SELECT id,name,sid,school from wp_ischool_class where sid=?');
    $bs->execute(array(56649));
    // 班级
    // $bs =$dbh->prepare('SELECT sid,cid,id,school,class,name,stuno2 from wp_ischool_student where sid=? and cid=?');
    //    $bs->execute(array(56650,5146));
        foreach( $bs as $v)
        {
            $path = '/data/web/ischool/erweima/tp/bzrtp/'.$v['sid'].'/'.$v['name'].'/';
            echo "------------".$path."\n\r";
            mkdirs($path);
            $errorCorrectionLevel = 'L';//容错级别
            $matrixPointSize = 3; //生成图大小
            $filename = $path.$v['school'].$v['name'].".png";//图片路径
            $urls = 'http://mobile.jxqwt.cn/information/smurl?cid='.$v['id'];
            $appid = "wx8c6755d40004036d";
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$urls.'&&getcode&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
            QRcode::png($urls, $filename, $errorCorrectionLevel, $matrixPointSize,2);
        }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
echo "二维码生成完成";
?>

</body>
</html>
