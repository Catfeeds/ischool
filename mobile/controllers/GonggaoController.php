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
use mobile\assets\SendMsg;
use mobile\assets\Helper;
use yii\helpers\Url;

/**
 * Site controller
 */
class GonggaoController extends BaseController {
   
    public $layout='gonggao';
    public function actionIndex(){
        
        $sid =  \yii::$app->request->get("sid");
        $openid = \yii::$app->view->params['openid'];
        $page = \yii::$app->request->get("page");
         if(isset($openid) && isset($sid)){
         
            $wher['openid'] = $openid;
            $aa =WpIschoolUser::find()->where($wher)->one();
	    if($aa){
            $aa->last_sid=$sid;
            $aa->save(false);
	    }
        }
        $user= WpIschoolUser::find()->where(['openid'=>$openid])->asArray()->all();
        
        if(empty($user)){
            $sid="1";
        }else{
            $sid=$user[0]['last_sid'];
        }
        //查询学校名称
       $data =WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->all();
     
        $return_arr['ischool']=$data[0]['name'];
        //查询公告信息条数
       $nm =WpIschoolGonggao::find()->select('name')->where(['sid'=>$sid])->asArray()->count();
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
        //下一页
        $next=$page+1;
        //上一页
        $last=$page-1;
        //总页数
        $path=URL_PATH;
        $return_arr['count']=$nm;
        $return_arr['totalPage']=$totalPage;
//        $return_arr['start']=$path."/index.php?s=/addon/Gonggao/Gonggao/index/openid/".$openid."/sid/".$sid."/page/1.html";
        $return_arr['start']=Url::toRoute(['gonggao/index','openid'=>$openid,'sid'=>$sid,'page'=>1]);
//        $return_arr['up']=$path."/index.php?s=/addon/Gonggao/Gonggao/index/openid/".$openid."/sid/".$sid."/page/".$last.".html";
        $return_arr['up']=Url::toRoute(['gonggao/index','openid'=>$openid,'sid'=>$sid,'page'=>$last]);

        $return_arr['down']=Url::toRoute(['gonggao/index','openid'=>$openid,'sid'=>$sid,'page'=>$next]);

        $return_arr['end']=Url::toRoute(['gonggao/index','openid'=>$openid,'sid'=>$sid,'page'=>$sum]);
        //进行权限验证
        $this->saveAccessList($openid,$sid);
        $do = $this->checkAccess("Root");
        $de = $this->checkAccess("Gonggao"); 
       
        if($de){
            $return_arr['bool']=1;
        }
        if($do){
            $return_arr['bool']=1;
        }
      
       $arr=WpIschoolGonggao::find()->select("id,title,ctime")->where(['sid'=>$sid])->orderBy('ctime desc')->offset($star)->limit($num)->asArray()->all();
        
       $return_arr['openid'] =$openid;
       $return_arr['sid'] =$sid;  
       $return_arr['path'] =URL_PATH;
       $return_arr['list_gonggao'] =$arr;
       $return_arr['pak'] =ICARD_NAME;
       $return_arr['jxt'] =TONGZHI_NAME;
       return $this->render('index',$return_arr);
    }
     
      public function actionDes(){
          $gid=\yii::$app->request->get("gid");
          $openid=\yii::$app->view->params['openid'];
          $sid= \yii::$app->request->get("sid");
          $return_arr['ischool']=\yii::$app->request->get("school");
          $gg =WpIschoolGonggao::find()->where(['id'=>$gid])->asArray()->all();
          $return_arr['gg']= $gg;
          $this->saveAccessList($openid,$sid);
          $do=$this->checkAccess('Root');
          $de=$this->checkAccess('Gonggao');
          if($de){
           $return_arr['bool']=1;
          }
           if($do){
               $return_arr['bool']=1;
           }
           $return_arr['pak']=ICARD_NAME;
           $return_arr['jxt']=TONGZHI_NAME;
           $return_arr['sid']=$sid;
           return $this->render('des',$return_arr);
           
      }
       public function actionAdd(){
        $openid=\yii::$app->view->params['openid'];
        $sid= \yii::$app->request->get("sid");
     
        $arr =WpIschoolUser::find()->select('last_sid')->where(['openid'=>$openid])->asArray()->all();

        if(empty($arr)){
           $dat= WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->all();
           $return_arr['ischool']=$dat[0]['name'];
         
        }else{
           $sid = $arr[0]['last_sid'];
           $da= WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->all();
           $return_arr['ischool']=$da[0]['name'];
        } 
        $return_arr['pak']=ICARD_NAME;
        $return_arr['jxt']=TONGZHI_NAME;
        $return_arr['sid']=$sid;
        return $this->render('add',$return_arr);
     }
      public function actionSubmit(){
        $openid=\yii::$app->view->params['openid'];
        $sid= \yii::$app->request->post("sid");       
        $title=\yii::$app->request->post("title");
        $content=\yii::$app->request->post("content");
    
        $ctime=time();
        $arr=WpIschoolUser::find()->select('name')->where(['openid'=>$openid])->asArray()->all();
        $name=$arr[0]['name'];
        $gonggao=new WpIschoolGonggao;
        $gonggao->sid=$sid;
        $gonggao->title=$title;
        $gonggao->content=$content;
        $gonggao->ctime=$ctime;
        $gonggao->name=$name;
        $a=$gonggao->save(false);
        if($a) {
            $at['status']='success';       
            $url = URL_PATH;
            $url = $url."/gonggao/broadggtz";
            $data['sid']= $sid;
            $helper=new Helper();
            $helper->asynBroad($url,$data);
	    
	$allUser =$helper->getAllUser($sid);
        $url = URL_PATH;
        $url = $url."/gonggao/index?sid=".$sid."&openid=";
        $data['url'] = $url;
        $data['title']="最新公告提醒";
        $data['content']=$school[0]['name']."发布了最新公告";
        $data['picurl'] =$this->getSchoolPics($sid);
        SendMsg::broadMsgToManyUsers($allUser,$data);

         }else{
            $at['status']='fail'; 
         }
         $this->ajaxReturn($at,'json');
      }
      
      public function actionBroadggtz(){
        $sid= \yii::$app->request->get("sid");
        $helper=new Helper();
        $allUser =$helper->getAllUser($sid);
        $school = $helper->getSchool($sid);        
        $url = URL_PATH;
        $url = $url."/gonggao/index?sid=".$sid."&openid=";
        $data['url'] = $url;
        $data['title']="最新公告提醒";
        $data['content']=$school[0]['name']."发布了最新公告";
        $data['picurl'] =$this->getSchoolPics($sid);
        SendMsg::broadMsgToManyUsers($allUser,$data);
        return 0;
     }
     public function actionDelete(){
        $openid=\yii::$app->view->params['openid'];
        $sid= \yii::$app->request->post("sid");
        $gid=\yii::$app->request->post("gid");
        $a=WpIschoolGonggao::find()->where(['id'=>$gid])->one();
        $arr=$a->delete();
        if($arr==1){
            $data['result']='success';
        }else{
            $data['result']='fail';
        }
        $this->ajaxReturn($data,'json');
     }
    public function actionEdit(){
        $openid=\yii::$app->view->params['openid'];
        $sid= \yii::$app->request->get("sid");
        $gid=\yii::$app->request->get("gid");
        $arr=WpIschoolUser::find()->select('last_sid')->where(['openid'=>$openid])->asArray()->all();      
        if(empty($arr)){
         $dat=WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->all(); 
         $return_arr['ischool']=$dat[0]['name'];     
        }
        else{
         $id =$arr[0]['last_sid'];
         $da=WpIschoolSchool::find()->select('name')->where(['id'=>$id])->asArray()->all();   
         $return_arr['ischool']=$da[0]['name'];
        }
        $arr=WpIschoolGonggao::find()->where(['id'=>$gid])->asArray()->all();  
        $return_arr['gonggao_edit']=$arr;
        $return_arr['sid']=$sid;
        $return_arr['pak']=ICARD_NAME;          
        $return_arr['jxt'] =TONGZHI_NAME;
	return $this->render('edit',$return_arr);
    }
    public function actionSub_edit(){
        $openid=\yii::$app->view->params['openid'];
        $sid= \yii::$app->request->get("sid");
        $oid=\yii::$app->request->post("gid");
        $title=\yii::$app->request->post("title");
        $content=\yii::$app->request->post("content");
        $ctime=time();
        $gonggao=WpIschoolGonggao::find()->where(['id'=>$oid])->one();     
        $gonggao->title=$title;
        $gonggao->content=$content;
        $gonggao->ctime=$ctime;
        $arr=$gonggao->save(false);
        if($arr) {
            $at['status']='success';
        }else {
            $at["status"]='fail';
        }
        $this->ajaxReturn($at,'json');
    }  
}


