<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>家长扫码绑定二维码</title>
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
        // return @mkdir($path,$mode);
        return @mkdir($path,$mode);
    }
    // 学校
    $bs =$dbh->prepare('SELECT sid,cid,id,school,class,name,stuno2 from wp_ischool_student where sid=?');
    $bs->execute(array(56775));
    // var_dump($bs);die;
        foreach( $bs as $v)
        {
            // $path = "E:/phpStudy/WWW/handel/yanzm/".$v['sid']."/".$v['class']."/";
    	    $path = '/data/web/ischool/erweima/tp/jztp/'.$v['sid'].'/'.$v['class'].'/';
            echo "------------".$path."\n\r";
            mkdirs($path);
            $errorCorrectionLevel = 'L';//容错级别
            $matrixPointSize = 3; //生成图大小
            $filename = $path.$v['stuno2'].".png";//图片路径
            $urls = 'http://mobile.jxqwt.cn/information/smjzurl?stuno2='.$v['stuno2'];
            QRcode::png($urls,$filename, $errorCorrectionLevel, $matrixPointSize,2);
        }

    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>

</body>
</html>
