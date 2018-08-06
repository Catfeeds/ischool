<?php
namespace frontend\controllers;
use app\controllers\BaseController;
use app\models\gonggao;
use app\models\ImportData;
use app\models\News;
use app\models\Pastudent;
use app\models\paUser;
use app\models\WpIschoolQunzu;
use app\models\WpIschoolStuLeave;
use app\models\WpIschoolTeaclass;
use app\models\WpIschoolSafecard;
use app\models\WpIschoolUserRole;
use app\models\WpIschoolWork;
use app\models\WpIschoolWorksh;
use backend\models\WpIschoolStudent;
use backend\models\WpIschoolUser;
use mobile\assets\SendMsg;
use yii\web\UploadedFile;
use yii\data\Pagination;
use app\models\UploadForm;

require_once 'excel.php';
class TeacherController extends BaseController
{
    public $userinfo;
    public $lastcid;
    private $source_data;   //上传的excel数据
    public function beforeAction($action){
        $info = $this->init();
        $shenfen  = $info['user'][0]['shenfen'];
        if($shenfen != "tea"){
            $url = \Yii::$app->session->get('url');
            return $this->redirect("$url")->send();
        }
        return true;
    }

    private function initExcel()
    {
        if (\Yii::$app->request->isPost) {
            $model = new ImportData();
            $model->upload = UploadedFile::getInstance($model, 'upload');
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
                }
                else return $this->assignPage("文件格式错误");
            }
        }
    }
    public function actionIndex()
    {
        $info = $this->init();
        $this->userinfo = $info['user'];
        $this->lastcid = isset($info['user'][0]['last_cid'])?$info['user'][0]['last_cid']:"0";
        return $this->render('index',[
                'info' => $info,
            ]
        );
    }

    //切换当前班级信息
    public function actionChangeclass()
    {
        $post = \yii::$app->request->post();
        $cid = $post['cid'];
        $sid = $this->getSchoolidbycid($cid)[0]['sid'];
        \yii::trace($sid);
        $info = $this->init();
        $userid = $info['user'][0]['id'];      //用户ID
        if(!empty($cid)){
            $model = paUser::findOne($userid);
            $model->last_cid = $cid;
            $model->last_sid = $sid;
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
    /***    修改用户名     */
    public function actionUpname()
    {
        $post = \yii::$app->request->post();
        $name = $post['newyhm'];
        $id = $post['usid'];
        $res = $this->Upname($name,$id);
        return $res;
    }
    /***    修改用户电话号码     */
    public function actionUptel()
    {
        $post = \yii::$app->request->post();
        $tel = $post['newtel'];
        $id = $post['usid'];
        $res = $this->Uptel($tel,$id);
        return $res;
    }
    //修改密码
    public function actionPassword()
    {
        $info = $this->init();
        return $this->render('password');
    }
    //绑定班级
    public function actionAddclass(){
        $this->actionIndex();
        $post = \yii::$app->request->post();
        $at = "";
        if(!empty($post)){
            $openid = $post["openid"];
            $cid = isset($post['cid'])?$post['cid']:"0";
            $role = $post['role'];
            $roles = $post['roles'];
//            $model = WpIschoolTeaclass::find()->where(['openid'=>$openid,'cid'=>$cid,'role'=>$role])->all();
                $model = WpIschoolTeaclass::find()->where(['and',"ispass != 'n'",['cid'=>$cid],['role'=>$role]])->all();
                if( $roles==1 && !empty($model)){
                    $at = 3;
                } else {
                    $model = new WpIschoolTeaclass();
                    $model->openid = $openid;
                    $model->cid = $cid;
                    $model->role = $role;
                    $model->tname = $this->userinfo[0]['name'];
                    $model->school = $post["school"];
                    $model->sid = $post["sid"];
                    $model->class = $post["class"];
                    $model->ctime = time();
                    $model->ispass = 0;
                    $model->tel = $this->userinfo[0]['tel'];
                    $model->uid = $this->getUid($openid);
                    $res = $model->save(false);
                    $newRole = $post["school"].$post["class"].$role;
                if($res){
                    $at = 5;
                    $title = "教师待审核信息";
                    $des = $this->userinfo[0]['name'] . "申请成为" . $newRole . "，请在学校管理页面进行审核。";
                    $where = "";
                    $where["rid"] = 1;
                    $where["sid"] = $post["sid"];
                    $er = WpIschoolUserRole::find()->select('openid')->where($where)->asArray()->all();
                    $data['pic_url'] =  $this->getSchoolPic($post["sid"]);
                    foreach ($er as $v) {
                        SendMsg::sendSHMsgToPa($v["openid"], $title, $des,"",$data['pic_url']);
                    }
                }else{
                    $at = 2;
                }
             }
        }
        return $at;
    }
    //取消绑定
    public function actionDelclass()
    {
        $this->actionIndex();
        $tel = $this->userinfo['0']['tel'];
        $id = \Yii::$app->request->get('id');//get获取参数
        $cid = \Yii::$app->request->get('cid');//get获取参数
        $model = WpIschoolTeaclass::findOne(['id'=>$id]);
        $models = paUser::findOne(['tel'=>$tel,'shenfen'=>'tea']);
        $models->last_cid = Null;
        if($cid == $this->userinfo['0']['last_cid']){
            $transaction = \yii::$app->db->beginTransaction();
            try{
                if( $model->delete(false) && $models->save(false))
                {
                    $res = 1;
                    $transaction->commit();
                }else {
                    $res = 0;
                    $transaction->rollBack();
                }
            }catch (\Exception $e)
            {
                $transaction->rollBack();
            }
        }else{
            if( $model->delete(false)){
                $res = 1;
            }
        }
        if ($res ==1) {
            \Yii::$app->session->setFlash("info", "删除成功");
        }else{
            \Yii::$app->session->setFlash("info", "删除失败");
        }
        return $this->redirect(['teacher/index']);
    }

    //文档管理
    public  function actionWdgl()
    {
        $type= $_GET['type'];
        if(!empty($type)){
            $this->actionIndex();
            $userinfo = $this->userinfo;
            $name = $userinfo[0]['name'];
            $openid = $userinfo[0]['openid'];
            $sid = $userinfo[0]['last_sid']?:"";
            $cid = $userinfo[0]['last_cid']?:"";
            if(empty($cid)){
                echo "<script>alert('您还没有绑定班级，请先绑定班级！');window.location='/teacher/index';</script>";
            }
            $grade_id = $this->getGradeidbycid($cid);   //年级ID
            $model = new UploadForm();
            if (\Yii::$app->request->isPost)
            {
                $model->file = UploadedFile::getInstance($model, 'file');
                if(empty($model->file)){
                    echo "<script>alert('请选择您要上传的文件！');window.location='/teacher/wdgl?type=".$type."';</script>";
                    exit();
                }
                $title = !empty($_POST['UploadForm']['title'])?$_POST['UploadForm']['title']:$model->file->baseName; //图片原始名
                if ($model->file && $model->validate()) {
                    if(!file_exists('ischool/uploads/wdgl/'.date('y/m/d',time()))){
                        mkdir('ischool/uploads/wdgl/'.date('y/m/d',time()),0777,true);
                    }
                    $picname = $this->create_random_string(13);
//                $model->file->saveAs('ischool/uploads/picture/'.date('y/m/d/',time()). $model->file->baseName . '.' . $model->file->extension);
//                var_dump($model->file->baseName);exit();
//                $picurl = '/ischool/uploads/picture/'.date('y/m/d/',time()). $model->file->baseName . '.' . $model->file->extension;  //图片原始名
                    $picurl = '/ischool/uploads/wdgl/'.date('y/m/d/',time()).$model->file->baseName. '.' . $model->file->extension;
//                    $picurl = '/ischool/uploads/wdgl/'.date('y/m/d/',time()).$picname. '.' . $model->file->extension;//图片重命名
                    $sql ="insert into attachment(openid,create_time,sid,title,url,grade_id,type,name) values(:openid,:create_time,:sid,:title,:url,:grade_id,:type,:name)";
                    $res = \Yii::$app->db->createCommand($sql,[':openid'=>$openid,':create_time'=>time(),':sid'=>$sid,':title'=>$title,':url'=>$picurl,':grade_id'=>$grade_id,':type'=>$type,':name'=>$name])->execute();
                    if($res>0 || $res===0){
                        $model->file->saveAs('ischool/uploads/wdgl/'.date('y/m/d/',time()). $model->file->baseName. '.' . $model->file->extension);
                        echo "<script>alert('上传成功！');window.location='/teacher/wdgl?type=".$type."';</script>";
                    }else{
                        echo "<script>alert('上传失败！');window.location='/teacher/wdgl';</script>";
                    }
                }
            }
            if(!empty($grade_id) && !empty($sid)){
                $inboxlist = $this->getwdgllist($sid, $grade_id,$type);    //收件人接受信息列表
                $info['pages'] = $inboxlist['pages'];
                $info['inboxlist'] = $inboxlist['dataprovider'];
                $outboxlist = $this->getwdgltlist($openid, $sid,$type);    //已发信息列表
                $info['pageso'] = $outboxlist['pages'];
                $info['outboxlist'] = $outboxlist['dataprovider'];
            }

            return $this->render('wdgl',[
                'info'=>$info,
                'model'=>$model
            ]);
        }else{
            return $this->redirect("/site/error");
        }

    }


    //获取幻灯片信息通过学校ID
    public function getAllCarosBySid($sid,$uid){
        $sql = "select * from attachment where sid = :sid and openid = :uid order BY id DESC";
        $res = \Yii::$app->db->createCommand($sql,[':sid'=>$sid,':uid'=>$uid])->queryAll();
        return $res;
    }

    /**
     *学生列表
     **/
    public function getBinds()
    {
        /**
         * 第一个参数为要关联的字表模型类名称，
         *第二个参数指定 通过子表的 customer_id 去关联主表的 id 字段
         */
        return $this->hasMany(Pastudent::className(), ['stu_id' => 'id']);
    }

    public function actionChild()
    {
        $this->actionIndex();
        $cid = $this->lastcid;
        $info['openid'] = $this->userinfo[0]['openid'];
        $stuname = \Yii::$app->request->post("stuname");//搜索提交的学生姓名
        if(!empty($stuname)){
            $sql = 'select s.*,p.isqqtel from wp_ischool_student s left join wp_ischool_pastudent p ON s.id= p.stu_id and p.openid is NOT null where s.cid=:cid and s.name=:name group by s.id';
            $info['info'] = \Yii::$app->db->createCommand($sql,[':cid'=>$cid,':name'=>$stuname])->queryAll();
//            $info['info'] = WpIschoolStudent::find()->where(['cid'=>$cid,'name'=>$stuname])->all();
        }else{
            $sql = 'select s.*,p.isqqtel from wp_ischool_student s left join wp_ischool_pastudent p ON s.id= p.stu_id and p.openid is NOT null where s.cid=:cid group by s.id ORDER BY p.isqqtel ASC';
            $info['info'] = \Yii::$app->db->createCommand($sql,[':cid'=>$cid])->queryAll();
//            echo "<pre>";
//            var_dump($info['info']);exit;
//            $info['info'] = WpIschoolStudent::find()->joinWith('binds')->where(['cid'=>$cid])->all();
        }
        $info['cid']  = $cid;
//        $info['sid'] = $this->lastsid;
        $schoolinfo = $this->getSchoolidbycid($cid);
        $info['sid']  = !empty($schoolinfo[0]['sid'])?$schoolinfo[0]['sid']:1;             //学校ID
        return $this->render('child',[
            "info" => $info
        ]);
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
            return $this->opSucceed("/teacher/child");
        }else{
            return $this->opFailed("/teacher/child");
        }
    }

    //学生联系人
    public function actionLxr(){
        $stuid=\yii::$app->request->post("id");
        $model = Pastudent::find()->where(['stu_id'=>$stuid,'ispass'=>'y'])->select("Relation,name,tel,email")->asArray()->all();
        return json_encode($model);
    }
    /*
    教师给学生请假操作
    */
    public function actionDoleave()
    {
        $post = \yii::$app->request->post();
        $res=WpIschoolUser::findOne(['openid'=>$post['openid']]);
        $res = \Yii::$app->db->createCommand()->insert('wp_ischool_stu_leave',array(
            'stu_id' => $post['stuidid'],
            'begin_time' => strtotime($post['statime']),
            'stop_time' => strtotime($post['endtime']),
            'openid' => $post['openid'],
            'ctime' => time(),
            'flag' => 1,
            'reason' => $post['reason'],
            'uid'=>$res['id'],
        ))->execute();
        if($res){
            return $this->opSucceed("/teacher/child");
        }else{
            return $this->opFailed("/teacher/child");
        }
    }

    //学生请假管理
    public function actionLeave()
    {
        $this->actionIndex();
        $openid = $this->userinfo[0]['openid'];
        $post = \yii::$app->request->post();
        if(!empty($post)){
            $post = \yii::$app->request->post();
            if(!empty($post["type"]) && !empty($post["id"]))
            {
                if($post["type"] =="jujue" || $post["type"] =="xiaojia"){
                    $flag =0;
                }else if($post["type"] =="pizhun"){
                    $flag =1;
                }
                $stu_leave=WpIschoolStuLeave::find()->where(['id'=>$post["id"]])->asArray()->all();
                $stuid = $stu_leave[0]['stu_id'];
                $popenid = $this->getAllopenidbystuid($stuid);
                $oktime = time();
                $res=WpIschoolUser::findOne(['openid'=>$openid]);
                $okuid=$res['id'];
                $del_sql = "update wp_ischool_stu_leave set flag=:flag,oktime='$oktime',okopenid='$openid',okuid='$okuid' WHERE id=:id";
                $res = \Yii::$app->db->createCommand($del_sql,[':id'=>$post['id'],':flag'=>$flag])->execute();
                if(!empty($res)){
                    if( $post["type"] !="xiaojia"){
                        if($flag == 1){
                            $msg = "您发送的请假已经通过审批";
                        }else if($flag == 0 && $post["type"] =="jujue"){
                            $msg = "您发送的请假请求被拒绝。若有问题请重新申请或联系班主任";
                        }
                        $sidinfo = $this->getSchoolidbystuid($stuid);
                        $sid = $sidinfo[0]['sid'];
                        $this->doSendLeavep($popenid,$msg,$sid);
                    }
                    return json_encode(['status'=>0]);
                }else{
                    return json_encode(['status'=>1]);
                }
            }
        }

        $cid = $this->lastcid;
        $role = WpIschoolTeaclass::find()->where(['cid'=>$cid,'openid'=>$openid,'role'=>"班主任"])->asArray()->all();
        if(!empty($role)){
            $info['rolesf'] = "y";
        }else{
            $info['rolesf'] = "n";
        }
        $lev_sql = "select t1.id,t1.begin_time,t1.stop_time,t1.reason,t2.name,t2.cardid,t2.stuno2 from wp_ischool_stu_leave t1 left join wp_ischool_student t2 on t1.stu_id=t2.id where t2.cid=:cid and t1.flag=1";     //已请假学生
        $info['yqj'] = \Yii::$app->db->createCommand($lev_sql,[':cid'=>$cid])->queryAll();
        $lev_sql = "select t1.id,t1.begin_time,t1.stop_time,t1.reason,t2.name,t2.cardid,t2.stuno2 from wp_ischool_stu_leave t1 left join wp_ischool_student t2 on t1.stu_id=t2.id where t2.cid=:cid and t1.flag=2";     //请假待审核学生
        $info['dsh'] = \Yii::$app->db->createCommand($lev_sql,[':cid'=>$cid])->queryAll();
        return $this->render('leave',[
            'info'=>$info,
        ]);
    }

    //教师批准或拒绝请假推送信息
    public function doSendLeavep($info,$msg,$sid){
        $data['pic_url'] =  $this->getSchoolPic($sid);
        $title = "学生请假通知信息";
        $final = "";
        foreach ($info as $v) {
            $final = SendMsg::sendSHMsgToPa($v,"请假信息",$msg,"",$data['pic_url']);
        }
        return $final;
    }

    //学生成绩管理
    public function actionManage()
    {
        $this->actionIndex();
        $info['cid']=$this->lastcid;
        $cid = $this->lastcid;
        $sinfo = $this->getSchoolidbycid($cid);
        $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
//        $sid = $this->lastsid;
        $info['sid']= $sid;
        $info['openid'] = $this->userinfo[0]['openid'];
        $nowyear = date("Y");
        $nowmonth = date("m");
        $xuenian = array();
        if($nowmonth > 7){
            $xuenian[] = array('year'=>$nowyear."-".($nowyear+1)."学年");
        }else{
            $xuenian[] = array('year'=>($nowyear-1)."-".$nowyear."学年");
        }
        $info['xuenian'] = $xuenian;
        $sql="SELECT * FROM `wp_ischool_chengjidan_type` WHERE ( `type` = 'gz' )";
        $info['type'] = \Yii::$app->db->createCommand($sql)->queryAll();
        $sql_cjd="SELECT * FROM `wp_ischool_class_chengjidan` WHERE ( `cid` = :cid ) ORDER BY id ASC";
        $info['cjdxx'] = \Yii::$app->db->createCommand($sql_cjd,[':cid'=>$info['cid']])->queryAll();
        return $this->render('manage',[
            'info'=>$info
        ]);
    }
    //上传成绩单
    public function actionUploadcjd(){
        $post = \Yii::$app->request->post();
        $this->initExcel();
        $sid = $post['sid'];
        $cid = $post['cid'];
        $examname = $post['exam'];
        $isopen = empty($post['isopen'])?'n':$post['isopen'];
        $openid = $post['openid'];
        $sql="SELECT id FROM `wp_ischool_chengjidan` WHERE (`name` =:name and `sid` =:sid)";   //成绩单ID查询
        $cjdid = \Yii::$app->db->createCommand($sql,[':name'=>$examname,'sid'=>$sid])->queryAll();
        if(empty($cjdid)){
            $cjdid = \Yii::$app->db->createCommand()->insert('wp_ischool_chengjidan',array(
                'name' => $examname,
                'sid' => $sid,
                'ctime' => time()
            ))->execute();
            $cjdid = \Yii::$app->db->getLastInsertID();
        }else{
            $cjdid = $cjdid[0]['id'];
        }
        $sql_class="SELECT * FROM `wp_ischool_class_chengjidan` WHERE ( `cid` =:cid and `cjdid` =:cjdid)"; //班级成绩单信息查询
        $class_cj = \Yii::$app->db->createCommand($sql_class,[':cid'=>$cid,'cjdid'=>$cjdid])->queryAll();
        if(empty($class_cj)){
            $excel_cont = $this->checkChengjiExcel($cid,$this->source_data);
            if($excel_cont['retcode']==0)
            {
                $res = \Yii::$app->db->createCommand()->insert('wp_ischool_class_chengjidan',array(
                    'cid' => $cid,
                    'cjdid' => $cjdid,
                    'cjdname' => $examname,
                    'isopen' => $isopen,
                    'creater' => $openid,
                    'ctime' => time(),
                ))->execute();
                $data = array('data'=>$excel_cont['retdata'],'cid'=>$cid,'cjdid'=>$cjdid,'examname'=>$examname,'openid'=>$openid);
                $excel_cont =  $this->sendRecordToParent1($data);
                $result = array("retcode"=>0,"retmsg"=>"发送成功");
            }else{
                //有错误
                $result = array("retcode"=>-1,"retmsg"=>"发送失败，错误信息为".$excel_cont['retdata']);
            }
        }else{
            //不能重复上传
            $result = array("retcode"=>-1,"retmsg"=>"该班级已有名为".$examname."的成绩单，不能重复上传");
        }
        echo "<script>parent.uploadCJDCallbak(".$result['retcode'].",'".$result['retmsg']."')</script>";
    }

//删除成绩单
    public function actionDelcjd()
    {
        $info = $this->init();
        $post = \yii::$app->request->post();
        $cjdid = $post['cjdxxid'];
        $sql_chengjidan = "delete from wp_ischool_chengjidan WHERE id=:id";
        $sql_class_chengjidan = "delete from wp_ischool_class_chengjidan WHERE cjdid=:cjdid and cid=:cid";
        $sql_chengji = "delete from wp_ischool_chengji WHERE cjdid=:cjdid and cid=:cid";
        $connect = \Yii::$app->db;
        $transaction = \yii::$app->db->beginTransaction();
        try  {
            if ($connect->createCommand($sql_chengjidan,[':id'=>$cjdid])->execute()  && $connect->createCommand($sql_class_chengjidan,[':cjdid'=>$cjdid,':cid'=>$this->lastcid])->execute() && $connect->createCommand($sql_chengji,[':cjdid'=>$cjdid,':cid'=>$this->lastcid])->execute())
            {
                $transaction->commit();
                echo "<script>alert('删除成功！');window.location='/teacher/manage';</script>";
            } else {
                $transaction->rollBack();
                echo "<script>alert('删除失败,请重试！');window.location='/teacher/manage';</script>";
            }
        } catch (Exception $e)
        {
            $transaction->rollBack();
            echo "<script>alert('删除失败,请重试！');window.location='/teacher/manage';</script>";
        }
    }

    public function sendRecordToParent1($_info)
    {
        $this->actionIndex();
        $sender = $this->userinfo[0]['name'];
        $data = $_info['data'];
        $cid = $_info['cid'];
        $cjdid = $_info['cjdid'];
        $examName = $_info['examname'];
        $length = count($data);
        $ctime = time();
        $sid = $this->getSchoolidbycid($cid);
        $sid = $sid = !empty($sid[0]['sid'])?$sid[0]['sid']:1;
        if($length > 1){
            $subject = $data[0];
            $cols = count($subject);
            $nameIndex = array_keys($subject,"姓名",false)[0];
            for($i=1;$i<$length;$i++) {
                $record = $data[$i];
                $stuname = explode("-", $record[$nameIndex]);
                $stuid = $stuname[1];
                $stuname = $stuname[0];
                $content = "家长您好,".$stuname."同学".$examName."成绩如下:\n\n";
                $sql = "insert into wp_ischool_chengji(stuid,stuname,cid,cjdid,kmid,kmname,score,ctime) values ";
                for($j = 0; $j < $cols; $j++){
                    if($j != $nameIndex){  //科目列
                        $kemu = explode("-",$subject[$j]);
                        $kmid = $kemu[1];
                        $kemu = $kemu[0];
                        $content .= $kemu.":".$record[$j]."\n\n";
                        $sql .= "(".$stuid.",'".$stuname."',".$cid.",".$cjdid.",".$kmid.",'".$kemu."',".$record[$j].",".$ctime."),";
                    }
                }
                $content .= "来自".$sender."老师\n";
                $sql = substr($sql,0,-1);
                $c = \Yii::$app->db->createCommand($sql)->execute();
                //发送
                if($c){
                    $picurl = $this->getSchoolPic($sid);
                    $paropenids = $this->getAllopenidbystuid($stuid);
                    $this->doSendRecord($paropenids,$content,$picurl);
                }
            }
        }
    }

    //执行成绩单发送
    private function doSendRecord($tos,$content,$picurl){
        $title = "学生成绩通知信息";
        $final = "";
        $url="";
        foreach($tos as $to) {
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
        $sql = "select id,name from wp_ischool_student WHERE cid=:cid and name = :name order BY name ASC";
        return \Yii::$app->db->createCommand($sql,[':cid'=>$cid,':name'=>$name])->queryAll();
    }
    private function getAllSysSubject(){
        $sql = "select id,name from wp_ischool_chengji_kemu where TYPE ='sys' ORDER BY sort ASC ";
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }
    private function assignPage($errorinfo)
    {
        return $this->render("page",[
            "errorinfo"=>$errorinfo
        ]);
    }
    public function actionQuerychengji(){
        $post = \yii::$app->request->post();
        $cjdid = $post['cjdid'];
        $cid = $post['cid'];
        // $cjd_sql = "select stuid,stuname,kmname,score from wp_ischool_chengji where cjdid=:cjdid and cid=:cid ORDER BY stuid asc,kmid asc"; 
        $cjd_sql = "SELECT c.stuid,c.stuname,c.kmname,c.score,t.sort FROM wp_ischool_chengji c left join wp_ischool_chengji_kemu t on c.kmid=t.id WHERE c.cid=:cid AND c.cjdid=:cjdid order by c.stuid asc,t.sort asc";
        $cjd = \Yii::$app->db->createCommand($cjd_sql,[':cjdid'=>$cjdid,":cid"=>$cid])->queryAll();
        $cjd = $this->scriptCjd($cjd);
        $title = $this->scriptTitle($cid,$cjdid);
        $result[] = $title;
        $result[] = $cjd;
        return json_encode($result);
    }
    //拼凑成绩单标题
    private function scriptTitle($cid,$cjdid){
        // $sql = "select distinct kmname from wp_ischool_chengji where cjdid=:cjdid order by kmid asc";
        $sql = "SELECT distinct kmname FROM wp_ischool_chengji c left join wp_ischool_chengji_kemu t on c.kmid=t.id WHERE c.cid=:cid AND c.cjdid=:cjdid order by c.stuid asc,t.sort asc";
        $title=\Yii::$app->db->createCommand($sql,[':cid'=>$cid,':cjdid'=>$cjdid])->queryAll();
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

    //家校沟通
    public function actionHomeschool()
    {
        $this->actionIndex();
        $cid = $this->lastcid;
        $info['students'] = !empty($cid)?$this->getAllstubycid($cid):"";
        $sql = "select * from wp_ischool_class where id = :id";
        $info['class'] = !empty($cid)?\Yii::$app->db->createCommand($sql,[':id'=>$cid])->queryAll():"";
        $type = 0;
        if(!empty($cid)) {
            $inboxlist = $this->getinboxlist($this->userinfo[0]['openid'], $type);    //收件人接受信息列表
            $info['pages'] = $inboxlist['pages'];
            $info['inboxlist'] = $inboxlist['dataprovider'];
            $outboxlist = $this->getoutboxlist($this->userinfo[0]['openid'], $type);    //已发信息列表
            $info['pageso'] = $outboxlist['pages'];
            $info['outboxlist'] = $outboxlist['dataprovider'];
        }
        return $this->render('homeschool', $info);
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
                return $this->redirect("/teacher/$url?type=shouxin");
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
                return $this->redirect("/teacher/$url?type=yifa");
            }
        }
    }

    public function actionInbox(){
        $this->actionIndex();
        $userinfo =$this->userinfo;
        $teaopenid = $userinfo[0]['openid'];            //当前教师的openid
        $post = \yii::$app->request->post();
        $stuid =$post['stuid'];
        $stuid=explode(",",$stuid); //字符串转数组
        $title = $post['title'];                //发件箱标题
        $totitle = "来自".$userinfo[0]['name']."的消息";      //收件箱标题
        $content = $post['content'];            //内容
        //正则去掉html标签
        if(!empty($title)){
            $preg = "/<\/?[^>]+>/i";
            $title = preg_replace($preg,'',$title);
            $des = preg_replace($preg,'',$content);
            $des = str_replace("&nbsp;","",$des);
//            $content = preg_replace($preg,'',$content);
        }
        $cid = $this->lastcid;
        $sinfo = $this->getSchoolidbycid($cid);
        $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
        $ur[0]=URL_PATH."/tongzhi/index?openid=";
        $ur[1]="&sid=".$sid;
        $data['url']   = $ur;       //图文跳转链接
        $data['title'] = $totitle;  //图文消息标题
        $data['content'] = $des;    //待入库的原始消息
        $data['pic_url'] =  $this->getSchoolPic($sid);
        $ctime = time();
        $msgType = $post['type'];
        if($post['type'] =="ly"){
            $openid = $this->getAllopenidbystuid($stuid);   //获取班级或者学生对应的家长的openid
        }else if($post['type'] =="gg"){
            $openid = $this->getAllopenidbycid($stuid);
        }else if($post['type'] =="hf"){
            $openid =array($post['stuid']);
        }
        $out_uid=$this->getUid($teaopenid);
        //发送前先存入发送箱
        $sql = "INSERT INTO `wp_ischool_outbox` ( `content` , `outopenid`, `ctime` , `title` , `type`, `out_uid` ) VALUES( :content, :outopenid, :ctime,:title, 0 ,:out_uid)";
        $outbox = \Yii::$app->db->createCommand($sql,[':content'=>$content,':outopenid'=>$teaopenid,':ctime'=>time(),':title'=>$title,':out_uid'=>$out_uid])->execute();
        if(!empty($openid)){
            $transaction = \Yii::$app->db->beginTransaction();       //事务开始
            try{
                $sql_inbox = "INSERT INTO `wp_ischool_inbox` ( `content` , `outopenid` , `inopenid` , `ctime` , `title` , `type`,`out_uid` , `in_uid` ) VALUES";
                foreach($openid as $inopenid){
                    $in_uid=$this->getUid($inopenid);
                    $sql_inbox .= "('$content', '$teaopenid','$inopenid',$ctime,'$totitle',0,'$out_uid','$in_uid'),";
                }
                $sql_inbox = substr($sql_inbox,0,-1);
                $inbox = \Yii::$app->db->createCommand($sql_inbox)->execute();
                $transaction->commit();

                $result = SendMsg::muiltPostMsg($openid,$data);
                if($result->errcode == 0){
                    $this->setMsgNum($msgType,$openid);
                }
                return json_encode(['status'=>0]);
            }catch(Exception $e){     // 如果有一条插入失败，则会抛出异常
                $transaction->rollBack();
                return json_encode(['status'=>1]);
            }
        }else{
            return json_encode(['status'=>2]);
        }
    }

    //平安通知
    public function actionSecurity(){
        $this->actionChild();
        $cid = $this->lastcid;
        $stuinfo = $this->getAllstubycid($cid);
        $stuid = array();
        foreach($stuinfo as $k=>$v){
            $stuid[] = $v['id'];
        }
        $stuname =$this->getStuname($cid);              //学生名字
        $begintm = $this->getBeginTimestamp("today");       //获得今天的时间戳
        $dklist = $this->getdklist($stuid,$begintm);        //今天的打卡信息列表
        $pagination = $dklist['pages'];
        $model = $dklist['model'];
        $beginwk = $this->getBeginTimestamp("week");       //获得本周的时间戳
        $dklistwk = $this->getdklist($stuid,$beginwk);        //本周的打卡信息列表
        $modelwk = $dklistwk['model'];
        $paginationwk = $dklistwk['pages'];
        $beginmh = $this->getBeginTimestamp("month");       //获得月的时间戳
        $dklistmh = $this->getdklist($stuid,$beginmh);        //本周的打卡信息列表

        $modelmh = $dklistmh['model'];
        $paginationmh = $dklistmh['pages'];
        return $this->render("security",[
            'pages'=>$pagination,
            'model'=>$model,
            'modelwk'=>$modelwk,
            'pageswk'=>$paginationwk,
            'modelmh'=>$modelmh,
            'pagesmh'=>$paginationmh,
            'info'=>$stuname
        ]);
    }


//考勤导出
    public function actionExport()
    {
        $this->actionIndex();
        $cid = $this->lastcid;
        $stuinfo = $this->getAllstubycid($cid);
        $stuid = array();
        foreach($stuinfo as $k=>$v){
            $stuid[] = $v['id'];
        }
        $stuname = $this->getStuname($cid);
        $model = new WpIschoolSafecard();

        $export_columns =
            [
                [
                    "attribute"=>"stuid",
                    'value' => function($model) use ($stuname) {
                        return isset($stuname[$model->stuid])?$stuname[$model->stuid]:"默认姓名";
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
        }else{
            $begin = $this->getBeginTimestamp("month");       //获得本月的时间戳
        }
        $info = $model->export($stuid,$begin);
        \yii::trace($info);
        \moonland\phpexcel\Excel::export([
            'models' => $info,
            'columns' => $export_columns,
            'fileName' => "kaoqin.xlsx"
        ]);
    }


    //导出考勤汇总信息
    public function actionExportkqhz(){
        $this->actionIndex();
        $cid = $this->lastcid;
        $post = \yii::$app->request->post();

        if(!empty($post)){
            if($post['type'] == "today"){
                $begin = $this->getBeginTimestamp("today");       //获得今天的时间戳
            }else if($post['type'] == "week"){
                $begin = $this->getBeginTimestamp("week");       //获得本周的时间戳
            }else{
                $begin = $this->getBeginTimestamp("month");       //获得本月的时间戳
            }
            $sql = "SELECT   b.class 班级,count(info) 总使用数,
                        COUNT(CASE WHEN info LIKE '%进校%' THEN 1
                               ELSE NULL
                               END) 进校总数,
                        COUNT(CASE WHEN info LIKE '%出校%' THEN 2
                               ELSE NULL
                               END) 出校总数,
                        COUNT(CASE WHEN info LIKE '%进宿舍%' THEN 3
                               ELSE NULL
                               END) 进宿舍总数,
                       COUNT(CASE WHEN info LIKE '%出宿舍%' THEN 4
                               ELSE NULL
                               END) 出宿舍总数
        FROM wp_ischool_safecard a LEFT JOIN wp_ischool_student b ON a.stuid=b.id AND b.cid=:cid and a.ctime >:begin GROUP BY b.class order by b.cid asc";
            $info = \Yii::$app->db->createCommand($sql,[':cid'=>$cid,':begin'=>$begin])->queryAll();
            unset($info[0]);
            $head = array('班级','总使用数','进校总数','出校总数','进宿舍总数','出宿舍总数');
            array_unshift($info,$head);
            $excel = new \Excelses();
            $excel->download($info, 'kaoqinhz');
        }
    }
//学校微官网
    public function actionSchoolwebsite()
    {
        $this->actionIndex();
        $cid = $this->lastcid;
        $sinfo = $this->getSchoolidbycid($cid);
        if(empty($cid)){
            $sid = $this->lastsid;
        }else{
            $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
        }
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
        return $this->render('schoolwebsite',$info);
    }

    //学校概况信息详情页
    public  function  actionWebsitemin(){
        $id = $_GET['id'];
        $sql = "select * from wp_ischool_hpage_colcontent WHERE id=:id";
        $info['info'] = \Yii::$app->db->createCommand($sql,[':id' => $id])->queryAll();
        return $this->render("websitemin",$info);
    }

    //公告详情页
    public function actionSchoolmin(){
        if(!empty($_GET['id'])){
            $id = $_GET['id'];
            $info['info'] =  gonggao::find()->where(['id'=>$id])->asArray()->all();
            return $this->render("schoolmin",$info);
        }else{
            $this->redirect("/teacher/notice");
        }
    }
    //动态详情页
    public function actionDongtaimin(){
        $id = $_GET['id'];
        $info['info'] =  News::find()->where(['id'=>$id])->asArray()->all();
        return $this->render("dongtaimin",$info);
    }
//校内公告列表
    public function actionSchoolnotice(){
        $this->actionIndex();
        $cid = $this->lastcid;
        $sinfo = $this->getSchoolidbycid($cid);
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
        return $this->render("schoolnotice",$info);
    }
//校内动态列表
    public function actionClassdynamics(){
        $this->actionIndex();
        $cid = $this->lastcid;
        $sinfo = $this->getSchoolidbycid($cid);
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
        return $this->render("classdynamics",$info);
    }

    //内部交流
    public function actionInternalcom()
    {
        $this->actionIndex();
        $cid = $this->lastcid;
        $sinfo = $this->getSchoolidbycid($cid);
        $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
        $name ="";
        if(\Yii::$app->request->isPost){
            $name = \Yii::$app->request->post('name');
            $teachers = $this->getAllteat($sid,$name);
            return json_encode($teachers);
        }
        $info['teachers'] = !empty($cid)?$this->getAllteat($sid,$name):"";
        $type = 1;
        if(!empty($cid)){
            $inboxlist = $this->getinboxlist($this->userinfo[0]['openid'], $type);    //收件人接受信息列表
            $info['pages'] = $inboxlist['pages'];
            $info['inboxlist'] = $inboxlist['dataprovider'];
            $outboxlist = $this->getoutboxlist($this->userinfo[0]['openid'], $type);    //已发信息列表
            $info['pageso'] = $outboxlist['pages'];
            $info['outboxlist'] = $outboxlist['dataprovider'];
        }
        return $this->render('internalcom', $info);
    }
    //执行校内内部交流信息发送
    public function actionDointer(){
        $this->actionIndex();
        $userinfo =$this->userinfo;
        $teaopenid = $userinfo[0]['openid'];            //当前教师的openid
        $post = \yii::$app->request->post();
        if(!empty($post)){
            if($post['type'] =="ly"){
                $openids = $post['openids'];   //获取提交的教师openid信息
                $openid=explode(",",$openids); //字符串转数组
            }else if($post['type'] =="hf"){
                $openid =array($post['openids']);
            }
            $title = $post['title'];                //发件箱标题
            $totitle = "来自".$userinfo[0]['name']."的消息";      //收件箱标题
            $content = $post['content'];            //内容
            //正则去掉html标签
            if(!empty($content)){
                $preg = "/<\/?[^>]+>/i";
                $title = preg_replace($preg,'',$title);
                $des = preg_replace($preg,'',$content);
                $des = str_replace("&nbsp;","",$des);
            }
            $ctime = time();
            $cid = $this->lastcid;
            $sinfo = $this->getSchoolidbycid($cid);
            $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
            $ur[0]=URL_PATH."/exchange/index?openid=";
            $ur[1]="&sid=".$sid;
            $data['url']   = $ur;       //图文跳转链接
            $data['title'] = $totitle;  //图文消息标题
            $data['content'] = $des;    //待入库的原始消息
            $data['pic_url'] =  $this->getSchoolPic($sid);
            $out_uid=$this->getUid($teaopenid);
            //发送前先存入发送箱
            $sql = "INSERT INTO `wp_ischool_outbox` ( `content` , `outopenid`, `ctime` , `title` , `type` , `out_uid`  ) VALUES( :content, :outopenid, :ctime,:title, 1,:out_uid)";
            $outbox = \Yii::$app->db->createCommand($sql,[':content'=>$content,':outopenid'=>$teaopenid,':ctime'=>time(),':title'=>$title,':out_uid'=>$out_uid])->execute();
            if(!empty($openid)){
                $transaction = \Yii::$app->db->beginTransaction();       //事务开始
                try{
                    $sql_inbox = "INSERT INTO `wp_ischool_inbox` ( `content` , `outopenid` , `inopenid` , `ctime` , `title` , `type`, `out_uid`, `in_uid` ) VALUES";
                    foreach($openid as $inopenid){
                        $in_uid=$this->getUid($inopenid);
                        $sql_inbox .= "('$content', '$teaopenid','$inopenid',$ctime,'$totitle',1,'$out_uid','$in_uid'),";
                    }
                    $sql_inbox = substr($sql_inbox,0,-1);
                    $inbox = \Yii::$app->db->createCommand($sql_inbox)->execute();
                    $transaction->commit();
                    $result = SendMsg::muiltPostMsg($openid,$data);
                    return json_encode(['status'=>0]);
                }catch(Exception $e){     // 如果有一条插入失败，则会抛出异常
                    $transaction->rollBack();
                    return json_encode(['status'=>1]);
                }
            }
        }
    }


    //发布公告
    public function actionFabugg()
    {
        $this->actionIndex();
        $cid = $this->lastcid;
        $sinfo = $this->getSchoolidbycid($cid);
        $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
        if(\Yii::$app->request->isGet)
        {
            $id = \Yii::$app->request->get('id');
            if(!empty($id)){
                $info['info'] =  gonggao::find()->where(['id'=>$id])->asArray()->all();
                return $this->render("fabugg",$info);
            }
        }
        if(\Yii::$app->request->isPost){
            $post = \Yii::$app->request->post();
            if(!empty($post['id'])){
                $model = gonggao::findOne(['id'=>$post['id']]);
            }else{
                $model = new gonggao();
            }
            $model->title = $post['title'];
            $model->content = $post['content'];
            $model->sid = $sid;
            $model->ctime = time();
            $model->name = $this->userinfo[0]['name'];
            $res = $model->save(false);
            if($res){
                return 0;
            }else{
                return 1;
            }
        }
        return $this->render('fabugg');
    }

    //公告删除信息
    public function actionDelgonggao()
    {
        $id = \Yii::$app->request->get('id');
        if(!empty($id)){
            $res = $this->delGonggao($id);
            if($res){
//                \Yii::$app->getSession()->setFlash('success', '删除成功');
                return $this->redirect("/teacher/schoolnotice");
            }
        }
    }

    //发布动态
    public function actionFabudt()
    {
        $this->actionIndex();
        $cid = $this->lastcid;
        $sinfo = $this->getSchoolidbycid($cid);
        $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
        if(\Yii::$app->request->isGet)
        {
            $id = \Yii::$app->request->get('id');
            if(!empty($id)){
                $info['info'] =  News::find()->where(['id'=>$id])->asArray()->all();
                return $this->render("fabudt",$info);
            }
        }
        if(\Yii::$app->request->isPost){
            $post = \Yii::$app->request->post();
            if(!empty($post['id'])){
                $model = News::findOne(['id'=>$post['id']]);
            }else{
                $model = new News();
            }
            $model->title = $post['title'];
            $model->content = $post['content'];
            $model->sid = $sid;
            $model->ctime = time();
            $model->name = $this->userinfo[0]['name'];
            $model->openid = $this->userinfo[0]['openid'];
            $res = $model->save(false);
            if($res){
                return 0;
            }else{
                return 1;
            }
        }
        return $this->render('fabudt');
    }

    //动态删除信息
    public function actionDeldongtai()
    {
        $id = \Yii::$app->request->get('id' );
        if(!empty($id)){
            $res = $this->delDongtai($id);
            if($res){
//                \Yii::$app->getSession()->setFlash('success', '删除成功');
                return $this->redirect("/teacher/classdynamics");
            }
        }
    }

    //群组交流
    public function actionQunzu(){
        $type= $_GET['type'];
        if(!empty($type)){
            $this->actionIndex();
            $userinfo = $this->userinfo;
//        var_dump($userinfo);exit();
            $openid = $userinfo[0]['openid'];
            $sid = $userinfo[0]['last_sid']?:"";
            $cid = $userinfo[0]['last_cid']?:"";
            $grade_id = $this->getGradeidbycid($cid);   //年级ID
            if(!empty($grade_id) && !empty($sid)){
                $info['chengyuan'] = paUser::find()->select('name')->where(['and','sid'=>$sid,['like','label',$type]])->asArray()->all();
//                $inboxlist = $this->getqunzuinlist($sid, $grade_id,$type);
                $inboxlist = $this->getqunzuinlist($sid, $grade_id,$type);    //收件人接受信息列表
                $info['pages'] = $inboxlist['pages'];
                $info['inboxlist'] = $inboxlist['dataprovider'];
                $outboxlist = $this->getqunzuoutlist($openid, $sid,$type);    //已发信息列表
                $info['pageso'] = $outboxlist['pages'];
                $info['outboxlist'] = $outboxlist['dataprovider'];
            }
            return $this->render('qunzu',$info);
        }else{
            return $this->redirect("/site/error");
        }
    }

    //根据班级ID获取年级ID
    public  function  getGradeidbycid($cid){
        $sql = "select level from wp_ischool_class where id = :cid";
        $res = \Yii::$app->db->createCommand($sql,[':cid'=>$cid])->queryOne();
        return $res["level"];
    }

    //执行校内群组交流信息发送
    public function actionDointerqunzu(){
        $this->actionIndex();
        $userinfo = $this->userinfo;
        $openid = $userinfo[0]['openid'];
        $sid = $userinfo[0]['last_sid']?:"1";
        $cid = $userinfo[0]['last_cid']?:"";
        if(empty($cid)){
            return json_encode(['status'=>2]);
        }
        $title = $userinfo[0]['name'];                //发件箱标题
        $grade_id = $this->getGradeidbycid($cid);   //年级ID
        $post = \yii::$app->request->post();
        if(!empty($post)){
            $type = $post['type'];
            $totitle = "来自".$userinfo[0]['name']."的消息";      //收件箱标题
            $content = $post['content'];            //内容
            //正则去掉html标签
            if(!empty($content)){
                $preg = "/<\/?[^>]+>/i";
                $title = preg_replace($preg,'',$title);
                $des = preg_replace($preg,'',$content);
                $des = str_replace("&nbsp;","",$des);
            }
            $ctime = time();
//群组信息推送
            $ur[0]=URL_PATH."/group/index?openid=";
            $ur[1]="&sid=".$sid."&qunzu=".$type;
//            $ur[0]=URL_PATH."/group/index?openid=";
//            $ur[1]="&qunzu=".$type;
            $data['url']   = $ur;       //图文跳转链接
            $data['title'] = $totitle;  //图文消息标题
            $data['content'] = $des;    //待入库的原始消息
            $data['pic_url'] =  $this->getSchoolPic($sid);
            $qunzuopenid = $this->getAllqunzuopid($sid,$type);
//            $cid = $this->lastcid;
//            $sinfo = $this->getSchoolidbycid($cid);
//            $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
                    $sql_inbox = "INSERT INTO `wp_ischool_qunzu` ( `content`, `outopenid`, `grade_id`,`sid`, `ctime` , `title` , `type` ) VALUES (:content,:outopenid,:grade_id,:sid,:ctime,:title,:type)";
                    $inbox = \Yii::$app->db->createCommand($sql_inbox,[':content'=>$content,':outopenid'=>$openid,':grade_id'=>$grade_id,':sid'=>$sid,':ctime'=>$ctime,':title'=>$title,':type'=>$type])->execute();
                if($inbox){
                    $tos = array();
                    foreach($qunzuopenid as $newopid){
                        if($newopid['openid'] == $openid) continue;
                        $tos[] = $newopid['openid'];


                    }
                    $result = SendMsg::muiltPostMsg($tos,$data);
                    return json_encode(['status'=>0]);
                }else{
                    return json_encode(['status'=>1]);
                }
            }
        }

    //收件箱删除信息
    public function actionDelinwd()
    {
        $id = \Yii::$app->request->post('id');
        if(!empty($id)){
            $res = $this->delInwdx($id);
            if($res){
                return json_encode(['status'=>0]);
            }else{
                return json_encode(['status'=>1]);
            }
        }else{
            return json_encode(['status'=>2]);
        }
    }
    //群组交流收件箱删除信息
    public function actionDelqzjl()
    {
        $id = \Yii::$app->request->post('id');
        if(!empty($id)){
            $res = $this->delQunzjl($id);
            if($res){
                return json_encode(['status'=>0]);
            }else{
                return json_encode(['status'=>1]);
            }
        }else{
            return json_encode(['status'=>2]);
        }
    }

    //计划审批
    public function actionShenpi(){
        $flag = \yii::$app->request->post('flag');
        $type = \yii::$app->request->post('type');
        switch ($flag){
            case 0:
                $flags = "all";         //全部
                break;
            case 1:
                $flags = "spwc";         //审批完成
                break;
            case 2:
                $flags = "spz";         //审批中
                break;
            default:
                $flags = "all";         //全部
                break;
        }
        $flag=!empty($flag)?$flag:"all";
        $type=!empty($type)?$type:"xiexin";
//        var_dump($type);exit();
        $this->actionIndex();
        $userinfo = $this->userinfo;
        $tid = $userinfo[0]['id'];
        $lastcid= $userinfo[0]['last_cid']?:"";
        if(empty($lastcid)){
            echo "<script>alert('您还没有绑定班级，请先绑定班级！');window.location='/teacher/index';</script>";
        }
        $toname = $userinfo[0]['name'];
        $schoolinfo = $this->getSchoolidbycid($lastcid);
        $lastsid  = !empty($schoolinfo[0]['sid'])?$schoolinfo[0]['sid']:1;
        $models = $this->getspgllist($tid,$lastsid,$flag);        //获取已发送信息列表
        $search = new WpIschoolWorksh();
        $sjxmodels = $search->search($lastsid,$tid,$flag);    //获取审批管理已接收信息列表
        $users = $this->getUser();  //通过openid获取用户姓名信息

        $model = new UploadForm();
        $post = \Yii::$app->request->post();
        if (!empty($post['zhuti']) && !empty($post['descr']) && !empty($post['spdy'])){
            $title = $post['zhuti'];        //标题
            $content = $post['descr'];      //内容
            $user_ids = $post["spdy"];
            $tos = $this->getUseropenidbyuid($post['spdy'][0])[$post['spdy'][0]];
            \Yii::trace($tos);
            $oldtitle = null;
            $fjurl = null;
            $flag1 = null;
            $model->file = UploadedFile::getInstance($model, 'file');
            $randName = time().'_'.rand(1000, 9999);
            $sql ="insert into wp_ischool_work(name,oldtitle,cid,ctime,flag,flag1,fjurl,title,content,tid,sid) values(:name,:oldtitle,:cid,:ctime,:flag,:flag1,:fjurl,:title,:content,:tid,:sid)";

            if ($model->file  && $model->validate()) {
                if(!file_exists('ischool/uploads/jhsp/'.date('y/m/d',time()))){
                    mkdir('ischool/uploads/jhsp/'.date('y/m/d',time()),0777,true);
                }
                $picname = $this->create_random_string(13);//随机文件名
//                $model->file->saveAs('ischool/uploads/picture/'.date('y/m/d/',time()). $model->file->baseName . '.' . $model->file->extension);
                $oldtitle = $model->file->baseName; //图片原始名
//                $size = $model->file->size;
                $fjurl = '/ischool/uploads/jhsp/'.date('y/m/d/',time()).$picname.'.'.$model->file->extension;//图片重命名存服务器
            }
            $transaction = \yii::$app->db->beginTransaction();
            $res = \Yii::$app->db->createCommand($sql,[':name'=>$toname,':oldtitle'=>$oldtitle,':cid'=>$lastcid,':ctime'=>time(),':flag'=>0,':flag1'=>$flag1,':fjurl'=>$fjurl,':title'=>$title,':content'=>$content,':tid'=>$tid,':sid'=>$lastsid])->execute();
            $workid = \Yii::$app->db->getLastInsertID();    //最新提交的审核信息id
            $sql_worksh = "insert into wp_ischool_worksh (name,work_id,tid,next_tid,xuhao,status,is_deleted,tjr_id) VALUES ";
            $i = 1;
            foreach ($user_ids as $k => $v){
                $name = $this->getUsernamebyuid($v)[$v];
                if ($k == 0){
                    $status = 0;    //待审核
                }else{
                    $status = 3; //没轮到我审批
                }
                $next = empty($user_ids[$i])?0:$user_ids[$i];
                $i++;
                $sql_worksh.="('$name',$workid,$v,$next,$k,$status,0,$tid),";
            }
                $sql_info = substr($sql_worksh,0,-1);
                $res2 = \Yii::$app->db->createCommand($sql_info)->execute();
            try{
                if ($res && $res2){
                    if(!empty($model->file)) {
                        $model->file->saveAs('ischool/uploads/jhsp/' . date('y/m/d/', time()) . $picname . '.' . $model->file->extension);
                    }
                    $transaction->commit();
                    $tstitle = $toname."的计划需要您审批";
                    $des = $title;
                    $data['pic_url'] =  $this->getSchoolPic($lastsid);
                    SendMsg::sendSHMsgToPa($tos, $tstitle, $des,"",$data['pic_url']);
                    echo "<script>alert('添加成功！');window.location='/teacher/shenpi';</script>";
                }else{
                    echo "<script>alert('添加失败！');window.location='/teacher/shenpi';</script>";
                }
            }catch (\Exception $e)
            {
                $transaction->rollBack();
                throw $e;
            }
        }
        $sql= "select label from wp_ischool_user  WHERE last_sid=$lastsid AND label IS NOT NULL AND label != '' GROUP BY label";
        $res = \yii::$app->db->createCommand($sql)->queryAll();
        if (empty($res)){
            $info['fenzu'] = "";
        }else{
            $s = "";
            foreach ($res as $value){

                $s.= $value['label'].",";   //拼接字符串
            }
            $fenzu  = substr($s,0,-1);   //去掉最后一个逗号
            $info['fenzu']=array_unique(explode(',', $fenzu)); // 去掉重复字串串重新组合一维数组
        }
            return $this->render('shenpi',[
                'sjxmodels' =>$sjxmodels,
                'models'=>$models,
                'info' => $info,
                'users' =>$users,
                'type' =>$type,
                'flags' =>$flags
            ]);
    }
}