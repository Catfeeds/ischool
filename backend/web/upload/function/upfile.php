<?php
	
//此路径是相对于本文件(uploadimg.php)来说的
$upload="../file/".date("y/m/d");
$data=date("y/m/d");
if(!file_exists($upload))
{
mkdir($upload,0755,true);
}
        $ext = pathinfo($_FILES['uloadfile']['name'],PATHINFO_EXTENSION);
        $name = uniqid().".".$ext;
//2.将文件移动到指定目录(核心代码)
move_uploaded_file($_FILES['uloadfile']['tmp_name'],$upload."/".$name);
$a="./upload/file/".$data."/".$name;
$a=ltrim($a,".");
$a=$PATH.$a;
$b=$_FILES['uloadfile']['name'];
echo "<script>parent.callback('$a','true','$b')</script>"; 


