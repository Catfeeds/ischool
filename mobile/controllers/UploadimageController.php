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
use mobile\models\WpIschoolSchool;
use mobile\models\WpIschoolUser;
use mobile\models\WpIschoolOrderjf;
use mobile\models\WpIschoolOrderjx;
use mobile\models\WpIschoolOrderbk;
use mobile\models\WpIschoolTeaclass;
use mobile\models\WpIschoolClass;
use mobile\models\WpIschoolKaku;
use mobile\models\WpIschoolStudentCard;
use mobile\models\WpIschoolPastudent;
use mobile\models\ZfCardInfo;
use yii\helpers\ArrayHelper;
require_once('/data/web/ischool/mobile/assets/SendWeixin2.php');
require_once('/smart/upload.php');
/**
 * Site controller
 */
class UploadimageController extends BaseController {
    private $source_data;
    private $export_data;
    public function actionUpload(){       
         //获取图片名称
         $file_name=\yii::$app->request->get("file_name"); 

         //获取图片流
         $streamData = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : ''; 
  
          if(empty($streamData)){ 
            $streamData = file_get_contents('php://input'); 
          } 
          //格式转化
          $streamData=base64_decode($streamData);  

          $ret=\UpImage::mkdir($file_name,$streamData);   
                      
            if($ret > 0)
            {
                  //对图片名称进行处理，获取进出校信息，以及电话卡号
                  //位置信息
                  $position=substr($file_name,6,2);              
                  switch($position){
                    case '01':
                      $position="大门口";
                      break;
                    case '02':
                      $position="小门口";
                      break;
                    case '03':
                      $position="东校区南门";
                      break;
                    case '04':
                      $position="东校区西门";
                      break;
                    case '05':
                      $position="东校区南门";
                      break;
                    case '06':
                      $position="西校区南门";
                      break;
                    default:
                      $position="宿舍门口";
                      
                  }
                  //状态信息
                  $state = substr($file_name,8,2);
                  if($state=='01'){
                    $state="进校";
                  }else{
                    $state="出校";
                  }
                  //获取电话卡号，同时获取学生信息
                  $stu_info=$this->getcard($file_name);   
                  //获取学校名称
                  $sid= substr($file_name,1,5); 
                  $school=WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->one();
                  //组织信息
                  $msg=$school['name'].$position.$state;
                  //获取openid
                  $openids = $this->getAllOpenid($stu_info['id']);
                  // $msg = "柘城县学苑中学进/出校";
                  //图片路径
                  $img_url_path = "http://mobile.jxqwt.cn/upload/imgs/".$file_name.".jpg";
                  //$timeinfo = date("Y-m-d H:i:s");
		               //时间格式
                  $y=substr($file_name,18,4);
                  $m=substr($file_name,22,2);
                  $d=substr($file_name,24,2);
                  $h=substr($file_name,26,2);
                  $i=substr($file_name,28,2);
                  $s=substr($file_name,30,2);
                  $timeinfo=$y."年".$m."月".$d."日".$h."时".$i."分".$s."秒";
                  foreach($openids as $key => $value){
                    $pattern_url = "/^((?!okr7Gv).)*$/is"; 
                    if (preg_match($pattern_url,$value['openid'])){ 
                         \sendWeiXin2::sendSafe($value['openid'],$stu_info['name'],$msg,$timeinfo,$img_url_path,$sid="");
                    }else{ 
                      $sid='56650';
                      \sendWeiXin2::sendSafe($value['openid'],$stu_info['name'],$msg,$timeinfo,$img_url_path,$sid);
                       
                    } 
                      
                  }
            }else{
              exit;
            }     
    }
    //数组去重
    private function array_unique_fb($origin){
        foreach ($origin as $key => $v){
            $v = json_encode($v);
            $temp[$key] = $v;
        }
        return $temp;
    }
    function FetchRepeatMemberInArray($array) { 
        // 获取去掉重复数据的数组 
        $unique_arr = array_unique ( $array ); 
        // 获取重复数据的数组 
        $repeat_arr = array_diff_assoc ( $array, $unique_arr ); 
        return $repeat_arr; 
    }
    //一体机服务
    public function actionSolution(){
        $receiveData=file_get_contents("php://input");  
        $date=date("Y-m-d H:i:s",time());
        $mobile_url= Yii::getAlias('@mobile');
        file_put_contents($mobile_url.'/runtime/logs/ykt_log.txt',$date." ".$receiveData."\r\n", FILE_APPEND);
        $receiveData=json_decode($receiveData,true);
        $return=array();
        $return['School']=$receiveData['School'];
        // return $return['School'];
        $return['Devid']=$receiveData['Devid'];
        $return['Cmd']=$receiveData['Cmd'];
        if($receiveData['Cmd']=='01'){
            $sid=substr($receiveData['School'],1);
            if(isset($receiveData['Telnum1'])){
                $tel1=$receiveData['Telnum1'];
                $stu1= WpIschoolPastudent::find()->select('stu_id')->where(['tel'=>$tel1])->asArray()->all();
            }
            if(isset($receiveData['Telnum2'])){
                $tel2=$receiveData['Telnum2'];
                $stu2= WpIschoolPastudent::find()->select('stu_id')->where(['tel'=>$tel2])->asArray()->all();
            }          
            if($stu1 || $stu2){
              $stu=array_merge($stu1,$stu2);
              $stu=$this->array_unique_fb($stu);
              $temp=$this->FetchRepeatMemberInArray($stu);
              $iii=0;
              foreach ($temp as $k => $val){
                   $info[$iii] =json_decode($val,true);
                   $iii++;
              }
              file_put_contents($mobile_url.'/runtime/logs/ykt_log.txt',"stu_id:".json_encode($info)."\r\n", FILE_APPEND);
              $return['Rysl']=count($info);
              foreach($info as $k => $v){
                  //查询餐卡人员编号
                  $stuInfo=WpIschoolStudent::findOne($v['stu_id']);
                  $stuno=$stuInfo->stuno2;
                  if(preg_match('/[a-zA-Z]/',$stuno)){
                      $user_no= substr($stuno,6);                    
                  }else{
                      $user_no= substr($stuno,2); 
                  }
                  file_put_contents($mobile_url.'/runtime/logs/ykt_log.txt',"user_no:".$user_no."\r\n", FILE_APPEND);
                  $ckInfo=ZfCardInfo::find()->where(['school_id'=>$sid,'user_no'=>$user_no])->asArray()->one();
                  $m=$k+1;
                  if($ckInfo){
                     if(preg_match('/[a-zA-Z]/',$ckInfo['user_no'] )){
                          $return['Ckrybh'.$m]= $ckInfo['user_no'];                     
                     }else{
                          $return['Ckrybh'.$m]= 'T'.$sid.$ckInfo['user_no']; 
                     }
                      // $return['Ckrybh'.$m]=$ckInfo['user_no'];                     
                  }
                  $res=WpIschoolOrderbk::find()->select('sfbk')->where(['stuid'=>$v['stu_id'],'ispass'=>1])->asArray()->orderBy('id DESC')->one();                 
                  $return['Bkzt'.$m] =($res['sfbk']=='0')?"11":'00';
                  //查询水卡人员编号
                  $pdo = $this->getdb_sk();         
                  $stmt = $pdo->prepare("select * from zf_card_info where user_no = ? and school_id = ? and status='zhengchang'");
                  $stmt -> execute([$stuno,$sid]);
                  $result = $stmt->fetch();
                  file_put_contents($mobile_url.'/runtime/logs/ykt_log.txt',"skuser_no:".$result['user_no']."\r\n", FILE_APPEND);
                  if($result){
                      $return['Skrybh'.$m]=$result['user_no'];
                  }
              }
              //验证成功             
              $return['Result']='00';
              
              
            }else{
              //验证失败
              $return['Result']='11';
            }
        }else if($receiveData['Cmd']=='02'){
            file_put_contents($mobile_url.'/runtime/logs/ykt_log.txt',"tel_no:".$receiveData['Telid']."\r\n", FILE_APPEND);
            $info=WpIschoolKaku::find()->select('epc')->where(['telid'=>$receiveData['Telid']])->asArray()->one();
            if($info){
              $return['Result']='00';
              $return['EPCid']=$info['epc'];
            }else{
              $return['Result']='11';             
            }
            $return['Ickh']=$receiveData['Ickh'];
        }else if($receiveData['Cmd']=='03'){
            if($receiveData['Bkzt']=='11'){
              if(isset($receiveData['Ckrybh']) && isset($receiveData['Skrybh'])){
                  $stuno2=$return['Ckrybh'] = $return['Skrybh'] = $receiveData['Ckrybh'];                 
              }else if(isset($receiveData['Ckrybh'])){
                  $stuno2=$return['Ckrybh']  = $receiveData['Ckrybh'];
              }else{
                  $stuno2=$return['Skrybh']  = $receiveData['Skrybh'];
              }
              $sid=substr($receiveData['School'],1);
              if(!preg_match('/[a-zA-Z]/',$stuno2) && strlen($stuno2)!=10){
                  $stuno2= 'T'.$sid.$stuno2;                     
              }
              $stuInfo = WpIschoolStudent::find()->where(['stuno2'=>$stuno2])->one();
              file_put_contents($mobile_url.'/runtime/logs/ykt_log.txt',"stuid:".$stuInfo['id']."\r\n", FILE_APPEND);
              if($stuInfo){
                  $telid=$receiveData['Ickh'];
                  // $phyid=$receiveData['Ickh'];
                  // $four=substr($phyid,6,2);
                  // $three=substr($phyid,4,2);
                  // $two=substr($phyid,2,2);
                  // $one=substr($phyid,0,2);
                  // $phyid=$four.$three.$two.$one;      
                  // $telid=hexdec($phyid);
                  file_put_contents($mobile_url.'/runtime/logs/ykt_log.txt',"telid:".$telid."\r\n", FILE_APPEND);
                  $kakuInfo=WpIschoolKaku::find()->where(['telid'=>$telid])->one();
                  if($kakuInfo){
                      $stuInfo->cardid=$kakuInfo['epc'];
                      $stuInfo->save(false);
                      $telInfo=WpIschoolStudentCard::find()->where(['stu_id'=>$stuInfo['id']])->one();
                      if($telInfo){
                          $telInfo->card_no=$kakuInfo['telid'];
                          $telInfo->save(false);
                      }else{
                         $tel=new WpIschoolStudentCard;
                         $tel->stuid=$stuInfo['id'];
                         $tel->card_no=$kakuInfo['telid'];
                         $tel->flag=1;
                         $tel->ctime=time();
                         $tel->save(false);
                      }
                      if(preg_match('/[a-zA-Z]/',$stuno2 )){
                          $user_no= substr($stuno2,6);                     
                      }else if(strlen($stuno2)==10){
                          $user_no= substr($stuno2,2); 
                      }else{
                          $user_no= $stuno2;
                      }
                      if(isset($receiveData['Ckrybh']) && isset($receiveData['Skrybh'])){
                          $cardInfo=$ckInfo=ZfCardInfo::find()->where(['school_id'=>$sid,'user_no'=>$user_no])->one();
                          if($cardInfo){
                              $cardInfo->phyid=$kakuInfo['telid'];
                              $cardInfo->card_no=$receiveData['Ckrykh'];
                              $cardInfo->save(false);
                          }
                          $pdo = $this->getdb_sk();
                          $stmt = $pdo->prepare("update zf_card_info set card_no=:card_no ,phyid=:phyid  where school_id = :school_id and user_no=:user_no");
                          $stmt->execute([":card_no" => $receiveData['Skrykh'],":phyid"=>$kakuInfo['telid'],":school_id"=>$sid,':user_no'=>$stuno2]); 
                      }else if(isset($receiveData['Ckrybh'])){
                          $cardInfo=$ckInfo=ZfCardInfo::find()->where(['school_id'=>$sid,'user_no'=>$user_no])->one();
                          if($cardInfo){
                              $cardInfo->phyid=$kakuInfo['telid'];
                              $cardInfo->card_no=$receiveData['Ckrykh'];
                              $cardInfo->save(false);
                          }
                      }else{
                          $pdo = $this->getdb_sk();
                          $stmt = $pdo->prepare("update zf_card_info set card_no=:card_no ,phyid=:phyid  where school_id = :school_id and user_no=:user_no");
                          $stmt->execute([":card_no" => $receiveData['Skrykh'],":phyid"=>$kakuInfo['telid'],":school_id"=>$sid,':user_no'=>$stuno2]);
                      }
                      
                      
                  }
                  
                  $bk=WpIschoolOrderbk::find()->where(['stuid'=>$stuInfo['id'],'ispass'=>'1','sfbk'=>'0'])->orderBy('id DESC')->one();
                  if($bk){
                    $bk->sfbk='1';
                    $bk->save(false);
                  }
              }
              
              $return['Result']="11";
            }
        }else if($receiveData['Cmd']=='04'){
            $sid=substr($receiveData['School'],1);
            $return['Ickh']=$receiveData['Ickh'];
            if($receiveData['Ckqc']=='00'){
              $result= $this->check_qc($receiveData['Ickh'],$sid,'ck');
              $res=empty($result) ? array('result'=>0) : $result ;
              file_put_contents($mobile_url.'/runtime/logs/ykt_log.txt',"result:".json_encode($res)."\r\n", FILE_APPEND);

              if($result){
                $count=count($result);
                $money=0;
                foreach($result as $key=>$value){
                    $money=$value['credit']+$money;
                }
                $return['Ckczze']=$money*100;
                $return['Ckczs']=$count;
                foreach($result as $key=>$value){
                    $a=$key+1;
                    $return['Ckcz'.$a]=$value['credit']*100;
                    $return['Ckczjym'.$a]=$value['trade_no'];
                }
              }else{
                 $return['Ckczze']=0;
                 $return['Ckczs']=0;
              }
            }
            if($receiveData['Skqc']=='00'){
              $result= $this->check_qc($receiveData['Ickh'],$sid,'sk');
              $res=empty($result) ? array('result'=>0) : $result ;
              file_put_contents($mobile_url.'/runtime/logs/ykt_log.txt',"result:".json_encode($res)."\r\n", FILE_APPEND);
              if($result){
                $count=count($result);
                $money=0;
                foreach($result as $key=>$value){
                    $money=$value['credit']+$money;
                }
                $return['Skczze']=$money*100;
                $return['Skczs']=$count;
                foreach($result as $key=>$value){
                    $a=$key+1;
                    $return['Skcz'.$a]=$value['credit']*100;
                    $return['Skczjym'.$a]=$value['trade_no'];
                }
              }else{
                 $return['Skczze']=0;
                 $return['Skczs']=0;
              }
            }
        }else if($receiveData['Cmd']=='05'){
            $sid=substr($receiveData['School'],1);
            // $return['Ickh']=$receiveData['Ickh'];
            if($receiveData['Ckqccs']>0 && $receiveData['Ckqczt']=='11'){
              $result= $this->update_qc($receiveData['Ickh'],$sid,$receiveData['Ckqcze'],'ck');
            }
            if($receiveData['Skqccs']>0 && $receiveData['Skqczt']=='11'){
              $result= $this->update_qc($receiveData['Ickh'],$sid,$receiveData['Skqcze'],'sk');             
            }
            if($result){
              $return['Ickh']=$receiveData['Ickh'];
              $return['Result']='11';
            }
        }else if($receiveData['Cmd']=='00'){
            $return['Result']='00';
        }
        return json_encode($return);

    }
    protected function update_qc($phyid,$school_id,$money,$type='ck'){
      $four=substr($phyid,6,2);
      $three=substr($phyid,4,2);
      $two=substr($phyid,2,2);
      $one=substr($phyid,0,2);
      $phyid=$four.$three.$two.$one;      
      $phyid=hexdec($phyid); 
      $mobile_url= Yii::getAlias('@mobile');
      file_put_contents($mobile_url.'/runtime/logs/ykt_log.txt',"phyid:".$phyid."\r\n", FILE_APPEND);
      try{
          if($type=='ck'){
            $pdo = $this->getdb();
          }else{
            $pdo = $this->getdb_sk();
          }
          $stmt = $pdo->prepare("select * from zf_card_info where phyid = ? and school_id = ?");
          $stmt -> execute([$phyid,$school_id]);
          $result = $stmt->fetch();
          if($result){
            $stmt1 = $pdo->prepare("select * from zf_recharge_detail where card_no = :id and (type = 'weixinchongzhi' or type='ZFBJSAPI' or type='WXAPPJSAPI') and is_active = 0 and school_id = :school_id order by id asc");
            $stmt1->execute([":id"=>$result['card_no'],":school_id"=>$school_id]);
            $row = $stmt1 -> fetchAll();
            if($row){
              foreach($row as $k=>$v){
                $ret_money+=$v['credit'];
                $balance_money = number_format($money/100,2,'.','');
                $stmt2 = $pdo->prepare("update zf_recharge_detail set qctime = :time, is_active = 1, balance = :balance where id = :id and (type = 'weixinchongzhi' or type='ZFBJSAPI' or type='WXAPPJSAPI')");
                  $stmt2->execute([":time" => time(),":balance"=>$balance_money,":id"=>$v['id']]);    
                  $stmt3 = $pdo->prepare("update zf_card_info set balance =  ? where id = ?");
                  $stmt3->execute([$balance_money,$result['id']]);
              }   
              return $ret_money * 100;
            }else return 0;
          }else return 0;
          $pdo->null;
      } catch (PDOException $e) {
          print "Error!: " . $e->getMessage() . "<br/>";
          die();        
      } 
    }
    protected function check_qc($phyid,$school_id,$type='ck'){
      $four=substr($phyid,6,2);
      $three=substr($phyid,4,2);
      $two=substr($phyid,2,2);
      $one=substr($phyid,0,2);
      $phyid=$four.$three.$two.$one;      
      $phyid=hexdec($phyid); 
      $mobile_url= Yii::getAlias('@mobile');
      file_put_contents($mobile_url.'/runtime/logs/ykt_log.txt',"phyid:".$phyid."\r\n", FILE_APPEND);
      try{
          if($type=='ck'){
            $pdo = $this->getdb();
          }else{
            $pdo = $this->getdb_sk();
          }
          $stmt = $pdo->prepare("select * from zf_card_info where phyid = ? and school_id = ? and status='zhengchang'");
          $stmt -> execute([$phyid,$school_id]);
          $result = $stmt->fetch();
          if($result)
          {
            $stmt1 = $pdo->prepare("select * from zf_recharge_detail where card_no = :id and (type = 'weixinchongzhi' or type='ZFBJSAPI' or type='WXAPPJSAPI') and is_active = 0 and school_id = :school_id order by id asc");
            $stmt1->execute([":id"=>$result['card_no'],":school_id"=>$school_id]);
            $row = $stmt1 -> fetchAll();
            if($row)
            {     
              return $row;
            }else return 0;
          }else return 0;
          $pdo->null;
      } catch (PDOException $e) {
          print "Error!: " . $e->getMessage() . "<br/>";
          die();        
      }

    }
    public function actionUploadEnterprise(){       
         //获取图片名称
         $file_name=\yii::$app->request->get("file_name"); 
          // $file_name='100000010286C642CF20180110152424971';
         //获取图片详细信息
         $streamData = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : ''; 
  
        if(empty($streamData)){ 
            $streamData = file_get_contents('php://input'); 
        } 
        //格式转化
        $streamData=base64_decode($streamData);  

        $ret=\UpImage::mkdir($file_name,$streamData);   
                      
          if($ret > 0)
          {
                //对图片名称进行处理，获取进出校信息，以及电话卡号
                //位置信息
                $position=substr($file_name,6,2);              
                switch($position){
                  case '01':
                    $position="大门口";
                    break;
                  case '02':
                    $position="小门口";
                    break;              
                  default:
                    $position="宿舍门口";
                    
                }
                
                //状态信息
                $state = substr($file_name,8,2);
                if($state=='01'){
                  $state="进厂";
                }else{
                  $state="出厂";
                }
                //获取电话卡号，同时获取学生信息
                $stu_info=$this->get_jingan_card($file_name); 
                
                // //获取学校名称
                $sid=substr($file_name,0,6);
                // var_dump($sid);die;
                 $school=$this->get_jingan_school($sid);


                //组织信息
                 $msg=$school['name'].$position.$state;
                  
                 // $msg='nanjiecun'.$position.$state;
                //获取openid
                $openids = $this->get_jingan_openid($stu_info['id']);
                // echo "<pre>";
                //  var_dump( $openids);die;
                // $msg = "柘城县学苑中学进/出校";
                //图片路径
                $img_url_path = "http://mobile.jxqwt.cn/upload/imgs/".$file_name.".jpg";
                //$timeinfo = date("Y-m-d H:i:s");
                 //时间格式
                $y=substr($file_name,18,4);
                $m=substr($file_name,22,2);
                $d=substr($file_name,24,2);
                $h=substr($file_name,26,2);
                $i=substr($file_name,28,2);
                $s=substr($file_name,30,2);
                $timeinfo=$y."年".$m."月".$d."日".$h."时".$i."分".$s."秒";
                foreach($openids as $key => $value){            
                    $sid='100000';
                    \sendWeiXin2::sendSafe($value['openid'],$stu_info['name'],$msg,$timeinfo,$img_url_path,$sid);                     
                }
          }else{
            exit;
          }     
    }
    public function get_jingan_school($sid){
        try{
            $pdoja=$this->get_jadb();
            $sql="select name from wp_ischool_school where id= ?";
            $stmtja = $pdoja->prepare($sql);
            $stmtja ->execute([$sid]);
            $result=$stmtja->fetch(\PDO::FETCH_ASSOC);
            $pdoja->null;
            return $result;
            
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();        
        } 
    }
    public function get_jingan_card($filename){
        //获取16进制电话卡号
        $four=substr($filename,16,2);
        $three=substr($filename,14,2);
        $two=substr($filename,12,2);
        $one=substr($filename,10,2);
        $cardno=$four.$three.$two.$one;      
        $cardno=hexdec($cardno);   
        //根据电话卡号获取学生信息       
        try{
            $pdoja=$this->get_jadb();
            $sql="select * from wp_ischool_student where telid= ?";
            $stmtja = $pdoja->prepare($sql);
            $stmtja ->execute([$cardno]);
            yii::trace($sql);
            $result=$stmtja->fetch(\PDO::FETCH_ASSOC);
            $pdoja->null;
            return $result;
            
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();        
        }             
    }
    //
    public function get_jingan_openid($stuid){
        try{
            $pdoja=$this->get_jadb();
            $sql="select openid from wp_ischool_user where last_stuid = ?";
            $stmtja = $pdoja->prepare($sql);
            $stmtja ->execute([$stuid]);
            $result=$stmtja->fetchAll(\PDO::FETCH_ASSOC);
            $pdoja->null;
            return $result;
            
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();        
        }
        // $modles = WpIschoolPastudent::find()->where(['and',"stu_id = $stuid","openid <>''"])->asArray()->all();
        // return $modles;
    }
    //连接景安服务器
     public function get_jadb(){
      try{
             $pdoja=new \PDO('mysql:host=122.114.51.145;port=3306;dbname=qiyeischool','ischool','!@#$ASDvgrrz7*9jqB');
             $pdoja->query("set character set 'utf8'");
      }catch (PDOException $e) {
             print "Error!: " . $e->getMessage() . "<br/>";
             die();        
      }   
      return $pdoja;
    }
    public function getcard($filename){
        //获取16进制电话卡号
        $four=substr($filename,16,2);
        $three=substr($filename,14,2);
        $two=substr($filename,12,2);
        $one=substr($filename,10,2);
        $cardno=$four.$three.$two.$one;      
        $cardno=hexdec($cardno);   
        //根据电话卡号获取学生信息
        $stuid=WpIschoolStudentCard::find()->select('stu_id')->where(['card_no'=>$cardno])->asArray()->all();
        foreach($stuid as $key=>$value){
            $stuinfo=WpIschoolStudent::find()->where(['id'=>$value['stu_id']])->asArray()->one();
            if($stuinfo){
                return $stuinfo;
            }
        }        
       
    }
 
    public function actionUp(){
      if (\Yii::$app->request->isPost) {
         $this->initExcel();
      
     
        foreach($this->source_data as $k=>$v){
          if($v['V']!='商户数据包'){
            $arr=explode('|',$v['V']);                  
            $arr1[$k]['id']=$arr[3];
            $arr1[$k]['jftype']=$arr[7];
            $t=substr($v['A'],1);
            $arr1[$k]['time']=$t;
            
            $to=substr($v['M'],1);
            if($to!='0.00'){
              $arr1[$k]['total']=$to;
            }else{
              $tuikuan=substr($v['Q'],1);
              $arr1[$k]['total']='-'.$tuikuan;
            }
            
            $m=substr($v['G'],1);
            $arr1[$k]['trade_no_yuan']=$v['G'];
            $arr1[$k]['trade_no']=$m;
            $op=substr($v['H'],1);
            $arr1[$k]['openid']=$op;

          }
        }
        $u_id=$arr1;
        // echo "<pre>";
        // var_dump($u_id);
        foreach($u_id as $k=>$v){
          // $a=(int)$v['id'];
          if (preg_match('/[a-zA-Z]/',$v['id']) || strlen($v['id'])>=9){
            //包含字母是卡号
            $res= WpIschoolStudent::find()->select('school,name,class,cid')->where(['stuno2'=>$v['id']])->asArray()->all();
          }else{
            //不包含字母是学生id
            $res= WpIschoolStudent::find()->select('school,name,class,cid')->where(['id'=>$v['id']])->asArray()->all();
          }
          
          if($v['jftype']!='jffw' && $v['jftype']!='ckcz'){          
              $jx=WpIschoolOrderjx::find()->select('zfopenid')->where(['trade_no'=>$v['trade_no']])->asArray()->all();
              if(!$jx){
                 $bk=WpIschoolOrderbk::find()->select('zfopenid')->where(['trade_no'=>$v['trade_no']])->asArray()->all();
              }
          }else if($v['jftype']=='jffw'){
              $jf=  WpIschoolOrderjf::find()->select('type')->where(['trade_no'=>$v['trade_no']])->asArray()->all();
          }else if($v['jftype']=='ckcz'){
              $jf=null;
          }
          
          
           $u_id[$k]['school']=$res[0]['school'];
           
           $u_id[$k]['name']=$res[0]['name'];
           $u_id[$k]['class']=$res[0]['class'];
           //判断东西校区
           //西校区班级id
           $arr_school=['5558','5559','5560','5613','5614','5615','5616','5617','5618','5619','5620','5621','5622','5664','5665','5666','5667',
           '5668','5590'];
           if(in_array($res[0]['cid'],$arr_school)){
              $u_id[$k]['xiaoqu']='西校区';
           }else{
              $u_id[$k]['xiaoqu']='东校区';
           }
           if($jx){
              $u_id[$k]['type']='功能费';
           }else if($jf){
              $u_id[$k]['type']=$jf[0]['type'];
           }else if($bk){
              $u_id[$k]['type']='补卡';
           }else{
              $u_id[$k]['type']='餐卡';
           }       
           if($u_id[2]['school']=='许昌市建安区第三高级中学'){
                $status=1;
           }else{
                $status=0;
           }
           foreach( $u_id as $key=>$ret){
                $u_id[$key]['id']=$key-1;
                unset($u_id[$key]['jftype']);
                unset($u_id[$key]['trade_no']);
                unset($u_id[$key]['openid']); 
                        
           }
      }
      //   echo "<pre>";
      // var_dump($u_id);
      foreach($u_id as $k=>$v){
        $data[$k]['id']=$v['id'];
        $data[$k]['school']=$v['school'];
        $data[$k]['type']=$v['type'];
        $data[$k]['time']=$v['time'];
        $data[$k]['class']=$v['class'];
        $data[$k]['name']=$v['name'];
        $data[$k]['total']=$v['total'];               
        $data[$k]['trade_no_yuan']=$v['trade_no_yuan'];
        if($status==1){
          $data[$k]['xiaoqu']=$v['xiaoqu'];         
        } 
      }

     
       require_once("/data/web/ischool/mobile/assets/ExportExcel.php");
      // $data= $u_id;
      $excelHead = "充值记录"; 
      $title = date("YmdHis",time());   #文件命名
      $headtitle= "<tr><th  colspan='3' >{$excelHead}</th></tr>"; 
      $titlename = "<tr> 
                <th style='width:70px;'>充值记录</th> 
                 </tr>"; 
      $filename = $title.".xls"; 
      \ExportExcel::excelData($data,$titlename,$headtitle,$filename);
 
      // $count= count($u_id)-2;   
      // $return_arr['stu']=$u_id;
      // $return_arr['count']=$count;
      // $return_arr['status']=$status;
    }
      // echo "<pre>";
      // var_dump($u_id);
     
     
      return $this->render('up',$return_arr);
    }

    public function actionCheckclass(){
     
        if (\Yii::$app->request->isPost) {
          $this->initExcel();     
          $y=0;
          $ii=0;
          $n=0;
          $mm=0;
          $m=0;
          $arr=array();
          foreach($this->source_data as $k=>$v){           
             if(strlen($v['G'])==9){
                  $v['G']='0'.$v['G'];
             }
             //高三的数据
             $result= WpIschoolKaku::find()->where(['telid'=>$v['G']])->asArray()->one();
             if($result){
                $m++;
                // $v['A']='T56650'.$v['A'];

                $stu=WpIschoolStudent::find()->where(['stuno2'=>$v['A']])->one();
                if($stu){
                  $stu->cardid=$result['epc'];
                  $a=$stu->save(false);
                }else{
                  $stu2=WpIschoolStudent::find()->where(['name'=>$v['B'],'class'=>$v['E'],'sid'=>'56650'])->one();
                  if($stu){
                     $stu2->cardid=$result['epc'];
                     $b=$stu2->save(false);
                  }
                
                }               
                if($a || $b){
                  $n++;
                  $stu_id =$stu->attributes['id'];
                  $card=WpIschoolStudentCard::find()->where(['stu_id'=>$stu_id])->one();
                  if($card){
                    $card->card_no=$result['telid'];
                    $b=$card->save();
                    $mm++;
                  }else{
                    $car=new WpIschoolStudentCard;
                    $car->stu_id=$stu_id;
                    $car->card_no=$result['telid'];
                    $car->flag=1;
                    $car->ctime=time();
                    $kk=$car->save(false);
                    if($kk){
                        $y++;
                    }
                  
                  }               
                }else{
                  $arr[$ii]=$v;
                  $ii++;
                }
               
             }                          
          }
           echo '学生找不到条数：'.$ii;
           echo '电话卡表插入条数：'.$y.'电话更新条数：'.$mm;
           echo 'epc更新条数：'.$n.'总条数'.$m;
          $return_arr['arr']=$arr;
        }
        return $this->render('checkclass',$return_arr);
    }
    // public function actionKaku(){
    //     $re=WpIschoolKaku::find()->asArray()->all();
    //     $n=0;
    //     $m=0;
    //     foreach($re as $k=>$v ){
    //         if(preg_match('/[a-zA-Z]/',$v['telid'])){
    //             $k=WpIschoolKaku::find()->where(['telid'=>$v['telid']])->one();
    //             $k->epc=$v['telid'];
    //             $k->telid=$v['epc'];
    //             $m=$k->save(false);
    //             if($m){
    //               $n++;
    //             }
    //         }else{
    //           $m++;
    //         }
    //     }
    //     echo '成功条数：'.$n;
    //     echo '失败条数：'.$m;
        
    // }
    //card_info表数据更新
    public function actionUpcard(){
      if (\Yii::$app->request->isPost) {
         $this->initExcel();
         // echo "<pre>";
         // var_dump($this->source_data);die;
         // $i=0;
         // $arr=array();
         // $ii=0;
         // $iii=0;
         // $pdo = $this->getdb();
         // $result=$this->source_data;
         // foreach($result as $k=>$v){
         //      $sql=" SELECT * FROM zf_card_info WHERE school_id=:school_id and user_no=:user_no";
         //      $stmt=$pdo->prepare($sql);
         //      $stmt->execute(array(':school_id'=>'56651',':user_no'=>$v['A']));      
         //      if($stmt->rowCount()){  
         //         $row=$stmt->fetch(\PDO::FETCH_ASSOC);
         //          if($row['card_no']==$v['E']){
         //              $i++;
         //          }else{
         //              $ii++;
                      
         //          }   
         //      }else{
         //        $iii++;
         //        $arr[]=$result[$k];
         //      }
         // }
         $post= yii::$app->request->post();
         $i=0;    
         foreach($this->source_data as $k=>$v){
              $na=WpIschoolClass::find()->select('name')->where(['id'=>$v['B']])->asArray()->one();             
              if($na){         
                $sql="update wp_ischool_student set class='".$na['name']."',cid=".$v['B']."  where name = '".$v['A']."' and sid ='".$post['schoolid']."' and class like '".$post['level']."%'";
                $res =Yii::$app->db->createCommand($sql)->execute();             
                if($res){
                  $i++;
                }
              }
                  
        }
        echo "修改条数为：".$i."</br>";
        echo "<pre>";       
      } 
      
      // echo "匹配条数".$i."</br>";
      // echo "不匹配条数".$ii."</br>";
      // echo "没找到条数".$iii."</br>";
      // echo "插入总数为：".$ii."</br>";
      // echo "失败总数为".$a;
      $return_arr['arr']=$arr;
      return $this->render('upcard',$return_arr);
    }
     private function initExcel() {
         
        if (\Yii::$app->request->isPost) {
            $model = new ImportData();         
            $model->upload =UploadedFile::getInstance($model, 'upload');                    
            if ($model->validate()) {                                   
                 $data = \moonland\phpexcel\Excel::widget([
                    'mode' => 'import',
                    'fileName' => $model->upload->tempName,
                    'setFirstRecordAsKeys' => false,
                    'setIndexSheetByName' => false,

                ]);
                $data = isset($data[0])?$data[0]:$data;
                 // var_dump($data);die;
                if(count($data) > 1)
                {
                  $this->source_data=$data;                                        
                }
                
            }
        }else{
            return false;
        }
    }

    public  function getStuidByepc($epc){
        $model = WpIschoolStudent::findOne(['cardid'=>$epc]);
        return $model;
    }

    public function getAllOpenid($stuid){
        $modles = WpIschoolPastudent::find()->where(['and',"stu_id = $stuid","openid <>''"])->asArray()->all();
        return $modles;
    }
    public function actionChange(){
      $class='高一八班';
      $stu=WpIschoolStudent::find()->select('name,class,stuno2,sid')->where(['sid'=>56650,'class'=>$class])->asArray()->all();      
      $pdo=$this->getdb();
      $i=0;
      $ii=0;
      foreach($stu as $k=>$v){
          $sql=" SELECT * FROM zf_card_info WHERE school_id=:school_id and user_name=:user_name and department_id='1008'";
          $stmt=$pdo->prepare($sql);
          $stmt->execute(array(':school_id'=>$v['sid'],':user_name'=>$v['name']));      
          if($stmt->rowCount()){  
               $row=$stmt->fetch(\PDO::FETCH_ASSOC);
               $stuno2='T56650'.$row['user_no'];
               $up=WpIschoolStudent::find()->where(['name'=>$row['user_name'],'sid'=>$row['school_id'],'class'=>$class])->one();     
               $up->stuno2=$stuno2;
               $lev=$up->save(false);
               if($lev){
                   $i++;                                                    
               }else{
                    echo 'fail';
               }         
          }else{
              $ii++;
              echo'<pre>';
              var_dump($stu[$k]);
          }
      }   
      var_dump($i,$ii); 
      //链接card库更新card_info表里三高高一的学生卡号        

    }
    public function getdb(){
      try{
             $pdo=new \PDO('mysql:host=127.0.0.1;dbname=card','root','hnzf123456');
             $pdo->query("set character set 'utf8'");
      }catch (PDOException $e) {
             print "Error!: " . $e->getMessage() . "<br/>";
             die();        
      }   
      return $pdo;
    }
    public function getdb_sk(){
      try{
             $pdo=new \PDO('mysql:host=127.0.0.1;dbname=card_water','root','hnzf123456');
             $pdo->query("set character set 'utf8'");
      }catch (PDOException $e) {
             print "Error!: " . $e->getMessage() . "<br/>";
             die();        
      }   
      return $pdo;
    }
    public function actionChangestuno(){
      $arr=['3854','3855','3856','3857','3858','3859','3860','3861','3862','3863','3864','3865','3866','3867','3868','3869','3870','3871','3872','3873','3874','3875'];
       // echo count($arr);die;
      $al=WpIschoolStudent::find()->select('name,stuno2')->where(['in','cid',$arr])->andWhere(['sid'=>56650])->asArray()->all();
      $count=WpIschoolStudent::find()->select('name,stuno2')->where(['in','cid',$arr])->andWhere(['sid'=>56650])->count();
      echo $count."<br/>";
      $i=0;
      foreach($al as $k=>$v){
          $st=substr($v['stuno2'],6);
          $stuno="T56651".$st;
          $a=WpIschoolStudent::find()->where(['stuno2'=>$v['stuno2']])->andWhere(['in','cid',$arr])->one();
          $a->stuno2=$stuno;
          $lev=$a->save();
          if($lev){
            $i++;
          }else{
            echo "fail";
          }
      }

     // echo "<pre>";
      echo $i;
      
    }
     public function actionUpqq(){
       //$arr=['5802','5803','5804','5805','5806','5807','5808','5809','5810','5811','5812','5813','5814','5815'];
     
       $all =WpIschoolStudent::find()->where(['sid'=>'56664'])->asArray()->all();
       $i=0;

       foreach($all as $k=>$v){
          $d=new WpIschoolPastudent;
          $d->Relation ='客服'; 
          $d->name = '客服'; 
          $d->tel = '13373948965'; 
          $d->sid = $v['sid']; 
          $d->stu_id = $v['id'];       
          $d->school = $v['school'];
          $d->stu_name = $v['name'];
          $d->cid = $v['cid'];
          $d->class = $v['class'];
          $d->ctime = time();
          $d->ispass = 'y';
          $d->isqqtel = 1;
          $leave = $d->save(false);
          if($leave){
              $i++; 
          }
       }     
       echo "插入成功：".$i."条";

     }
    //圈存状态查询
     public function actionQcstatus(){

      if(\yii::$app->request->isPost){
        $trade_no=\yii::$app->request->post('trade_no');
       
        $pdo = $this->getdb();
        $sql="select * from zf_recharge_detail as a inner join zf_card_info as b on a.card_no=b.card_no and a.school_id=b.school_id where a.trade_no = '".$trade_no."'";
        $stmt=$pdo->query($sql);
        if($stmt->rowCount()){
          $row=$stmt->fetch();
          if($row['school_id']==56651){
            $sid=56650;
          }else{
            $sid=$row['school_id'];
          }
          //查学校
         $school= WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->one();
         $row['school_name']=$school['name'];


        }else{
          $row=0;
        }
        $pdo->null;
        $return_arr['result']=$row;
      }
      return $this->render('qcstatus',$return_arr);
     }
     public function actionEditqc(){
          $danhao=\yii::$app->request->post('danhao');
          $sql="update zf_recharge_detail set balance=0,is_active=0,qctime=0 where trade_no = ?";
          $pdo = $this->getdb();
          $stmt = $pdo->prepare($sql);          
          if($stmt->execute(array($danhao))){
            $a=$danhao;
          }else{
            $a=0;
          }
          $pdo->null;
          $this->ajaxReturn($a,'json');
     }
     public function actionSearchConsume(){
           // var_dump(\yii::$app->request->post());die;
          $post=\yii::$app->request->post();

          $starttime=strtotime($post['starttime']);
          $endtime=strtotime($post['endtime'])+86399;
          try{
              $pdo = $this->getdb();
              $sql="select * from zf_deal_detail where card_no = ? and school_id = ? and created < ? and created > ? order by created desc";
              $stmt = $pdo->prepare($sql);
              $stmt->execute([$post['card_no'],$post['school_id'],$endtime,$starttime]);
              yii::trace($sql);
              $result=$stmt->fetchAll(\PDO::FETCH_ASSOC);
              $pdo->null;
          } catch (PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();        
          }
         
          if($result){
              if($result[0]['school_id']==56651){
                $sid=56650;
              }else{
                $sid=$result[0]['school_id'];
              }
              $school= WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->one();
             
              foreach($result as &$v){                      
                      $v['time'] = date("Y-m-d H:i:s",$v['created']); 
                      $v['school_name']=$school['name'];                    
              }
              $result = array("flag"=>0,"ckshuju"=>$result);
          }else{
              $result = array("flag"=>1);
          }
          $this->ajaxReturn($result,'json');
     }
     public function actionBiaoqian(){
        $sql="select openid from wp_ischool_teaclass where sid='56769'  and ispass='y' and role='班主任' and class like '九%'";
        $tea=Yii::$app->db->createCommand($sql)->queryAll();
        $i=0;
        foreach($tea as $k=>$v){
          $th=WpIschoolUser::find()->where(['openid'=>$v['openid']])->one();    
          if($th){
               $th->label='初中班主任组';
               $res=$th->save(false);
          }
       
          if($res){
            $i++;
          }
       }

       echo '结果数：'.$i++;
     }
     public function actionCeshi(){
        $receiveData['Telnum1']='15836908008';
        $receiveData['Telnum2']='15886753866';
            if(isset($receiveData['Telnum1'])){
                $tel1=$receiveData['Telnum1'];
                $stu1= WpIschoolPastudent::find()->select('stu_id')->where(['tel'=>$tel1])->asArray()->all();
            }
            if(isset($receiveData['Telnum2'])){
                $tel2=$receiveData['Telnum2'];
                $stu2= WpIschoolPastudent::find()->select('stu_id')->where(['tel'=>$tel2])->asArray()->all();
            } 
            $stu=array_merge($stu1,$stu2);
            var_dump($stu);
              $stu=$this->array_unique_fb($stu);

              $temp=$this->FetchRepeatMemberInArray($stu);
              $iii=0;
              foreach ($temp as $k => $val){
                   $info[$iii] =json_decode($val,true);
                   $iii++;
              }

     }

   public function actionUpapp(){
      if (\Yii::$app->request->isPost) {
         $this->initExcel();
/*         \yii::trace($this->source_data);
         $str = "okr7Gv0d649ljFXF1dR3KmGLG0U4|56650|薛斐|139616|1396161526011649883|10|pa-jx-qq-ck-ygyu";
         $arr=explode('|',$str);
         echo "<pre>";
        var_dump($this->source_data);
        exit;*/

        foreach($this->source_data as $k=>$v){
          if($v['V']!='商户数据包'){
            $arr=explode('|',$v['V']);                  
            $arr1[$k]['id']=$arr[5];
            $arr1[$k]['jftype']=$arr[0];
            $t=substr($v['A'],1);
            $arr1[$k]['time']=$t;
            
            $to=substr($v['M'],1);
            if($to!='0.00'){
              $arr1[$k]['total']=$to;
            }else{
              $tuikuan=substr($v['Q'],1);
              $arr1[$k]['total']='-'.$tuikuan;
            }
            
            $m=substr($v['G'],1);
            $arr1[$k]['trade_no_yuan']=$v['G'];
            $arr1[$k]['trade_no']=$m;
            $op=substr($v['H'],1);
            $arr1[$k]['uid']=$arr[1];

          }
        }
        $u_id=$arr1;
        // echo "<pre>";
        // var_dump($u_id);
        foreach($u_id as $k=>$v){
          // $a=(int)$v['id'];
          if (preg_match('/[a-zA-Z]/',$v['id']) || strlen($v['id'])>=9){
            //包含字母是卡号
            $res= WpIschoolStudent::find()->select('school,name,class,cid')->where(['stuno2'=>$v['id']])->asArray()->all();
          }else{
            //不包含字母是学生id
            $res= WpIschoolStudent::find()->select('school,name,class,cid')->where(['id'=>$v['id']])->asArray()->all();
          }
          
          if($v['jftype']!='canka' && $v['jftype']!='xuefei' && $v['jftype']!='shufei' && $v['jftype']!='zhusufei'){          
              $jx=WpIschoolOrderjx::find()->select('zfopenid')->where(['trade_no'=>$v['trade_no']])->asArray()->all();
              if(!$jx){
                 $bk=WpIschoolOrderbk::find()->select('zfopenid')->where(['trade_no'=>$v['trade_no']])->asArray()->all();
              }
          }else if($v['jftype']=='xuefei' || $v['jftype']=='shufei' || $v['jftype']=='zhusufei'){
              $jf=  WpIschoolOrderjf::find()->select('type')->where(['trade_no'=>$v['trade_no']])->asArray()->all();
          }else if($v['jftype']=='canka'){
              $jf=null;
          }
          
          
           $u_id[$k]['school']=$res[0]['school'];
           
           $u_id[$k]['name']=$res[0]['name'];
           $u_id[$k]['class']=$res[0]['class'];
           //判断东西校区
           //西校区班级id
           $arr_school=['5558','5559','5560','5613','5614','5615','5616','5617','5618','5619','5620','5621','5622','5664','5665','5666','5667',
           '5668','3893','3894','3895','3896','3897','3898','3899','3900','3901','3902','3903','3904','3905','3906','3907','3908','3909','3910','3911','5590'];
           if(in_array($res[0]['cid'],$arr_school)){
              $u_id[$k]['xiaoqu']='西校区';
           }else{
              $u_id[$k]['xiaoqu']='东校区';
           }
           if($jx){
              $u_id[$k]['type']='功能费';
           }else if($jf){
              $u_id[$k]['type']=$jf[0]['type'];
           }else if($bk){
              $u_id[$k]['type']='补卡';
           }else{
              $u_id[$k]['type']='餐卡';
           }       
           if($u_id[2]['school']=='许昌市建安区第三高级中学'){
                $status=1;
           }else{
                $status=0;
           }
           foreach( $u_id as $key=>$ret){
                $u_id[$key]['id']=$key-1;
                unset($u_id[$key]['jftype']);
                unset($u_id[$key]['trade_no']);
                unset($u_id[$key]['openid']); 
                        
           }
      }
      //   echo "<pre>";
      // var_dump($u_id);
      foreach($u_id as $k=>$v){
        $data[$k]['id']=$v['id'];
        $data[$k]['school']=$v['school'];
        $data[$k]['type']=$v['type'];
        $data[$k]['time']=$v['time'];
        $data[$k]['class']=$v['class'];
        $data[$k]['name']=$v['name'];
        $data[$k]['total']=$v['total'];               
        $data[$k]['trade_no_yuan']=$v['trade_no_yuan'];
        if($status==1){
          $data[$k]['xiaoqu']=$v['xiaoqu'];         
        } 
      }

     
       require_once("/data/web/ischool/mobile/assets/ExportExcel.php");
      // $data= $u_id;
      $excelHead = "充值记录"; 
      $title = date("YmdHis",time());   #文件命名
      $headtitle= "<tr><th  colspan='3' >{$excelHead}</th></tr>"; 
      $titlename = "<tr> 
                <th style='width:70px;'>充值记录</th> 
                 </tr>"; 
      $filename = $title.".xls"; 
      \ExportExcel::excelData($data,$titlename,$headtitle,$filename);
 
      // $count= count($u_id)-2;   
      // $return_arr['stu']=$u_id;
      // $return_arr['count']=$count;
      // $return_arr['status']=$status;
    }
      // echo "<pre>";
      // var_dump($u_id);
     
     
      return $this->render('upapp',$return_arr);
    }
     

}

