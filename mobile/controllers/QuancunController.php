<?php
namespace mobile\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use mobile\models\UploadForm;
use yii\web\UploadedFile;
use mobile\models\ImportData;
use mobile\models\WpIschoolStudent;
/**
 * Site controller
 */
class QuancunController extends BaseController {
    public function actionCeshi(){
      // $data="FAFA000D056669010101004e85A6FF00000020171151205081750AFAF";
      // $data='aa162414e85a64126debb';
      //查询圈存信息状况
      preg_match_all("/^FAFA[0-9A-Za-z]*AFAF$/",$data,$matches);
    
     //   $num='02017121512114763369'; 
  	  for($i = 1; $i <= strlen($num)/2; $i++){
		       $nu=substr($num, ($i-1)*2, 2);
		       $str.=dechex($nu);
      }
      var_dump($str);die;
      if(!empty($matches[0])){

         $school16 = substr($data,8,6);//学校号3个字节
		 $position16 = substr($data,14,2);//位置
		 $order = substr($data,18,2);//命令码01
		 $card16 = substr($data,20,8);//IC卡号（16进制的物理卡号）
		 $money16 = substr($data,28,8); //卡内当前余额
		 $A1=substr($data,0,16);
		 $A2=substr($data,-8);

		 //进制转换
		 $sid = hexdec($school16);		
		 $phyid = hexdec($card16);
		 $balance = hexdec($money16);	     
	     if($order=='01'){	
	         //查询相关数据 
	     	 $result = check_money($phyid,$sid);
	     	 // $result=1;
	     	 if($result!=0){
	     	 	$arr=array();	     	 		     	 	
	     	 	$mon='';
	     	 	$result=array(0=>array('credit'=>1,'trade_no'=>'02017121512114763369'),1=>array('credit'=>2,'trade_no'=>'02017121512353922572'));	     	 	
	     	 	foreach($result as $key=>$value){
	     	 		  $trade_no='';
	     	 		  $credit='';
	     	 		  //先处理订单号
	     	 		  $value['trade_no']='0'.$value['trade_no'];
	     	 		  for($i = 1; $i <= strlen($value['trade_no'])/2; $i++){
				      	   $trade=substr($value['trade_no'], ($i-1)*2, 2);  
				      	   $trade16=dechex($trade);
				      	   $trade16=str_pad($trade16,2,"0",STR_PAD_LEFT);  
				      	   $trade_no.=$trade16;	   
				      	        	       	   	  
				      }
				      //再处理价格
				      $credit16=dechex($value['credit']*100);
				      $credit16=str_pad($credit16,8,"0",STR_PAD_LEFT); 
				      for ($i = 1; $i <= strlen($credit16)/2; $i++){
			               $credit.=substr($credit16, -$i*2, 2);
			          }
	     	 	      $ic=$credit.$trade_no;
					  $arr[$key]=$ic;
					  $mon+=$value['credit']*100;
			    }
			    
			    $num=dechex($mon);
				$num=str_pad($num,8,"0",STR_PAD_LEFT);   	 
			    for ($i = 1; $i <= strlen($num)/2; $i++){
			          $total.=substr($num, -$i*2, 2);
			    }
			    $count=str_pad(dechex(count($arr)),2,"0",STR_PAD_LEFT); 
			    $ic16=implode('',$arr);

	     	 }				     
	     	 echo "-----".$phyid."------".$sid."\n";		 
			 echo date("Y-m-d H:i:s")."------".$mon."\n";
			 $date =date("Ymdhis",time());	
			 $strs = $A1.$count.'1100'.$card16.$total.$ic16.$date.$A2;
			 for($i = 1; $i <= strlen($strs)/2; $i++){
				   $trade=substr($strs, ($i-1)*2, 2); 
				   $ar[$i-1]=$trade;	
			 }
			 // echo '<pre>';
			 // var_dump($ar);die;
			 $serv->send($fd,$ar);
	     }
		

      }
      //圈存成功后上报信息
      // $data='FAFA000D056669010102004e85A6FF00000020171151205081750AFAF';
      preg_match_all("/^FAFA[0-9A-Za-z]*AFAF$/", $data, $matche);
		if (!empty($matche[0])) {
			$school16 = substr($data,8,6);//学校号3个字节
			$order = substr($data,18,2);//命令码02
			$card16 = substr($data,20,8);//IC卡号（16进制的物理卡号）
			$money16 = substr($data,28,8); //卡内当前余额
			$time = substr($data,36,13); //卡内当前余额
			$sid = hexdec($school16);		
		    $phyid = hexdec($card16);
		    $money=hexdec($money16);//当前余额
			if($order=='02'){
				echo "--22-------".$phyid."\n".$money."------";
       			$result = check_moneyt($phyid,$sid,$money);
			    echo date("Y-m-d H:i:s")."---".$data."------".$sid."-------".$result."\n";
			}				      			
		 }


   
    }


}

