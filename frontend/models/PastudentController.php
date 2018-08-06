<?php

namespace frontend\controllers;
use app\models\gonggao;
use app\models\News;
use app\models\WpIschoolStuLeave;
use app\models\ZfCardInfo;
use app\models\ZfDealDetail;
use app\models\WpIschoolSafecard;
use Yii;
use app\controllers\BaseController;
use app\models\school;
use app\models\WpIschoolOutbox;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Pastudent;
use app\models\paUser;
use app\models\WpIschoolInbox;
use  yii\web\Session;
use yii\data\Pagination;
use yii\data\ArrayDataProvider;
use mobile\assets\SendMsg;

class PastudentController extends BaseController
{
    //public $layout=true;
    public $laststuid;  //当前孩子id
    public $paopenid;   //当前家长openid
    public $cid;        //当前孩子所在的班级ID
    public $paname;     //家长姓名
    public $stuinfo;    //学生信息
    public $pauser;      //用户家长信息
    public $gonggao;    //当前学校公告列表信息
    public $dongtai;    //当前学校动态列表信息
    public function beforeAction($action){
        $info = $this->init();
        $shenfen  = $info['user'][0]['shenfen'];
        if($shenfen != "jiazhang"){
            $url = \Yii::$app->session->get('url');
            return $this->redirect("$url")->send();
        }
        return true;
    }
    public function actionIndex()
    {
        $info = $this->init();
        return $this->render('index',[
            'info' => $info,
        ]
        );
    }

    public function actionChild()
    {
        $painfo = $this->init();
        $this->pauser = $painfo['user'];
        $paid = $painfo['user'][0]['id'];      //用户ID
//        $userinfo = paUser::findOne($paid);
        $this->paopenid =$painfo['user'][0]['openid'];
        $this->paname = $painfo['user'][0]['name'];         //家长姓名
        $this->laststuid = empty($painfo['user'][0]['last_stuid'])?0:$painfo['user'][0]['last_stuid'];   //学生ID
        $stuinfo = $this->stuinfo($this->laststuid);
        $this->stuinfo = $stuinfo;
        $info['school'] = !empty($stuinfo[0]['school'])?$stuinfo[0]['school']:"学校暂时为空";        //学生所在学校
        $info['class']  = !empty($stuinfo[0]['class'])?$stuinfo[0]['class']:"班级暂时为空";          //学生所在班级
        $info['name']  = !empty($stuinfo[0]['name'])?$stuinfo[0]['name']:"学生姓名暂时为空";            //学生姓名
        $cid = !empty($stuinfo[0]['cid'])?$stuinfo[0]['cid']:0;                      //班级ID
        $this->cid = $cid;   //班级ID
        $info['cid']  = $cid;
        $info['sid']  = !empty($stuinfo[0]['sid'])?$stuinfo[0]['sid']:0;             //学校ID
        $info['stuid']  = !empty($stuinfo[0]['id'])?$stuinfo[0]['id']:0;             //学生ID
        $info['stuleave'] = $this->Stleavelist($this->laststuid); //学生请假信息
        $info['chengji'] = $this->Scorequery($cid);
        $headmaster = $this->Headmaster($cid);  //班主任信息，可以是多个
        $teaname="";
        foreach ($headmaster as $v) {
            $teaname.="/".$v["tname"];
        }
        $info['teaname'] = ltrim($teaname,"/"); //班主任姓名
        $tel = "";

        foreach($headmaster as $v){
            $sid = $v['sid'];
            $openid = $v['openid'];
//            $teainfo = $this->teainfo($sid,$openid);
            $tel.= "/".$v['tel'];
//            $tel.="/".$teainfo[0]["tel"];
        }
        $info['tel'] =ltrim($tel,"/");      //班主任电话
//        $paqq = array();
//        foreach($painfo['pastudent'] as $key=>$value){
//            if($painfo['pastudent'][$key]['isqqtel'] == "1"){
//               $paqq[] = $painfo['pastudent'][$key];
//            }
//        }
        $laststuid = empty($this->laststuid)?0:$this->laststuid;
        $info['notel'] = $this->getnoqqh($laststuid);//非亲情号码用户注册的号码
        $info['paqq'] = $this->getqqh($laststuid);
        return $this->render('child',[
            'info' =>$info,
            'painfo' => $painfo
        ]);
    }

    //请假被拒绝的信息不再显示
    public function actionQqbxs(){
        $id = \Yii::$app->request->get('id');
        if(!empty($id)){
            $models = WpIschoolStuLeave::findOne($id);
            $models->flag = 0;
            $models->save(false);
        }
        return $this->redirect("/pastudent/child");
    }
    //根据学生ID获取孩子非亲情号码
    public function getnoqqh($stuid){
        $model = Pastudent::find()->where(['and',"stu_id = $stuid",'isqqtel <>1'])->asArray()->all();
        return $model;
    }
    //根据学生ID获取孩子亲情号码
    public function getqqh($stuid){
        $model = Pastudent::findAll(['stu_id'=>$stuid,'isqqtel'=>1]);
        return $model;
    }

    //切换孩子信息
    public function actionChcld(){
        $post = \yii::$app->request->post();
        $stuid = $post['stuid'];
        $painfo = $this->init();
        $paid = $painfo['user'][0]['id'];      //用户ID
        if(!empty($stuid)){
            $model = paUser::findOne($paid);
            $model->last_stuid = $stuid;
            $sinfo = $this->getSchoolidbystuid($stuid);
            $model->last_sid = empty($sinfo[0]['sid'])?1:$sinfo[0]['sid'];
            $res = $model->save(false);
            if($res){
                return 0;
            }else{
                return 1;
            }
        }else{
            return 1;
        }
    }
/***
修改家长用户名字
 */
    public function actionUpname()
    {
        $post = \yii::$app->request->post();
        $name = $post['newyhm'];
        $id = $post['usid'];
        $res = $this->Upname($name,$id);
        return $res;
    }

    /***
    修改家长电话号码
     */
    public function actionUptel()
    {
        $post = \yii::$app->request->post();
        $tel = $post['newtel'];
        $id = $post['usid'];
        return $this->Uptel($tel,$id);
//        $upuser = paUser::findOne($id);
//        $upuser->tel = $tel;
//        $upname = $upuser->save();
//        if($upname){
//            return 0;
//        }else{
//            return 1;
//        }
    }

    /*  家长移除绑定 */
    public function actionDelchild(){
        $this->actionChild();
        $tel = $this->pauser['0']['tel'];
        $models = paUser::findOne(['tel'=>$tel,'shenfen'=>'jiazhang']);
        $post = \yii::$app->request->post();
        $id = $post['qxid'];
        $stuid = $post['stuid'];
        $upuser = Pastudent::findOne($id);
        if($stuid == $this->pauser[0]['last_stuid']){
            $models->last_stuid = Null;
            $transaction = \yii::$app->db->beginTransaction();
            try{
                if( $upuser->delete() && $models->save(false))
                {
                    $res = 0;
                    $transaction->commit();
                }else {
                    $res = 1;
                    $transaction->rollBack();
                }
            }catch (Exception $e)
                {
                       $transaction->rollBack();
                }
        }else if( $upuser->delete())
        {
                $res = 0;
        }
             return $res;
    }

    /**家长关注孩子*/
    public function actionAddchild(){
        $this->actionChild();
        $tel = $this->pauser[0]['tel'];
        $post =  \YII::$app->request->post();
        $cid  = $post['cid'];               //班级ID
        $openid = $post['openid'];          //家长openid
        $student = $post['student'];        //学生名字

        $userinfo  = \Yii::$app->db->createCommand("select * from wp_ischool_user  where  tel ='$tel' AND shenfen='jiazhang' AND openid = '".$openid."'" )->queryAll();  //获取用户信息
        $pname = (!empty($userinfo))?$userinfo[0]['name']:"用户名暂时为空";      //用户名
        $tel   = (!empty($userinfo))?$userinfo[0]['tel']:"电话号码暂时为空";       //电话
        $res = $this->isHasStudent($cid,$student);      //检查班级有无此人
        $at ="";
        if(!empty($res)){
            $stuid = $res[0]['id'];         //学生id
            $painfo  = $this->getPainfo($tel,$stuid);     //检查家长表中是否已经有手机号码和学生ID的信息
            if($painfo){                        //家长表中存在有孩子信息
                $popenid = $painfo[0]['openid'];
                if(!empty($popenid)){       //家长表中有有孩子信息和openid
                    $at = 3;                        //at  1未导入 2失败 3已绑定 5成功
                }else{                  //家长表中有有孩子信息没有openid
                    $uppastu = Pastudent::findOne($painfo[0]['id']);
                    $uppastu->name = $pname;
                    $uppastu->openid = $openid;
                    $upname = $uppastu->save(false);
                    if($upname){
                        $at = 5;
                    }else{
                        $at = 2;
                    }
                }
            }else{      //家长表中不存在孩子信息 直接填加
                $uppastu = new Pastudent();
                $uppastu->name = $pname;
                $uppastu->openid = $openid;
                $uppastu->ctime = time();
                $uppastu->stu_id = $stuid;
                $uppastu->school = $post['school'];
                $uppastu->cid = $cid;
                $uppastu->class = $post['class'];
                $uppastu->tel = $tel;
                $uppastu->stu_name = $student;
                $uppastu->ispass = "y";
                $uppastu->sid = $post['sid'];
                $upname = $uppastu->insert(false);
                if($upname){
                    $at = 5;
                }else{
                    $at = 2;
                }
            }
        }else{
            $at = 1;
        }

        if($at ==5){
            $models = paUser::findOne(['openid'=>$openid]);
            if(!empty($models)){
                $models->last_stuid = $stuid;
                $models->save(false);
            }
        }
        return $at;
    }

    /*  检测某班级是否存在某学生 */
    public function isHasStudent($cid,$name){
        $connection  = \Yii::$app->db;
        $command = $connection->createCommand("select id from wp_ischool_student where cid='".$cid."' and name ='".$name."' ORDER BY convert(name USING gbk)");
        $res =  $command->queryAll();
        return $res;
    }
/**通过学生ID和手机号码检查家长有没有openid**/
    public function getPainfo($tel,$stuid){
        $connection  = \Yii::$app->db;
        $command = $connection->createCommand("select * from wp_ischool_pastudent where tel='".$tel."' and stu_id='".$stuid."' ORDER BY convert(name USING gbk)");
        $req = $command->queryAll();
        return $req;
    }
//修改亲情号码
    public function actionUpqqh()
    {
        $post = \yii::$app->request->post();
        $id = $post['id'];
        $model = Pastudent::findOne($id);
        $model->name = $post['userID'];
        $model->tel = $post['userPhon'];
        $model->Relation = $post['userName'];
        $res = $model->save(false);
        if($res){
            return $this->opSucceed("/pastudent/child");
        }else{
            return $this->opFailed('pastudent/child');
        }
    }
//删除亲情号码
    public  function actionDelqqh(){
        $post = \yii::$app->request->post();
        $id = $post['id'];
        $model = Pastudent::findOne($id);
        $res = $model->delete();
        if($res){
            return json_encode(['status'=>0]);
        }else{
            return json_encode(['status'=>1]);
        }
    }
//添加亲情号码
    public  function actionAddqqh(){
        $post = \yii::$app->request->post();
        $model =new Pastudent();
        $model->name= $post['userName'];
        $model->ctime= time();
        $model->stu_id= $post['stuid'];
        $model->school= $post['school'];
        $model->cid= $post['cid'];
        $model->class= $post['class'];
        $model->tel= $post['userPhon'];
        $model->stu_name= $post['stuname'];
        $model->ispass= "y";
        $model->sid= $post['sid'];
        $model->Relation= $post['userID'];
        $model->isqqtel= 1;
        $res = $model->save(false);
        if($res){
            return $this->opSucceed("/pastudent/child");
        }else{
           return $this->opFailed("/pastudent/child");
        }
    }
//请假申请
    public function actionQingjia(){
        $this->actionChild();
        $cid = $this->cid;
        $sname = $this->stuinfo[0]['name'];
        $post = \yii::$app->request->post();
        if(!empty($post['stuid']))
        {
            $res = \Yii::$app->db->createCommand()->insert('wp_ischool_stu_leave',array(
                    'stu_id' => $post['stuid'],
                    'begin_time' => strtotime($post['statime']),
                    'stop_time' => strtotime($post['endtime']),
                    'openid' => $post['openid'],
                    'ctime' => time(),
                    'flag' => 2,
                    'reason' => $post['reason'],
            ))->execute();
            $sidinfo = $this->getSchoolidbystuid($post['stuid']);
            $sid = $sidinfo[0]['sid'];
            $headmaster = $this->Headmaster($cid);  //班主任信息，可以是多个
            if($res){
                $this->doSendLeave($headmaster,$sname,$sid);
                return $this->opSucceed("/pastudent/child");
            }else{
                return $this->opFailed("/pastudent/child");
            }
        }else{
            return $this->opFailed("/pastudent/child");
        }
    }

    //执行请假信息发送
    private function doSendLeave($tos,$sname,$sid){
        $title = "学生请假通知信息";
        $msg = "的家长申请请假，请在【我的服务】->【我的资料】中进行审核";
        $final = "";
        $data['pic_url'] =  $this->getSchoolPic($sid);
        foreach ($tos as $v) {
            $final = SendMsg::sendSHMsgToPa($v['openid'], $title, $sname . $msg,"",$data['pic_url']);
        }
        return $final;
    }

    //请假人列表
    public function Stleavelist($stuid){
        $sql = "SELECT * FROM wp_ischool_stu_leave WHERE stu_id=:stuid AND stop_time>:time AND flag!=0";
        $users = \Yii::$app->db->createCommand($sql,[':stuid'=>$stuid,':time'=>time()])->queryAll();
        return $users;
    }
    //家长查询成绩界面
    public function Scorequery($cid){
        $sql = "SELECT cjdid,cjdname,isopen FROM wp_ischool_class_chengjidan WHERE cid=:cid order by id ASC ";
        $users = \Yii::$app->db->createCommand($sql,[':cid'=>$cid])->queryAll();
        return $users;
    }

    //拼凑成绩单标题
    private function scriptTitle($cjdid,$cid){
        $sql = "select distinct kmname from wp_ischool_chengji where cjdid=:cjdid and cid=:cid order by kmid asc";
        $title = \Yii::$app->db->createCommand($sql,[':cjdid'=>$cjdid,':cid'=>$cid])->queryAll();
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
    //家长执行查询成绩
    public function actionDoscorequery(){
        $post = \yii::$app->request->post();
        $cid = $post['cid'];
        $cjdid = $post['cjdid'];
        $stuid= $post['stuid'];
        $isopen = $post['isopen'];
        if($stuid != "all"){   //查个人
            $sql = "SELECT stuid,stuname,kmname,score FROM wp_ischool_chengji WHERE cid=:cid AND cjdid=:cjdid AND stuid=:stuid order by kmid asc";
            $users = \Yii::$app->db->createCommand($sql,[':cid'=>$cid,':cjdid'=>$cjdid,':stuid'=>$stuid])->queryAll();
        }elseif($isopen == "y" && $stuid == "all"){
            $sql = "SELECT stuid,stuname,kmname,score FROM wp_ischool_chengji WHERE cid=:cid AND cjdid=:cjdid order by stuid asc,kmid asc";
            $users = \Yii::$app->db->createCommand($sql,[':cid'=>$cid,':cjdid'=>$cjdid])->queryAll();
        }
        $title = $this->scriptTitle($cjdid,$cid);
        $cid = $this->scriptCjd($users);
        $res[] = $title;
        $res[] = $cid;
        return json_encode($res);
    }

    public function actionPassword(){
        return $this->render('password');
    }

//    public function actionCheckMobileExists()
//    {    $mobile = \Yii::$app->request->post('mobile');
//        if(User::findByMobile($mobile)){
//            echo 'Success';
//        }else{
//            echo  'Failed';
//        }
//    }
//doPostBack()会提交到SendLoginCode方法发送登录验证码：
//    public function actionSend()
//    {
//        $ss = \yii::$app->request->post();
//        $mobile = Yii::$app->request->post('tel');
//
//        $code = $this->createCode();
//        $content = "本次验证码为".$code."，您正在登录xxxx，打死也不告诉别人哦";
//        if($this->send($mobile,$content)===true){
//            //检查session是否打开
//            if(!Yii::$app->session->isActive){
//                Yii::$app->session->open();
//            }
//            //验证码和短信发送时间存储session
//            Yii::$app->session->set('login_sms_code',$code);
//            Yii::$app->session->set('login_sms_time',time());
//            return 'Success';
//        }else{
//            return  'Failed';
//        }
//    }
//    public function getSmsCode()
//    {
//        //检查session是否打开
//
//        if(!Yii::$app->session->isActive){
//            Yii::$app->session->open();
//        }
//        //取得验证码和短信发送时间session
//        $signup_sms_code = intval(Yii::$app->session->get('login_sms_code'));
//        $signup_sms_time = Yii::$app->session->get('login_sms_time');
//        if(time()-$signup_sms_time < 600){
//            return $signup_sms_code;
//        }else{
//            return 888888;
//        }
//    }
//家校沟通
    public function actionHomeschool(){
        $this->actionChild();
        $laststuid = $this->laststuid;              //当前学生ID
        $inopenid = $this->paopenid;                //家长openid
        $cid = $this->cid;                          //当前班级ID
        $info['bzr'] = !empty($laststuid)?$this->Allteacher($cid):"";     //班主任信息
        $type = 0;
        if(!empty($inopenid) && !empty($laststuid)){
            $inboxlist = $this->getinboxlist($inopenid,$type);    //收件人接受信息列表
            $info['pages'] = $inboxlist['pages'];
            $info['inboxlist'] = $inboxlist['dataprovider'];
            $outboxlist= $this->getoutboxlist($inopenid,$type);    //已发信息列表
            $info['pageso'] = $outboxlist['pages'];
            $info['outboxlist'] = $outboxlist['dataprovider'];
        }
        return $this->render('homeschool',$info);
    }
    //收件箱删除信息
    public function actionDelinbox()
    {
        $id = \Yii::$app->request->get('id');
        $url = \Yii::$app->request->get('url');
        if(!empty($id)){
            $res = $this->delInbox($id);
            if($res){
                \Yii::$app->getSession()->setFlash('success', '删除成功');
                return $this->redirect("/pastudent/$url?type=shouxin");
            }
        }
    }
//发件箱删除信息
    public function actionDeloutbox()
    {
        $id = \Yii::$app->request->get('id');
        $url = \Yii::$app->request->get('url');
        if(!empty($id)){
            $res = $this->delOutbox($id);
            if($res){
                \Yii::$app->getSession()->setFlash('success', '删除成功');
                return $this->redirect("/pastudent/$url?type=yifa");
            }
        }
    }
    //保存发信内容
    public function actionInbox(){
        $this->actionChild();
        $paopenid = $this->paopenid;            //家长openid
        $post = \yii::$app->request->post();
        $topenid =$post['toopenid'];           //老师openid
        $toopenid=explode(',',$topenid);
        $ctime = time();
        $title = $post['title'];                //发件箱标题
        $totitle = "来自".$this->paname."的消息";      //收件箱标题
        $content = $post['content'];            //内容

        if(!empty($content)){
            $preg = "/<\/?[^>]+>/i";
            $des = preg_replace($preg,'',$content);
        }
        $stuid = $this->laststuid;
        $sinfo = $this->getSchoolidbystuid($stuid);
        $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
        $ur[0]=URL_PATH."/tongzhi/index?openid=";
        $ur[1]="&sid=".$sid;
        $data['url']   = $ur;       //图文跳转链接
        $data['title'] = $totitle;  //图文消息标题
        $data['content'] = $des;    //待入库的原始消息
        $data['pic_url'] =  $this->getSchoolPic($sid);
        $msgType = 'xx';    //家长发送不是公告信息
        //发送前先存入发送箱
        $sql = "INSERT INTO `wp_ischool_outbox` ( `content` , `outopenid`, `ctime` , `title` , `type` ) VALUES( :content, :outopenid, :ctime,:title, 0)";
        $outbox = \Yii::$app->db->createCommand($sql,[':content'=>$content,':outopenid'=>$paopenid,':ctime'=>$ctime,':title'=>$title]);
        $outbox = $outbox->execute();
        $transaction = \Yii::$app->db->beginTransaction();       //事务开始
        try{
            foreach($toopenid as $inopenid){
                $sql = "INSERT INTO `wp_ischool_inbox` ( `content` , `outopenid` , `inopenid` , `ctime` , `title` , `type` ) VALUES( :content, :outopenid,:inopenid, :ctime,:title,0)";
                $inbox = \Yii::$app->db->createCommand($sql,[':content'=>$content,':outopenid'=>$paopenid,':inopenid'=>$inopenid,':ctime'=>$ctime,':title'=>$totitle])->execute();
            }
            $transaction->commit();
            $result = SendMsg::muiltPostMsg($toopenid,$data);
            if($result->errcode == 0){
                $this->setMsgNum($msgType,$topenid);
            }
            return json_encode(['status'=>0]);
        }catch(Exception $e){     // 如果有一条查询失败，则会抛出异常
            $transaction->rollBack();
            return json_encode(['status'=>1]);
        }
    }

    //平安通知模块
    public function actionSecurity(){
        $this->actionChild();
        $stuinfo = $this->stuinfo;
        if(!empty($stuinfo))
        {
            $stuid = $stuinfo[0]['id'];                         //学生id
            $info['stuname'] =$stuinfo[0]['name'];              //学生名字
            $begintm = $this->getBeginTimestamp("today");       //获得今天的时间戳
            $dklist = $this->getdklist($stuid,$begintm);        //今天的打卡信息列表
            $pagination = $dklist['pages'];
            $model = $dklist['model'];
            $beginwk = $this->getBeginTimestamp("week");       //获得本周的时间戳
            $dklistwk = $this->getdklist($stuid,$beginwk);        //本周的打卡信息列表
            $modelwk = $dklistwk['model'];
            $paginationwk = $dklistwk['pages'];
            return $this->render("security",[
                'pages'=>$pagination,
                'model'=>$model,
                'modelwk'=>$modelwk,
                'pageswk'=>$paginationwk,
                'info'=>$info
            ]);
        }
        return $this->render("security");
    }

    //平安通知导出
    public function actionExport()
    {
        $this->actionChild();
        $stuinfo = $this->stuinfo;
        $stuid = $this->laststuid;
        $name = $stuinfo[0]['name'];
        $model = new WpIschoolSafecard();
        $export_columns =
            [
                [
                    "attribute"=>"stuid",
                    'value' => function() use ($name){
                        return $name;
                    },
                    "header"=>"姓名"
                ],
                [
                    "attribute"=>"ctime",
                    "header"=>"刷卡时间",
                    "format"=>"datetime"
                ],
                [
                    "attribute"=>"info",
                    "header"=>"进校/出校"
                ],
            ];
        $post = \yii::$app->request->post();
        if($post['type'] == "today"){
            $begin = $this->getBeginTimestamp("today");       //获得今天的时间戳
        }else if($post['type'] == "week"){
            $begin = $this->getBeginTimestamp("week");       //获得本周的时间戳
        }

        $info = $model->export($stuid,$begin);
        \moonland\phpexcel\Excel::export([
            'models' => $info,
            'columns' => $export_columns,
            'fileName' => "kaoqin.xlsx"
        ]);
    }

    //餐卡消费记录
    public  function actionRecords(){
        $this->actionChild();
        $stuinfo = $this->stuinfo;
        if(!empty($stuinfo))
        {
            $info['stuname'] = $stuinfo[0]['name'];
            $stuno2 = $stuinfo[0]['stuno2'];                         //学生学号
            $begintm = $this->getBeginTimestamp("today");       //获得今天的时间戳
            $info['today'] = $this->getxflist("111",$begintm);
            $begintmwk = $this->getBeginTimestamp("week");       //获得本周的时间戳
            $info['week'] = $this->getxflist("111",$begintmwk);
            $begintmmt = $this->getBeginTimestamp("month");       //获得本月的时间戳
            $info['month'] = $this->getxflist("111",$begintmmt);
            return $this->render("records",$info);
        }
        return $this->render("records");
    }
    //获取消费列表信息
    public function getxflist($stuno,$begintm){
        $cardinfo = ZfCardInfo::find()->where(['user_no'=>$stuno])->select('card_no')->asArray()->all();
        $card_no = $cardinfo[0]['card_no']; //card表中学生学号user_no
        $models = ZfDealDetail::find()->where(['card_no'=>$card_no])->andFilterWhere(['>','created',$begintm]);
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' =>$models->count(),
        ]);
        $model = $models->orderBy('created DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $info['pages']=$pagination;
        $info['model'] =$model;
        return $info;
    }

    //学校微官网信息
    public function  actionWebsite(){
        $this->actionChild();
        $info['students'] = $this->stuinfo;
        $stuid = $this->laststuid;
        $sinfo = $this->getSchoolidbystuid($stuid);
        $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
        $res = $this->getSchoolnamebysid($sid);
        if(!empty($res)){
            $info['school'] = $res[0]['name'];
        }else{
            $info['school'] = "正梵高级中学";
        }
        $res=$this->getLunbopicurl($sid);
        if(!empty($res)){
            $info['schoolpic'] =$res[0]['picurl'];
        }else{
            $info['schoolpic'] ="";
        }
        $info['gonggao'] = gonggao::find()->where(['sid'=>$sid])->orderBy('ctime Desc')->asArray()->all();
        $info['dongtai'] = News::find()->where(['sid'=>$sid])->orderBy('ctime Desc')->asArray()->all();
        $info['columns'] = \yii::$app->db->createCommand("select t3.* from(SELECT t2.id,t2.title,t2.toppicture,t2.content,t2.sketch,t2.sid,t.id as cid,t.name from wp_ischool_hpage_colname t LEFT JOIN wp_ischool_hpage_colcontent t2 on t.id=t2.cid where t.sid=".$sid." ORDER BY t2.id desc) t3 GROUP BY t3.name")->queryAll();
        return $this->render('website',$info);
    }
    //公告详情页
    public function actionSchoolmin(){
        $id = $_GET['id'];
        if(!empty($id)){
        $info['info'] =  gonggao::find()->where(['id'=>$id])->asArray()->all();
        return $this->render("schoolmin",$info);
        }else{
            $this->redirect("/pastudent/notice");
        }
    }
    //动态详情页
    public function actionDongtaimin(){
        $id = $_GET['id'];
        $info['info'] =  News::find()->where(['id'=>$id])->asArray()->all();
        return $this->render("newsmin",$info);
    }
    //学校概况信息详情页
    public  function  actionWebsitemin(){
        $id = $_GET['id'];
        $sql = "select * from wp_ischool_hpage_colcontent WHERE id=:id";
        $info['info'] = \Yii::$app->db->createCommand($sql,[':id' => $id])->queryAll();
        return $this->render("websitemin",$info);
    }
    //校内公告列表
    public function actionNotice(){
        $this->actionChild();
        $info['students'] = $this->stuinfo;
        $stuid = $this->laststuid;
        $sinfo = $this->getSchoolidbystuid($stuid);
        $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
        $model = gonggao::find()->where(['sid'=>$sid])->orderBy('ctime Desc');
        $pagination = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' =>$model->count(),
        ]);
        $model = $model->orderBy('ctime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $info['pages'] = $pagination;
        $info['dataprovider']=$model;
        return $this->render("notice",$info);
    }
    //校内动态列表
    public function actionDynamics(){
        $this->actionChild();
        $info['students'] = $this->stuinfo;
        $stuid = $this->laststuid;
        $sinfo = $this->getSchoolidbystuid($stuid);
        $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
        $model = News::find()->where(['sid'=>$sid])->orderBy('ctime Desc');
        $pagination = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' =>$model->count(),
        ]);
        $model = $model->orderBy('ctime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $info['pages'] = $pagination;
        $info['dataprovider']=$model;
        return $this->render("dynamics",$info);
    }


    public function actionTest()
    {
        $tos = ["oUMeDwLBklMzOqyGuxhuA-Pmzsu0","oUMeDwC5bsoGmgX6mC8qk3gzPnu8"];
        $data['公告信息'] = "公告";
        $data['公告内容'] = "公告";
        $data['url'] = "";
        $result = SendMsg::broadMsgToManyUserTest($tos,$data);
        var_dump($result);
    }
}
