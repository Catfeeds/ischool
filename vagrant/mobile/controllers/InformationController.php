<?php
namespace mobile\controllers;

use Yii;
use mobile\controllers\BaseController;
use mobile\models\WpIschoolUser;
use mobile\models\WpIschoolSchool;
use mobile\models\WpIschoolSubject;
use mobile\models\WpIschoolStudent;
use mobile\models\WpIschoolStuLeave;
use mobile\models\WpIschoolSuperman;
use mobile\models\WpIschoolSchooltype;
use mobile\models\WpIschoolTeacher;
use mobile\models\WpIschoolTeaclass;
use mobile\models\WpIschoolUserTuijian;
use mobile\models\WpIschoolPastudent;
use mobile\models\WpIschoolProvince;
use mobile\models\WpIschoolPicschool;
use mobile\models\WpIschoolUserschool;
use mobile\models\WpIschoolUserRole;
use mobile\models\WpIschoolLeaveRule;
use mobile\models\WpIschoolChengjidanType;
use mobile\models\WpIschoolChengjidan;
use mobile\models\WpIschoolClassChengjidan;
use mobile\models\WpIschoolChengji;
use mobile\models\WpIschoolClass;
use mobile\models\WpIschoolCity;
use mobile\models\WpIschoolManage;
use mobile\models\WpIschoolSchoolManageEpc;
use mobile\models\WpIschoolChengjiKemu;
use mobile\models\WpIschoolClassImages;
use mobile\models\ImportData;
use mobile\models\UploadForm;
use mobile\assets\Helper;
use mobile\assets\SendMsg;
use mobile\assets\Excel;
use yii\helpers\Url;
use yii\web\UploadedFile;



class InformationController extends BaseController {
    public $layout='information';
    private $source_data;//上传的excel数据 
    public function actionIndex(){
        $openid = \yii::$app->view->params['openid'] ;  
        $res= WpIschoolUser::find()->where(['openid'=>$openid])->asArray()->all();
        if(empty($res))
        {
            $url = Url::toRoute(['information/add','openid'=>$openid,'sid'=>1]);
	    return $this->redirect($url);
        }
        else
        {
            $res= WpIschoolUser::find()->where(['openid'=>$openid])->asArray()->all();
            $sid=$res[0]["last_sid"];
            $return_arr['sid']=$sid;
            $return_arr['pak'] = ICARD_NAME;
            $return_arr['jxt'] = TONGZHI_NAME;
            return $this->render('index',$return_arr);
        }            
    }
    /* 绑定基本资料页面*/
    public function actionAdd(){
        $openid = \yii::$app->request->get("openid");   
        $sid = \yii::$app->request->get("sid");  
        $res=WpIschoolUser::find()->where(['openid'=>$openid])->asArray()->all();
        if(!empty($res))
        {
            Url::toRoute(['information/index','openid'=>$openid]);       
        }
        $return_arr['openid']=$openid;
        $return_arr['sid']=$sid;
        $return_arr['pak']=ICARD_NAME;
        return $this->render('add',$return_arr);
    }
    public function actionSaveuserinfo(){
        $openid = \yii::$app->request->post("openid");  
        $username = \yii::$app->request->post("username"); 
        $tel = \yii::$app->request->post("tel"); 
        $rectel = \yii::$app->request->post("rectel");//推荐人
	$shenfen = \yii::$app->request->post("shenfen");
        $res=WpIschoolUser::find()->where(['tel'=>$tel])->asArray()->all();
        if(empty($res))
        {
            if(!empty($rectel)&&$rectel!=null&&$rectel!=""){
                $sql="update wp_ischool_user set score=score+1000 where tel=".$rectel;
                Yii::$app()->db->createCommand($sql)->execute(); 
                $m2=new WpIschoolUserTuijian;          
                $m2->name=$username;
                $m2->openid=$openid;
                $m2->utel=$rectel;
                $m2->save(false);
            } //先处理推荐人可以很好的解决自己推荐自己的情况
            $m=new WpIschoolUser;
            $m->name=$username;
            $m->tel=$tel;
            $m->openid=$openid;
            $m->score=100;
            $m->last_sid=1;
            $m->ctime=time();
	    $m->shenfen=$shenfen;
	    $info = rand(100000, 999999);
	    $m->pwd = md5($info);
            $m->save(false);
	
	    $msg = "尊敬的用户您好，您的初始登录密码为".$info."，用此密码您可以在电脑端进行登录，感谢您的注册。";
	    $data = '{
                "touser":"'.$openid.'",
                "msgtype":"text",
                "text":
                {
                "content":"'.$msg.'"
                }
                }';
            SendMsg::https_post(SendMsg::getUrl('kf'),$data);
            $at="success";
        }
        else
        {
            $at=0;
        }

        $this->ajaxReturn($at,'json');
    }
    public function actionMyallinfo(){
        $openid = \yii::$app->view->params['openid'] ; 
        $sid= \yii::$app->request->get("sid"); 
        //--------查找学校-----------------//
        $art=WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->all();
        //------查找用户信息--------------//
        $user=WpIschoolUser::find()->select('id,name,tel,score')->where(['openid'=>$openid])->asArray()->all();
       
        $tea=WpIschoolTeacher::find()->select('id,sid,school,ispass,tname')->where(['openid'=>$openid])->asArray()->all();
       
        $par=WpIschoolPastudent::find()->select('id,school,class,stu_name,ispass')->where(['openid'=>$openid])->asArray()->all();
        $return_arr['school']=$art[0]['name'];
        $return_arr['list_tea']=$tea;
        $return_arr['list_p']=$par;
        $return_arr['u']=$user[0]['id'];
        $return_arr['uname']=$user[0]['name'];
        $return_arr['utel']=$user[0]['tel'];
        $return_arr['score']=$user[0]['score'];
//       var_dump($par);die;
        return $this->render('myallinfo',$return_arr);
    }
    
     public function actionEdituserinfo(){
        $openid= \yii::$app->view->params['openid']; 
        $res=WpIschoolUser::find()->where(['openid'=>$openid])->asArray()->all();
        $res2=WpIschoolUserTuijian::find()->select('utel')->where(['openid'=>$openid])->asArray()->all();
        $return_arr['the_user']=$res;
        $return_arr['utel']=$res2[0]['utel'];
        return $this->render('edituserinfo',$return_arr);
     }
     public function actionSaveuser(){
        $openid=\yii::$app->view->params['openid'];
        $username = \yii::$app->request->post("username");
        $tel = \yii::$app->request->post("tel");              
        $ret=WpIschoolUser::find()->where(['tel'=>$tel])->andwhere(['<>','openid',$openid])->asArray()->all();
        if($ret[0]["tel"]==$tel)
        {
            $at=0;
        }
        else
        {   
            $user = WpIschoolUser::find()->where(['openid'=>$openid])->one(); 
            $user->name=$username;
            $user->tel=$tel;
            $user->ctime=time();        
            $user->save(false);   
            $at="success";          
            $teacher = WpIschoolTeacher::find()->where(['openid'=>$openid])->one(); 
	    if($teacher) {
            	$teacher->tname=$username;
            	$teacher->tel=$tel;
            	$teacher->ctime=time();        
            	$teacher->save(false);
	    }           
            $teaclass = WpIschoolTeaclass::find()->where(['openid'=>$openid])->one(); 
	    if ($teaclass) {
            	$teaclass->tname=$username;
            	$teaclass->ctime=time();        
            	$teaclass->save(false);
	    }                       
            $pastudent = WpIschoolPastudent::find()->where(['openid'=>$openid])->one(); 
	    if ($pastudent){
            	$pastudent->name=$username;
            	$pastudent->tel=$tel;
            	$pastudent->ctime=time();        
            	$pastudent->save(false);
	    }
        }
        $this->ajaxReturn($at,'json');
     }
     
     public function actionMyallchild(){
         $openid=\yii::$app->view->params['openid'];
         $res = WpIschoolPastudent::find()->select('stu_id,stu_name,ispass')->where(['openid'=>$openid])->asArray()->all();  
         $return_arr['list_stu']=$res;
         return $this->render('myallchild',$return_arr);
     }
     public function actionAddonechild(){
        $openid=\yii::$app->request->get("openid"); 
        $res=WpIschoolUserschool::find()->select('schoolid')->where(['openid'=>$openid])->asArray()->all();        
        $return_arr['ssid']=$res[0]["schoolid"];
        $rp= WpIschoolSchool::find()->select('id,name')->where(['id'=>$res[0]["schoolid"]])->asArray()->all();  
        $return_arr['schname']=$rp[0]["name"];
        $return_arr['schid']=$rp[0]['id'];
        $sql='select distinct pro from wp_ischool_school ORDER BY convert(pro USING gbk)';
        $ress=WpIschoolSchool::findBySql($sql)->asArray()->all();
        $return_arr['list_pro']=$ress; 
        return $this->render('addonechild',$return_arr);
     }
     //获取城市列表
     public function actionGetcity(){
        $pname=\yii::$app->request->get("code"); 
        $sql="select distinct city from wp_ischool_school where pro='".$pname."' ORDER BY convert(city USING gbk)";
        $res=WpIschoolSchool::findBySql($sql)->asArray()->all();            
        $this->ajaxReturn($res,'json');
    }
    //获取区县列表
     public function actionGetarea(){
        $pname=\yii::$app->request->get("code"); 
        $sql="select distinct county from wp_ischool_school where city='".$pname."' ORDER BY convert(county USING gbk)";
        $res=WpIschoolSchool::findBySql($sql)->asArray()->all();            
        $this->ajaxReturn($res,'json');       
    }
    //获取学校类型
    public function actionGettype(){
        $pname=\yii::$app->request->get("code"); 
        $sql="select distinct schtype from wp_ischool_school where county='".$pname."' order by schtype desc";
        $res=WpIschoolSchool::findBySql($sql)->asArray()->all();            
        $this->ajaxReturn($res,'json');    
    }
    
    public function actionGetschoo(){
        $cname=\yii::$app->request->get("code"); 
        $area=\yii::$app->request->get("area"); 
        $sql="select id,name from wp_ischool_school where schtype='".$cname."' and county='".$area."' ORDER BY convert(name USING gbk)";
        $res=WpIschoolSchool::findBySql($sql)->asArray()->all();            
        $this->ajaxReturn($res,'json');        
    }
    public function actionGetallclass(){
        $sid =\yii::$app->request->post("school"); 
        $flag=\yii::$app->request->post("flag");
        if($flag == 1){          
            $res= WpIschoolClass::find()->select('name,id')->where(['sid'=>$sid])->andwhere(['<>','level',0])->orderBy("level,class")->asArray()->all(); 
          
        }else{
             $res= WpIschoolClass::find()->select('name,id')->where(['sid'=>$sid])->orderBy("level,class")->asArray()->all(); 
        }
       
        $this->ajaxReturn($res,'json');   
     }
     public function actionDoaddchild(){
        $openid =\yii::$app->request->post("openid");
        $school =\yii::$app->request->post("school");
        $sid =\yii::$app->request->post("sid");
        $classname =\yii::$app->request->post("classname");
        $cid =\yii::$app->request->post("cid");
        $student =\yii::$app->request->post("student");
        $ry  = WpIschoolUser::find()->select('name,tel')->where(['openid'=>$openid])->asArray()->all(); 
        $paname = $ry[0]["name"];
        $tel = $ry[0]["tel"];
        $at = "";
        $res = $this->isHasStudent($student,$school,$classname);  //检查班级有无此人
      
        if($res) {
            $stuid = $res[0]['id'];
            $ro = $this->checkname($tel,$stuid);
            if($ro)
            {
                $where  = "";
                $where["tel"]   = $tel;
                $where["stu_id"] = $stuid;
                $p=WpIschoolPastudent::find()->where($where)->one();
                $p->openid = $openid;
                $p->name = $paname;                
                $p->save(false);
                $at = 5;
            }
            else
            {
                $has=$this->isCheckedStudent($openid,$stuid);   //检测当前家长是否已绑定该学生
                if($has){
                    $at = 3;
                }else{
                    $mm= new  WpIschoolPastudent;
                    $mm->stu_id = $stuid;
                    $mm->stu_name = $student;
                    $mm->class  = $classname;
                    $mm->cid    = $cid;
                    $mm->school = $school;
                    $mm->openid = $openid;
                    $mm->name = $paname;
                    $mm->tel  = $tel;
                    $mm->ctime = time();
                    $mm->ispass = "y";
                    $mm->sid  = $sid;
                    $idp = $mm->save(false);
                    if($idp > 0 || $idp === 0)
                    {
                        $at = "success";
                        $where = "";
                        $where["openid"] = $openid;
                        $m=WpIschoolUser::find()->where($where)->one();                                         
                        $m->last_sid = $sid;
                        $m->save(false);
                        $at = 5;
                    }
                    else
                    {
                        $at=2;
                    }
                }
            }

        }
        else
        {
            $at=1;
        }

        $this->ajaxReturn($at,'json');
    }
    //保存上传图片的班级信息
    public function actionSaveclassinfo(){
        $cid =\yii::$app->request->get("cid");      
        $sid =\yii::$app->request->get("sid");  
        $openid=\yii::$app->view->params['openid'];
        $level=WpIschoolUser::find()->select('level')->where(['openid'=>$openid])->asArray()->one();
//        session_start();
//        $_SESSION['sid']=$sid;
//        $_SESSION['cid']=$cid;
        $return_arr['level']=$level;
        $return_arr['sid']=$sid;
        $return_arr['cid']=$cid;
        return $this->render('saveclassinfo',$return_arr); 
    }
     //增加轮播图片
    public function actionDoaddimages(){
        $sid=\yii::$app->request->get("sid"); 
        $cid=\yii::$app->request->get("cid"); 
        $picurl=\yii::$app->request->get("picurl");      
        $m= new  WpIschoolClassImages;         
        $m->sid = $sid;
        $m->cid=$cid;
        $m->picurl = $picurl;
        $isdo = $m->save(false);
        if($isdo>0 || $isdo===0){
        $this->ajaxReturn('success','json');
        }else{
        $this->ajaxReturn('fail','json');
        }
    }
   
    //学生成绩查询
    public function actionQuerychildcj(){
        $cid =\yii::$app->request->get("cid");
        $id =\yii::$app->request->get("id");
        $name =\yii::$app->request->get("name");
        $con['cid'] = $cid;
        $cjds =WpIschoolClassChengjidan::find()->select('cjdid,cjdname,isopen')->where($con)->orderBy('id asc')->asArray()->all(); 
        $stus=WpIschoolStudent::find()->select('id,name')->where($con)->asArray()->all(); 
        $return_arr['cid']=$cid;
        $return_arr['cjds']=$cjds;
        $return_arr['stu_id']=$id;
        $return_arr['stu_name']=$name;
        $return_arr['cid']=$cid;    
        return $this->render('querychildcj',$return_arr);   
    }
     //学生成绩查询操作
    public function actionDoquerychengji(){
        $stuid =\yii::$app->request->get("stuid");
        $cjdid=\yii::$app->request->get("examid");
        $cid=\yii::$app->request->get("cid");
        $con['cid'] = $cid;
        $con['cjdid'] = $cjdid;
        if($stuid != "all"){   //查个人
            $con['stuid'] = $stuid;
        }
        $cjd = WpIschoolChengji::find()->select('stuid,stuname,kmname,score')->where($con)->orderBy('stuid asc,kmid asc')->asArray()->all(); 
        $cjd = $this->scriptCjd($cjd);
        $title = $this->scriptTitle($cjdid);
        $result[] = $title;
        $result[] = $cjd;
        $this->ajaxReturn($result,'json');

    }
    //拼凑成绩单标题
    private function scriptTitle($cjdid){
        $sql="select distinct kmname from wp_ischool_chengji where cjdid=".$cjdid." order by kmid asc";
        $title = WpIschoolChengji::findBySql($sql)->asArray()->all();       
        $newTitle = array();
        $newTitle[] = "姓名";
        foreach($title as $v){
            $newTitle[] = $v['kmname'];
        }
        return $newTitle;
    }
    //拼凑成绩
    private function scriptCjd($cjd_arr){
        $cjd = array();
        $cj = array();
        $leng = count($cjd_arr);
        for($i = 0;$i < $leng;$i++){
            if($i != 0){
                if($cjd_arr[$i]['stuid']==$cjd_arr[$i-1]['stuid']){
                    $cj[] = $cjd_arr[$i]['score'];
                }else{
                    $cjd[] = $cj;
                    $cj = array();
                    $cj[] = $cjd_arr[$i]['stuname'];
                    $cj[] = $cjd_arr[$i]['score'];
                }
            }else{
                $cj[] = $cjd_arr[$i]['stuname'];
                $cj[] = $cjd_arr[$i]['score'];
            }

            if($i == $leng-1){
                $cjd[] = $cj;
            }
        }
        return $cjd;
    }
     /*  检测某班级是否存在某学生 */
     public function isHasStudent($sname,$school,$class){
        $where["name"]=$sname;
        $where["school"]=$school;
        $where['class']=$class;
       
        $res= WpIschoolStudent::find()->select('id')->where($where)->asArray()->all(); 
        
        return $res;
     }
     
    public function checkname($tel,$stuid){
        $where["tel"] = $tel;
        $where["stu_id"] = $stuid;
        $req = WpIschoolPastudent::find()->where($where)->asArray()->all(); 
        $openid=$req[0]["openid"];
        if(!empty($req))
        {
            if(empty($openid))
            {
                $res=true;
            }
            else
            {
                $res=false;
            }
        }
        else
        {
            $res=false;
        }
        return $res;
    }
    
    /*  检查当前家长是否已经绑定该学生，已绑定不可重复 */
    public function isCheckedStudent($openid,$stuid){
        $con['openid']=$openid;
        $con['stu_id']=$stuid;
        $res = WpIschoolPastudent::find()->select('id')->where($con)->asArray()->all(); 
        if($res){
            return true;
        }else{
            return false;
        }
    }
     public function actionOnechild(){
        $return_arr['stu_id']=\yii::$app->request->get("stuid");
        $return_arr['stu_name']=\yii::$app->request->get("stuname");
        $return_arr['openid']=\yii::$app->request->get("openid");
        $stuid =\yii::$app->request->get("stuid");
        $openid=\yii::$app->request->get("openid");
        $res=WpIschoolPastudent::find()->where(['stu_id'=>$stuid])->asArray()->all(); 
        $return_arr['cid']= $res[0]['cid'];     
        $school=$res[0]["school"];
        $re=WpIschoolSchool::find()->where(['name'=>$school])->asArray()->all();       
        $sid=$re[0]["id"];
        $r=WpIschoolUser::find()->where(['openid'=>$openid])->asArray()->all();          
        $sd=$r[0]["last_sid"];
        $bool="";
        if($sd==$sid)
        {
            $bool=1;
        }
        $return_arr['sid']=$sid;
        $return_arr['bool']=$bool;
        return $this->render('onechild',$return_arr);
     }
     //孩子班级
     public function actionChildclass(){
        $openid=\yii::$app->request->get("openid");
        $stuid=\yii::$app->request->get("stuid");
        $res=WpIschoolStudent::find()->where(['id'=>$stuid])->asArray()->all(); 
        $stuname=$res[0]["name"];
        $return_arr['class']=$res[0]["class"];
        $return_arr['openid']=$openid;
        $return_arr['school']=$res[0]["school"];
        $whe["cid"]=$res[0]["cid"];
        $whe["role"]="班主任";
        $whe["ispass"]="y";
        $rep=WpIschoolTeaclass::find()->where($whe)->asArray()->all(); 
        $name="";
        foreach ($rep as $v) {
            $name.="/".$v["tname"];
        }
        $name=ltrim($name,"/");
        $tel="";
        $where="";
        foreach ($rep as $v) {
            $where["sid"]=$v["sid"];
            $where["openid"]=$v["openid"];
            $reo=WpIschoolTeacher::find()->where($where)->asArray()->all();
            $tel.="/".$reo[0]["tel"];
        }
        $tel=ltrim($tel,"/");
        $return_arr['tel']=$tel;
        $return_arr['name']=$name;
        $return_arr['stuid']=$stuid;
        $return_arr['stuname']=$stuname;
        return $this->render('childclass',$return_arr);
     }
     //亲情号
     public function actionQinqinghao(){
        $openid=\yii::$app->request->get("openid");  
        $stu_id=\yii::$app->request->get("id");
        $sql="select * from wp_ischool_pastudent where isqqtel=1 and stu_id=$stu_id and is_deleted= 0 and tel IS NOT NULL";
        $arr = WpIschoolPastudent::findBySql($sql)->asArray()->all();
        $return_arr['stuid']=$stu_id;
        $return_arr['openid']=$openid;
        $return_arr['sid']=$arr[0]['sid'];
        $return_arr['id']=$arr[0]['id'];
        $return_arr['arr']=$arr;
        $return_arr['time']=time();  
       
        return $this->render('qinqinghao',$return_arr); 
     }
     //添加亲情号
    public function actionAddqqh(){
        $openid=\yii::$app->request->get("openid"); 
        $stu_id=\yii::$app->request->get("stuid");                
        $arr2 = WpIschoolPastudent::find()->where(['stu_id'=>$stu_id,'openid'=>$openid])->asArray()->all(); 
        $d=new WpIschoolPastudent;
        $d->Relation = \yii::$app->request->get("Relation"); 
        $d->name = \yii::$app->request->get("name"); 
        $d->tel = \yii::$app->request->get("tel"); 
        $d->sid = \yii::$app->request->get("sid"); 
        $d->stu_id = \yii::$app->request->get("stuid");       
        $d->school = $arr2[0]['school'];
        $d->stu_name = $arr2[0]['stu_name'];
        $d->cid = $arr2[0]['cid'];
        $d->class = $arr2[0]['class'];
        $d->ctime = time();
        $d->ispass = 'y';
        $d->isqqtel = 1;
        $leave = $d->save(false);
        if($leave === 0 || $leave > 0){
            $result = 0;
        }else{
            $result = 1;
        }
        $this->ajaxReturn($result,'json');  
      }
      
    public function actionDelqqh(){
       $id=\yii::$app->request->get("id"); 
       $f = WpIschoolPastudent::find()->where(['id'=>$id])->asArray()->all();   
       $openid = $f[0]['openid'];
       if(!empty($openid)){
            $this->ajaxReturn(['status'=>0],'json');
       }else{
            $m= WpIschoolPastudent::find()->where(['id'=>$id])->one();
            $res =$m->delete();
            if($res === 0 || $res > 0){
                $this->ajaxReturn(['status'=>1],'json');
            }else{
                $this->ajaxReturn(['status'=>2],'json');
            }
       }
    }
    public function actionUpdateqqh(){
       $openid=\yii::$app->request->get("openid"); 
       $id =\yii::$app->request->get("id");
       $d=WpIschoolPastudent::findOne($id);
       $d->name=\yii::$app->request->get("name"); 
       $d->tel=\yii::$app->request->get("tel"); 
       $d->sid=\yii::$app->request->get("sid");       
       $d->Relation=\yii::$app->request->get("Relation");             
       $leave = $d->save(false);
       if($leave === 0 || $leave > 0){
           $result = 0;
       }else{
           $result = 1;
       }
           $this->ajaxReturn($result,'json');   
    }
     public function actionQingjia(){
       $stuid =\yii::$app->request->get("id");
       $openid =\yii::$app->request->get("openid");
       $stuname =\yii::$app->request->get("name");
       $return_arr['stuid']=$stuid;
       $return_arr['openid']=$openid;
       $return_arr['stuname']=$stuname;    
       return $this->render('qingjia',$return_arr);  
     }
     //学生请假信息处理
     public function actionAskchildleave(){
        $stuid =\yii::$app->request->get("stuid");
        $openid =\yii::$app->request->get("openid");
        $begin_time =\yii::$app->request->get("begintime");
        $end_time =\yii::$app->request->get("endtime");
        $leave_reason=\yii::$app->request->get("leave_reason");

        $end_time = $this->createLeaveStoptTime($stuid,$begin_time,$end_time);
        $d=new WpIschoolStuLeave;
        $d->stu_id = $stuid;
        $d->openid = $openid;
        $d->begin_time = $this->fromDateToStamp($begin_time);
        $d->stop_time  = $end_time;
        $d->ctime  = time();
        $d->flag   = 2;
        $d->reason = $leave_reason;
        $leave = $d->save(false);
        $cid = $this->getCid($stuid);       //获取学生所在班级id
        $topenid = $this->getBzr($cid);     //获取学生班级对应的班主任openid
        $helper = new Helper();
        $student = $helper->getStudent($stuid);
        $sname = $student[0]['name'];
        if($leave === 0 || $leave > 0){
            $result = 0;
            $this->doSendLeave($topenid,$sname);
        }else{
            $result = 1;
        }
        $this->ajaxReturn($result,"json");  
    }
     
     //执行请假信息发送
    private function doSendLeave($tos,$sname){
        $title = "学生请假通知信息";
        $msg = "的家长申请请假，请在【我的服务】->【我的资料】中进行审核";
        $final = "";
        foreach ($tos as $v) {
            $final = SendMsg::sendSHMsgToPa($v['openid'], $title, $sname . $msg);
        }
        return $final;
    }
      /**
     * @param $stu_id
     * @param $start_time
     * @param $stop_time
     * @return int
     * 创建请假结束时间
     */
    private function createLeaveStoptTime($stu_id,$start_time,$stop_time){
        $helper = new Helper();
        $school = $helper->getSchoolByStuid($stu_id);
        $con['sid']=$school[0]['id'];
        $con['type']=0;
        $leave_time_rule=WpIschoolLeaveRule::find()->where($con)->asArray()->all(); 
        $old_stop_time = $this->fromDateToStamp($stop_time);
        if(empty($leave_time_rule)){   //未指定规则
            return $old_stop_time;
        }else{
            //主要针对临颍一高的凌晨自动销假，即开始时间到该时间那天的凌晨
            $start_ymd = explode("-", $start_time);
            $start_ymd = $start_ymd[0]."-".$start_ymd[1]."-".$start_ymd[2];
            $stop_hm = $leave_time_rule[0]['stop_time'];
            $stop_hm = explode(":",$stop_hm);
            $new_stop_time = $start_ymd."-".$stop_hm[0]."-".$stop_hm[1];
            $new_stop_time = $this->fromDateToStamp($new_stop_time);
            return $new_stop_time < $old_stop_time ? $new_stop_time:$old_stop_time;
        }
    }
     //根据学生id获取班级cid
    function getCid($stuid){
        $info=WpIschoolStudent::find()->where(['id'=>$stuid])->asArray()->all();
        $cid=$info[0]["cid"];
        return $cid;
    }   
     //获取班主任信息$openid
    function getBzr($cid){
        //根据班级cid查询出班主任的信息
        $where["cid"]=$cid;
        $where["role"]="班主任";
        $where["ispass"]="y";
        $info= WpIschoolTeaclass::find()->where($where)->andwhere(['<>','openid',""])->groupBy('openid')->asArray()->all();
        return $info;
    }
      /*
         * 将日期转换为时间戳
         * $data形如"2015-9-10-10-30"
         */
    public function fromDateToStamp($date){      
        $date = explode("-",$date);
        $y=$date[0];
        $m=$date[1];
        $d=$date[2];
        $hour=$date[3];
        $minute=$date[4];
        $second = 0;
        return mktime($hour, $minute, $second, $m, $d ,$y);
    }
    //取消关注的学生信息删除
    public function actionDeleonechild(){
        $openid=\yii::$app->request->post("openid");
        $stu_id=\yii::$app->request->post("stuid");
        $pastudent=WpIschoolPastudent::find()->where(['openid'=>$openid,'stu_id'=>$stu_id])->one(); 
        $res=$pastudent->delete();
        if($res===0||$res>0){
            $this->ajaxReturn('success','json');
        }else{
            $this->ajaxReturn('fail','json');
        }
    }
    public function actionP_changeschool(){
        $stu_id=\yii::$app->request->post("stuid");
        $openid=\yii::$app->request->post("openid");
        $arr=WpIschoolStudent::find()->select('school')->where(['id'=>$stu_id])->asArray()->all();            
        $data=  WpIschoolSchool::find()->select('id')->where(['name'=>$arr[0]['school']])->asArray()->all(); 
        $user= WpIschoolUser::find()->where(['openid'=>$openid])->one(); 
        $user->last_sid=$data[0]['id'];
        $user->save(false);      
        $sid=$data[0]['id'];
        if($user==1){
            $at["sid"]=$sid;
            $at["info"]="success";
        }else{
            $at["info"]="false";
        }
        $this->ajaxReturn($at,"json");
    }
    
    public function actionMyallclass(){
        $openid=\yii::$app->request->get("openid");
        $sid=\yii::$app->request->get("sid");
//      $sql="SELECT t.* from wp_ischool_teaclass t LEFT JOIN wp_ischool_school t2 on t.sid=t2.id where t.openid='".$openid."' and t2.schtype!='教育局' and t.sid = '".$sid."' GROUP BY t.class";
//      $sql="SELECT t.* from wp_ischool_teaclass where openid='".$openid."' and sid = '".$sid."' and class!='".管理."'" ;
        $where="";
        $where['openid']=$openid;
        $where['sid']=$sid;        
        $res = WpIschoolTeaclass::find()->where($where)->andwhere(['<>','class','管理'])->asArray()->all();
        if(empty($res))
        {
            //没有绑定教师信息
            $res=1;
        }
        else
        {
            foreach ($res as &$v) {
                if($v["class"]=="管理")
                {
                    $v["sta"]=1;
                }
            }
        }
        $return_arr['list_class']=$res;
        $return_arr['sid']=$sid;
        
        return $this->render('myallclass',$return_arr);
    }
    public function actionAddoneclass(){
        $openid=\yii::$app->request->get("openid");
        $sid=\yii::$app->request->get("sid");
        $return_arr['sid']=$sid;
        $ro=WpIschoolUserschool::find()->where(['openid'=>$openid])->asArray()->all(); 
        $ry=  WpIschoolSchool::find()->select('id,name')->where(['id'=>$ro[0]["schoolid"]])->asArray()->all();
        $return_arr['schname']=$ry[0]["name"];
        $return_arr['schid']=$ry[0]['id'];
        $res= WpIschoolSubject::find()->asArray()->all();
        $r= WpIschoolManage::find()->asArray()->all();
        $sql="select distinct pro from wp_ischool_school ORDER BY convert(pro USING gbk)";
        $ress = WpIschoolSchool::findBySql($sql)->asArray()->all();
        $return_arr['list_pro']=$ress;
        $return_arr['da']=$r;
        $return_arr['openid']=$openid;
        $return_arr['data']=$res;
        return $this->render('addoneclass',$return_arr);
    }
    
     public function actionDoaddclass(){
        $openid=\yii::$app->request->post("openid");
        $role=\yii::$app->request->post("role");
        $cid=\yii::$app->request->post("cid");
        $classname=\yii::$app->request->post("classname");
        $sid=\yii::$app->request->post("sid");
     
        $school=\yii::$app->request->post("school");   //学校名称
        $rolel=\yii::$app->request->post("rolel");//管理角色
        $tea=\yii::$app->request->post("tea");//身份
        $res=WpIschoolUser::find()->where(['openid'=>$openid])->asArray()->all();    
         
        $tel=$res[0]["tel"];
        $tname=$res[0]["name"];
        //判断事先是否已将教师导入，若已导入自动通过，否则发送审核信息
        //以学校和手机号为条件查询是否已先前导入       
        $where["tel"] = $tel;
        $where["sid"] = $sid;
        $teaInfo = WpIschoolTeacher::find()->select('id,openid,ispass')->where($where)->asArray()->all(); 
    
        if(!empty($teaInfo)){
            //更新openid
            if(empty($teaInfo[0]['openid'])){
                $teaModel = WpIschoolTeacher::findOne($teaInfo[0]['id']);
                $teaModel->openid=$openid;
                $teaModel->save(false);
            }
        }
        $ru = WpIschoolTeacher::find()->where(['openid'=>$openid,'sid'=>$sid])->asArray()->all();
          
        if(empty($ru))
        {
            $mm=new WpIschoolTeacher;           
            $mm->tname=$tname;
            $mm->sid=$sid;
            $mm->school=$school;
            $mm->openid=$openid;
            $mm->tel=$tel;
            $mm->ispass=0;
            $mm->ctime=time();
           $a= $mm->save(false);
        }
        $isSendSHMsg = false;
        $newRole = "";
        if($tea==1)
        {//教师身份
            $where="";
            $where["openid"]=$openid;
            $where["cid"]=$cid;
            $where["role"]=$role;
            $tu = WpIschoolTeaclass::find()->where($where)->asArray()->all();
            if(empty($tu))
            {   
                $m=new WpIschoolTeaclass;
                $m->tname=$tname;
                $m->openid=$openid;
                $m->school=$school;
                $m->cid=$cid;
                $m->role=$role;
                $m->class=$classname;
                $m->sid=$sid;
                $m->ctime=time();
                if(!empty($teaInfo) && $teaInfo[0]['ispass'] == "y"){
                    $m->ispass = 'y';
                    $m->save(false);
                }else{
                    $m->save(false);
                    $isSendSHMsg = true;
                    $newRole = $school.$classname.$role;

                }
                $at="success";

            }
            else
            {//角色名重复
                $at=2;
            }
        }else {//管理身份
            $where="";
            $where["openid"]=$openid;
            $where["sid"]=$sid;
            $where["role"]=$rolel;
            $tu = WpIschoolTeaclass::find()->where($where)->asArray()->all();
            if(empty($tu))
            {    
                $m=new WpIschoolTeaclass;
                $m->tname=$tname;
                $m->openid=$openid;
                $m->school=$school;
                $m->role=$rolel;
                $m->class="管理";
                $m->sid=$sid;
                $m->ctime=time();
                if(!empty($teaInfo) && $teaInfo[0]['ispass'] == "y"){
                    $m->ispass = 'y';
                    $m->save(false);
                }else {
                    $m->save(false);
                    $isSendSHMsg = true;
                    $newRole = $school.$rolel;
                }
                $at="success";

            }
            else
            {
                $at=2;
            }
        }
        if($isSendSHMsg){
            $title="教师待审核信息";
            $des=$tname."申请成为".$newRole."，请在学校管理页面进行审核。";
            $where="";
            $where["rid"]=1;
            $where["sid"]=$sid;
            $er = WpIschoolUserRole::find()->select('openid')->where($where)->asArray()->all();
            foreach ($er as $v) {
                SendMsg::sendSHMsgToPa($v["openid"],$title,$des);
            }
        }
        $m = WpIschoolUser::find()->where(['openid'=>$openid])->one();
        $m->last_sid=$sid;
        $m->save(false);
        $this->ajaxReturn($at,'json'); 
     }
     public function actionOneclass(){
        $cid=\yii::$app->request->get("cid"); 
        $tcid=\yii::$app->request->get("tcid"); 
        $openid=\yii::$app->request->get("openid"); 
        $type=\yii::$app->request->get("type"); 
        $sid=\yii::$app->request->get("sid"); 
        $res= WpIschoolTeaclass::find()->select('cid,class,role')->where(['cid'=>$cid])->asArray()->all();
        $return_arr['type']=$type;
        $return_arr['the_class']=$res;
        $return_arr['role']=0;
        foreach ($res as $re){
            if($re['role']=="班主任"){
                $return_arr['role']=1;
                break;
            }
        }
        $return_arr['tcid']=$tcid;
        $return_arr['sid']=$sid;
        $return_arr['cid']=$cid;
        $return_arr['openid']=$openid;
        $r= WpIschoolUser::find()->where(['openid'=>$openid])->asArray()->all();   
        $sd=$r[0]["last_sid"];
        $bool="";
        if($sd==$sid)
        {
            $bool=1;
        }
        $return_arr['bool']=$bool;         
        return $this->render('oneclass',$return_arr);
     }
     
     public function actionAllstudent(){
        $cid=\yii::$app->request->get("cid");  
        $sid=\yii::$app->request->get("sid"); 
        $tcid=\yii::$app->request->get("tcid");     
        $sql="SELECT @rownum:=@rownum+1 rownum, t.id,t.name From
			(SELECT @rownum:=0,wp_ischool_student.id,wp_ischool_student.name FROM wp_ischool_student where cid=".$cid.") t";
        $res =WpIschoolStudent::findBySql($sql)->asArray()->all();
        $ren=  WpIschoolTeaclass::find()->where(['id'=>$tcid])->asArray()->all();
        if($ren[0]["ispass"]!=="y")
        {
            $res=1;
        }
        if($ren[0]["role"]=="班主任"){
            $role=0;
        }else{
            $role=1;
        }
        if(empty($res))
        {
            $res=2;
        }
        $return_arr['tcid']=$tcid;
        $return_arr['list_stu']=$res;
        $return_arr['role']=$role;
        $return_arr['cid']=$cid;
        $return_arr['sid']=$sid;
        $return_arr['cname']=$ren[0]["class"];
//        var_dump($return_arr);die;
        return $this->render('allstudent',$return_arr);
     }
     
     public function actionAddstudent(){
        $cid=\yii::$app->request->get("cid");  
        $sid=\yii::$app->request->get("sid");
        $tcid=\yii::$app->request->get("tcid");
        $openid=\yii::$app->request->get("openid"); 
        $return_arr['sid']=$sid;
        $return_arr['cid']=$cid;
        $return_arr['openid']=$openid;
        $return_arr['tcid']=$tcid;  
        return $this->render('addstudent',$return_arr);
     }
     
     public function actionCheckstuno(){
        $stuno=\yii::$app->request->post("stuno");   
        $cid=\yii::$app->request->post("cid");  
        $res= WpIschoolStudent::find()->where(['stuno2'=>$stuno])->asArray()->all();
        if(empty($res))
        {
            $at="success";
        }
        else
        {
            $st="fail";
        }
        $this->ajaxReturn($at,'json'); 
     }
     public function actionDoaddstudent(){
        $openid=\yii::$app->request->post("openid");  
        $cid=\yii::$app->request->post("cid"); 
        $sid=\yii::$app->request->post("sid");
        $type=\yii::$app->request->post("type");
        $address=\yii::$app->request->post("address");
        $name=\yii::$app->request->post("name");
        $stuno=\yii::$app->request->post("stuno");
        $at="";      
        $m=new WpIschoolStudent;
        if($type=="add")
        {
            $rt=WpIschoolClass::find()->where(['id'=>$cid])->asArray()->all();          
            $schoolname=$rt[0]["school"];
            $classname=$rt[0]["name"];
            $m->sid=$sid;
            $m->school=$schoolname;
            $m->class=$classname;
            $m->cid=$cid;
            $m->address=$address;
            $m->name=$name;
            $m->stuno2=$stuno;  //学校自己的学号
            $m->stuno=time().$this->createRande(5); //系统生成的唯一标志
            $m->ctime=time();
            $m->save(false);
            $at="success";
        }
        else
        {         
            $at="success";
        }
        $this->ajaxReturn($at,'json');
     }
     
     function createRande($bits){

        $i=0;
        $rander = "";
        while($i < $bits){
            $rander .= rand(0,9);
            $i++;
        }

        return $rander;
    }
    
    public function actionStudes(){
        $openid=\yii::$app->request->get("openid");
        $stuid=\yii::$app->request->get("stuid"); 
        $cid=\yii::$app->request->get("cid"); 
        $sid=\yii::$app->request->get("sid"); 
        $tcid=\yii::$app->request->get("tcid"); 
        $return_arr['sid']=$sid;
        $return_arr['cid']=$cid;
        $return_arr['tcid']=$tcid;
        $return_arr['stuid']=$stuid;
        $return_arr['openid']=$openid;
        $res=WpIschoolStudent::find()->where(['id'=>$stuid])->asArray()->all(); 
        $return_arr['name']=$res[0]["name"];
        $return_arr['address']=$res[0]["address"];
        $return_arr['tel']=$res[0]["tel"];
        $return_arr['stuno']=$res[0]["stuno2"];
        return $this->render('studes',$return_arr);
    }
    public function actionLxr(){
        $tcid=\yii::$app->request->get("tcid");
        $stuid=\yii::$app->request->get("stuid");
        $cid=\yii::$app->request->get("cid");
        $sid=\yii::$app->request->get("sid");
        $openid=\yii::$app->request->get("openid");
        $res=WpIschoolPastudent::find()->where(['stu_id'=>$stuid])->asArray()->all();
//        var_dump($stuid);die;
        foreach ($res as &$v) {
            if(empty($v["openid"]))
            {
                $v["type"]=0;
            }
            else
            {
                $v["type"]=1;
            }
        }
        $return_arr['res']=$res;
        $return_arr['cid']=$cid;
        $return_arr['sid']=$sid;
        $return_arr['tcid']=$tcid;
        $return_arr['stuid']=$stuid;
        $return_arr['openid']=$openid;   
        return $this->render('lxr',$return_arr);
    }

    public function actionAddlxr(){
        $return_arr['tcid']   =\yii::$app->request->get("tcid");     
        $return_arr['stuid']  =\yii::$app->request->get("stuid");  
        $return_arr['cid']    =\yii::$app->request->get("cid");
        $return_arr['sid']    =\yii::$app->request->get("sid");
        $return_arr['openid'] =\yii::$app->request->get("openid");
        
        return $this->render('addlxr',$return_arr); 
    }
     public function actionDoaddlxr(){
        $tel=\yii::$app->request->post("tel");
        $username=\yii::$app->request->post("username");
        $email=\yii::$app->request->post("email");
        $stuid=\yii::$app->request->post("stuid");
//        var_dump($stuid);die;
        $res=WpIschoolStudent::find()->where(['id'=>$stuid])->asArray()->all();
        $where="";
        $where["stu_id"]=$stuid;
        $where["name"]=$username;
        $ren=WpIschoolPastudent::find()->where($where)->asArray()->all();
        $rt="";
        if(empty($ren))
        {
            $m=new WpIschoolPastudent;
            $m->email=$email;
            $m->stu_id=$stuid;
            $m->name=$username;
            $m->school=$res[0]["school"];
            $m->cid=$res[0]["cid"];
            $m->class=$res[0]["class"];
            $m->tel=$tel;
            $m->stu_name=$res[0]["name"];
            $m->ctime=time();
            $m->ispass="y";
	    $m->isqqtel = 1;
            $m->save(false);
            $rt=1;
        }
        else
        {
            $rt=0;
        }

        $this->ajaxReturn($rt,'json');
     } 
     
    
     public function actionDellxr(){
        $id=\yii::$app->request->post("id"); 
        
        $pastudent=WpIschoolPastudent::find()->where(['id'=>$id])->one();  
        $res=$pastudent->delete();
        $rt="";
        if(!empty($res))
        {
            $rt="success";
        }
        else
        {
            $rt="false";
        }
        $this->ajaxReturn($rt,"json");
     }
      public function actionOnestuleave(){
        $stuid=\yii::$app->request->get("id");
        $tcid=\yii::$app->request->get("tcid"); 
        $sid=\yii::$app->request->get("sid"); 
        $cid=\yii::$app->request->get("cid"); 
        $openid=\yii::$app->request->get("openid");              
        $helper = new Helper();
        $student = $helper->getStudent($stuid);
         $return_arr['student']=$student;
        $return_arr['cid']=$cid;
        $return_arr['sid']=$sid;
        $return_arr['tcid']=$tcid;
        $return_arr['openid']=$openid;         
         return $this->render('onestuleave',$return_arr); 
      }
      //提交请假
      public function actionDooneleave(){
        $stuid=\yii::$app->request->get("stuid");
        $openid=\yii::$app->request->get("openid"); 
        $begin_time=\yii::$app->request->get("begintime"); 
        $end_time=\yii::$app->request->get("endtime"); 
        $leave_reason=\yii::$app->request->get("leave_reason");  
        if($this->isLeaveNumValid($stuid)){ //验证规则
            $end_time = $this->createLeaveStoptTime($stuid,$begin_time,$end_time);//按规则生成结束时间
            $d=new WpIschoolStuLeave;
            $d->stu_id = $stuid;
            $d->openid = $d->okopenid = $openid;
            $d->begin_time = $this->fromDateToStamp($begin_time);
            $d->stop_time  = $end_time;
            $d->ctime = $d->oktime = time();
            $d->flag = 1;
            $d->reason = $leave_reason;
            $leave = $d->save(false);
            if($leave === 0 || $leave > 0){
                
                $this->sendtoSubcriber($stuid,$d->begin_time,$d->stop_time,$leave);
                $result = 0;
            }else{
                $result = 1;
            }
        }else{
            $result = 2;    //规定时间段内批假名额已满
        }

        $this->ajaxReturn($result,"json");  
      }
      
      /**
     *规定时间段内请假名额是否有效
     */
     private function isLeaveNumValid($stu_id){
        $now = time();
        $helper = new Helper();
        $school = $helper->getSchoolByStuid($stu_id);
        $con['sid']=$school[0]['id'];
        $con['type']=1;
        $leave_num_rule = WpIschoolLeaveRule::find()->where($con)->asArray()->all();
        if(empty($leave_num_rule)){  //无规则限制
            return true;
        }else{
            $leave_start_time = -1;
            $leave_num = 0;

            foreach ($leave_num_rule as $v) {
                $start_time = $v['start_time'];
                $stop_time = $v['stop_time'];
                $start_time = explode(":", $start_time);
                $stop_time = explode(":", $stop_time);
                $date = new Date();
                if($stop_time[0] < $start_time[0]){
                    //结束时间的小时小于开始时间的小时则认为是跨天，如21:30-5:30
                    $start_stamp_today = $date->stampOfToday($start_time[0],$start_time[1],"0");
                    $stop_stamp_today = $date->stampOfToday($stop_time[0],$stop_time[1],"0")+3600*24;//系统时间基数是今天，所以加一天
                    if($now>=$start_stamp_today && $now<=$stop_stamp_today){
                        $leave_start_time = $start_stamp_today;
                        $leave_num = $v['num'];
                        break;
                    }

                    $start_stamp_yesterday = $start_stamp_today - 3600*24;
                    $stop_stamp_yesterday = $stop_stamp_today - 3600*24;
                    if($now>=$start_stamp_yesterday && $now<=$stop_stamp_yesterday){
                        $leave_start_time = $start_stamp_yesterday;
                        $leave_num = $v['num'];
                        break;
                    }

                }else{
                    $start_stamp_today = $date->stampOfToday($start_time[0],$start_time[1],"0");
                    $stop_stamp_today = $date->stampOfToday($stop_time[0],$stop_time[1],"0");
                    if($now>=$start_stamp_today && $now<=$stop_stamp_today){
                        $leave_start_time = $start_stamp_today;
                        $leave_num = $v['num'];
                        break;
                    }
                }
            }

            if($leave_start_time==-1){//不在规定规则时间段内
                return true;
            }else{
                $cid = $helper->getClassByStuid($stu_id)[0]['id'];
                $count_sql = "select count(t.id) as counter from wp_ischool_stu_leave t ".
                    " left join wp_ischool_student t2 on t.stu_id=t2.id".
                    " where t2.cid=".$cid." and t.oktime>=".$leave_start_time." and t.flag=1";
               $leave_count =  WpIschoolStuLeave ::findBySql($count_sql)->asArray()->all();
                if($leave_count[0]['counter']>=$leave_num){  //名额已满
                    return false;
                }else{
                    return true;
                }
            }
         }
      }
      
      /**
     *@stuid 学生id
     *@$begin_time 开始时间的时间戳
     *@$stop_time 结束时间的时间戳
     */
     private function sendtoSubcriber($stuid,$begin_time,$stop_time,$id){
        $url = URL_IP."/pub";
        $sid_epc =WpIschoolStudent::find()->select('sid,cardid')->where(['id'=>$stuid])->asArray()->all();       
        $sid = $sid_epc[0]['sid'];
        $epc = $sid_epc[0]['cardid'];
        if(!empty($epc)){
            $data = $sid."  leave-".$epc."-".$begin_time."-".$stop_time."-".$id;
            $data = array('msg'=>$data);
            $this->broad($url,$data);
        }
        return 0;
     }
     
     /**
     * @param $url
     * @param $data
     * @return int
     * 做异步post请求后立即返回避免等待
     */
     public function broad($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);//1秒后立即执行
        curl_exec($ch);
        curl_close($ch);
     }
     public function actionLeavestu(){
        $cid=\yii::$app->request->get("cid");
        $sid=\yii::$app->request->get("sid"); 
        $tcid=\yii::$app->request->get("tcid"); 

        $lev_sql = "select t1.id,t2.name,t2.cardid,t1.begin_time,t1.stop_time from wp_ischool_stu_leave t1 left join wp_ischool_student t2 on t1.stu_id=t2.id where t2.cid=".$cid." and t1.flag=1";
        $res = WpIschoolStuLeave::findBySql($lev_sql)->asArray()->all();
        $ren = WpIschoolTeaclass::find()->where(['id'=>$tcid])->asArray()->all();   
        if($ren[0]["ispass"]!=="y")
        {
            $res=1;
        }
        if($ren[0]["role"]=="班主任"){
            $role=0;
        }else{
            $role=1;
        }
        if(empty($res))
        {
            $res=2;
        }
        $return_arr['tcid']=$tcid;
        $return_arr['list_stu']=$res;
        $return_arr['role']=$role;
        $return_arr['cid']=$cid;
        $return_arr['sid']=$sid;
        $return_arr['cname']=$ren[0]["class"];
        $return_arr['openid']=\yii::$app->request->get("openid");
        return $this->render('leavestu',$return_arr); 
     }
      public function actionQingjiainfo(){
        $cid=\yii::$app->request->get("cid");
        $sid=\yii::$app->request->get("sid"); 
        $back=\yii::$app->request->get("back");  
        $tcid=\yii::$app->request->get("tcid");   
        $id=\yii::$app->request->get("id"); 
        //学生name
        $name=\yii::$app->request->get("name");
        $openid=\yii::$app->request->get("openid");
        $res=WpIschoolStuLeave::find()->select('reason')->where(['id'=>$id])->asArray()->all();   
        $return_arr['content']=$res[0]["reason"];
        $return_arr['tcid']=$tcid;
        $return_arr['cid']=$cid;
        $return_arr['sid']=$sid;
        $return_arr['back']=$back;
        $return_arr['name']=$name;
        $return_arr['openid']=$openid;
        return $this->render('qingjiainfo',$return_arr); 
      }
      
     public function actionDeleteoneleave(){
         $lid=\yii::$app->request->post("id");
         $sid=\yii::$app->request->post("sid");    
         $lid = explode("-",$lid);
         $epc = $lid[1];
         $lid = $lid[0];
         $d=WpIschoolStuLeave::find()->where(['id'=>$lid])->one();
         $d->flag=0;
         $leave = $d->save(false);
         if($leave===0 || $leave>0){
            $result = 0;
            $this->toSubcriber($sid,$epc,$lid);
         }else{
            $result = 1;
         }
         $this->ajaxReturn($result,"json");  
     }
       
       //将即时信息发给pc端的发布者，并有其转发出去，下位机订阅接收
     private function toSubcriber($sid,$epc,$id){
            $url = URL_IP."/pub";
            $data = $sid."  cancelLeave-".$epc."-".$id;
            $data = array('msg'=>$data);
            $this->broad($url,$data);
            return 0;
     }
       
     public function actionShenqing(){
            $cid=\yii::$app->request->get("cid");
            $sid=\yii::$app->request->get("sid"); 
            $tcid=\yii::$app->request->get("tcid");                               
            $sql="select t.id,t.begin_time,t.stop_time,t2.name from wp_ischool_stu_leave t".
                " left join wp_ischool_student t2 on t.stu_id = t2.id ".
                " where t2.cid=".$cid." and t.flag=2";
            $res=WpIschoolStuLeave::findBySql($sql)->asArray()->all();         
            $ren=WpIschoolTeaclass::find()->where(['id'=>$tcid])->asArray()->all();            
            if($ren[0]["ispass"]!="y")
            {
                $res=1;
            }
            if($ren[0]["role"]=="班主任"){
                $role=0;
            }else{
                $role=1;
            }
            if(empty($res))
            {
                $res=2;
            }
            $return_arr['tcid']=$tcid;
            $return_arr['list_stu']=$res;
            $return_arr['role']=$role;
            $return_arr['cid']=$cid;
            $return_arr['sid']=$sid;
            $return_arr['cname']=$ren[0]["class"];
            $return_arr['openid']=\yii::$app->request->get("openid");
            return $this->render('shenqing',$return_arr); 
     }
     //请假申请回复
     public function actionResponsestuleave(){
        $flag=\yii::$app->request->get("flag");
        $lid=\yii::$app->request->get("lid"); 
        $openid=\yii::$app->request->get("openid"); 
        $stu_leave=WpIschoolStuLeave::find()->where(['id'=>$lid])->asArray()->all(); 
        $stuid = $stu_leave[0]['stu_id'];
        $popenid = $this->getParents($stuid);
        if($flag == 0){   //批准
            if($this->isLeaveNumValid($stuid)){
                $d= WpIschoolStuLeave::find()->where(['id'=>$lid])->one();
                $d->flag     = 1;
                $d->oktime   = time();
                $d->okopenid = $openid;
                $res=$d->save(false);
                if($res === 0 || $res > 0){
                    $this->sendtoSubcriber($stuid,$stu_leave[0]['begin_time'],$stu_leave[0]['stop_time'],$lid);
                    $result = 0;
                    $msg = "您发送的请假已经通过审批";
                    $this->doSendLeavep($popenid,$msg);
                }else{
                    $result = 1;
                }
            }else{
                $result = 2;
            }
        }else{   //拒绝
            $d= WpIschoolStuLeave::find()->where(['id'=>$lid])->one();
            $d->flag     = 0;
            $d->oktime   = time();
            $d->okopenid = $openid;
            $res = $d->save(false);
            if($res === 0 || $res > 0){
                $result = 0;
                $msg = "您发送的请假请求被拒绝。若有问题请重新申请或联系班主任";
                $this->doSendLeavep($popenid,$msg);
            }else{
                $result = 1;
            }
        }

        $this->ajaxReturn($result,"json"); 
    }
    //根据学生ID获取家长的信息
    function getParents($stuid){
        $where["stu_id"]=$stuid;
        $where["ispass"]="y";
        $where["is_deleted"]="y";
        $info= WpIschoolPastudent::find()->where($where)->andwhere(['<>','openid',''])->groupBy('openid')->asArray()->all();                          
        return $info;
    } 
    //教师批准或拒绝请假推送信息
    public function doSendLeavep($info,$msg){
        $title = "学生请假通知信息";
        $final = "";
        foreach ($info as $v) {
            $final = SendMsg::sendSHMsgToPa($v["openid"],"请假信息",$msg);
        }
        return $final;
    }
    public function actionChengjiindex(){ 
        $return_arr['cid']=\yii::$app->request->get("cid");
        $return_arr['tcid']=\yii::$app->request->get("tcid"); 
        $return_arr['cname']=\yii::$app->request->get("cname");
        $return_arr['sid']=\yii::$app->request->get("sid");
        $return_arr['openid']=\yii::$app->request->get("openid");           
        return $this->render('chengjiindex',$return_arr); 
    }
    //学生成绩单上传发送
     public function actionSendchengji(){ 
        $nowyear = date("Y");
        $nowmonth = date("m");
        $xuenian = array();

        if($nowmonth > 7){
            $xuenian[] = array('year'=>$nowyear."-".($nowyear+1)."学年");
        }else{
            $xuenian[] = array('year'=>($nowyear-1)."-".$nowyear."学年");
        }
        
        $examtype= WpIschoolChengjidanType::find()->where(['type'=>'gz'])->asArray()->all();   
        $return_arr['xuenian']=$xuenian;
        $return_arr['examtype']=$examtype;
        $return_arr['cid']=\yii::$app->request->get("cid");
        $return_arr['tcid']=\yii::$app->request->get("tcid");
        $return_arr['cname']=\yii::$app->request->get("cname");
        $return_arr['sid']=\yii::$app->request->get("sid");
        $return_arr['openid']=\yii::$app->request->get("openid"); 
        return $this->render('sendchengji',$return_arr); 
     }
     private function initExcel() {
        if (\Yii::$app->request->isPost) {
           
            //$model = new ImportData();
            $model = new ImportData();
           
            $model->upload =UploadedFile::getInstance($model, 'upload');
//            var_dump( $model->upload);die; 
            
            if ($model->validate()) {                                   
                 $data = \moonland\phpexcel\Excel::widget([
                    'mode' => 'import',
                    'fileName' => $model->upload->tempName,
                    'setFirstRecordAsKeys' => false,
                    'setIndexSheetByName' => false,
                ]);
                $data = isset($data[0])?$data[0]:$data;
          
                if(count($data) > 1)
                {
                    array_shift($data);
                    $this->source_data = $data;
                    
                }else{
                    echo "<script>alert('文件只能为.xls格式!')</script>";die;             
//                    return $this->assignPage("文件格式错误");
                }
                
            }
        }
    }
    private function assignPage($errorinfo){
        return $this->render("page",[
            "errorinfo"=>$errorinfo
        ]);
    }
      public function actionUploadchengjidan(){ 
        $this->initExcel();
        $cid=\yii::$app->request->post("cid");
        $sid=\yii::$app->request->post("sid");
        $examname=\yii::$app->request->post("exam");
        $isopen=\yii::$app->request->post("isopen");
        $openid=\yii::$app->request->post("openid");
        $isopen = empty($isopen)?'n':$isopen;
        $con['name'] = $examname;
        $con['sid'] = $sid;
         //建立成绩单档案
        $cjdid = WpIschoolChengjidan::find()->select('id')->where($con)->asArray()->all();           
        if(empty($cjdid)){
            $d=new WpIschoolChengjidan;           
            $d->ctime= time();
            $d->save(false);
            $cjdid = $d->attributes['id'];
            
        }else{
            $cjdid = $cjdid[0]['id'];
        }    
      
        $isposted =  WpIschoolClassChengjidan::find()->where(['cid'=>$cid,'cjdid'=>$cjdid])->asArray()->all(); 
     
        if(empty($isposted)){
             $excel_cont = $this->checkChengjiExcel($cid,$this->source_data);
             if($excel_cont['retcode']==0){  
                 $w=new WpIschoolClassChengjidan;
                //保存
                $w->cid = $cid;
                $w->cjdid = $cjdid;
                $w->isopen = $isopen;
                $w->cjdname = $examname;
                $w->creater = $openid;
                $w->ctime  = time();
                $res=$w->save(false);
                $data = array('data'=>$excel_cont['retdata'],'cid'=>$cid,'cjdid'=>$cjdid,'examname'=>$examname,'openid'=>$openid);
                $excel_cont = $this->sendRecordToParent1($data);
                 $result = array("retcode"=>0,"retmsg"=>"发送成功");             
                }else{
                   //有错误
                   $result = array("retcode"=>-1,"retmsg"=>"发送失败，错误信息为".$excel_cont['retdata']);
                }           
        } else{
            //不能重复上传
            $result = array("retcode"=>-1,"retmsg"=>"该班级已有名为".$examname."的成绩单，不能重复上传");
        }

        echo "<script>parent.uploadCJDCallbak(".$result['retcode'].",'".$result['retmsg']."')</script>";      
      }
    public function sendRecordToParent1($_info){
        $data = $_info['data'];
        $cid = $_info['cid'];
        $cjdid = $_info['cjdid'];
        $examName = $_info['examname'];
        $openid = $_info['openid'];
        $length = count($data);
        if($length > 1){
            $ctime = time();
            $subject = $data[0];
            $cols = count($subject);
            $nameIndex = array_keys($subject,"姓名",false)[0];
            $stuNumIndex = array_keys($subject,"学号",false)[0];
            $sender = $this->getOneTeaOfClass($cid,$openid)[0]['tname'];      
            for($i=1;$i<$length;$i++){
                $record = $data[$i];
                $stuname = explode("-",$record[$nameIndex]);
                $stuid = $stuname[1];
                $stuname = $stuname[0];
                $content = "家长您好,".$stuname."同学".$examName."成绩如下:\n\n";
                $sql = "insert into wp_ischool_chengji(stuid,stuname,cid,cjdid,kmid,kmname,score,ctime) values ";
                for($j = 0; $j < $cols; $j++){
                    if($j != $nameIndex && $j != $stuNumIndex){  //科目列
                        $kemu = explode("-",$subject[$j]);
                        $kmid = $kemu[1];
                        $kemu = $kemu[0];
                        $content .= $kemu.":".$record[$j]."\n\n";
                        $sql .= "(".$stuid.",'".$stuname."',".$cid.",".$cjdid.",".$kmid.",'".$kemu."',".$record[$j].",".$ctime."),";
                    }
                }
                $content .= "来自".$sender."老师\n";
                //保存
                $sql = substr($sql,0,-1);
                echo $sql.";;;";
                $c = \yii::$app->db->createCommand($sql)->execute();        
                //发送
                $sid = $this->getSchoolsid($stuid);
                $picurl = $this->getSchoolPic($sid);
                $paropenids = $this->getParOpenid($stuid);
                $this->doSendRecord($paropenids,$content,$picurl);
            }
        }
        return 0;
    }
    private function getOneTeaOfClass($cid,$openid){
        $con['cid'] = $cid;
        $con['openid'] = $openid;
        $m= WpIschoolTeaclass::find()->select('tname')->where($con)->asArray()->all(); 
        return $m;
    }
    //根据学生id获取学校sid
    function getSchoolsid($stuid){
        $sid = WpIschoolStudent::find()->select('sid')->where(['id'=>$stuid])->asArray()->all(); 
        return $sid;
    }

    //获取学校图片信息
    private function getSchoolPic($sid){
         $toppic = WpIschoolPicschool::find()->select('toppic')->where(['schoolid'=>$sid])->asArray()->all(); 
        if($toppic){
            return $toppic[0]['toppic'];
        }else{
            return URL_PATH."/upload/syspic/msg.jpg";
        }
    }
    //根据学生id获取家长openid
    private function getParOpenid($stuid){
        $res = WpIschoolPastudent::find()->select('openid')->where(['stu_id'=>$stuid])->asArray()->all();   
        $leng=count($res);
        $parOpendis=array();
        for ($i=0; $i <$leng; $i++) {
            if(!empty($res[$i]['openid']))
            {
                $parOpendis[$i]=$res[$i]['openid'];
            }

        }
        return $parOpendis;
    }
    //执行成绩单发送
    private function doSendRecord($tos,$content,$picurl){
        $title = "学生成绩通知信息";
        $final = "";
        $url="";
        foreach($tos as $to) {
            var_dump($to);
            $final = SendMsg::sendSHMsgToPa($to, $title, $content,$url,$picurl);
        }
        return $final;
    }
      //验证成绩excel的数据有效性
     private function checkChengjiExcel($cid,$file_name){
        $begin_row_num = 2;

        $data_arrs=$file_name;
        //去除首行列标题字符串中的空格
        $data_arr =[];
        foreach($data_arrs as $value){
            $data_arr[] = array_values($value);
        }

        foreach($data_arr[0] as $k=>$v){
            $data_arr[0][$k] = str_replace(' ', '', $v);
        }

        for($i=0;$i<count($data_arr);$i++){
            $data_arr[$i] = array_filter($data_arr[$i],create_function('$v','return isset($v);'));
        }
        $data_arr[0] = array_filter($data_arr[0]);
        foreach($data_arr as $k=>$v){
            if(!$v){//判断是否为空（false）
                unset($data_arr[$k]);//删除
            }
        }
        $first_row = $data_arr[0];
        //检查是否有姓名列
        if(!in_array("姓名",$first_row)){
            return array("retcode"=>-1,"retdata"=>"请指定姓名列");
        }
        //检查列名是否重复
        foreach($first_row as $k=>$v){
            foreach($first_row as $kt=>$vt){
                if($v==$vt && $k!=$kt){
                    return array("retcode"=>-1,"retdata"=>"名为[".$v."]的列重复");
                }else{
                    continue;
                }
            }
        }

        //检查科目名称有效性
        $sys_subject = $this->getAllSysSubject();
        foreach($data_arr[0] as $key=>$excel_sub){
            if($excel_sub!="姓名"){
                $isFound = false;
                //在系统科目查找名称和id，系统里没有的视为无效的科目
                foreach($sys_subject as $sys_sub){
                    if($excel_sub == $sys_sub['name']){
                        //将系统科目名称和id拼接，备用后来的插入操作
                        $data_arr[0][$key]=$sys_sub['name']."-".$sys_sub['id'];
                        $isFound = true;
                        break;
                    }
                }
                if(!$isFound){
                    return array("retcode"=>-1,"retdata"=>"名为[".$excel_sub."]的列为无效的列名");
                }
            }

        }

        //验证科目内容有效性
        $leng = count($data_arr);
        $cols = count($first_row);
        $nameIndex = array_keys($first_row,"姓名",false)[0];
        for($index=1; $index < $leng; $index++){
            $record = $data_arr[$index];
            for($i=0; $i < $cols; $i++){
                if($i == $nameIndex){
                    //检查姓名
                    if($record[$i]==""){
                        return array("retcode"=>-1,"retdata"=>"第".($begin_row_num+$index)."行姓名不能为空");
                    }else{
                        $student = $this->queryStudentInfo($cid,$record[$i]);
                        if(!empty($student)){
                            $data_arr[$index][$nameIndex] .= "-".$student[0]['id'];
                        }else{  //不存在该学生
                            return array("retcode"=>-1,"retdata"=>"第".($begin_row_num+$index)."行，该班不存在名为".$record[$nameIndex]."的学生");
                        }
                    }
                }else{      //检查其他列的数值
                    if(!is_numeric($record[$i])){
                        return array("retcode"=>-1,"retdata"=>"第".($begin_row_num+$index)."行".$record[$nameIndex].$first_row[$i]."成绩无效");
                    }
                }
            }
        }

        return array("retcode"=>0,"retdata"=>$data_arr);
    }
    
    private function queryStudentInfo($cid,$name){ 
        $con['cid'] = $cid;
        $con['name'] = $name;
        $m= WpIschoolStudent::find()->select('id,name')->where($con)->asArray()->all(); 
        return $m;
    }
    private function getAllSysSubject(){
       $m= WpIschoolChengjiKemu::find()->select('id,name')->orderBy('sort asc')->asArray()->all();        
        return $m;
    }
      //学生成绩单上传发送
      public function actionQuerychengji(){
        $cid=\yii::$app->request->get("cid");  
        $cjd=WpIschoolClassChengjidan::find()->select('cjdid,cjdname')->where(['cid'=>$cid])->orderBy('id asc')->asArray()->all();     
        $stus=WpIschoolStudent::find()->select('id,name')->where(['cid'=>$cid])->orderBy('id asc')->asArray()->all();   
        $return_arr['cid']=$cid;
        $return_arr['cjds']=$cjd;
        $return_arr['students']=$stus;
        $return_arr['tcid']=\yii::$app->request->get("tcid");  
        $return_arr['cname']=\yii::$app->request->get("cname");  
        $return_arr['sid']=\yii::$app->request->get("sid");
        $return_arr['openid']=\yii::$app->request->get("openid");
        return $this->render('querychengji',$return_arr);
      } 
      //退出班级
      public function actionDeleoneclass(){
        $id=\yii::$app->request->post("id");  
        $res=WpIschoolTeaclass::find()->where(['id'=>$id])->asArray()->all();  
         WpIschoolTeaclass::deleteAll(['id'=>$id]);
	if(!WpIschoolTeaclass::findOne(['sid'=>$res[0]["sid"],"openid"=>$res[0]["openid"]]))
        WpIschoolTeacher::deleteAll(['sid'=>$res[0]["sid"],"openid"=>$res[0]["openid"]]);
        $where="";
        $where["openid"]=$res[0]["openid"];
        $m= WpIschoolUser::find()->where($where)->one();
        $m->last_sid=1;
        $m->save(false);
        $at="success";
        $this->ajaxReturn($at,"json");  
      }
      //我是校长页面
     public function actionLoadschool(){
        $sid=\yii::$app->request->get("sid"); 
        $openid=\yii::$app->request->get("openid"); 
        $where["openid"]=$openid;
        $where["rid"]=1;
        $where["shenfen"]="school";
//        var_dump($where);die;
        $re= WpIschoolUserRole::find()->where($where)->asArray()->all();  
        $sql="select name from wp_ischool_superman where openid='".$openid."'";
        $opds = WpIschoolSuperman::findBySql($sql)->asArray()->all();
        if(!empty($opds)){
            $opd = "";
        }else{
            $opd = "readonly";
        }
        $return_arr['opd']=$opd;
//       var_dump($re);die;
        if(!empty($re))  //已绑定，跳转到管理
        {
            echo "<script>window.location.href='".URL_PATH."/manager/index?openid=".$openid."&sid=".$re[0]["sid"]."' </script>";
        }
        else   //否则可自建学校
        {
            $rep= WpIschoolSchooltype::find()->asArray()->all();          
            $sql="select * from wp_ischool_province ORDER BY convert(name USING gbk)";
            $res= WpIschoolProvince::findBySql($sql)->asArray()->all(); 
            $return_arr['sctype']=$rep;
            $return_arr['list_pro']=$res;
            $return_arr['openid']=$openid;       
            $rp=WpIschoolSchool::find()->where(['id'=>$res[0]["schoolid"]])->asArray()->all();  
            $return_arr['schname']=$rp[0]["name"];
            $return_arr['sid']=$sid;
            return $this->render('loadschool',$return_arr); 
        }  
       
      }
     //获取城市列表 
      public function actionGetcitybyprovince(){
          $code=\yii::$app->request->get("code"); 
          $sql="select * from wp_ischool_city where provincecode='".$code."' ORDER BY convert(name USING gbk)";
          $res=WpIschoolCity::findBySql($sql)->asArray()->all(); 
          $this->ajaxReturn($res,'json'); 
      }
      //获取县区列表
      public function actionGetcountybycity(){
          $code=\yii::$app->request->get("code"); 
          $sql="select * from wp_ischool_area where citycode='".$code."' ORDER BY convert(name USING gbk)";
          $res=WpIschoolCity::findBySql($sql)->asArray()->all(); 
          $this->ajaxReturn($res,'json');         
      }
      public function actionGetsch(){
          $cname=\yii::$app->request->get("code");
          $area=\yii::$app->request->get("area");
          $county=\yii::$app->request->get("county");
          $pro=\yii::$app->request->get("pro");
          $rt=WpIschoolProvince::find()->where(['code'=>$pro])->asArray()->all();
          $rp=WpIschoolCity::find()->where(['code'=>$county])->asArray()->all();     
          $sql="select * from wp_ischool_school where schtype='".$cname."' and pro='".$rt[0]["name"]."' and city='".$rp[0]["name"]."' and county='".$area."' ORDER BY convert(name USING gbk)";
          $res=WpIschoolSchool::findBySql($sql)->asArray()->all(); 
          $this->ajaxReturn($res,'json');         
      }
       public function actionDoaddschool(){
          $school=\yii::$app->request->post("school"); 
          $openid=\yii::$app->request->post("openid");
          $type=\yii::$app->request->post("type");
          $area=\yii::$app->request->post("area");
          $city=\yii::$app->request->post("city");
          $pro=\yii::$app->request->post("pro");
          $pro=WpIschoolProvince::find()->where(['code'=>$pro])->asArray()->all();
          $pro=$pro[0]["name"];
          $city=WpIschoolCity::find()->where(['code'=>$city])->asArray()->all();                 
          $city=$city[0]["name"];
          $where=array();                  
          $sid="";
          $where["pro"]=$pro;
          $where["city"]=$city;
          $where["county"]=$area;
          $where["schtype"]=$type;
          $where["name"]=$school;
          $res=WpIschoolSchool::find()->where($where)->asArray()->all();  
          if(empty($res)){
                $m= new WpIschoolSchool;
                $m->pro=$pro;
                $m->city=$city;
                $m->county=$area;
                $m->schtype=$type;
                $m->name=$school;
                $sid=$m->save(false);
          }else{
                $sid=$res[0]["id"];
          }
          $ry=WpIschoolUser::find()->where(['openid'=>$openid])->asArray()->all();  
          $name=$ry[0]["name"];
          $tel = $ry[0]['tel'];         
          $whe["openid"]=$openid;
          $whe["rid"]=1;
          $whe["shenfen"]="school";
          $rp=WpIschoolUserRole::find()->where($whe)->asArray()->all();  

          if(empty($rp)) {           
            $where="";
            $where["sid"]=$sid;
            $where["rid"]=1;
            $where["shenfen"]="school";
            $rq=WpIschoolUserRole::find()->where($where)->asArray()->all();          
            if(empty($rq))
            {
                $m=new WpIschoolUserRole;
                $m->openid=$openid;
                $m->rid=1;
                $m->shenfen="school";
                $m->school=$school;
                $m->sid=$sid;
                $m->name=$name;
                $m->save(false);               
                $mm=WpIschoolUser::find()->where(['openid'=>$openid])->one();
                $mm->last_sid=$sid;
                $mm->save(false);               
                $teaclass=new WpIschoolTeaclass;
                $teaclass->tname=$ry[0]["name"];
                $teaclass->openid=$openid;
                $teaclass->school=$school;
                $teaclass->sid=$sid;
                $teaclass->class="管理";
                $teaclass->cid=0;
                $teaclass->role="校长";
                $teaclass->ctime=time();
                $teaclass->ispass="y";
                $teaclass->save(false);
                if(!$this->isTeacherDouble($sid,$openid)){
                    $m3=new WpIschoolTeacher;
                    $m3->tname=$ry[0]["name"];
                    $m3->openid=$openid;
                    $m3->school=$school;
                    $m3->sid=$sid;
                    $m3->tel=$tel;
                    $m3->ctime=time();
                    $m3->ispass="y";
                    $m3->save(false);
                }
                $pl="";
                $this->GiveEpcrole($pl,$openid,$sid);
                $num=$sid;
            }
            else
            {//该学校已经有校长不能新建
                $num="xiaozhang";
            }

        }
        else
        {//已经成为其他学校的校长不能新建学校
            if(empty($res))
            {
                $m=WpIschoolSchool::find()->where(['id'=>$sid])->one();
                $m->delete();
            }
            $num="chongfu";
        }
        $this->ajaxReturn($num,"json"); 
     }
     
     public function isTeacherDouble($sid,$openid){      
        $con['sid']=$sid;
        $con['openid']=$openid;
        $res =  WpIschoolTeacher::find()->where($con)->asArray()->all(); 
        if(empty($res)){
            return 0;
        }else{
            return 1;
        }
    }
    
    /** 给予平安通知管理员身份*/
    public function GiveEpcrole($pl,$user,$sid){
        $where['openid']=$user;
        $where['sid']=$sid;
        $arr=WpIschoolSchoolManageEpc::find()->where($where)->asArray()->all();
        if(empty($arr)){
            $ar =  WpIschoolUser::find()->select('tel')->where(['openid'=>$user])->asArray()->all();
            $pwd=substr($ar[0]['tel'],-6);
            $f=new WpIschoolSchoolManageEpc;
            $f->name=$ar[0]['tel'];
            $f->pwd=md5($pwd);
            $f->openid=$user;
            $f->ctime=time();
            $f->sid=$sid;
            $pin=$f->save(false);
            $oo =  WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->all();
            $school=$oo[0]['name'];
            if($pin){
                $openid=$user;
                $msg="尊敬的".$ar[0]['tel']."用户,您已成为".$school."的平安通知管理员，您登录平安通知系统的用户名为：".$ar[0]['tel']."密码为：".$pwd;
                $data = '{
                "touser":"'.$openid.'",
                "msgtype":"text",
                "text":
                {
                "content":"'.$msg.'"
                }
                }';
            }
        }
        SendMsg::https_post(SendMsg::getUrl('kf'),$data);
    }
    //扫码绑定班主任中转方法
    public function actionSmurl(){      
        $appid = "wx8c6755d40004036d";      
        $secret = "bb0e9a8a2a7cb366b57d2db1b66e24fc";
        $cid=\yii::$app->request->get("cid"); //班级ID
        $urls = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.URL_PATH.'/information/smaddclass?cid='.$cid.'/getcode&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';    
	return $this->redirect($url);
    }
    function getJson($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }
        //扫码绑定班主任信息
    public function actionSmaddclass(){
        $urls = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $appid = "wx8c6755d40004036d";      
        $secret = "bb0e9a8a2a7cb366b57d2db1b66e24fc";
        $cid=\yii::$app->request->get("cid"); //班级ID
        $code=\yii::$app->request->get("code"); //班级ID
        //第一步:取得openid
        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $oauth2 = $this->getJson($oauth2Url);
        $openid = $oauth2['openid'];

        if(!isset($openid)){
            $openid = $_SESSION['openid'];
        }
        $classinfo =WpIschoolClass::find()->where(['id'=>$cid])->asArray()->all();      
        if(empty($classinfo)){
            return false;
        }
        $userinfo =WpIschoolUser::find()->where(['openid'=>$openid])->asArray()->all();  //查询用户表中是否有该用户        
        if(empty($userinfo)){
            $user=new WpIschoolUser;
            $user->name = $classinfo[0]['school'].$classinfo[0]['name']."班主任";
            $user->openid  = $openid;
            $user->last_sid =$classinfo[0]['sid'];
            $user->ctime = time();
            $user->save(false);
        }
        $userinfo = WpIschoolUser::find()->where(['openid'=>$openid])->asArray()->all();  //插入后重新查询一次          
        $whe['sid'] = $classinfo[0]['sid'];
        $whe['tname'] = $userinfo[0]['name'];
        $teainfo = WpIschoolTeacher::find()->where($whe)->asArray()->all();
        if(!empty($teainfo)){
            if(empty($teaInfo[0]['openid'])){
                $tea=WpIschoolTeacher::find()->where(['id'=>$teaInfo[0]['id']])->one();
                $tea->openid=$openid;
                $tea->save(false);              
            }
        }
        if (empty($teainfo)) {
            $wh["openid"]=$openid;
            $wh["sid"]=$classinfo[0]['sid'];
            $ru= WpIschoolTeacher::find()->where($wh)->asArray()->all();    
            if(empty($ru))
            {
                $teacher=new WpIschoolTeacher;
                $teacher->tname=$userinfo[0]['name'];
                $teacher->sid=$classinfo[0]['sid'];
                $teacher->school=$classinfo[0]['school'];
                $teacher->openid=$openid;
                $teacher->ispass=0;
                $teacher->tel=$userinfo[0]['tel'];
                $teacher->ctime=time();
                $teacher->save(false);
            }
        }
        
        $isSendSHMsg = false;
        $newRole ="";
        $w['openid'] = $openid;
        $w['cid'] = $cid;
        $w['role'] = '班主任';
        // $w['role'] = iconv("UTF-8", "GB2312//IGNORE", '班主任');
        $teaclassinfo = WpIschoolTeaclass::find()->where($w)->asArray()->all();       
        if(empty($teaclassinfo)){
            $teaclass=new WpIschoolTeaclass;
            $teaclass->tname=$userinfo[0]['name'];
            $teaclass->openid=$openid;
            $teaclass->school=$classinfo[0]['school'];
            $teaclass->cid=$cid;
            $teaclass->role="班主任";
            $teaclass->class=$classinfo[0]['name'];
            $teaclass->sid=$classinfo[0]['sid'];
            $teaclass->ctime=time();
            if(!empty($teainfo) && $teainfo[0]['ispass'] == "y"){
                $teaclass->ispass = 'y';
                $teaclass->save(false);
                $at = "0";
            }else{
                $teaclass->save(false);
                $at = "1";
                $isSendSHMsg = true;
                $newRole = $classinfo[0]['school'].$classinfo[0]['name']."班主任";
            }
        }else
        {	
        	if ($teaclassinfo[0]['ispass'] !="y" && !empty($teainfo) && $teainfo[0]['ispass'] == "y") {
        		$ispass = 'y';
        		$wheress['openid'] = $openid;
        		$wheress['cid'] = $cid;
        		$wheress['role'] = '班主任';
                        $te=WpIschoolTeaclass::find()->where($wheress)->one();
                        $te->ispass=$ispass;
                        $te->save(false);             
                        $at = "0";
        	}else{
        		//角色名重复
	            $w['ispass'] = '0';
                    $teaclassinfo =  WpIschoolTeaclass::find()->where($w)->asArray()->all();  	          
	            if (!empty($teaclassinfo)) {
	                $at=3;      //已经发送申请通知 请勿重复发送
	            }else{
	                $w['ispass'] = 'y';
                        $teaclassinfo =  WpIschoolTeaclass::find()->where($w)->asArray()->all();  	                
	                $at=2;      //绑定已经成功，请勿重复绑定
	            }
        	}
        	
        }
        //切换当前学校 
        $user=WpIschoolTeaclass::find()->where(['openid'=>$openid])->one();
        $user->last_sid = $classinfo[0]['sid'];
        $user->save(false);
        if($isSendSHMsg) {
            $title = "教师待审核信息";
            $des = $userinfo[0]['name'] . "申请成为" . $newRole . "，请在学校管理页面进行审核。";
            $where = "";
            
            $m = M("ischool_user_role");
            $where["rid"] = 1;
            $where["sid"] = $classinfo[0]['sid'];
            $er = WpIschoolUserRole::find()->select('openid')->where($where)->asArray()->all();            
            foreach ($er as $v) {
                SendMsg::sendSHMsgToPa($v["openid"], $title, $des);
            }
        }
        $return_arr['openid']=$openid;  
        $return_arr['sid']= $classinfo[0]['sid'];
        $return_arr['at']=$at;
        return $this->render('smaddclass',$return_arr);  
    }
    /*  扫码绑定保存新增孩子操作 */
    public function actionDosmaddchild(){
        $appid = "wx8c6755d40004036d";
        $secret = "bb0e9a8a2a7cb366b57d2db1b66e24fc";
        $code=\yii::$app->request->get("code");  
        //第一步:取得openid
        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $oauth2 = $this->getJson($oauth2Url);
        $openid = $oauth2['openid'];
        if(!isset($openid)){
            $openid = $_SESSION['openid'];
        }
        $stuno2=\yii::$app->request->get("stuno2");        
        if($stuno2)
        {
            $xx = WpIschoolStudent::find()->where(['stuno2'=>"'.$stuno2.'"])->asArray()->all();           
//          $xx = $x->where('stuno2="'.$stuno2.'"')->select();
            $sid=$xx[0]['sid'];
            $cid=$xx[0]['cid'];
            $stuid=$xx[0]['id'];
            $student = $xx[0]['name'];
            $classname = $xx[0]['class'];
            $school = $xx[0]['school'];
        }
        else 
        {
            $sid=\yii::$app->request->get("sid"); 
            $cid=\yii::$app->request->get("cid"); 
            $stuid=\yii::$app->request->get("id");      
            $xx = WpIschoolStudent::find()->where(['id'=>$stuid])->asArray()->all();     
            $student = $xx[0]['name'];
            $classname = $xx[0]['class'];
            $school = $xx[0]['school'];
        }
        $ry = WpIschoolUser::find()->select('openid,name,tel')->where(['openid'=>$openid])->asArray()->all();    
        if(!isset($ry[0]['openid'])){
            //-----将家长信息存入用户表-----//
            $us=new WpIschoolUser;
            $us->name=$student."家长";
            $us->openid=$openid;
            $us->ctime=time();
            $us->last_sid=$sid;
            $user=$m->save(false);
        }
        $paname = isset($ry[0]["name"])?$ry[0]["name"]:$student."妈妈";
        $tel = $ry[0]["tel"];

        $at = "";
        $res = $this->isHasStudentNew($stuid,$sid,$cid);  //检查班级有无此人
        if($res) {
            $stuid = $res[0]['id'];
            $ro = $this->checkname($tel,$stuid);
            if($ro)
            {
                $p=WpIschoolPastudent::find()->where(['tel'=>$tel,'stu_id'=>$stuid])->one();   
                $p->openid = $openid;
                $p->name = $paname;
                $p->save(false);
                $at = 5;
            }
            else
            {
                $has=$this->isCheckedStudent($openid,$stuid);   //检测当前家长是否已绑定该学生
                if($has){
                    $at = 3;
                }else{
                    $mm= new WpIschoolPastudent;
                    $mm->stu_id = $stuid;
                    $mm->stu_name = $student;
                    $mm->class  = $classname;
                    $mm->cid    = $cid;
                    $mm->school = $school;
                    $mm->openid = $openid;
                    $mm->name = $paname;
                    $mm->tel  = $tel;
                    $mm->ctime = time();
                    $mm->ispass = "y";
                    $mm->sid  = $sid;
                    $idp = $mm->save(false);
                    if($idp > 0 || $idp === 0)
                    {
                        $at = "success";                  
                        $m=WpIschoolUser::find()->where(['openid'=>$openid])->one();                      
                        $m->last_sid = $sid;
                        $m->save();
                        $at = 5;
                    }
                    else
                    {
                        $at=2;
                    }
                }
            }

        }
        else
        {
            $at=1;
        }
        $return_arr['at']=$at;
        $return_arr['openid']=$openid;
        $return_arr['sid']=$sid;
        return $this->render('dosmaddchild',$return_arr); 
    }
    /*  2017-1-9检查班级是否有学生 */
    public function isHasStudentNew($sname,$school,$class){
        $where["id"]=$sname;
        $where["sid"]=$school;
        $where['cid']=$class;
        $where['is_deleted']=0;
        $res= WpIschoolStudent::find()->select('id')->where($where)->asArray()->all();
        return $res;
    }
    //图片上传功能
    public function actionUploadimgs(){   
        $sid=\yii::$app->request->get("sid"); 
        $openid=\yii::$app->request->get("openid"); 
        $res=WpIschoolUserschool::find()->select('schoolid')->where(['openid'=>$openid])->asArray()->all();            
        $return_arr['ssid']=$res[0]["schoolid"];
        $rp= WpIschoolSchool::find()->select('id,name')->where(['id'=>$res[0]["schoolid"]])->asArray()->all();  
        $return_arr['schname']=$rp[0]["name"];
        $return_arr['schid']=$rp[0]['id'];
        $sql='select distinct pro from wp_ischool_school ORDER BY convert(pro USING gbk)';
        $ress=WpIschoolSchool::findBySql($sql)->asArray()->all();
        $return_arr['list_pro']=$ress; 
        $return_arr['sid']=$sid;
        return $this->render('uploadimgs',$return_arr); 
    }
     public function actionUploading(){
         $sid=\yii::$app->request->get("sid");
         $cid=\yii::$app->request->get("cid");
         $childDir = date("y/m/d");   
         $upload='upload/photos/'.$sid.'/'.$cid.'/'.$childDir;
         if(!file_exists($upload))
		{
			mkdir($upload,0755,true);
		}
         $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {  
                $filepath= $upload.$model->file->baseName . '.' . $model->file->extension;
                $model->file->saveAs($filepath);              
                $picurl= $filepath;    
                $m= new  WpIschoolClassImages;         
                $m->sid = $sid;
                $m->cid=$cid;
                $m->picurl = $picurl;
                $isdo = $m->save(false);
                if($isdo>0 || $isdo===0){
                    echo "<script> alert('发送成功！');</script>";die;
                   
                }
       
            }
        }

  
     }
    
}

   




