<?php
namespace mobile\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use mobile\controllers\BaseController;
use mobile\models\WpIschoolUser;
use mobile\models\WpIschoolTeaclass;
use mobile\models\WpIschoolPastudent;
use mobile\models\WpIschoolStudent;
use mobile\models\WpIschoolAlertmsg;
use mobile\models\WpIschoolSafecard;
/**
 * Site controller
 */
class YicardController extends BaseController {
    public $layout='yicard';
    public function actionIndex(){
         $openid=\yii::$app->request->get("openid");
         $sid= \yii::$app->request->get("sid");
         $user= WpIschoolUser::find()->select('last_sid')->where(['openid'=>$openid])->asArray()->all();
         if(empty($user)){
            $sid=1;
         }else{
            $sid=$user[0]["last_sid"];
         }
         $con['openid']=$openid;
         $con['ispass']="y";
         $con['sid']=$sid;
         $res=WpIschoolTeaclass::find()->select('school,class,cid,role')->where($con)->andwhere(['not like','role','%校长%'])->asArray()->all();        
         $sql="select school,class,stu_id,stu_name,'家长' as role from wp_ischool_pastudent where openid='".$openid."'and sid='".$sid."' and ispass='y'";  
         $res2=WpIschoolPastudent::findBySql($sql)->asArray()->all();        
          //支付提示
          $enddate = time()+3600*24*PayNotify;  //20天提醒
          $sql1="select t.name,t.enddatepa from wp_ischool_student t left join wp_ischool_pastudent t2 on t2.stu_id=t.id where t2.openid='".$openid."' and t2.ispass='y' and t.enddatepa<".$enddate." and t.sid='".$sid."'";
          $alert= WpIschoolStudent::findBySql($sql1)->asArray()->all();
          $alertname = "";
          $alertdate = "";
          if($alert){
                foreach($alert as $v){
                        $alertname .= $v['name'].'/';
                        $alertdate .= date("Y年m月d日",$v['enddate']).'/';
                }
                $alertname = substr($alertname,0,-1);
                $alertdate = substr($alertdate,0,-1);

                //可配置的提示信息
                $sql2="select alert from wp_ischool_alertmsg where type='pakpay'";
                $alert=WpIschoolAlertmsg::findBySql($sql2)->asArray()->all();
                $return_arr['alertmsg']=$alert[0]['alert'];
              
           }
           $return_arr['sid']=$sid;
           $return_arr['alert']=$alertname;
           $return_arr['enddate']=$alertdate;
           $return_arr['list_class']=$res;
           $return_arr['list_par']=$res2;
           $return_arr['pak'] =ICARD_NAME;
           $return_arr['jxt'] =TONGZHI_NAME;  
//           var_dump($return_arr);die;
           return $this->render('index',$return_arr);
    }
    
      public function actionCheckstuinfo(){
            
            $stuid=\yii::$app->request->get("stuid");
            $stu = WpIschoolStudent::find()->select('name')->where(['id'=>$stuid])->asArray()->all();
            $type=\yii::$app->request->get("type");
            $beginTs=$this->getBeginTimestamp($type);
            $sql="select info,ctime from wp_ischool_safecard where stuid=".$stuid. " and ctime>".$beginTs." and info <> '未到'";
            $res= WpIschoolStudent::findBySql($sql)->asArray()->all();      
//            var_dump($res);die;
            $list_cards="";
            if(!empty($res))
            {
                $list_cards=$this->getStuStudyInfo($res);
            }
            $return_arr['list_cards']=$list_cards;
            $return_arr['stu']=$stu;
        
            return $this->render('checkstuinfo',$return_arr);
         }
         
    /*  计算时间戳，本月头month，本周头week，本日头today时间戳作为查询起点 */
     function getBeginTimestamp($type){
        $beginTs="";
        if($type=='today') {
            $beginTs=strtotime(date('Y-m-d'));
        }else if($type='week'){
            $date = date("Y-m-d");
            $first=1;                                // 1 表示每周星期一为开始时间，0表示每周日为开始时间
            $w = date("w", strtotime($date));       //获取当前是本周的第几天，周日是 0，周一 到周六是 1 -6
            $d = $w ? $w - $first : 6;              //如果是周日 -6天
            $now_start = date("Y-m-d", strtotime("$date -".$d." days")); //本周开始时间：
            $beginTs =  strtotime($now_start);     //本周起始时间戳
        }else{
            $beginTs=strtotime(date('Y-m'));
        }

           return $beginTs;
       }
        /*  将学生考勤信息按一周排列 */
      public function getStuStudyInfo($arr){
        $list_cards=array();
        $week="";
        $cards="";
        $len=count($arr);
        for($i=0;$i<$len;$i++){
            $theWeek=date('w',$arr[$i]['ctime']);
            if($theWeek!=$week){
                $week=$theWeek;
                if(!empty($cards)){
                    $list_cards[]=$cards;
                }
                $cards=array();
            }
            $cards['card'][]=$arr[$i];
            $cards['day']=$this->getWeek($week);

            if($i==($len-1)){
                $list_cards[]=$cards;
            }
        }

        return $list_cards;
      }
    
       public function actionCheckallstuinfo(){
           
            $cid=\yii::$app->request->get("cid");
            $res= WpIschoolStudent::find()->select('id')->where(['cid'=>$cid])->asArray()->all();
            $arr="";
            foreach ($res as $v) {
                $arr[]=$v["id"];
            }         
            $where="";
            $yW=date("yW");
            $d=date("w");      
            $where["yearweek"]=$yW;
            $where["weekday"]=$d;
         
            $res= WpIschoolSafecard::find()->where($where)->andwhere(['in','stuid',$arr])->asArray()->all();
                 
            foreach ($res as &$v) {
                $where="";
                $where["id"]=$v["stuid"];
                $re= WpIschoolStudent::find()->where($where)->asArray()->all();              
                $v["name"]=$re[0]["name"];
            }
            $return_arr['list_stu']=$res;

            return $this->render('checkallstuinfo',$return_arr);        
       }
       
       //查询本周迟到学生
        public function actionCheckallstuinfoweek(){
            $cid=\yii::$app->request->get("cid");
            $res= WpIschoolStudent::find()->select('id')->where(['cid'=>$cid])->asArray()->all();
            $arr="";
            foreach ($res as $v) {
                $arr[]=$v["id"];
            }             
            $where="";
            $yW=date("yW");         
            $where["yearweek"]=$yW;
            //周一迟到
            $where["weekday"]=1;
            $one= WpIschoolSafecard::find()->select('stuid,ctime,info')->where($where)->andwhere(['in','stuid',$arr])->asArray()->all();          
            foreach ($one as &$v) {              
                $re=WpIschoolStudent::find()->where(['id'=>$v["stuid"]])->asArray()->all();             
                $v["name"]=$re[0]["name"];
            }
            //周二迟到
            $where["weekday"]=2;
            $two=WpIschoolSafecard::find()->select('stuid,ctime,info')->where($where)->andwhere(['in','stuid',$arr])->asArray()->all();            
            foreach ($two as &$v) {              
                $re=WpIschoolStudent::find()->where(['id'=>$v["stuid"]])->asArray()->all();                 
                $v["name"]=$re[0]["name"];
            }
            //周三迟到
            $where["weekday"]=3;
            $three=WpIschoolSafecard::find()->select('stuid,ctime,info')->where($where)->andwhere(['in','stuid',$arr])->asArray()->all();  
            foreach ($three as &$v) {
                $re=WpIschoolStudent::find()->where(['id'=>$v["stuid"]])->asArray()->all();               
                $v["name"]=$re[0]["name"];
            }
            //周四迟到
            $where["weekday"]=4;
            $four=WpIschoolSafecard::find()->select('stuid,ctime,info')->where($where)->andwhere(['in','stuid',$arr])->asArray()->all(); 
            foreach ($four as &$v) {
                $re=WpIschoolStudent::find()->where(['id'=>$v["stuid"]])->asArray()->all();  
                $v["name"]=$re[0]["name"];
            }
            //周五迟到
            $where["weekday"]=5;
            $five=WpIschoolSafecard::find()->select('stuid,ctime,info')->where($where)->andwhere(['in','stuid',$arr])->asArray()->all(); 
            foreach ($five as &$v) {
                $re=WpIschoolStudent::find()->where(['id'=>$v["stuid"]])->asArray()->all();
                $v["name"]=$re[0]["name"];
            }
            //周六迟到
            $where["weekday"]=6;
            $six=WpIschoolSafecard::find()->select('stuid,ctime,info')->where($where)->andwhere(['in','stuid',$arr])->asArray()->all(); 
            foreach ($six as &$v) {
                $re=WpIschoolStudent::find()->where(['id'=>$v["stuid"]])->asArray()->all();
                $v["name"]=$re[0]["name"];
            }
            //周日迟到
            $where["weekday"]=7;
            $seven=WpIschoolSafecard::find()->select('stuid,ctime,info')->where($where)->andwhere(['in','stuid',$arr])->asArray()->all(); 

            foreach ($seven as &$v) {
                $re=WpIschoolStudent::find()->where(['id'=>$v["stuid"]])->asArray()->all();
                $v["name"]=$re[0]["name"];
            }

            $return_arr['one']=$one;       
            $return_arr['two']=$two;
            $return_arr['three']=$three; 
            $return_arr['four']=$four;   
            $return_arr['five']=$five;  
            $return_arr['six']=$six; 
            $return_arr['seven']=$seven;   
            $return_arr['one']=$one;                                   
            return $this->render('checkallstuinfoweek',$return_arr);  

        }
          //查询本月迟到学生
         public function actionCheckallstuinfomonth(){ 
            $cid=\yii::$app->request->get("cid");
            $res= WpIschoolStudent::find()->select('id')->where(['cid'=>$cid])->asArray()->all();           
            $arr="";
            foreach ($res as $v) {
                $arr.=",".$v["id"];
            }
            $arr=trim($arr);
            $arr=ltrim($arr,",");
            $ym=date("ym");
            $sql="select id,stuid,count(DISTINCT stuid)as num  FROM wp_ischool_safecard  where yearmonth=".$ym." AND stuid IN (".$arr.") GROUP BY(stuid)";
            $res=WpIschoolSafecard::findBySql($sql)->asArray()->all();    
            $sql2="select id,stuid,ctime,info FROM wp_ischool_safecard  where yearmonth=".$ym." AND stuid IN (".$arr.")";
            $res2=WpIschoolSafecard::findBySql($sql2)->asArray()->all();
            foreach ($res as &$v) {
                $rw=WpIschoolStudent::find()->where(['id'=>$v["stuid"]])->asArray()->all();         
                $v["stname"]=$rw[0]["name"];
            }
            foreach ($res2 as &$v) {              
                $rw=WpIschoolStudent::find()->where(['id'=>$v["stuid"]])->asArray()->all();   
                $v["stname"]=$rw[0]["name"];
            }
            
            $return_arr['info']=$res;   
            $return_arr['info2']=$res2;                                   
            return $this->render('checkallstuinfomonth',$return_arr);  
         }
         
       public function actionCenter(){
            $id=\yii::$app->request->get("id");
            $m = WpIschoolSafecard::find()->where(['id'=>$id])->one(); 
            $m->info="进校";
            $m->ctime=time();
            $m->save();
            $at="success";
            $this->ajaxReturn($at,"json");
       }
       
       public function actionSendmsgtopa(){
            $id=\yii::$app->request->get("id");
            $res = WpIschoolSafecard::find()->select('stuid')->where(['id'=>$id])->all();                               
            $where="";
            $where["id"]=$res[0]["stuid"];          
            $re=WpIschoolStudent::find()->select('cid,name')->where($where)->asArray()->all();                     
            $where="";
            $where["cid"]=$re[0]["cid"];
            $where["stu_name"]=$re[0]["name"];
            $r= WpIschoolPastudent::find()->where($where)->asArray()->all(); 
            foreach ($r as $v)
            {
                $msg="家长您好，您的孩子".$v["stu_name"]."未到校";
                $openid=$v['openid'];
                $data = '{
                    "touser":"'.$openid.'",
                    "msgtype":"text",
                    "text":
                    {
                       "content":"'.$msg.'"
                    }
                    }';
                SendMsg::singlePostMsg(SendMsg::getUrl('kf'),$data);
            }
            $where="";
            $where["id"]=$id;
            $safecard = WpIschoolSafecard::find()->where($where)->one(); 
            $safecard->delete();               
            $at="success";
            $this->ajaxReturn($at,"json");
       }
       
}





