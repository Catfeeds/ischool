 <?php
  class ExportExcel{
      /**
      * @desc   将数据导出到Excel中
      * @param  $data array 设置表格数据 
      * @param  $titlename string 设置head 
      * @param  $title string 设置表头 
      * @param  $filename 设置默认文件名
      * @return 将字符串输出，即输出字节流，下载Excel文件
     */ 
     public function excelData($data,$titlename,$title,$filename){ 
         #xmlns即是xml的命名空间
        $str = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\r\nxmlns:x=\"urn:schemas-microsoft-com:office:excel\"\r\nxmlns=\"http://www.w3.org/TR/REC-html40\">\r\n<head>\r\n<meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n</head>\r\n<body>"; 
        #以下构建一个html类型格式的表格
        // $str .= $title; 
         $str .="<table border=1><head>".$titlename."</head>"; 
        foreach ($data  as $key=> $rt ){ 
             $str .= "<tr>"; 
             foreach ( $rt as $k => $v ) 
             { 
                 $str .= "<td>{$v}</td>"; 
             } 
             $str .= "</tr>\n"; 
         } 
         $str .= "</table></body></html>"; 
         header( "Content-Type: application/vnd.ms-excel; name='excel'" );   #类型
         header( "Content-type: application/octet-stream" );     #告诉浏览器响应的对象的类型（字节流、浏览器默认使用下载方式处理）
         header( "Content-Disposition: attachment; filename=".$filename );   #不打开此文件，刺激浏览器弹出下载窗口、下载文件默认命名
         header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" ); 
         header( "Pragma: no-cache" );   #保证不被缓存或者说保证获取的是最新的数据
         header( "Expires: 0" ); 
         exit( $str ); 
     }
 
 
 }
 
 
 
