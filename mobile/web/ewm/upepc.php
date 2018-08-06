<?php
/**
 * 根据post过来的学号更新EPC和电话卡
 */
require_once "db.php";
// $stuno2 = "G411002200111061014";
// $epc = 'cs123122';
// $card_no = 265994665;
// $_POST['stuno2'] = "";
$streamData = $_GET;
if(empty($streamData)) {
    $streamData = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
}elseif(empty($streamData)){
    $streamData = file_get_contents('php://input');
}
// $streamData=json_encode($streamData);
$streamData = $streamData['num'];
if(!empty($streamData)) {
    $localtime=date('y-m-d H:i:s',time());
    $get = explode(";",$streamData);
    $stuno2 = $get[0]?trim($get[0]):"";
    $epc = $get[2]?trim($get[2]):"";
    $card_no = $get[1]?trim($get[1]):"";
    if(strlen($card_no) < 10){
        $card_no= sprintf('%010s', $card_no);
    }
    $log_file = "log.php";
    // file_put_contents($log_file, $streamData, FILE_APPEND);
   //  file_put_contents($log_file, $get, FILE_APPEND);
   file_put_contents($log_file, $localtime.'----'.$stuno2.'----'.$epc.'----'.$card_no."\r\n", FILE_APPEND);
   upepc($stuno2,$epc,$card_no);
}

function upepc($stuno2,$epc,$card_no){
    // $stuno2 = $_POST['stuno2']?:"";
    // $epc = $_POST['epc']?:"";
    // $card_no = $_POST['cardid']?:"";
    if (empty($stuno2) || empty($epc) || empty($card_no)) {
        echo ":1";
    }else{
        // echo ":0";exit();
        $db = DatabaseUtils::getDatabase();
        //$db = new PDO("mysql:host=127.0.0.1;dbname=ischool", "root", 'root',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $stmt1 = $db->prepare('select * from wp_ischool_student where stuno2 =? ');
        $stmt1->execute(array($stuno2));

        foreach($stmt1 as $value){
            if(empty($value['stuno2'])){
                echo ":01";
            }
            try {
                $db->beginTransaction();
                $upstuepc = $db->prepare('update wp_ischool_student set cardid = ?,is_linshi = 1  where stuno2 = ? ');
                $upstuepc->execute(array($epc,$stuno2));
                $sql1 = $db->prepare("select * from wp_ischool_school_epc where stu_id = '".$value['id']."'");
                $sql1->execute();
                $res = $sql1->fetchAll();

                if(empty($res)){
                    $inschoolepc = $db->prepare('insert into wp_ischool_school_epc (Name,EPC,sid,stu_id,Class_name,type,LastTime) values (?,?,?,?,?,?,?)');
                    $inschoolepc->execute(array($value['name'],$epc,$value['sid'],$value['id'],$value['class'],0,time()));
                }else{
                    $upschoolepc = $db->prepare('update wp_ischool_school_epc set EPC = ?,LastTime=? where stu_id = ? ');
                    $upschoolepc->execute(array($epc,time(),$value['id']));
                }

               $sql2 = $db->prepare("select * from wp_ischool_student_card where stu_id = '".$value['id']."'");
                $sql2->execute();
                $res2 = $sql2->fetchAll();
               if(empty($res2)){
                   $instucard = $db->prepare('insert into wp_ischool_student_card (stu_id,card_no,flag,ctime) values (?,?,?,?)');
                   $instucard->execute(array($value['id'],$card_no,1,time()));
               }else{
                   $upstucard = $db->prepare('update wp_ischool_student_card set card_no = ?,ctime =? WHERE  stu_id = ? ');
                   $upstucard->execute(array($card_no,time(),$value['id']));
               }
                $db->commit();
                $arr['stuno2'] = $stuno2;
                $arr['status'] = '0';
                echo ":0";
                // echo "success";
                // return $stuno2.'---'.'0';
            } catch (Exception $e) {
                $db->rollBack();
                // echo "Failed: " . $e->getMessage();
                // return $stuno2.'---'.'1';
                $arr['stuno2'] = $stuno2;
                $arr['status'] = '1';
                echo ":1";
            }
        }
    }
}