<?php
namespace mobile\assets;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use mobile\models\WpIschoolSchool;
use mobile\models\WpIschoolStudent;
use mobile\models\WpIschoolClass;
use mobile\models\WpIschoolTeacher;
use mobile\models\WpIschoolTeaclass;
use mobile\models\WpIschoolPastudent;
class Helper {
    /**
     * @param $sid
     * return 所有用户
     */
    function getAllUser($sid){
        $allUserArr=array();
        $this->getAllTeacher($sid,$allUserArr);
        $this->getAllParents($sid,$allUserArr);
        return $allUserArr;
    }
    /**
     * @param $sid
     * return 所有老师
     */
    function getAllTeacher($sid,&$teaArr){
        
       // $sql="select distinct openid from wp_ischool_teaclass where sid=".$sid;
       //   $teachers =  WpIschoolTeaclass::findBySql($sql)->asArray()->all();
       $teachers=WpIschoolTeaclass::find()->select('openid')->where(['sid'=>$sid])->groupBy('openid')->asArray()->all();
      
        foreach($teachers as $v){
            $teaArr[] = $v['openid'];
        }
        return 0;
    }
    /**
     * @param $sid
     * return 所有家长
     */
    function getAllParents($sid,&$parArr){
       // $sql="select distinct openid from wp_ischool_pastudent where sid=".$sid;
       //  $parents = WpIschoolPastudent::findBySql($sql)->asArray()->all();
        $parents=WpIschoolPastudent::find()->select('openid')->where(['sid'=>$sid])->groupBy('openid')->asArray()->all();
        foreach($parents as $v){
            $parArr[] = $v['openid'];
        }
        return 0;
    }
    /**
     * 获取学校信息
     * @stuid 学生id
    */
    function getSchoolByStuid($stuid){
        $sql = "select t.* from wp_ischool_school t ".
                "left join wp_ischool_student t2".
                " on t.id=t2.sid".
                " where t2.id=".$stuid;
        $m= WpIschoolSchool::findBySql($sql)->asArray()->all();
        return $m;
    }   
    function getStudent($stuid){
        $sql="select * from wp_ischool_student where id=".$stuid;
        $m= WpIschoolStudent::findBySql($sql)->asArray()->all();
        return $m;
    }
    
    /**
    * 获取班级信息
    * @stuid 学生id
    */
    function getClassByStuid($stuid){
        $sql = "select t.* from wp_ischool_class t".
               " left join wp_ischool_student t2".
               " on t.id=t2.cid where t2.id=".$stuid;
        $m= WpIschoolClass::findBySql($sql)->asArray()->all();
        return $m; 
    }
    
    function getSchool($sid){
        //$sql="select * from wp_ischool_school where id=".$sid;
         return $m= WpIschoolSchool::findone($sid);
//        return M()->query("select * from wp_ischool_school where id=".$sid);
    }
    
     function asynBroad($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);//1秒后立即执行
        curl_exec($ch);
        curl_close($ch);

    }
}

