<?php
$childDir = date("y/m/d");
$upload="../picture/".$childDir;
if(!file_exists($upload))
{
   mkdir($upload,0755,true);
}
$file_name = "/".uniqid().".jpeg";
$path = $upload.$file_name;
$base64_str = $_POST['data'];
$ifp = fopen( $path, "wb" );
fwrite( $ifp, base64_decode( $base64_str) );
fclose( $ifp );
$a="./upload/picture/".$childDir.$file_name;
$a=ltrim($a,".");
$a = $a;
$data = json_encode(array('msg'=>"success",'file_path'=>$a,'success'=>true));
echo $data;
