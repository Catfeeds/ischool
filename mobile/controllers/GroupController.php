<?php

namespace mobile\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\helpers\Url;
use mobile\models\WpIschoolQunzu;
use mobile\models\WpIschoolUser;
use mobile\models\WpIschoolClass;
use mobile\assets\SendMsg;
class GroupController extends BaseController {
    public $layout='group';
    //首页
    public function actionIndex(){
        $qunzu=\yii::$app->request->get('qunzu');
        $sid=\yii::$app->view->params['sid'];
        $openid=\yii::$app->view->params['openid'];

        $alluser=WpIschoolUser::find()->where(['last_sid'=>$sid])->andWhere(['like','label',$qunzu])->orderBy('convert(name using gbk) ASC')->asArray()->all();
  
        if($alluser){
          foreach($alluser as $k=>$v){
              if($v['openid']==$openid){
                $return_arr['username']=$v['name']; 
              }
          }

          $return_arr['alluser']=$alluser;
        }
        $return_arr['sid']=$sid;
        $return_arr['openid']=$openid;
        $return_arr['qunzu']=$qunzu;
        return $this->render('index',$return_arr);
    }
    //编辑信息页面
    public function actionSendmsg(){
        return $this->render('sendmsg',$return_arr);
    }
    //已发信息
    public function actionOutbox(){
        $openid=\yii::$app->view->params['openid'];
        $qunzu=\yii::$app->request->get('qunzu');
        $sid=\yii::$app->view->params['sid'];
        
        $nm= WpIschoolQunzu::find()->where(['sid'=>$sid,'type'=>$qunzu,'outopenid'=>$openid])->asArray()->count();
        $page=\yii::$app->request->get("page");
        //每页显示的条数
        $num=8;
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
        $return_arr['start']=$path."/group/outbox?qunzu=".$qunzu."&sid=".$sid."&page=1";
        $return_arr['up']=$path."/group/outbox?qunzu=".$qunzu."&sid=".$sid."&page=".$last;
        $return_arr['down']=$path."/group/outbox?qunzu=".$qunzu."&sid=".$sid."&page=".$next;
        $return_arr['end']=$path."/group/outbox?qunzu=".$qunzu."&sid=".$sid."&page=".$sum;
        
        $res=WpIschoolQunzu::find()->where(['sid'=>$sid,'type'=>$qunzu,'outopenid'=>$openid])->orderBy('ctime desc')->offset($star)->limit($num)->asArray()->all();
        $res=$this->handleContent($res);
        $return_arr['list_msg'] =$res;
        $return_arr['count'] =$nm;
        $return_arr['totalPage'] =$totalPage;
        $return_arr['sid'] =$sid;
        return $this->render('outbox',$return_arr);
    }
  
    //处理信息
    public function handleContent($res){
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
        return $res;
    }
    //收件信息
    public function actionInbox(){
        $openid=\yii::$app->view->params['openid'];
        $qunzu=\yii::$app->request->get('qunzu');
        $sid=\yii::$app->view->params['sid'];
      
        $nm= WpIschoolQunzu::find()->where(['sid'=>$sid,'type'=>$qunzu])->asArray()->count();
        $page=\yii::$app->request->get("page");
        //每页显示的条数
       $num=8;
        //一共多少页
        $sum=ceil($nm/$num);
        $totalPage=ceil($nm/$num);
        if(!empty($page)){
              if($page<1)
              {
                  $page=1;
              }
              if($page>$sum)
              {
                  $page=$sum;
              }
        }else{
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
        $return_arr['start']=$path."/group/inbox?qunzu=".$qunzu."&sid=".$sid."&page=1";
        $return_arr['up']=$path."/group/inbox?qunzu=".$qunzu."&sid=".$sid."&page=".$last;
        $return_arr['down']=$path."/group/inbox?qunzu=".$qunzu."&sid=".$sid."&page=".$next;
        $return_arr['end']=$path."/group/inbox?qunzu=".$qunzu."&sid=".$sid."&page=".$sum;
        
        
        $res=WpIschoolQunzu::find()->where(['sid'=>$sid,'type'=>$qunzu])->orderBy('ctime desc')->offset($star)->limit($num)->asArray()->all();
         
        $res=$this->handleContent($res);
        $return_arr['count'] =$nm;
        $return_arr['totalPage'] =$totalPage;
        $return_arr['list_msg'] =$res;
        return $this->render('inbox',$return_arr);
    }
    //发送消息
    public function actionDosendmsg(){  
         $strpath = \yii::$app->request->post("strpath");//附件
         $openid  = \yii::$app->view->params['openid'];//发送人  
         $sid = \yii::$app->view->params['sid'];//发送人 
         $des = $msg =\yii::$app->request->post("msg"); 
         $qunzu = \yii::$app->request->post("qunzu");
         $serid   = \yii::$app->request->post("serid");//语音id
         $title   = \yii::$app->request->post("title");  //消息主题  

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
         //查找年级id
         $sql="select c.level from wp_ischool_user as u inner join wp_ischool_class as c on u.last_cid=c.id where u.openid='".$openid."'";
         $grade=WpIschoolUser::findBySql($sql)->asArray()->all();
         //将信息入库
         $qz=new WpIschoolQunzu;
         $qz->content=$msg;
         $qz->outopenid=$openid;
         if($grade){
          $qz->grade_id=$grade[0]['level'];
         }       
         $qz->sid=$sid;
         $qz->ctime=time();
         $qz->title=$title;
         $qz->fujian=$strpath;
         $qz->type=$qunzu;
         $result=$qz->save(false);
         //消息添加后发送推送信息
         $data['title']=$title;
         $data['content']=$des;
         $data['sid']=$sid;
         $tos=$this->getAllopenid($sid,$qunzu);
         $this->sendmsg($data,$title,$tos,$qunzu);
         if($result){
             $result=json_decode('{"errcode":0}');
         }
         $this->ajaxReturn($result,'json');
      }
      public function getAllopenid($sid,$qunzu){
         $openid=\yii::$app->view->params['openid'];
         $pOpenids=array();  
         $all=WpIschoolUser::find()->select('openid')->where(['last_sid'=>$sid])->andWhere(['like','label',$qunzu])->asArray()->all();                     
         $leng=count($all);
         for($i=0;$i<$leng;$i++){
          if($all[$i]['openid']!=$openid){
             $pOpenids[]=$all[$i]['openid'];
          }             
         }
        
         return $pOpenids;
      }
     public function sendmsg($data,$uname,$tos,$qunzu){
         $title=$data['title'];
         $content=$data['content'];
         $sid=$data['sid'];
         $title = "来自".$uname."老师的消息";
         // $url='http://mobile.jxqwt.cn/group/index?qunzu='.$qunzu;
         $ur[0]="http://mobile.jxqwt.cn/group/index?openid=";
         $ur[1]="&sid=".$sid."&qunzu=".$qunzu;
         $data['title'] = $title;
         $data['content'] = $des;
         $data['url'] = $ur;
         $data['picurl'] =$this->getSchoolPics($sid);
         return SendMsg::muiltPostMsg($tos,$data,$url);
     }
      /*  删除已发送信息 */
     public function actionDeleoutbox(){       
       $mid=\yii::$app->request->post("mid");
       $res= WpIschoolQunzu::findOne($mid)->delete();     
       if($res>0||$res===0){
          $this->ajaxReturn('success','json');
       }else{
          $this->ajaxReturn('fail','json');
       }  
     }
}