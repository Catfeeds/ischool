<?php
$info = file_get_contents("info.txt");
$info_arr = explode("\n",$info);
$final_arr = [];
foreach($info_arr as $row)
{
	if(empty($row)) continue;
	else {
		$trade_no = substr($row, -19);
		if(!in_array($trade_no,$final_arr))
		$final_arr[] = substr($row, -19);
	}
}
echo count($final_arr);
var_dump($final_arr);
