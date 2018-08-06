<?php
namespace mobile\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use mobile\controllers\BaseController;
use mobile\models\WpIschoolNews;
use mobile\models\WpIschoolUser;
use mobile\models\WpIschoolSchool;
use mobile\models\WpIschoolTeaclass;
use yii\helpers\Url;
use mobile\assets\Helper;
use mobile\assets\SendMsg;

/**
 * Site controller
 */
class SchoolnewsController extends BaseController {
    public $layout='schoolnews';
    public function actionIndex(){  
       
        $sid = \yii::$app->request->get("sid");
        $openid = \yii::$app->request->get("openid");
        $page = \yii::$app->request->get("page");
        $user= WpIschoolUser::find()->select('last_sid')->where(['openid'=>$openid])->asArray()->all();
        
        if(empty($user)){
            $sid=1;
            $data =WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->all();
            $return_arr['ischool']=$data[0]['name'];
        }else{
            if($sid==""||empty($sid)){
                $sid=$user[0]["last_sid"];
            }
            $data =WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->all();
            $return_arr['ischool']=$data[0]['name'];
        }
       
        //查询新闻信息条数
       $nm =WpIschoolNews::find()->where(['sid'=>$sid])->asArray()->count();
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
        $return_arr['start']=Url::toRoute(['schoolnews/index','openid'=>$openid,'sid'=>$sid,'page'=>1]);
        $return_arr['up']=Url::toRoute(['schoolnews/index','openid'=>$openid,'sid'=>$sid,'page'=>$last]);
        $return_arr['down']=Url::toRoute(['schoolnews/index','openid'=>$openid,'sid'=>$sid,'page'=>$next]);
        $return_arr['end']=Url::toRoute(['schoolnews/index','openid'=>$openid,'sid'=>$sid,'page'=>$sum]);
        //进行权限验证
        $this->saveAccessList($openid,$sid);
        $do = $this->checkAccess("Root");
        $bzr =WpIschoolTeaclass::find()->select('id')->where(["sid"=>$sid,"ispass"=>"y","role"=>"校长","openid"=>$openid])->asArray()->all();
        if($bzr){
             $return_arr['bool']=1;
        }
        // if($do){
        //     $return_arr['bool']=1;
        // }else{               
        //     $de = $this->checkAccess("News");            
        //     if($de){
        //         $return_arr['bool']=1;
        //     }else{
        //         //判断是否该校班主任，是则自动拥有班级动态发布权限                 
        //         $bzr =WpIschoolTeaclass::find()->select('id')->where(["sid"=>$sid,"ispass"=>"y","role"=>"班主任","openid"=>$openid])->asArray()->all();
        //         if(!empty($bzr)){
        //          $return_arr['bool']=1;
        //         }                
        //      }
 
        // }
          $arr=WpIschoolNews::find()->select("id,title,ctime")->where(['sid'=>$sid])->orderBy('ctime desc')->offset($star)->limit($num)->asArray()->all();
        
        $return_arr['openid'] =$openid;
        $return_arr['sid'] =$sid;  
        $return_arr['path'] =URL_PATH;
        $return_arr['list_news'] =$arr;
        $return_arr['pak'] =ICARD_NAME;
        $return_arr['jxt'] =TONGZHI_NAME;    
        return $this->render('index',$return_arr);
    }
    public function actionDes(){
        $gid=\yii::$app->request->get("gid");
        $openid=\yii::$app->view->params['openid'];
        $sid= \yii::$app->request->get("sid");
        $return_arr['ischool']=\yii::$app->request->get("school");
        
        $gg =WpIschoolNews::find()->where(['id'=>$gid])->asArray()->all();
        $return_arr['gg']= $gg;
        $this->saveAccessList($openid,$sid);
        $do=$this->checkAccess('Root');
       
        if($do){
             $return_arr['bool']=1;
             $return_arr['boolt']=1;
        }else{
             $de=$this->checkAccess('News');
           if($de){
             $return_arr['bool']=1;
             $return_arr['boolt']=1;   
            }else{
                //判断是否该校班主任，是则自动拥有班级动态发布权限                 
                // $bzr =WpIschoolTeaclass::find()->select('id')->where(["sid"=>$sid,"ispass"=>"y","role"=>"班主任","openid"=>$openid])->asArray()->all();
                // if(!empty($bzr)){
                //  $return_arr['bool']=1;
                // }

                if($gg){
                    if($gg[0]['openid']==$openid){
                        $return_arr['boolt']=1;
                    }
                 }
            }       
        }
         $return_arr['sid']=$sid;
         $return_arr['pak']=ICARD_NAME;
         $return_arr['jxt']=TONGZHI_NAME;
//         var_dump($return_arr);die;
         return $this->render('des',$return_arr);
       
           
     }
     public function actionAdd(){
        $openid=\yii::$app->view->params['openid'];
        $sid= \yii::$app->request->get("sid");  
        $arr =WpIschoolUser::find()->select('last_sid')->where(['openid'=>$openid])->asArray()->all();
        if(!empty($arr)){
             $sid = $arr[0]['last_sid'];
        }
        $dat =WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->all();
        $return_arr['ischool']=$dat[0]['name'];
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
        $schoolnews=new WpIschoolNews;
        $schoolnews->sid=$sid;
        $schoolnews->title=$title;
        $schoolnews->content=$content;
        $schoolnews->ctime=$ctime;
        $schoolnews->name=$name;
        $schoolnews->openid=$openid;
        $news=$schoolnews->save(false);
        if($news) {
            $at['status']='success';       
            $url = URL_PATH;
            $url = $url."/schoolnews/broadggTz";
            $data['sid']= $sid;
            $this->asynBroad($url,$data);
		

	$helper=new Helper();
	$allUser =$helper->getAllUser($sid);

	\yii::trace($allUser);
        $url = URL_PATH;
        $url = $url."/schoolnews/index?sid=".$sid."&openid=";
        $data['url'] = $url;
        $data['title']="最新动态提醒";
        $data['content']=$school[0]['name']."发布了最新动态";
        $data['picurl'] =$this->getSchoolPics($sid);
        SendMsg::broadMsgToManyUsers($allUser,$data);
		
         }else{
            $at['status']='fail'; 
         }
         $this->ajaxReturn($at,'json');
      }
       public function broadggTz(){
        $sid= \yii::$app->request->get("sid");
        $allUser =$this->getAllUser($sid);
        $school = $this->getSchool($sid);        
        $url = URL_PATH;
	$url = $url."/schoolnews/index?sid=".$sid."&openid=";
        $data['url'] = $url;
        $data['title']="最新动态提醒";
        $data['content']=$school[0]['name']."发布了最新动态";
        $data['picurl'] =$this->getSchoolPics($sid);
        SendMsg::broadMsgToManyUsers($allUser,$data);
        return 0;
      }
      public function actionDelete(){      
        $gid=\yii::$app->request->post("gid");
        $arr=WpIschoolNews::find()->where(['id'=>$gid])->one();
        $m=$arr->delete();
        if($m){
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
        $arr=WpIschoolNews::find()->where(['id'=>$gid])->asArray()->all();  
        $return_arr['schoolnews_edit']=$arr;
        $return_arr['pak']=ICARD_NAME;   
        $return_arr['jxt'] =TONGZHI_NAME;
        $return_arr['sid'] =$sid;
	return $this->render('edit',$return_arr);
    }
    public function actionSub_edit(){
        $oid=\yii::$app->request->post("gid");
        $title=\yii::$app->request->post("title");
        $content=\yii::$app->request->post("content");
        $ctime=time();
        $schoolnews=WpIschoolNews::find()->where(['id'=>$oid])->one();     
        $schoolnews->title=$title;
        $schoolnews->content=$content;
        $schoolnews->ctime=$ctime;
        $arr=$schoolnews->save(false);
        if($arr) {
            $at['status']='success';
        }else {
            $at["status"]='fail';
        }
        $this->ajaxReturn($at,'json');
    }  
}




