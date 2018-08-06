<?php
namespace mobile\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\helpers\Url;
use mobile\controllers\BaseController;
use mobile\models\WpIschoolUser;
use mobile\models\WpIschoolInbox;
use mobile\models\WpIschoolTeaclass;
use mobile\models\WpIschoolClass;
use mobile\models\WpIschoolPastudent;
use mobile\models\WpIschoolStudent;
use mobile\models\WpIschoolOutbox;
use mobile\models\WpIschoolPicschool;
use mobile\models\WpIschoolMsgcount;
use mobile\assets\SendMsg;
/**
 * Site controller
 */
class TongzhiController extends BaseController {
    public $layout='tongzhi';
    public function actionIndex(){
        $openid=\yii::$app->view->params['openid'];   
        
        $user= WpIschoolUser::find()->select('last_sid')->where(['openid'=>$openid])->asArray()->all();  
       
        if(empty($user)){
            $sid=1;
        }else{
            $sid=$user[0]["last_sid"];
        }
        
        $res2=WpIschoolInbox::find()->where(['inopenid'=>$openid,'type'=>'0'])->asArray()->all();
        
        //发公告班级
        $list_class = $this->getAllClass($openid,$sid)?:[];         
        $list_tea = $this->getAlltea($openid,$sid)?:[]; 

        
         // var_dump($list_tea);die;
        //发留言联系人
        $list_lxr = $this->getAllStuByOpid($openid,$sid);
        $list_stu = $this->getAllstu($openid,$sid);//与本人有关的所有孩子
        $list_tea1= $list_tea;

        //过期限制查询，主要针对家长
        if(empty($list_lxr) && $list_tea){
            $enddate =$this->checkdate($openid,$sid);
            if($enddate[0]['enddatejx']<time()){
               echo "<script>alert('您的家校沟通业务尚未开通，请点击【我要支付】开通后使用！');window.history.go(-1);</script>";die;
            }            
        }

        foreach($list_tea as $e){         
         $c[$e['cid']]=$e;           
        }
        
        foreach($list_stu as $e){
            $c[$e['cid']]=isset($c[$e['cid']])? $c[$e['cid']]+$e : $e;
        }
        // //方便演示，如果联系人老师都没有，默认取一条数据
        // if(empty($list_tea) && empty($list_lxr) && empty($list_class) && $openid=='oUMeDwEvvR3ZVPAT1toJ3LZj3QcM'){
        //     $list_tea1=WpIschoolTeaclass::find()->where(['openid'=>'oUMeDwIomEvqdWgWiz59A3zVeat8'])->groupBy(['openid'])->asArray()->all();
        // }
        $list_tea = $c;
        $return_arr['list_class']=$list_class;//发公告班级
        $return_arr['list_tea']=$list_tea1;
      
        $return_arr['list_lxr']=$list_lxr; // 发留言联系人
        $return_arr['list_msg']=$res2;    //收件箱
        $return_arr['sid']=$sid;
        $return_arr['openid']=$openid;
        
        $rest=WpIschoolTeaclass::find()->where(['openid'=>$openid])->asArray()->all();
        if(!empty($rest)){
          $return_arr['stat']="one";
        }
        $res = parent::Jssdk();
       
        $return_arr['appid']=$res['appId'];
        $return_arr['timestamp']=$res["timestamp"];
        $return_arr['nonceStr']=$res["nonceStr"];
        $return_arr['signature']=$res["signature"];
        $return_arr['pak'] =ICARD_NAME;           
        return $this->render('index',$return_arr);
    }
    function checkdate($openid,$sid){
      $query="select a.enddatejx from wp_ischool_student as a inner join wp_ischool_pastudent as b on a.id=b.stu_id where b.openid='".$openid."' and b.sid=$sid";
      return Yii::$app->db->createCommand($query)->queryAll();
    }
    function getAllstu($openid,$sid){
        $sql="select a.stu_id,a.cid,a.class,a.stu_name,b.enddatejx from wp_ischool_pastudent as a left join wp_ischool_student as b on a.stu_id=b.id where openid='".$openid."' and ispass='y' and a.sid=$sid";
        $cids = WpIschoolPastudent::findBySql($sql)->asArray()->all();     
        return $cids;
    }
    /*  抓取本人所带班级 特定于老师角色 */
    function getAllClass($openid,$sid){       
//    $sql = "select distinct t.cid,t.class from wp_ischool_teaclass t left join wp_ischool_class t2 on t2.id=t.cid where t.openid='".$openid."' and t.ispass='y' and t2.level !=0";
      $sql = "select distinct t.cid,t.class from wp_ischool_teaclass t left join wp_ischool_class t2 on t2.id=t.cid where t.openid='".$openid."' and t.sid='".$sid."' and t.ispass='y' and t2.level !=0";
      return  WpIschoolTeaclass::findBySql($sql)->asArray()->all();       
    }
    /*  抓取本人所绑定孩子所在班级 特定于家长角色 */
    function getAlltea($openid,$sid){
       
        $cids =WpIschoolPastudent::find()->select('stu_id,cid,class')->where(['openid'=>$openid,'ispass'=>'y','sid'=>$sid])->asArray()->all();
       
         $len = count($cids);
        $cid = "";
        for($i = 0; $i < $len; $i++){
          if($i != ($len-1)){
            $cid = $cid.$cids[$i]['cid'].',';
          }else{
            $cid = $cid.$cids[$i]['cid'];
          }

        }
        $sid = "";
        for($i = 0; $i < $len; $i++){
            if($i != ($len-1)){
                $sid = $sid.$cids[$i]['stu_id'].',';
            }else{
                $sid = $sid.$cids[$i]['stu_id'];
            }
        }
        $cid = trim($cid,","); 
      
	$cid_arr = explode(",",$cid);
	$cid_arr_filter = array_filter($info_arr);
	$cid = join(",",$cid_arr_filter);
        if($cid!=''){
          $sql = " select cid,id,tname,openid from wp_ischool_teaclass where cid in(".$cid.") and ispass='y' group by openid order by convert(tname using gbk) asc";
          $result= WpIschoolTeaclass::findBySql($sql)->asArray()->all();           
        }else{
          $result=null;
        }
        return $result;
    }
    /* 抓与本人有关的所有班级的学生 本人可能是老师或家长抑或双重角色 */
    public function getAllStuByOpid($openid,$sid){
        $cids = $this->getAllCid($openid,$sid);
        $cid=implode(",",$cids);
        $cid  = trim($cid,",");
        $sqls = "select id,name,enddatejx from wp_ischool_student where cid in(".$cid.") order by convert(name using gbk) asc";
        $allstu= WpIschoolStudent::findBySql($sqls)->asArray()->all(); 
    
        if($allstu){
           foreach($allstu as $k=>$v){
              $res=WpIschoolPastudent::find()->where(['stu_id'=>$v['id'],'isqqtel'=>'0'])->asArray()->all();
              $allstu[$k]['bending']=empty($res) ? 0:1;          
              $v['enddatejx']<time()? $allstu[$k]['endjx']=0:$allstu[$k]['endjx']=1;         
           }
         
        }

        return $allstu;

    }
     /*  抓相关的所有班级 */
    public function getAllCid($openid,$sid){
        $sql = "SELECT DISTINCT cid from wp_ischool_teaclass WHERE ispass='y' and openid='".$openid."' and sid='".$sid."'";
        $res= WpIschoolTeaclass::findBySql($sql)->asArray()->all();
        $array=array(0);
        foreach($res as $key => $value){
            if( $value['cid']!=" " ){
               array_push($array,$value['cid']);
            }
        }
        return $array;
    }
    public function actionInbox(){
       $openid = \yii::$app->view->params['openid'];
       $sid =  \yii::$app->request->get("sid");
       $path=URL_PATH;
       $nm= WpIschoolInbox::find()->where(['inopenid'=>$openid,'type'=>0])->asArray()->count();
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
        $return_arr['start']=$path."/tongzhi/inbox?openid=".$openid."&sid=".$sid."&page=1";
        $return_arr['up']=$path."/tongzhi/inbox?openid=".$openid."&sid=".$sid."&page=".$last;
        $return_arr['down']=$path."/tongzhi/inbox?openid=".$openid."&sid=".$sid."&page=".$next;
        $return_arr['end']=$path."/tongzhi/inbox?openid=".$openid."&sid=".$sid."&page=".$sum;      
        $res= WpIschoolInbox::find()->where(['inopenid'=>$openid,'type'=>0])->orderBy('ctime desc')->offset($star)->limit($num)->asArray()->all();

        foreach ($res as &$v) {
            if(time() - $v["ctime"] > 250000)
            {
                $v["date"] = "fail";
            }

            $content=$v["content"];
            $sta=substr($content,0,2);
            if($sta=="<p")
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
                $str = "YBUJEeeJlqZoeDd6NI8ke2sQe_g9QI5begaoUrILdnteQmG5aBgLeZYfUntlwCfj";
                $len = strlen($str);
                $length = strlen($v["content"]);
                if($length == $len)
                {
                  $v["serverId"] = $content;
                  $v["content"] = "";
                  $v["type"] = "voice";
                }
                else
                {
                  $v["type"] = "txt";
                }
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
        return $this->render('inbox', $return_arr);
    }
     /*  删除已接受信息 */
     public function actionOutbox(){ 
        $openid = \yii::$app->view->params['openid'];
        $sid =  \yii::$app->request->get("sid");
        $path=URL_PATH;
        $nm= WpIschoolOutbox::find()->where(['outopenid'=>$openid,'type'=>0])->asArray()->count();
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
       
       $return_arr['start']=$path."/tongzhi/outbox?openid=".$openid."&sid=".$sid."&page=1";
       $return_arr['up']=$path."/tongzhi/outbox?openid=".$openid."&sid=".$sid."&page=".$last;
       $return_arr['down']=$path."/tongzhi/outbox?openid=".$openid."&sid=".$sid."&page=".$next;
       $return_arr['end']=$path."/tongzhi/outbox?openid=".$openid."&sid=".$sid."&page=".$sum;
       $res= WpIschoolOutbox::find()->where(['outopenid'=>$openid,'type'=>0])->orderBy('ctime desc')->offset($star)->limit($num)->asArray()->all();

       foreach ($res as &$v) {

           $content=$v["content"];
           $sta=substr($content,0,2);
           if($sta=="<p")
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
                $str = "YBUJEeeJlqZoeDd6NI8ke2sQe_g9QI5begaoUrILdnteQmG5aBgLeZYfUntlwCfj";
                $len = strlen($str);
                $length = strlen($v["content"]);
                if($length == $len)
                {
                  $v["serverId"] = $v["content"];
                  $v["content"] = "";
                  $v["type"] = "voice";
                }
                else
                {
                  $v["type"] = "txt";
                  $v["content"]=$v["content"];
                }
             
               
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
       $return_arr['sid'] =$sid;
       $return_arr['count'] =$nm;
       $return_arr['totalPage'] =$totalPage;
       $return_arr['list_msg'] =$res;
       return $this->render('outbox',$return_arr);
       
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
           $sta=substr($content,0,2);
           if($sta=="<p")
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
//       var_dump($return_arr['name']);die;
       $return_arr['type'] =$type;
       $return_arr['toopenid'] =$outopenid;          
       $res=$this->Jssdk();
       $return_arr['appid'] =$res["appId"];;  
       $return_arr['timestamp'] =$res["timestamp"]; 
       $return_arr['nonceStr'] =$res["nonceStr"];  
       $return_arr['signature'] =$res["signature"];  
      
       return $this->render('sendmsg',$return_arr);
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
       
        $username = $this->getSenderInfo($openid);
        $arr= WpIschoolUser::find()->select('last_sid')->where(['openid'=> $openid])->asArray()->all();           
        if(empty($arr)){
          $sid=1;
        }else{
          $sid=$arr[0]["last_sid"];
        }
        $ur[0]=$path."/tongzhi/index?openid=";
        $ur[1]="&sid=".$sid;
       
        if($msgType=='gg') {   //公告
          $tos = $this->getParOpidByCid($to);
        }else{                
          $tos= $this->getParByStuids($to);
        }
        //正则去掉html标签
        if(!empty($des)){
           $preg = "/<\/?[^>]+>/i";
           $des = preg_replace($preg,'',$des);
           $des=preg_replace('/[&nbsp;]/','', $des); 
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
        $data['pic_url'] =  $this->getSchoolPic($sid); 
        $result = $this->sendJXTMsg($openid,$tos,$data);
        if($result->errcode == 0){
            $this->setMsgNum($msgType,$to);
        }
        $this->ajaxReturn($result,'json');
     }
     /*  发送人的信息 姓名 */
     public function getSenderInfo($openid){
      $res= WpIschoolUser::find()->select('name')->where(['openid'=> $openid])->asArray()->all(); 
      return $res[0]['name'];
     }
     
     /*  通过发公告班级id抓去本班家长openid，
        此处可能是同时多个班级，发留言则是单个班级
        */
     function getParOpidByCid($cids){           
        $pOpenids = array();
        $cids = explode(";",$cids);

        foreach ($cids as $v) {  //**********此段代码有争议 hhb
            $len = strlen($v);
            if($len > 11)//长度大于11位是openid否则就是stuid
            {
              $openid[] = $v;
            }
        }

        foreach ($cids as $cid) {
            $con['cid'] = $cid;
            $con['ispass'] = 'y';
            $res = WpIschoolPastudent::find()->select('openid')->where($con)->asArray()->all();          
            $this->insertArrToOther($res,$pOpenids);
        }
        return $pOpenids;
     }
     
      /*  将结果openid数组分别追加到另一个数组，最后返回新数组 */
     function insertArrToOther($oldArr,&$newArr){
        $len1 = count($oldArr);
        for($i = 0; $i < $len1; $i++){
            $newArr[] = $oldArr[$i]['openid'];
        }
     }
     
      /*  发留言中 通过学生id获取家长openid需要批量查询 */
     /*  由于有老师混杂其间，而且老师身份传来的直接是openid，所有需要做2合1操作 */
     function getParByStuids($stuids){
	$stuids = trim($stuids, ";");
        $stuids = explode(';',$stuids);      
        $openid = "";
        foreach ($stuids as $v) { //******此段代码有争议 hhb
            $len = strlen($v);
            if($len > 11)//长度大于11位是openid否则就是stuid
            {
              $openid[] = $v;
            }
        }
	$con[] = "and";
        $con[] = array('in','stu_id',$stuids);
        $con[] = array("=",'ispass','y');
        $res = WpIschoolPastudent::find()->select('openid')->where($con)->asArray()->all();      
       
        $pOpenids = array();
        $this->insertArrToOther($res,$pOpenids);
        if(!empty($openid))
        {
            $pOpenids = array_merge($openid,$pOpenids);
        }
        
        return $pOpenids;
     }
     
      //获取学校图片信息
     private function getSchoolPic($sid){       
        $toppic =WpIschoolPicschool::find()->select('toppic')->where(['schoolid'=> $sid])->asArray()->all();   
        if($toppic){
            return $toppic[0]['toppic'];
        }else{
            return URL_PATH."/upload/syspic/msg.jpg";
        }
     }
    
     private function sendJXTMsg($from,$tos,$data){      
        $des     = $data['des'];
        $msg     = $data['content'];
        $zhuti   = $data['zhuti'];
        $strPath = $data['strpath'];
        $uname   = $data['title'];
        //发送前存入发件箱
        $this->addToOutboxBeforeSend($from,$msg,$zhuti,$strPath);
        foreach($tos as $to){
            $this->addToInboxBeforeSend($from,$to,$msg,$uname,$strPath);
        }  
        $openid =\yii::$app->view->params['openid'];
        $res= WpIschoolUser::find()->select('last_sid')->where(['openid'=> $openid])->asArray()->all();         
        $sid  =$res[0]['last_sid'];
        $one=WpIschoolTeaclass::find()->select()->where(['tname'=>$uname,'sid'=>$sid,'ispass'=>'y'])->andwhere(['<>','class','管理'])->asArray()->one(); 
        if($one){
            $shenfen="老师";
        }else{
            $shenfen="家长";
        }
        $data['title'] = "来自".$uname."".$shenfen."的消息";
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
            $d->type    = 0;
            $d->out_uid=$this->getUid();        
            $res = $d->save();
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
            $d->type     = 0;
            $d->out_uid=$this->getUid();
            $d->in_uid=$this->getUid($to);
            $res = $d->save();
            if($res > 0 || $res === 0){
                return 'y';
            }else{
                return 'n';
            }
     }
      
     
      /**
      * 信息发送频率统计
      */
      private function setMsgNum($msgType,$to){
        $ym     = date("Ym");
        $cidArr = explode(';',$to);
        if($msgType == 'gg'){
            $type = 0;
        }else{
            $type = 1;
            $cids = WpIschoolStudent::find()->select('cid')->distinct(true)->where(['in','id',$cidArr])->asArray()->all();                               
            foreach($cids as $cid){
                $cidArr[] = $cid['cid'];
            }
        }

        foreach($cidArr as $cid){
            $data['cid'] = $cid;
            $data['ym'] = $ym;
            $data['type'] = $type;
            $res =WpIschoolMsgcount::find()->where($data)->asArray()->all();
            if(empty($res)){
                $m=new WpIschoolMsgcount;
                $m->num = 1;
                $m->save();
            }else{
                WpIschoolMsgcount::updateAllCounters($data,['num'=>1]);
            }
        }
    }
}





