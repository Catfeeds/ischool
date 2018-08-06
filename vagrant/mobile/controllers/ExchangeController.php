<?php
namespace mobile\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use mobile\controllers\BaseController;
use mobile\models\WpIschoolGonggao;
use mobile\models\WpIschoolUser;
use mobile\models\WpIschoolSchool;
use mobile\models\WpIschoolTeacher;
use mobile\models\WpIschoolUserRole;
use mobile\models\WpIschoolTeaclass;
use mobile\models\WpIschoolInbox;
use mobile\models\WpIschoolOutbox;
use mobile\models\WpIschoolClass;
use yii\helpers\Url;
use mobile\assets\SendMsg;
/**
 * Site controller
 */
class ExchangeController extends BaseController {
    public $layout='exchange';
    public function actionIndex(){     
        $openid = \yii::$app->request->get("openid");     
        $user= WpIschoolUser::find()->select('last_sid')->where(['openid'=>$openid])->asArray()->all();       
        if(empty($user)){
            $sid=1;
        }else{
            $sid=$user[0]["last_sid"];
        }
        $res=WpIschoolTeaclass::find()->where(['openid'=>$openid,'sid'=>$sid,'ispass'=>'y'])->asArray()->all();
        
        if(!empty($res)){

            $sql='select id from wp_ischool_user_role where sid='.$sid." and openid='".$openid."'";
            $ismanager=WpIschoolUserRole::findBySql($sql)->asArray()->all();
            if($ismanager){
                $sqls='select distinct level from wp_ischool_class where sid='.$sid." and level>=0 order by level asc";  
                 $list_level=WpIschoolClass::findBySql($sqls)->asArray()->all();
                   
                 $len=count($list_level);
                 for($i = 0; $i < $len; $i++){
                         //获取年级下的班级
                         $list_level[$i]['grade'] = $this->getGrade($list_level[$i]['level']);
                       
                          $sql1="select id,name from wp_ischool_class where sid=".$sid." and level=".$list_level[$i]['level'].' order by level,class';
                          $list_level[$i]['classes'] =WpIschoolClass::findBySql($sql1)->asArray()->all();
//                          var_dump( $list_level);die;
                          //获取班级下的任课老师
                         $lentwo = count($list_level[$i]['classes']);
                         for($j = 0; $j < $lentwo; $j++){
                                 $cid = $list_level[$i]['classes'][$j]['id'];
                                 
                                 $sql2='select distinct openid,tname,id from wp_ischool_teaclass where cid='.$cid." and ispass='y'";
                                 $list_level[$i]['classes'][$j]['teas'] = WpIschoolTeaclass::findBySql($sql2)->asArray()->all();

                         }
                     }
                     $return_arr['list_level']= $list_level; //发公告 年级 班级 班级老师
              }
//              $sql3="select id,tname from wp_ischool_teacher where sid=".$sid." order by convert(tname using gbk) asc";
              $sql3="select id,tname from wp_ischool_teaclass where sid=".$sid." and ispass='y' group by openid order by convert(tname using gbk) asc";
              $list_lxr = WpIschoolTeacher::findBySql($sql3)->asArray()->all();
              $return_arr['list_lxr']=$list_lxr;
              $return_arr['openid']=$openid;
              $return_arr['sid']=$sid;

              $res = parent::Jssdk();

              $return_arr['appid']=$res['appId'];
              $return_arr['timestamp']=$res["timestamp"];
              $return_arr['nonceStr']=$res["nonceStr"];
              $return_arr['signature']=$res["signature"];
              $return_arr['pak'] =ICARD_NAME;
              $return_arr['jxt'] =TONGZHI_NAME;
//              var_dump($return_arr['list_level'][0]['classes']);die;
              return $this->render('index',$return_arr);
         } else{
            $school = WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->count();
            if($school){
                 $school = $school[0]['name'];
            }else{
                $school = "";
            }
            $school = "您不是".$school."内部人员，无法进入该校内部交流。请点击【我的服务】》【我的资料】》【我是老师】进行切换学校";
             echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
             <script type="text/javascript">
             alert("'.$school.'");
             window.history.go(-1);</script>';              
         }         
       
    }
     //获取年级名称
    private function getGrade($levelid){
        $leves=array(0=>'学校内部群组',1=>'一年级',2=>'二年级',3=>'三年级',4=>'四年级',5=>'五年级',6=>'六年级',7=>'七年级',8=>'八年级',9=>'九年级');
        return $leves[$levelid];
    }

     //收件箱
    public function actionInbox(){
          $openid = \yii::$app->view->params['openid'];

          $sid =  \yii::$app->request->get("sid");
          $path=URL_PATH;
          $nm= WpIschoolInbox::find()->where(['inopenid'=>$openid,'type'=>1])->asArray()->count();
          $page=\yii::$app->request->get("page");
           //每页显示的条数
          $num=5;
            //一共多少页
          $sum=ceil($nm/$num);
          $totalPage=ceil($nm/$num);

          if(!empty($page))
          {
              if($page<1)
              {
                  $page=1;
              }
              if($page>$sum)
              {
                  $page=$sum;
              }
          }
          else
          {
              $page=1;
          }
          //当前是第几页
          $nowpage=$page;
          //每页第一条
          $star=($nowpage-1)*$num;
          if($star<0){
              $star=0;
          }
          $next=$page+1;
          $last=$page-1;
//            $openid="'".$openid."'" ;  
          $return_arr['start']=$path."/exchange/inbox?openid=".$openid."&sid=".$sid."&page=1";
          $return_arr['up']=$path."/exchange/inbox?openid=".$openid."&sid=".$sid."&page=".$last;
          $return_arr['down']=$path."/exchange/inbox?openid=".$openid."&sid=".$sid."&page=".$next;
          $return_arr['end']=$path."/exchange/inbox?openid=".$openid."&sid=".$sid."&page=".$sum;
      $sql="select * from wp_ischool_inbox where inopenid='".$openid."' and type=1 order by ctime desc  limit $star,$num ";
//           $res= WpIschoolInbox::find()->where(['inopenid'=>$openid,'type'=>1])->orderBy('ctime desc')->offset($star)->limit($num)->asArray()->all();
       $res=WpIschoolInbox::findBySql($sql)->asArray()->all();

          foreach ($res as &$v) {

              $content=$v["content"];
              $sta=substr($content,0,3);
              if($sta=="<p>")
              {
                  $star=strrpos($content,"</p>");
                  $end=$star+4;
                  $return=substr($content,$end);
                  if($return!=false)
                  {
                      $v["serverId"]=$return;
                      $v["type"]="voice";
                      $sta=substr($content,0,3);
                      $v["content"]=substr($content,0,$end);
                  }
                  else
                  {
                      $v["type"]="txt";
                  }
              }
              else
              {
                  $v["serverId"]=$content;
                  $v["type"]="voice";
                  $v["content"]="";
              }

              if($v["fujian"]==""||$v["fujian"]=="one")
              {
                  $v["fujian"]="f";
              }
              else
              {
                  $sta=substr($v["fujian"],4);
                  $arr=explode("#",$sta);
                  $v["fujian"]=$arr;
              }
          }
          $return_arr['count'] =$nm;
          $return_arr['totalPage'] =$totalPage;
          $return_arr['list_msg'] =$res;
          return $this->render('inbox',$return_arr);
      }    
        /*  删除已接受信息 */
       public function actionOutbox(){
          $openid = \yii::$app->view->params['openid'];
          $sid =  \yii::$app->request->get("sid");
          $path=URL_PATH;
          $nm= WpIschoolOutbox::find()->where(['outopenid'=>$openid,'type'=>1])->asArray()->count();
          $page=\yii::$app->request->get("page");
          //每页显示的条数
          $num=5;
           //一共多少页
          $sum=ceil($nm/$num);
          $totalPage=ceil($nm/$num);

         if(!empty($page))
         {
             if($page<1)
             {
                 $page=1;
             }
             if($page>$sum)
             {
                 $page=$sum;
             }
         }
         else
         {
             $page=1;
         }
         //当前是第几页
         $nowpage=$page;
         //每页第一条
         $star=($nowpage-1)*$num;
         if($star<0){
             $star=0;
         }
         $next=$page+1;
         $last=$page-1;
         $return_arr['start']=$path."/exchange/outbox?openid=".$openid."&sid=".$sid."&page=1";
         $return_arr['up']=$path."/exchange/outbox?openid=".$openid."&sid=".$sid."&page=".$last;
         $return_arr['down']=$path."/exchange/outbox?openid=".$openid."&sid=".$sid."&page=".$next;
         $return_arr['end']=$path."/exchange/outbox?openid=".$openid."&sid=".$sid."&page=".$sum;
         $res= WpIschoolOutbox::find()->where(['outopenid'=>$openid,'type'=>1])->orderBy('ctime desc')->offset($star)->limit($num)->asArray()->all();

         foreach ($res as &$v) {

             $content=$v["content"];
             $sta=substr($content,0,3);
             if($sta=="<p>")
             {
                 $star=strrpos($content,"</p>");
                 $end=$star+4;
                 $return=substr($content,$end);
                 if($return!=false)
                 {
                     $v["serverId"]=$return;
                     $v["type"]="voice";              
                     $v["content"]=substr($content,0,$end);
                 }
                 else
                 {
                     $v["type"]="txt";
                 }
             }
             else
             {
                 $v["serverId"]=$content;
                 $v["type"]="voice";
                 $v["content"]="substr($content,0,$end);";
             }

             if($v["fujian"]==""||$v["fujian"]=="one")
             {
                 $v["fujian"]="f";
             }
             else
             {
                 $sta=substr($v["fujian"],4);
                 $arr=explode("#",$sta);
                 $v["fujian"]=$arr;
             }
         }
         $return_arr['count'] =$nm;
         $return_arr['totalPage'] =$totalPage;
         $return_arr['list_msg'] =$res;
         $return_arr['sid'] =$sid;
//            var_dump($return_arr);die;
         return $this->render('outbox',$return_arr);
       }


        /*  发信息页面 */
       public function actionSendmsg(){            
          $type=\yii::$app->request->get("type");
          $res="";
          $name="";
          $outopenid="";
          $stas=0;
          switch ($type) {
              case 'back':   //回复
                 $outopenid=\yii::$app->request->get("outopenid");                   
                 $name=$this->getSenderInfo($outopenid);
                 break;
              case 'transmit':   //转发
                 $stas=1;
                 $id=\yii::$app->request->get("id"); 
                 $res= WpIschoolInbox::find()->where(['id'=>$id])->asArray()->all();
                 break;
              case 'outtransmit':
                 $stas=1;
                 $id=\yii::$app->request->get("id");
                 $res= WpIschoolOutbox::find()->where(['id'=>$id])->asArray()->all();
                 break;
          }

         $content=$res[0]["content"];
         if($stas==1)
         {
             $sta=substr($content,0,3);
             if($sta=="<p>")
             {
                 $star=strrpos($content,"</p>");
                 $end=$star+4;
                 $return=substr($content,$end);
                 if($return!=false)
                 {
                     $serverId=$return;
                     $ty="voice";
                     $sta=substr($content,0,3);
                     $content=substr($content,0,$end);
                 }
                 else
                 {
                     $ty="txt";
                 }
             }
             else
             {
                 $serverId=$content;
                 $ty="voice";
                 $content="";
             }
             $fujian=$res[0]["fujian"];
             if($fujian==""||$fujian=="one")
             {
                 $fujian="f";
             }
             else
             {
                 $sta=substr($fujian,4);
                 $fujian=$sta;
             }
         }
         $return_arr['fujian'] =$fujian;
         $return_arr['ty'] =$ty;
         $return_arr['serverId'] =$serverId;
         $return_arr['content'] =$content;
         $return_arr['name'] =$name;
         $return_arr['type'] =$type;
         $return_arr['toopenid'] =$outopenid;                       
//         var_dump($return_arr);die;
         return $this->render('sendmsg',$return_arr);
       }
     /*  发送人的信息 姓名 */
     public function getSenderInfo($openid){
        $res= WpIschoolUser::find()->select('name')->where(['openid'=> $openid])->asArray()->all(); 
        return $res[0]['name'];
     }
     /*  删除已发送信息 */
     public function actionDeleoutbox(){       
       $mid=\yii::$app->request->post("mid");
       $res= WpIschoolOutbox::findOne($mid)->delete();     
       if($res>0||$res===0){
          $this->ajaxReturn('success','json');
       }else{
          $this->ajaxReturn('fail','json');
       }  
     }
      /*  删除已接受信息 */
     public function actionDeleinbox(){  
        $mid=\yii::$app->request->post("mid");
        $res= WpIschoolInbox::findOne($mid)->delete();     
        if($res>0||$res===0){
            $this->ajaxReturn('success','json');
        }else{
            $this->ajaxReturn('fail','json');
        }   
     }
     //执行发消息的方法
      public function actionDosendmsg(){  
         $path    = URL_PATH;
         $strpath = \yii::$app->request->post("strpath");//附件
         $openid  = \yii::$app->view->params['openid'];//发送人
         $to      = \yii::$app->request->post("to");//接收人  
         $des     = \yii::$app->request->post("msg"); 
         $msg     = \yii::$app->request->post("msg");//消息内容 
         $serid   = \yii::$app->request->post("serid");//语音id
         $title   = \yii::$app->request->post("title");  //消息主题
         $msgType = \yii::$app->request->post("msgType");  //信息类型 公告还是留言
         $operate_type =\yii::$app->request->post("operate_type");  //操作类型    
         $arr= WpIschoolUser::find()->select('last_sid')->where(['openid'=> $openid])->asArray()->all();           

         if(empty($arr)){
           $sid=1;
         }else{
           $sid=$arr[0]["last_sid"];
         }
         $ur[0]=$path."/exchange/index?openid=";
         $ur[1]="&sid=".$sid;
         $username = $this->getSenderInfo($openid);
         if($msgType=='gg') {   //公告
           $tos = $this->getOpidByLevels($sid,$to);
         }else{                  //留言
           $tos= $this->getOpidByTids($to,$operate_type);
         }
         //图文信息的内容，与入库的原始html内容$msg不同
         if(!empty($des)){
           $preg = "/<\/?[^>]+>/i";
           $des = preg_replace($preg,'',$des);
         }
         if($serid!="one"){
           if(empty($des)){
             $des = "您收到一条语音信息";
           }
           $msg = $msg.$serid;
         }
         $data['title'] = $username;  //图文消息标题
         $data['des']   = $des;       //图文消息内容
         $data['content'] = $msg;    //待入库的原始消息
         $data['zhuti'] = $title;     //待入库的消息主题
         $data['url']   = $ur;       //图文跳转链接
         $data['strpath'] = $strpath; //附件
         $result = $this->sendExchangeMsg($openid,$tos,$data);
         $this->ajaxReturn($result,'json');
      }

      //levels形如"g-1;c-731;g-3;"是年级级别和班级id的混合体需要判断
     public function getOpidByLevels($sid,$levels){
         $ls = ""; //存放年级级别的字符串
         $cs = ""; //存放班级id的字符串
         $ts = ""; //存放teaclass id的字符串
         $levelArr = explode(",",substr($levels, 0, -1));
         foreach($levelArr as $k){
             $atomArr = explode("-",$k);
             if($atomArr[0] == "g"){
                 $ls .= $atomArr[1].",";
             }else if($atomArr[0] == "c"){
                 $cs .= $atomArr[1].",";
             }else {
                 $ts .= $atomArr[1].",";
             }
         }
         $ls = substr($ls, 0, -1);
         $cs = substr($cs, 0, -1);
         $ts = substr($ts, 0, -1);
         $pOpenids = array(); //存放所有联系人openid的数组         
         $sql="select distinct t1.openid from wp_ischool_teaclass t1 left join wp_ischool_class t2 on t1.cid=t2.id where t2.sid=".$sid." and t1.ispass='y' and t2.level in(".$ls.")";
         $res=WpIschoolTeaclass::findBySql($sql)->asArray()->all();
         if($res){
             $leng = count($res);
             for($i = 0; $i < $leng; $i++){
                 $pOpenids[] = $res[$i]['openid'];
             }
         }
         $sql1="select distinct openid from wp_ischool_teaclass where ispass='y' and "." cid in(".$cs.")";
         $res = WpIschoolTeaclass::findBySql($sql1)->asArray()->all();          
         if($res){
             $leng = count($res);
             for($i = 0; $i < $leng; $i++){
                 $pOpenids[] = $res[$i]['openid'];
             }
         }
         $sql2="select distinct openid from wp_ischool_teaclass where ispass='y' and "." id in(".$ts.")";
         $res = WpIschoolTeaclass::findBySql($sql2)->asArray()->all(); 
         if($res){
                 $leng = count($res);
                 for($i = 0; $i < $leng; $i++){
                         $pOpenids[] = $res[$i]['openid'];
                 }
         }
         $pOpenids = array_unique($pOpenids);
         return $pOpenids;
     }

     /*  发留言中 通过老师id获取其对应的openid */
    public function getOpidByTids($tids,$operate_type){
         $tids=explode(';',$tids);
         $pOpenids=array();
         if($operate_type=='back'){
             $leng=count($tids);
             for($i=0;$i<$leng;$i++){
                 $pOpenids[]=$tids[$i];
             }
         }else{
             $res =WpIschoolTeacher::find()->select('openid')->where(['in','id',$tids])->asArray()->all();                         
             $leng=count($res);
             for($i=0;$i<$leng;$i++){
                 $pOpenids[]=$res[$i]['openid'];
             }
         }

         return $pOpenids;
     }

     private function sendExchangeMsg($from,$tos,$data){
         $des     = $data['des'];        //纯内容
         $msg     = $data['content']; //原始内容附带html标签
         $zhuti   = $data['zhuti'];
         $strPath = $data['strpath'];
         $uname   = $data['title'];
         //发送前存入发件箱
         $this->addToOutboxBeforeSend($from,$msg,$zhuti,$strPath);
         foreach($tos as $to){
             $this->addToInboxBeforeSend($from,$to,$msg,$uname,$strPath);
         }
         $title = "来自".$uname."的消息";
         $data['title'] = $title;
         $data['content'] = $des;
         return SendMsg::muiltPostMsg($tos,$data);
     }

      private function addToOutboxBeforeSend($from,$msg,$title,$strpath=""){
         $d=new WpIschoolOutbox;
         $d->content  = $msg;
         $d->outopenid = $from;
         $d->title   = $title;
         $d->ctime    = time();
         $d->fujian  = $strpath;
         $d->type    = 1;        
         $res = $d->save(false);
         return $res;
     }

     private function addToInboxBeforeSend($from,$to,$msg,$uname,$strpath=""){
         $d=new WpIschoolInbox;//收件箱
         $d->content  = $msg;
         $d->outopenid = $from;
         $d->inopenid  = $to;
         $d->title     = "来自".$uname."的消息";
         $d->ctime    = time();;
         $d->fujian    = $strpath;
         $d->type     = 1;
         $res = $d->save(false);
         if($res > 0 || $res === 0){
             return 'y';
         }else{
             return 'n';
         }
     }
     function actionSearchlxr(){
        $sid = \yii::$app->request->get("sid");
        $name = \yii::$app->request->get("name");
        if(!empty($name)){
            $sql = "select id,tname from wp_ischool_teacher where sid=".$sid." and tname like'%".$name."%' order by convert(tname using gbk) asc";
        }else{
            $sql="select id,tname from wp_ischool_teacher where sid=".$sid." order by convert(tname using gbk) asc";
        }
        $m=WpIschoolTeacher::findBySql($sql)->asArray()->all();
        $this->ajaxReturn($m,"json");
    }

}



