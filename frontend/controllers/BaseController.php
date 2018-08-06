<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-03-17
 * Time: 8:46
 */
namespace app\controllers;
use app\models\Attachment;
use app\models\User;
use app\models\WpIschoolQunzu;
use app\models\WpIschoolUserRole;
use app\models\WpIschoolWork;
use app\models\WpIschoolWorksh;
use backend\models\WpIschoolUser;
use Yii;
use Yii\db\Expression;
use app\models\gonggao;
use app\models\News;
use app\models\WpIschoolInbox;
use app\models\WpIschoolOutbox;
use app\models\WpIschoolSafecard;
use app\models\WpIschoolTeaclass;
use backend\models\WpIschoolStudent;
use frontend\models\WpIschoolMsgcount;
use frontend\models\WpIschoolPicschool;
use yii\web\Controller;
use app\models\BaseModel;
use yii\web\ForbiddenHttpException;
use app\models\Pastudent;
use app\models\paUser;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use mobile\assets\SendMsg;

class BaseController extends Controller
{
    public $basemodel;
    public $layout;
    public $lastcid;
    public $lastsid;    //用户身份的当前sid
    public $last_sid;    //管理员校长身份sid
    public $user;
    public function init()
    {
        parent::init();
//        \Yii::$app->session['isLogin'] = 1;
        $session = \Yii::$app->session;
        $tel = $session->get('tel');
        $lifetime = $session->get('lifetime');
        $openid = $session->get('openid');
//        $role = $session->get('role');
//var_dump($tel);exit();
        if(isset($tel) && !empty($tel) && $lifetime>time() && !empty($openid))
        {
            $this->enableCsrfValidation = false;
            //\yii::$app->view->params['schoolid'] = \yii::$app->user->getIdentity()['school_id'];
            $this->basemodel = new BaseModel();
            $user  = new paUser();
            $info['user'] = $user->getParinfo($tel);
            $this->lastsid = !empty($info['user'][0]['last_sid'])?$info['user'][0]['last_sid']:1;
            $this->user = $info['user'];
            \yii::$app->view->params['user'] = $info['user'];
            $info['path'] = Yii::$app->request->hostInfo;
            \yii::$app->view->params['path'] = $info['path'];
            $connection  = \Yii::$app->db;
            $command = $connection->createCommand('select distinct pro from wp_ischool_school ORDER BY convert(pro USING gbk)');
            $info['pro'] = $command->queryAll();

            if($info['user'][0]['shenfen'] == "jiazhang"){
                $this->layout ="main.php";
                $model = new Pastudent();
                $info['pastudent'] = !empty($info['user'][0]['openid'])?$model->getPastudent($info['user'][0]['openid']):"";
                $info['countChild'] =  !empty($info['pastudent'])?count($info['pastudent']):0;
                \yii::$app->view->params['key'] = $info['pastudent'];
            }
            if($info['user'][0]['shenfen'] == "tea"){
                $this->layout ="maintc.php";
                $models = new WpIschoolTeaclass();
//                $info['teachers'] = $models->getTeachers($info['user'][0]['tel']);
                $info['teachers'] = !empty($info['user'][0]['openid'])?$models->getTeachers($info['user'][0]['openid']):"";
                if(!empty($info['teachers'])){
                    \yii::$app->view->params['teachers'] = $info['teachers'];
                }else{
                    \yii::$app->view->params['teachers'] = "";
                }
                $info['teacheres'] = !empty($info['user'][0]['openid'])?$models->getTeacheres($info['user'][0]['openid']):"";
                $this->lastcid = $info['user'][0]['last_cid'];
            }
            if($info['user'][0]['shenfen'] == "guanli"){
                $this->layout ="maingl.php";
                $sql_school = "select * from wp_ischool_school WHERE id=:id ";    //获取当前学校的详细信息
                $sid = !empty($info['user'][0]['last_sid'])?$info['user'][0]['last_sid']:"0";
                $info['sname'] = $connection->createCommand($sql_school,[":id"=>$sid])->queryAll();
                $openid = !empty($info['user'][0]['openid'])?$info['user'][0]['openid']:"";
                if(!empty($openid)){
                    $info['school'] = $this->getAllschool($openid);
                    $info['schools'] = $this->getAllschools($openid);
                }else{
                    $info['school'] ="";
                }
                \yii::$app->view->params['schools'] = !empty($info['school'])?$info['school']:"";
//            if(!empty($info['sname'])){
//                \yii::$app->view->params['sname'] = $info['sname'];
//            }else{
//                \yii::$app->view->params['sname'] = "";
//            }
                $this->last_sid = !empty($info['school'][0]['sid'])?$info['school'][0]['sid']:"1";
            }
            $info['subject'] = $this->Getsubject();//教师角色列表
            $info['manage'] = $this->Getmanage();//其他人员角色列表
            return $info;
        }else{
            echo '<html>';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>';
            echo "<script>alert('您还没有登录，请先登录！');window.location='/site/denglu';</script>";
            echo '</html>';
            exit();
        }

    }
    protected function getUid($type=null){
        if($type==null){
            $openid=\yii::$app->view->params['openid'];
        }else{
            $openid=$type;
        }       
        $res=WpIschoolUser::findOne(['openid'=>$openid]);
        return $res['id'];
    }

    //根据用户openid获取用户绑定并且审核过的学校列表
    public function getAllschool($openid)
    {
        $sql = "select * from wp_ischool_user_role where openid= :openid AND shenfen='school' AND ispass='y'";
        $res = \Yii::$app->db->createCommand($sql,[':openid'=>$openid])->queryAll();
        return $res;
    }
    //根据用户openid获取用户绑定的学校列表包含未审核的
    public function getAllschools($openid)
    {
        $sql = "select * from wp_ischool_user_role where openid= :openid AND shenfen='school'";
        $res = \Yii::$app->db->createCommand($sql,[':openid'=>$openid])->queryAll();
        return $res;
    }

    /**根据学生ID获取学生表对应的信息*/
    public function stuinfo($id){
        $connection  = \Yii::$app->db;
        $command = $connection->createCommand("select * from wp_ischool_student where id='".$id."'");
        $res =  $command->queryAll();
        return $res;
    }
    //根据班级ID获取改班级学生表对应的姓名
    public function getStuname($cid){
        if(empty($cid)){
            $cid =  $this->lastcid;
        }
        $model = WpIschoolStudent::find()->where(['cid'=>$cid])->orderBy('name asc')->asArray()->all();
        return ArrayHelper::map($model, 'id', 'name');
    }

    //根据学校ID获取改学校学生表对应的姓名
    public function getStunamebysid($sid){
        if(empty($sid)){
            $cid =  $this->lastsid;
        }
        $model = WpIschoolStudent::find()->where(['sid'=>$sid])->orderBy('name asc')->asArray()->all();
        return ArrayHelper::map($model, 'id', 'name');
    }

    //根据用户ID获取用户表对应的姓名
    public function getUsernamebyuid($uid){
        $model = WpIschoolUser::find()->where(['id'=>$uid])->orderBy('name asc')->asArray()->all();
        return ArrayHelper::map($model, 'id', 'name');
    }

    //根据用户ID获取用户表对应的openid
    public function getUseropenidbyuid($uid){
        $model = WpIschoolUser::find()->where(['id'=>$uid])->orderBy('name asc')->asArray()->all();
        return ArrayHelper::map($model, 'id', 'openid');
    }

    /**根据班级ID获取老师信息*/
    public  function  Allteacher($id){
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select * from wp_ischool_teaclass where cid='".$id."' and ispass='y' group by openid");
        $res =  $command->queryAll();
        return $res;
    }

    /**根据班级ID获取班主任信息*/
    public  function  Headmaster($id){
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select * from wp_ischool_teaclass where cid='".$id."' and role ='班主任'");
        $res =  $command->queryAll();
        return $res;
    }
    /**根据学校ID获取该学校所有已审核老师信息此段暂时不用 用下面的*/
    public  function  getAlltea($sid,$name){
            $sql = "select * from wp_ischool_teaclass where sid=".$sid." and ispass='y' and tel !='' GROUP BY tel order by convert(tname using gbk) asc";
        if(!empty($name)){
            $sql = "select * from wp_ischool_teaclass where sid=".$sid." and ispass='y' and tel !=''and tname like '%$name%' GROUP BY tel order by convert(tname using gbk) asc";
        }
        $connection  = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $res =  $command->queryAll();
        return $res;
    }
    /**根据学校ID获取该学校所有已审核老师信息*/
    public  function  getAllteat($sid,$name){
        $sql = "select * from wp_ischool_teaclass where sid=".$sid." and ispass='y' GROUP BY openid order by convert(tname using gbk) asc";
        if(!empty($name)){
            $sql = "select * from wp_ischool_teaclass where sid=".$sid." and ispass='y' and tname like '%$name%' GROUP BY openid order by convert(tname using gbk) asc";
        }
        $connection  = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $res =  $command->queryAll();
        return $res;
    }


    /**根据sid，openid获取对应老师详细信息*/
//    public function teainfo($sid,$openid){
//        $connection  = Yii::$app->db;
//        $command = $connection->createCommand("select * from wp_ischool_teacher where sid='".$sid."' and openid ='".$openid."'");
//        $res =  $command->queryAll();
//        return $res;
//    }

    public function opSucceed($url)
    {
        return $this->render("/pastudent/alert",[
            "info"=>"操作成功",
            "location_url"=>$url
        ]);
    }
    public function opFailed($url)
    {
        return $this->render("/pastudent/alert",[
            "info"=>"操作失败",
            "location_url"=>$url
        ]);
    }
    /*  计算时间戳，本月头month，本周头week，本日头today时间戳作为查询起点 */
   public function getBeginTimestamp($type){
        $beginTs="";
        if($type=='today') {
            $beginTs=strtotime(date('Y-m-d'));
        }else if($type=='week'){
            $date = date("Y-m-d");
            $first=1;                                // 1 表示每周星期一为开始时间，0表示每周日为开始时间
            $w = date("w", strtotime($date));       //获取当前是本周的第几天，周日是 0，周一 到周六是 1 -6
            $d = $w ? $w - $first : 6;              //如果是周日 -6天
            $now_start = date("Y-m-d", strtotime("$date -".$d." days")); //本周开始时间：
            $beginTs =  strtotime($now_start);     //本周起始时间戳
        }else if($type=='month'){
            $beginTs=strtotime(date('Y-m'));
        }

        return $beginTs;
    }
    /***    修改用户名     */
    public function Upname($name,$id)
    {
        $session = \Yii::$app->session;
        $upuser = paUser::findOne($id);
        $upuser->name = $name;
        $upname = $upuser->save(false);
        if($upname){
            unset($session['name']);
            $session['name'] = $name;
            return 0;
        }else{
            return 1;
        }
    }
    /***    修改用户电话号码     */
    public function Uptel($tel,$id)
    {
        $session = \Yii::$app->session;
        $upuser = paUser::findOne($id);
        $upuser->tel = $tel;
        $istel = paUser::findOne(['tel'=>$tel]);    //查询手机号是否存在
        if($istel){
            return 2;
        }
        $uptel = $upuser->save(false);
        if($uptel){
            unset($session['tel']);
            $session['tel'] = $tel;
            return 0;
        }else{
            return 1;
        }
    }
    /**从数据库获取城市信息*/
    public function actionGetcity(){
        $pname = \yii::$app->request->post('pro');
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select distinct city from wp_ischool_school where pro='".$pname."' ORDER BY convert(city USING gbk)");
        $post =  json_encode($command->queryAll());
        return $post;
    }
    /**从数据库获取乡镇信息*/
    public function actionGetcounty(){
        $pname = \yii::$app->request->post('city');
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select distinct county from wp_ischool_school where city='".$pname."' ORDER BY convert(county USING gbk)");
        $post =  json_encode($command->queryAll());
        return $post;
    }
//学校类型 高中初中小学
    public function actionGettype(){
        $pname = \yii::$app->request->post('county');
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select distinct schtype from wp_ischool_school where county='".$pname."' ORDER BY convert(schtype USING gbk)");
        $post =  json_encode($command->queryAll());
        return $post;
    }

    //学校名字
    public function actionGetschool(){
        $area = \yii::$app->request->post('county');
        $cname = \yii::$app->request->post('schtype');
        $connection  = Yii::$app->db;
//        $command = $connection->createCommand("select id,name from wp_ischool_school where schtype='".$cname."' and county='".$area."' ORDER BY convert(name USING gbk)");
        $command = $connection->createCommand("select id,name from wp_ischool_school where county='".$area."' ORDER BY convert(name USING gbk)");
        $post =  json_encode($command->queryAll());
        return $post;
    }
    public function actionGetclass(){
        $sid = \yii::$app->request->post('sid');
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select id,name from wp_ischool_class where sid='".$sid."' ORDER BY convert(name USING gbk)");
        $post =  json_encode($command->queryAll());
        return $post;
    }
    /**从数据库获取教师角色信息*/
    public function Getsubject(){
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select distinct name,id from wp_ischool_subject ORDER BY id asc ");
        $post =  $command->queryAll();
        return $post;
    }
    /**从数据库获取学校其他人员角色信息*/
    public function Getmanage(){
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select distinct name,id from wp_ischool_manage ORDER BY id asc ");
        $post =  $command->queryAll();
        return $post;
    }
    //获取收件箱内容列表
    public function getinboxlist($inopenid,$type){
        $model = WpIschoolInbox::find()->where(['inopenid'=>$inopenid,'type'=>$type]);
        $pagination = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' =>$model->count(),
        ]);
        $model = $model->orderBy('id DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $res['pages'] = $pagination;
        $res['dataprovider']=$model;
        return $res;
    }
    //获取发件箱内容列表
    public function getoutboxlist($outopenid,$type){
        $model = WpIschoolOutbox::find()->where(['outopenid'=>$outopenid,'type'=>$type]);
        $pagination = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' =>$model->count(),
        ]);
        $model = $model->orderBy('id DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $res['pages'] = $pagination;
        $res['dataprovider']=$model;
        return $res;
    }
    //根据班级ID获取改班级所有的学生信息
    public function getAllstubycid($cid){
        $model = WpIschoolStudent::find()->where(['cid'=>$cid])->orderBy('name asc')->asArray()->all();
        return $model;
    }
    //根据学生ID获取对应的所有家长的openid
    public function getAllopenidbystuid($stuid){
        $res = Pastudent::find()->where(['and',['in', 'stu_id', $stuid],['ispass'=>'y'],"`openid` != ''"])->asArray()->all();
        $pOpenids = array();
        $this->insertArrToOther($res,$pOpenids);
        return $pOpenids;
    }
    //根据班级ID获取改班级所有学生的家长openid
    public  function getAllopenidbycid($cid){
        $res = Pastudent::find()->where(['and',['cid' => $cid],['ispass'=>'y'],"`openid` != ''"])->asArray()->all();
        $pOpenids = array();
        $this->insertArrToOther($res,$pOpenids);
        return $pOpenids;
    }
    /*  将结果openid数组分别追加到另一个数组，最后返回新数组 */
    function insertArrToOther($oldArr,&$newArr){
        $len1 = count($oldArr);
        for($i = 0; $i < $len1; $i++){
            $newArr[] = $oldArr[$i]['openid'];
        }
    }
//删除收件箱的信息
    public function delInbox($id){
        $delinbox = WpIschoolInbox::findOne($id)->delete();
        return $delinbox;
    }
    //删除发件箱的信息
    public function delOutbox($id){
        $delinbox = WpIschoolOutbox::findOne($id)->delete();
        return $delinbox;
    }
    //删除公告的信息
    public function delGonggao($id){
        $delinbox = gonggao::findOne($id)->delete();
        return $delinbox;
    }
    //删除动态的信息
    public function delDongtai($id){
        $delinbox = News::findOne($id)->delete();
        return $delinbox;
    }
    //获取打卡列表信息
    public function getdklist($stuid,$begintm){
        $models = WpIschoolSafecard::find()->where(['and',['in', 'stuid', $stuid],['>','ctime',$begintm],['<>','info','未到']]);
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' =>$models->count(),
        ]);
        $model = $models->orderBy('ctime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $info['pages']=$pagination;
        $info['model'] =$model;
        return $info;
    }
    //获取某一天打卡列表信息
    public function getDaylist($stuid,$begintm,$endtime){
        $models = WpIschoolSafecard::find()->where(['and',['in', 'stuid', $stuid],['between','ctime',$begintm,$endtime],['<>','info','未到']]);
        $pagination = new Pagination([
            'defaultPageSize' =>20,
            'totalCount' =>$models->count(),
        ]);
        $model = $models->orderBy('ctime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $info['pages']=$pagination;
        $info['model'] =$model;
        return $info;
    }

    //根据学校ID获取学校名称
    public  function getschoolnamebysid($sid)
    {
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select id,name from wp_ischool_school where id='".$sid."' ORDER BY convert(name USING gbk)")->queryAll();
        return $command;
    }
    //根据学生ID获取学校ID
    public function getSchoolidbystuid($stuid)
    {
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select sid,school,cid from wp_ischool_student where id='".$stuid."' ORDER BY convert(name USING gbk)")->queryAll();
        return $command;
    }

    //根据班级id获取学校id
    public function getSchoolidbycid($cid){
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select sid,school from wp_ischool_class where id='".$cid."' ORDER BY convert(name USING gbk)")->queryAll();
        return $command;
    }
    //根据sid获取轮播图片的URL地址
    public function getLunbopicurl($sid)
    {
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select sid,picurl from wp_ischool_hpage_lunbo where sid='".$sid."' ORDER BY id desc")->queryAll();
        return $command;
    }

    //生成指定长度的字符串
        function create_random_string($random_length) {
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
            $random_string = '';
            for ($i = 0; $i < $random_length; $i++) {
            $random_string .= $chars [mt_rand(0, strlen($chars) - 1)];
            }
            return $random_string;
        }

    //修改密码
    public function actionUpdatepwd(){
        $user = $this->user;
        $post =\yii::$app->request->post();
        $oldpasswd = $post['pwd'];  //老密码
        $newpasswd = $post['newPwd'];    //新密码
        if(md5($oldpasswd) == $user[0]['pwd']){
            $model = paUser::findOne($user[0]['id']);
            $model->pwd = md5($newpasswd);
            $res = $model->save(false);
            if($res>0 || $res === 0){
                echo "<script>alert('修改成功！');history.go(-1);</script>";
            }else{
                echo "<script>alert('修改失败！');history.go(-1);</script>";
            }
        }else{
            echo "<script>alert('密码不正确，请填写正确的密码！');history.go(-1);</script>";
        }
    }

    //获取学校图片信息
    public function getSchoolPic($sid){
        $toppic =WpIschoolPicschool::find()->select('toppic')->where(['schoolid'=> $sid])->asArray()->all();
        if($toppic){
            return $toppic[0]['toppic'];
        }else{
            return URL_PATH."/upload/syspic/msg.jpg";
        }
    }

    /**
     * 信息发送频率统计
     */
    public function setMsgNum($msgType,$to){
        $ym     = date("Ym");
        $cidArr = explode(',',$to);
        if($msgType == 'gg'){
            $type = 0;
        }else{
            $type = 1;
            $cids = WpIschoolStudent::find()->select('cid')->distinct(true)->where(new \yii\db\Expression('FIND_IN_SET("'.$cidArr.'",id)'))->asArray()->all();
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
                $m->save(false);
            }else{
                WpIschoolMsgcount::updateAllCounters($data,['num'=>1]);
            }
        }
    }

    public function getOneTeaOfClass($cid,$openid){
        $con['cid'] = $cid;
        $con['openid'] = $openid;
        $m= WpIschoolTeaclass::find()->select('tname')->where($con)->asArray()->all();
        return $m;
    }

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

        $sql="select distinct openid from wp_ischool_teaclass where sid=".$sid;
        $teachers =  WpIschoolTeaclass::findBySql($sql)->asArray()->all();
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
        $sql="select distinct openid from wp_ischool_pastudent where sid=".$sid;
        $parents = Pastudent::findBySql($sql)->asArray()->all();
        foreach($parents as $v){
            $parArr[] = $v['openid'];
        }
        return 0;
    }

    //切换当前身份信息
    public function actionChangeshenfen()
    {
        $user = $this->user;
//        var_dump($user);exit();
        $post = \yii::$app->request->post();
        $shenfen = $post['shenfen'];
        if(!empty($shenfen)){
            $model = paUser::findOne($user[0]['id']);
            $model->shenfen = $shenfen;
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

    //获取群组发送收件箱内容列表
    public function getqunzuinlist($sid,$grade_id,$type){
//        $model = WpIschoolQunzu::find()->where(['sid'=>$sid,'grade_id'=>$grade_id,'type'=>$type]);
        $model = WpIschoolQunzu::find()->where(['sid'=>$sid,'type'=>$type]);
        $pagination = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' =>$model->count(),
        ]);
        $model = $model->orderBy('ctime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $res['pages'] = $pagination;
        $res['dataprovider']=$model;
        return $res;
    }
    //获取群组中自己发件箱内容列表
    public function getqunzuoutlist($outopenid,$sid,$type){
//        $model = WpIschoolQunzu::find()->where(['outopenid'=>$outopenid,'grade_id'=>$grade_id,'type'=>$type]);
        $model = WpIschoolQunzu::find()->where(['outopenid'=>$outopenid,'sid'=>$sid,'type'=>$type]);
        $pagination = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' =>$model->count(),
        ]);
        $model = $model->orderBy('id DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $res['pages'] = $pagination;
        $res['dataprovider']=$model;
        return $res;
    }

    //获取文档管理收件箱内容列表
    public function getwdgllist($sid,$grade_id,$type){
//        $model = Attachment::find()->where(['sid'=>$sid,'grade_id'=>$grade_id,'type'=>$type]);
        $model = Attachment::find()->where(['sid'=>$sid,'type'=>$type]);
        $pagination = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' =>$model->count(),
        ]);
        $model = $model->orderBy('create_time DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $res['pages'] = $pagination;
        $res['dataprovider']=$model;
        return $res;
    }

    //获取文档管理中自己发件箱内容列表
    public function getwdgltlist($outopenid,$sid,$type){
//        $model = Attachment::find()->where(['openid'=>$outopenid,'grade_id'=>$grade_id,'type'=>$type]);
        $model = Attachment::find()->where(['openid'=>$outopenid,'sid'=>$sid,'type'=>$type]);
        $pagination = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' =>$model->count(),
        ]);
        $model = $model->orderBy('id DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $res['pages'] = $pagination;
        $res['dataprovider']=$model;
        return $res;
    }

    //删除文档管理文档的信息
    public function delInwdx($id){
        $delinbox = Attachment::findOne($id)->delete();
        return $delinbox;
    }

    //删除群组交流的信息
    public function delQunzjl($id){
        $delinbox = WpIschoolQunzu::findOne($id)->delete();
        return $delinbox;
    }
    //文档下载
    public function actionUpload(){
        // header("Content-type:text/html;charset=utf-8");
// $file_name="cookie.jpg";
        $time=$_GET['time'];
        if(time()-$time>30){
            exit();
        }
        $file_name = $_GET['url'];
        $name = $_GET['name'];
        // $newname = $name.".".pathinfo($file_name,PATHINFO_EXTENSION);
        $newname=substr($file_name, strripos($file_name, '/') + 1);
        // var_dump($newname);die;
//        $file_name="圣诞狂欢.jpg";
//用以解决中文不能显示出来的问题
        // $file_name=iconv("utf-8","gb2312",$file_name);
        $file_path=$_SERVER['DOCUMENT_ROOT'].$file_name;
//首先要判断给定的文件存在与否
        if(!file_exists($file_path)){
            echo "没有该文件";
            return ;
        }
        $fp=fopen($file_path,"r");
        $file_size=filesize($file_path);
//下载文件需要用到的头
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length:".$file_size);
        Header("Content-Disposition: attachment; filename=".$newname);
        $buffer=1024;
        $file_count=0;
//向浏览器返回数据
        while(!feof($fp) && $file_count<$file_size){
            $file_con=fread($fp,$buffer);
            $file_count+=$buffer;
            echo $file_con;
        }
        fclose($fp);
    }
    //获取一个群组下面所有人的openid
    public function getAllqunzuopid($sid,$type){
        $sql = "select openid from wp_ischool_user where last_sid= :sid AND label like '%$type%'";
        $res = \Yii::$app->db->createCommand($sql,[':sid'=>$sid])->queryAll();
        return $res;
    }

    //审批文档下载
    public function actionUploadsp(){
        $id=$_GET['id'];    //文件ID
        $tid = $_GET['tid'];    //用户ID
        $sid = $_GET['sid'];    //学校ID
        if ($this->checkPerssion($id,$tid,$sid)){
            $model = WpIschoolWork::findOne($id);
            $newna = $model['oldtitle'];
//            $name = $_GET['name'];
            $file = "aaa.jpg";
//            echo pathinfo($file, PATHINFO_EXTENSION);exit();
            $newname=$newna.".".pathinfo($model['fjurl'], PATHINFO_EXTENSION);
            $file_path=$_SERVER['DOCUMENT_ROOT'].$model['fjurl'];
//            var_dump($newname);exit();

            //首先要判断给定的文件存在与否
            if(!file_exists($file_path)){
                echo "没有该文件";
                return ;
            }
            $fp=fopen($file_path,"r");
            $file_size=filesize($file_path);
    //下载文件需要用到的头
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length:".$file_size);
            Header("Content-Disposition: attachment; filename=".$newname);
            $buffer=1024;
            $file_count=0;
    //向浏览器返回数据
            while(!feof($fp) && $file_count<$file_size){
                $file_con=fread($fp,$buffer);
                $file_count+=$buffer;
                echo $file_con;
            }
            fclose($fp);
      }
    }

    //检查权限
    public function checkPerssion($id,$tid,$sid){
        Yii::trace($this->user[0]['id']);Yii::trace($this->user[0]['last_sid']);
        $tid2= $this->user[0]['id'];//当前用户ID
        if($this->user[0]['shenfen'] =='tea'){
            if ($tid == $tid2){
                $res = WpIschoolWork::find($id);
            }else{
                $res = WpIschoolWorksh::find()->where("work_id = ".$id." and tid=".$tid2." and status !=3")->all();
            }
            if ($res && $sid== $this->user[0]['last_sid']){
//            if ($tid == $this->user[0]['id']){
                return true;
            }else{
                echo "非法下载！";
            }
        }elseif ($this->user[0]['shenfen'] =='guanli'){
            $model = WpIschoolUserRole::findOne(['openid'=>$this->user[0]['openid'],'sid'=>$sid]);
            if ($model){
                return true;
             }
        }else{
            echo "非法下载！";
        }
    }

    //获取审批管理已发送信息列表
    public function getspgllist($tid,$sid,$flag){
        !empty($tid)?$arr['tid']=$tid:"";
        !empty($sid)?$arr['sid']=$sid:"";
        $arr['is_deleted'] = 0;
        if ($flag==0){
            $model = WpIschoolWork::find()->where($arr);
        }elseif ($flag ==1){
            $model = WpIschoolWork::find()->where($arr)->andFilterWhere(['!=','flag',  0]);
        }elseif ($flag ==2){
            $model = WpIschoolWork::find()->where($arr)->andFilterWhere(['flag' => 0]);
        }
        $pagination = new Pagination([
            'defaultPageSize' => 15,
            'totalCount' =>$model->count(),
        ]);
        $model = $model->orderBy('ctime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $res['pages'] = $pagination;
        $res['dataprovider']=$model;
        return $res;
    }

    public function getWork(){
        return $this->hasOne(WpIschoolWork::className(), ['id' => 'work_id']);
    }

    //获取审批管理已接收信息列表
    public function getspglsjlist($openid,$sid){
        if (empty($openid)){
            echo "<script>alert('请确定您的身份信息！');window.location='/teacher/shenpi';</script>";
            return false;
        }
        !empty($sid)?$arr['sid']=$sid:"";
        $model = WpIschoolWorksh::find()->joinWith('work');
        $pagination = new Pagination([
            'defaultPageSize' => 15,
            'totalCount' =>$model->count(),
        ]);
        $model = $model->orderBy('wp_ischool_work.ctime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $res['pages'] = $pagination;
        $res['dataprovider']=$model;
        return $res;
    }

    //根据分组名字获取对应的分组成员
    public function actionGetrenyuan(){
        $userinfo = $this->user;
        $shenfen = $userinfo[0]['shenfen'];
        if ($shenfen =="guanli"){
            $lastsid = $this->last_sid;
        }else{
            $lastsid = $this->lastsid;
        }
        Yii::trace($userinfo[0]['shenfen']);
        $fenzu = \yii::$app->request->post('fenzu');
        if (!empty($fenzu)){
            $connection  = Yii::$app->db;
            $command = $connection->createCommand("select id,openid,name  from wp_ischool_user where label like '%".$fenzu."%' and last_sid=$lastsid ");
            $post =  json_encode($command->queryAll());
            return $post;
        }
    }

    //根据openid获取用户的基本信息
    public function getUser($openid){
        /*$sql = "select name,openid from wp_ischool_user where openid= :openid";
        $res = \yii::$app->db->createCommand($sql,[':openid'=>$openid])->queryOne();
        */
        $res = WpIschoolUser::find()->asArray()->all();
        return ArrayHelper::map($res, "openid", "name");
        return $res;
    }

    //根据审核计划ID获取对应的分组成员状态
    public function actionGetstatus(){
        $wkid = \yii::$app->request->post('id');
        $connection  = Yii::$app->db;
        $command = $connection->createCommand("select status,oktime,name,reason  from wp_ischool_worksh where  work_id='".$wkid."' order by xuhao asc");
        $post =  json_encode($command->queryAll());
        return $post;
    }

    //删除采购计划
    public function actionDelshenpi(){
        $id=\yii::$app->request->post('id');
        $transaction = \yii::$app->db->beginTransaction();
        try{
            $d=WpIschoolWork::findOne($id);
            $d->is_deleted = 1;
            $sql = "update wp_ischool_worksh set is_deleted=1 where work_id=:id";
            $spup = \yii::$app->db->createCommand($sql,[':id'=>$id])->execute();
            if( $d->update(false) && $spup)
            {
                $res = 0;
                $transaction->commit();
            }else {
                $res = 1;
                $transaction->rollBack();
            }
        }catch (\Exception $e)
        {
            $transaction->rollBack();
        }
        return $res;
    }

    //同意计划审批
    public function actionAgree(){
        $id=\yii::$app->request->post('id');
        $reason = \yii::$app->request->post('reason');
        $d=WpIschoolWorksh::findOne($id);
        $next_tid = $d->next_tid;
        $xuhao = $d->xuhao+1;
        $work_id = $d->work_id;
        $d->status = 1;
        $d->oktime = time();
        $d->reason = $reason;
        $model = WpIschoolWork::findOne($work_id);
        if ($next_tid !=0){
            $e = WpIschoolWorksh::findOne(['tid'=>$next_tid,'xuhao'=>$xuhao,'work_id'=>$work_id]);
            $e->status = 0;
            Yii::trace($e->tjr_id);
            $name = $this->getUsernamebyuid($e->tjr_id)[$e->tjr_id];
            $tos = $this->getUseropenidbyuid($next_tid)[$next_tid];
            $tstitle = $name."的计划需要您审批";
            $des = $model->title;
            $data['pic_url'] =  $this->getSchoolPic($model->sid);
//            SendMsg::sendSHMsgToPa($tos, $tstitle, $des,"",$data['pic_url']);
        }elseif ($next_tid ==0){
            $e = WpIschoolWork::findOne($work_id);
            $e->flag = 1;
            $tos = $this->getUseropenidbyuid($e->tid)[$e->tid];
            $tstitle = "您的计划已经通过审批";
            $des = $model->title;
            $data['pic_url'] =  $this->getSchoolPic($model->sid);
        }
        $transaction = \yii::$app->db->beginTransaction();
        try{
            if($d->save(false) && $e->save(false)){
                $at=0;
                $transaction->commit();
                SendMsg::sendSHMsgToPa($tos, $tstitle, $des,"",$data['pic_url']);
            }else{
                $at=1;
            }
        }catch (\Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
        return $at;
    }

    //拒绝审批计划
    public function actionRefuse(){
        $id=\yii::$app->request->post('id');
        $reason = \yii::$app->request->post('reason');
        $d=WpIschoolWorksh::findOne($id);
        $d->status = 2;
        $d->oktime = time();
        $d->reason = $reason;
        $result=$d->save(false);
        $work_id = $d->work_id;
        $e = WpIschoolWork::findOne($work_id);
        $e->flag = 2;
        $transaction = \yii::$app->db->beginTransaction();
        try{
            if($d->save(false) && $e->save(false)){
                $at=0;
                $transaction->commit();
                $tos = $this->getUseropenidbyuid($e->tid)[$e->tid];
                $tstitle = "您的计划已经拒绝";
                $des = $e->title;
                $data['pic_url'] =  $this->getSchoolPic($e->sid);
                SendMsg::sendSHMsgToPa($tos, $tstitle, $des,"",$data['pic_url']);
            }else{
                $at=1;
                $transaction->rollBack();
            }
        }catch (\Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
        return $at;
    }

    protected function PostCurl($url,$data)
    {
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置header
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($data)));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return array("errcode"=>-1,"errmsg"=>'发送错误号'.curl_errno($curl).'错误信息'.curl_error($curl));
        }
        curl_close($curl);
        return json_decode($result, true);
    }

    protected function getPostInfo($arrInfo){
        $url="http://60.205.148.191:8080/yqsh_third/api/onecard/message";
        //申请商户的时候生成的key
        $key="44EC6C5C17BA74D1759AB0AEB782E4EE";
        //第三方代码
        $thirdCode='510102180306020203';
        $arrInfo['thirdCode']=$thirdCode;
        $new_array=$arrInfo;
        if($new_array['name']!=''){
            unset($new_array['name']);
        }
        $mac=strtoupper(md5(implode('',$new_array).$key));          
        $arrInfo['mac']=$mac;       
        $data=json_encode($arrInfo , JSON_UNESCAPED_UNICODE);
        $result=$this->PostCurl($url,$data);
        return $result;
    }
}