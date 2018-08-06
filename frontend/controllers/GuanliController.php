<?php
namespace frontend\controllers;
use app\controllers\BaseController;
use app\models\gonggao;
use app\models\News;
use app\models\paUser;
use app\models\school;
use app\models\WpIschoolHpageColcontent;
use app\models\WpIschoolHpageColname;
use app\models\WpIschoolSafecard;
use app\models\WpIschoolTeaclass;
use app\models\WpIschoolWork;
use app\models\WpIschoolWorksh;
use yii\web\UploadedFile;
use app\models\ImportData;
use yii\data\Pagination;
use app\models\UploadForm;
use mobile\assets\SendMsg;
require_once 'excel.php';
class GuanliController extends BaseController
{
    public $userinfo;
    public function beforeAction($action){
        $info = $this->init();
        $shenfen  = $info['user'][0]['shenfen'];
        if($shenfen != "guanli"){
            $url = \Yii::$app->session->get('url');
            return $this->redirect("$url")->send();
        }
        return true;
    }
    //首页个人中心
    public function actionIndex()
    {
        $info = $this->init();
        $this->userinfo = $info['user'];
        $class = \Yii::$app->request->get('class');
        if(!empty($info['schools'])){
            foreach($info['schools'] as $key=>$value){
                $info['schoolid'][$value['id']] = $value['sid'];
            }
            $ids=(implode(',',$info['schoolid']));
            $sql_school = "select * from wp_ischool_school WHERE FIND_IN_SET(id,:id)";    //获取当前学校的详细信息
            $info['sname'] = \Yii::$app->db->createCommand($sql_school,[":id"=>$this->last_sid])->queryAll();
            foreach($info['sname'] as $key=>$value){
                $info['snames'][$value['id']] = $value;
            }
        }

//        var_dump($info['sname']);exit();
//        $info['stype'] = $this->getSchoolinfo();
        return $this->render('index',[
                'info' => $info,
            ]
        );
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

    //切换当前学校信息
    public function actionChangeschool()
    {
        $post = \yii::$app->request->post();
        $sid = $post['sid'];
        $info = $this->init();
        $userid = $info['user'][0]['id'];      //用户ID
        if(!empty($sid)){
            $model = paUser::findOne($userid);
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

    //绑定学校
    public function actionAddschool(){
        $this->actionIndex();
        $post = \yii::$app->request->post();
        $at = "";
        if(!empty($post)){
            $openid = $post["openid"];
            $sid = $post['sid'];
            $school = $post['school'];
            $name = $this->userinfo[0]['name'];
            $sqll = "select * from wp_ischool_user_role where sid=:sid and shenfen='school' and ispass='y'";
            $mod = \Yii::$app->db->createCommand($sqll,[':sid'=>$sid])->queryAll();
            $sql = "select * from wp_ischool_user_role where openid = :openid and sid=:sid and shenfen='school'";
            $model = \Yii::$app->db->createCommand($sql,['openid'=>$openid,':sid'=>$sid])->queryAll();
            if(!empty($mod)){
                $at = 4;
            }else if(!empty($model)){
                $at = 3;
            }else{
                $sql = "insert into wp_ischool_user_role(id,openid,rid,school,sid,name,shenfen,ispass) values('','$openid',1,'$school',$sid,'$name','school','y')";
                $res = \Yii::$app->db->createCommand($sql)->execute();
                if($res===0 || $res>0){
                    $at = 5;
                    $model = paUser::findOne(['openid'=>$openid]);
                    $model->last_sid = $sid;
                    $model->save(false);
                }else{
                    $at = 2;
                }
            }
        }
        return $at;
    }

    //取消绑定学校
    public function actionDelschool()
    {
        $this->actionIndex();
        $tel = $this->userinfo['0']['tel'];
        $id = \Yii::$app->request->get('id');//get获取参数
        $sid = \Yii::$app->request->get('sid');//get获取参数
        $sql ="delete  from wp_ischool_user_role WHERE id=:id";
        $models = paUser::findOne(['tel'=>$tel]);
        $models->last_sid = Null;
        $res = "";
        if($sid == $this->userinfo['0']['last_sid']){
            $transaction = \yii::$app->db->beginTransaction();
            try{
                if(\yii::$app->db->createCommand($sql,[':id' =>$id])->execute() && $models->save(false))
                {
                    $res = 1;
                    $transaction->commit();
                }else {
                    $res = 0;
                    $transaction->rollBack();
                }
            }catch (Exception $e)
            {
                $transaction->rollBack();
            }
        }else{
            if( \yii::$app->db->createCommand($sql,[':id' =>$id])->execute()){
                $res = 1;
            }
        }
        if ($res ==1) {
            \Yii::$app->session->setFlash("info", "删除成功");
        }else{
            \Yii::$app->session->setFlash("info", "删除失败");
        }
        return $this->redirect(['guanli/index']);
    }

    //修改密码
    public function actionPasswd()
    {
       return $this->render('passwd');
    }


    //平安通知
    public function actionSafecard()
    {
        $this->actionIndex();
        $info = $this->init();
        $sid = $this->last_sid;
//        $stuinfo = $this->getAllstubycid($sid);
        $stuname =$this->getStunamebysid($sid);              //学生名字
        $stuid = array();
        foreach($stuname as $k=>$v)
        {
            $stuid[] = $k;
        }
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
        return $this->render("safecard",[
            'pages'=>$pagination,
            'model'=>$model,
            'modelwk'=>$modelwk,
            'pageswk'=>$paginationwk,
            'modelmh'=>$modelmh,
            'pagesmh'=>$paginationmh,
            'info'=>$stuname,
            'infos'=>$info
        ]);
        return $this->render('safecard');
    }
//平安通知导出
    public function actionExport()
    {
        $this->actionIndex();
        $sid = $this->last_sid;
        $stuinfo = $this->getStunamebysid($sid);
        $stuid = array();
        foreach($stuinfo as $k=>$v){
            $stuid[] = $k;
        }
        $model = new WpIschoolSafecard();
        $export_columns =
            [
                [
                    "attribute"=>"stuid",
                    'value' => function($model) use ($stuinfo) {
                        return isset($stuinfo[$model->stuid])?$stuinfo[$model->stuid]:"默认姓名";
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

//学校微官网
    public function actionSchoolwebsite()
    {
        $info = $this->init();
        $this->actionIndex();
        $sid = $this->last_sid;
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

    //校园首页界面下面添加<学生风采>,<教师风采>，<学校概况>的界面
    public function actionAdd()
    {
        $this->actionIndex();
        $openid=!empty($this->userinfo[0]['openid'])?$this->userinfo[0]['openid']:1;
        $sid=$this->last_sid;
        $type=\yii::$app->request->get("type");
        $cid = \yii::$app->request->get("cid");
        $tem = \yii::$app->request->get("tem");
        $render_array = array();
        switch ($type) {
            case 'school':
                $render_array['top']="学校概况";
                break;
            case 'teacher':
                $render_array['top']="教师风采";
                break;
            case 'student':
                $render_array['top']="学生风采";
                break;
            default:
                $render_array['top']=$type;
                break;
        }

        $res=school::findOne($sid);
        $render_array['ischool']=$res["name"];
        $render_array['openid']=$openid;
        $render_array['sid']=$sid;
        $render_array['cid']=$cid;
        $render_array['tem']=$tem;
        $render_array['type']=$type;
        return $this->render("add",$render_array);
    }

    //编辑页面
    public function actionEdit()
    {
        $this->actionIndex();
        $openid=!empty($this->userinfo[0]['openid'])?$this->userinfo[0]['openid']:"";
        $id=\yii::$app->request->get("id");
        $type=\yii::$app->request->get("type");
        //$m=M("ischool_hpage_colcontent");
        $m = new WpIschoolHpageColcontent();
        $wh["id"]=$id;
        $res=$m->findOne($wh);
        $title=$res["title"];
        $content=$res["content"];
        $toppicture=$res["toppicture"];
        $sketch=$res["sketch"];
        $sid=$res["sid"];

        //$m=M("ischool_school");
        $m = new school();
        $where["id"]=$sid;
        $res=$m->findOne($where);
        $render_array = [];
        $render_array['ischool']=$res["name"];
        $render_array['id']=$id;
        $render_array['title']=$title;
        $render_array['openid']=$openid;
        $render_array['content']=$content;
        $render_array['toppicture']=$toppicture;
        $render_array['sketch']=$sketch;
        $render_array['type']=$type;
        $render_array['sid']=$sid;
        //$render_array['path']=$path;
        return $this->render("edit",$render_array);

    }

    public function actionDoadd()
    {
        $this->actionIndex();
        $openid=!empty($this->userinfo[0]['openid'])?$this->userinfo[0]['openid']:1;
        $sid=$this->last_sid;
        $title=\yii::$app->request->post("title");
        $content=\yii::$app->request->post("content");
        $img=\yii::$app->request->post("img");
        $sketch=\yii::$app->request->post("sketch");
        $type=\yii::$app->request->post("type");
        $cid = \yii::$app->request->post("cid");
        $tem = \yii::$app->request->post("tem");

        if($tem=='moren'){
            $arr = array('teacher'=>'教师风采','student'=>'学生风采','school'=>'学校概况');

            foreach ($arr as $key => $value) {
                $m = new WpIschoolHpageColname();
                $m->name = $value;
                $m->sid = $sid;
                $newid = $m->save(false);
                if($key==$type){
                    $cid = $newid;
                }
            }
        }

        //$m=M("ischool_hpage_colcontent");
        $m = new WpIschoolHpageColcontent();
        $m->title=$title;
        $m->openid=$openid;
        $m->content=$content;
        $m->toppicture=$img;
        $m->sketch=$sketch;
        $m->sid=$sid;
        $m->cid=$cid;
        $res = $m->save(false);
        if($res>0 || $res===0){
            return json_encode(['status'=>0]);
        }else{
            return json_encode(['status'=>1]);
        }
    }
    public function actionDoedit()
    {
        $this->actionIndex();
        $openid=!empty($this->userinfo[0]['openid'])?$this->userinfo[0]['openid']:1;
        $sid=$this->last_sid;
        $id=$_POST["id"];
        $title=$_POST["title"];
        $content=$_POST["content"];
        $img=$_POST["img"];
        $sketch=$_POST["sketch"];
        $m = WpIschoolHpageColcontent::findOne($id);
        $m->title=$title;
        $m->openid=$openid;
        $m->content=$content;
        $m->toppicture=$img;
        $m->sketch=$sketch;
        $res = $m->save(false);
        if($res>0 || $res===0){
            return json_encode(['status'=>0]);
        }else{
            return json_encode(['status'=>1]);
        }
    }

//校内公告列表
    public function actionSchoolnotice(){
        $info = $this->init();
        $this->actionIndex();
        $useinfo = $this->userinfo;
        $sid = $this->last_sid;
        if(empty($sid)){
            $sid = "0";
        }
        $model = gonggao::find()->where(['sid'=>$sid])->orderBy('ctime Desc');
        $pagination = new Pagination([
            'defaultPageSize' => 13,
            'totalCount' =>$model->count(),
        ]);
        $model = $model->orderBy('ctime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $info['sid'] = $sid;
        $info['pages'] = $pagination;
        $info['dataprovider']=$model;
        return $this->render("schoolnotice",$info);
    }

    //公告详情页
    public function actionSchoolmin(){
        if(!empty($_GET['id'])){
            $id = $_GET['id'];
            $info['info'] =  gonggao::find()->where(['id'=>$id])->asArray()->all();
            return $this->render("schoolmin",$info);
        }else{
            $this->redirect("/guanli/notice");
        }
    }

    //发布公告
    public function actionFabugg()
    {
        $this->actionIndex();
        $sid = $this->last_sid;
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
            $model->sid = $this->last_sid;
            $model->ctime = time();
            $model->name = $this->userinfo[0]['name'];
            $res = $model->save(false);
            if($res){
                $allUser =$this->getAllUser($sid);
                $school = $this->getschoolnamebysid($sid);
                $url = URL_PATH;
                $url = $url."/gonggao/index?sid=".$sid."&openid=";
                $data['url'] = $url;
                $data['title']="最新公告提醒";
                $data['content']=$school[0]['name']."发布了最新公告";
                $data['pic_url'] =  $this->getSchoolPic($sid);
                SendMsg::broadMsgToManyUsers($allUser,$data);
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
        if(isset($id) && $id !=""){
            $res = $this->delGonggao($id);
            if($res){
//                \Yii::$app->getSession()->setFlash('success', '删除成功');
                return $this->redirect("/guanli/schoolnotice");
            }
        }
    }
    //校内动态列表
    public function actionClassdynamics(){
        $info = $this->init();
        $this->actionIndex();
        $useinfo = $this->userinfo;
        $sid = $this->last_sid;
        if(empty($sid)){
            $sid = "0";
        }
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
        $info['sid'] =$sid;
        return $this->render("classdynamics",$info);
    }

    //动态详情页
    public function actionDongtaimin(){
        $id = $_GET['id'];
        $info['info'] =  News::find()->where(['id'=>$id])->asArray()->all();
        return $this->render("dongtaimin",$info);
    }

    //发布动态
    public function actionFabudt()
    {
        $this->actionIndex();
        $sid = $this->last_sid;
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
            $model->sid = $this->last_sid;
            $model->ctime = time();
            $model->name = $this->userinfo[0]['name'];
            $model->openid = $this->userinfo[0]['openid'];
            $res = $model->save(false);
            if($res){
                $allUser =$this->getAllUser($sid);
                $school = $this->getschoolnamebysid($sid);
                $url = URL_PATH;
                $url = $url."/schoolnews/index?sid=".$sid."&openid=";
                $data['url'] = $url;
                $data['title']="最新动态提醒";
                $data['content']=$school[0]['name']."发布了最新动态";
                $data['pic_url'] =  $this->getSchoolPic($sid);
                SendMsg::broadMsgToManyUsers($allUser,$data);
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
        $id = \Yii::$app->request->get('id');
        if(isset($id) && $id !=""){
            $res = $this->delDongtai($id);
            if($res){
//                \Yii::$app->getSession()->setFlash('success', '删除成功');
                return $this->redirect("/guanli/classdynamics");
            }
        }
    }

    //内部交流
    public function actionInternalcom()
    {
        $info = $this->init();
        $this->actionIndex();
        $useinfo = $this->userinfo;
        $sid = $this->last_sid;
        $name ="";
        $info['teachers'] = $this->getAllteat($sid,$name);
        if(\Yii::$app->request->isPost){
            $name = \Yii::$app->request->post('name');
            $teachers = $this->getAllteat($sid,$name);
            return json_encode($teachers);
        }
        $type = 1;
        $inboxlist = $this->getinboxlist($this->userinfo[0]['openid'],$type);    //收件人接受信息列表
        $info['pages'] = $inboxlist['pages'];
        $info['inboxlist'] = $inboxlist['dataprovider'];
        $outboxlist= $this->getoutboxlist($this->userinfo[0]['openid'],$type);    //已发信息列表
        $info['pageso'] = $outboxlist['pages'];
        $info['outboxlist'] = $outboxlist['dataprovider'];
        return $this->render('internalcom',$info);
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
//                $content = preg_replace($preg,'',$content);
            } 
            $ctime = time();
            $sid = $this->last_sid;
            $ur[0]=URL_PATH."/exchange/index?openid=";
            $ur[1]="&sid=".$sid;
            $data['url']   = $ur;       //图文跳转链接
            $data['title'] = $totitle;  //图文消息标题
            $data['content'] = $des;    //待入库的原始消息
            $data['pic_url'] =  $this->getSchoolPic($sid);
            $out_uid=$this->getUid($teaopenid);
            //发送前先存入发送箱
            $sql = "INSERT INTO `wp_ischool_outbox` ( `content` , `outopenid`, `ctime` , `title` , `type` , `out_uid` ) VALUES( :content, :outopenid, :ctime,:title, 1,:out_uid)";
            $outbox = \Yii::$app->db->createCommand($sql,[':content'=>$content,':outopenid'=>$teaopenid,':ctime'=>time(),':title'=>$title])->execute();
            if(!empty($openid)){
                $transaction = \Yii::$app->db->beginTransaction();       //事务开始
                try{
                    $sql_inbox = "INSERT INTO `wp_ischool_inbox` ( `content` , `outopenid` , `inopenid` , `ctime` , `title` , `type` , `out_uid`, `in_uid`) VALUES";
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

    //发件箱删除信息
    public function actionDeloutbox()
    {
        $id = \Yii::$app->request->get('id');
        if(!empty($id)){
            $res = $this->delOutbox($id);
            if($res){
                \Yii::$app->getSession()->setFlash('success', '删除成功');
                return $this->redirect("/guanli/internalcom?type=yifa");
            }
        }
    }
    //收件箱删除信息
    public function actionDelinbox()
    {
        $id = \Yii::$app->request->get('id');
        if(!empty($id)){
            $res = $this->delInbox($id);
            if($res){
                \Yii::$app->getSession()->setFlash('success', '删除成功');
                return $this->redirect("/guanli/internalcom?type=shouxin");
            }
        }
    }
    //班级列表
    public function actionClasslist()
    {
        $info = $this->init();
        $this->userinfo = $info['user'];
        $class = \Yii::$app->request->get('class');
        $sql = "select * from wp_ischool_class WHERE sid =:sid ORDER BY level,class ASC";
        if(!empty($class)){
            $sql = "select * from wp_ischool_class WHERE sid =:sid AND name LIKE '%$class%'ORDER BY level,class ASC";
        }
        $urlparam = empty($class)?"":"&tname=".$class;//搜索拼接参数
        $res = \Yii::$app->db->createCommand($sql,[':sid'=>$this->last_sid])->queryAll();
        $page = \yii::$app->request->get("page");
        //每页显示的条数
        $num = 15;
        $nm = count($res);
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
        $list = \Yii::$app->db->createCommand($sql." limit ".$num." offset ".$star."",[':sid'=>$this->last_sid])->queryAll();
        $ym = date('Ym');
        $sql_tea = "select * from wp_ischool_teaclass where cid=:cid and ispass='y'";   //班级中的老师
        $sql_msggg = "select * from wp_ischool_msgcount WHERE  cid=:cid AND TYPE =0 and ym=:ym"; //公告信息统计
        $sql_msgly = "select * from wp_ischool_msgcount WHERE  cid=:cid AND TYPE =1 and ym=:ym"; //留言信息统计
        foreach ($list as $k=>$v) {
            $cid=$v["id"];
            $rq=\Yii::$app->db->createCommand($sql_tea,[':cid'=>$cid])->queryAll();
            $msggg = \Yii::$app->db->createCommand($sql_msggg,[':cid'=>$cid,'ym'=>$ym])->queryAll();
            $msgly = \Yii::$app->db->createCommand($sql_msgly,[':cid'=>$cid,'ym'=>$ym])->queryAll();
            $tnameStr = "";
            foreach ($rq as $v){
                $tnameStr .= $v['tname'].'/';
            }
            $list[$k]['tname'] = substr($tnameStr, 0, -1);
            $list[$k]['ggcount'] = empty($msggg)?0:$msggg[0]['num'];
            $list[$k]['lycount'] = empty($msgly)?0:$msgly[0]['num'];
            $list[$k]['role'] = $rq[0]['role'];
        }
        $info["allclass"] = $list;
//        var_dump($list);exit();
        $info['start'] = "/guanli/classlist?page=1".$urlparam;
        $info['up'] = "/guanli/classlist?page=$last".$urlparam;
        $info['down'] = "/guanli/classlist?page=$next".$urlparam;
        $info['end'] = "/guanli/classlist?page=$sum".$urlparam;
//        var_dump($info);
        return $this->render('classlist',[
                'info' => $info,
            ]
        );
    }
    //首页查看请假
    public  function actionCkqj()
    {
        $get = \Yii::$app->request->get();
        $info=[];
        $info['classname'] = empty($get['name'])?"":$get['name'];
        $cid = \Yii::$app->request->get('cid');
        if(!empty($cid)){
            $lev_sql = "select t1.id,t2.name,t2.stuno2,t1.begin_time,t1.stop_time,t1.reason from wp_ischool_stu_leave t1 left join wp_ischool_student t2 on t1.stu_id=t2.id where t2.cid=".$cid." and t1.flag=1 order by t1.id desc";
            $info['qingjia'] = \Yii::$app->db->createCommand($lev_sql)->queryAll();
            $page = \yii::$app->request->get("page");
            //每页显示的条数
            $num = 15;
            $nm = count($info['qingjia']);
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
            $list = \Yii::$app->db->createCommand($lev_sql." limit ".$num." offset ".$star."",[':sid'=>$this->last_sid])->queryAll();
            $info["list"] = $list;
            $info['count'] = $nm;
            $info['start'] = "/guanli/ckqj?cid=$cid&page=1";
            $info['up'] = "/guanli/ckqj?cid=$cid&page=$last";
            $info['down'] = "/guanli/ckqj?cid=$cid&page=$next";
            $info['end'] = "/guanli/ckqj?cid=$cid&page=$sum";
            $info['yeshu'] = $sum;
        }
        return $this->render("ckqj",$info);
    }
    //首页查看考勤
    public  function actionCkkq()
    {
        $get = \Yii::$app->request->get();
        $cid = empty($get['cid'])?"":$get['cid'];
        $info['cid'] = $cid;
        $info['classname'] = empty($get['name'])?"":$get['name'];
        if(!empty($cid)){
            $stuinfo = $this->getAllstubycid($cid);
            $stuid = array();
            foreach($stuinfo as $k=>$v){
                $stuid[] = $v['id'];
            }
            $info['stuname'] =$this->getStuname($cid);              //学生名字
            $begintm =\Yii::$app->request->get('time');     //查询时间
            $begintm = strtotime(date($begintm));       //获得某一天开始的时间戳
            if(empty($begintm)){
                $begintm =strtotime(date("Y-m-d"));//获得今天开始的时间戳
            }
            $endtime = $begintm+86400;
            $model = $this->getDaylist($stuid,$begintm,$endtime);        //今天的打卡信息列表
            $info['dklist'] = $model['model'];
            $info['pages'] = $model['pages'];
        };
        return $this->render("ckkq",$info);
    }

    //首页删除班级
    public function actionDelclass(){
        $id = \yii::$app->request->post('cid');//班级ID
        $sql = "select * from wp_ischool_student WHERE cid=:id";
        $res = \Yii::$app->db->createCommand($sql,[':id'=>$id])->queryAll();
        if(!empty($res)){
            return json_encode(['status'=>2]);
        }else{
            $sql = "delete  from wp_ischool_class WHERE id=:id";
            $res = \Yii::$app->db->createCommand($sql,[':id'=>$id])->execute();
            if($res){
                return json_encode(['status'=>0]);
            }else{
                return json_encode(['status'=>1]);
            }
        }
    }

    //首页班级配置
    public function actionBjpz(){
        $info = $this->init();
        $sid = $this->last_sid;
        $info['teainfo'] = $this->getAllteat($sid,"");
        $info['listclass'] = \Yii::$app->db->createCommand("select id,name from wp_ischool_class where sid='".$sid."' ORDER BY convert(name USING gbk)")->queryAll();
        return $this->render("bjpz",$info);
    }

    /*  获取当前班级或部门的老师列表 */
    public function actionGetteas(){
        $get = \Yii::$app->request->get();
        $cid=$get['cid'];
        $sql = "select id,tname,role from wp_ischool_teaclass WHERE cid=:cid and ispass='y'";
        $res = \Yii::$app->db->createCommand($sql,[":cid"=>$cid])->queryAll();
        $res2['result']='success';
        $res2['data']=$res;
        return json_encode($res2);
    }
    /*  删除老师信息 */
    public function actionDeletetea(){
        $get = \Yii::$app->request->get();
        $id=$get['tcid'];
        $sql = "delete  from wp_ischool_teaclass WHERE id=:id";
        $res = \Yii::$app->db->createCommand($sql,[':id'=>$id])->execute();
        if($res>0||$res===0){
            $data['result']='success';
        }else{
            $data['result']='fail';
        }
        return json_encode($data);
    }
    public function actionDopzbj()
    {
        $infos = $this->init();
        $post = \yii::$app->request->post();
        $at = "";
        if(!empty($post)){
            $openid = $post["openid"];
            $cid = $post['cid'];
            $role = $post['role'];
            $model = WpIschoolTeaclass::find()->where(['openid'=>$openid,'cid'=>$cid,'role'=>$role])->all();
            if(!empty($model)){
                $at['id'] = 3;
            }else{
                $model = new WpIschoolTeaclass();
                $model->openid = $openid;
                $model->cid = $cid;
                $model->role = $role;
                $model->tname = $post['teaname'];
                $model->school = $infos['sname'][0]['name'];
                $model->sid = $this->last_sid;
                $model->class = $post["class"];
                $model->ctime = time();
                $model->ispass = 'y';
                $model->tel = $post["tel"];
                $model->uid = $this->getUid($openid);
                $res = $model->save(false);
                $data['tname'] = $post['teaname'];
                $data['role'] = $role;
                if($res){
                    $at['id'] = 5;
                    $at['data'] = $data;
                }else{
                    $at['id'] = 2;
                }
            }
        }
        return json_encode($at);
    }

    //考勤导出
    public function actionKqdc(){
        $info = $this->init();
        return $this->render("kqdc",$info);
    }
    //导出基本考勤的信息
    public function actionExportkq(){
        $this->actionIndex();
        $sid = $this->last_sid;
        $post = \Yii::$app->request->post();
        if(!empty($post)){
            $from_time = strtotime(date($post['kq_date_from']));
            $to_time = strtotime(date($post['kq_date_to']))+86400;
            $sql = "select b.class,b.name,from_unixtime(a.ctime,'%Y-%m-%d %H:%i:%s') ts,a.info from wp_ischool_safecard a "
                ." left join wp_ischool_student b on b.id=a.stuid where b.sid=:sid and "
                ." a.ctime between :from_time and :to_time order by b.class,b.name,a.ctime asc ";
            $info = \Yii::$app->db->createCommand($sql,[':sid'=>$sid,':from_time'=>$from_time,':to_time'=>$to_time])->queryAll();
            $head = array('班级','姓名','进出时间','进出状态');
            array_unshift($info,$head);
            $excel = new \Excelses();
            $excel->download($info, 'kaoqin');
        }
    }
    //导出考勤汇总信息
    public function actionExportkqhz(){
        $this->actionIndex();
        $sid = $this->last_sid;
        $post = \Yii::$app->request->post();
        if(!empty($post)){
            $from_time = strtotime(date($post['kq_date_from']));
            $to_time = strtotime(date($post['kq_date_to']))+86400;
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
        FROM wp_ischool_safecard a LEFT JOIN wp_ischool_student b ON a.stuid=b.id AND b.sid=:sid and a.ctime BETWEEN :from_time AND :to_time GROUP BY b.class order by b.cid asc";
            $info = \Yii::$app->db->createCommand($sql,[':sid'=>$sid,':from_time'=>$from_time,':to_time'=>$to_time])->queryAll();
            unset($info[0]);
            $head = array('班级','总使用数','进校总数','出校总数','进宿舍总数','出宿舍总数');
            array_unshift($info,$head);
            $excel = new \Excelses();
//            var_dump($info);exit();
            $excel->download($info, 'kaoqinhz');
        }
    }
    //所有教师
    public function actionSyjs(){
        $info = $this->init();
        $this->actionIndex();
        $sid = $this->last_sid;
        $sql = "select * from wp_ischool_teaclass where sid=:sid AND ispass='y' AND role != '校长' order by tname asc";
        $tname = \Yii::$app->request->get('tname');
        if(!empty($tname)){
            $sql = "select * from wp_ischool_teaclass where  sid=:sid AND ispass='y' AND tname LIKE '%$tname%' order by tname asc";
        }
        $urlparam = empty($tname)?"":"&tname=".$tname;
        $infos = \Yii::$app->db->createCommand($sql,[':sid'=>$sid])->queryAll();
        $page = \yii::$app->request->get("page");
        //每页显示的条数
        $num = 15;
        $nm = count($infos);
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
        $list = \Yii::$app->db->createCommand($sql." limit ".$num." offset ".$star."",[':sid'=>$sid])->queryAll();
        $info["list"] = $list;
        $info['count'] = $nm;
        $info['start'] = "/guanli/syjs?page=1".$urlparam;
        $info['up'] = "/guanli/syjs?page=$last".$urlparam;
        $info['down'] = "/guanli/syjs?page=$next".$urlparam;
        $info['end'] = "/guanli/syjs?page=$sum".$urlparam;
        $info['yeshu'] = $sum;
        return $this->render("syjs",$info);
    }

    //删除教师
    public function actionDelteacher(){
        $id = \Yii::$app->request->post('id');
        if(!empty($id)){
            $sql = "delete from wp_ischool_teaclass where id =:id";
            $res = \yii::$app->db->createCommand($sql,[':id'=>$id])->execute();
            if($res>0||$res===0){
                return json_encode(['status'=>0]);
            }else{
                return json_encode(['status'=>1]);
            }
        }
    }

    //待审核教师
    public function actionDshjs(){
        $info = $this->init();
        $this->actionIndex();
        $sid = $this->last_sid;
        $models = WpIschoolTeaclass::find()->where(['sid'=>$sid,'ispass'=>'0']);
        $post= \Yii::$app->request->post();
        if(!empty($post)){
            $id = $post['id'];
            $ispass = $post['ispass'];
            $con['id'] = $id;
            $p= WpIschoolTeaclass::find()->select('openid,sid,cid')->where($con)->asArray()->one();
            $openid = $p["openid"];
            //将用户表sid变更
            $mn = paUser::findOne(['openid'=>$openid]);
            $mn->last_sid=$sid;
            $mn->last_cid=$p["cid"];
            $model = WpIschoolTeaclass::findOne($id);
            $model->ispass = $ispass;
            $res = $model->save(false);
            if($res>0 || $res===0){
                $at = '0';
                if($ispass == 'y'){
                    $mn->save(false);
                    $title="审核通过！";
                    $des="您绑定成为老师的请求审核成功,并且当前班级已经切换为该班级！请在点击我的服务>我的资料>我的班级中进行查看！";
                }else{
                    $title="审核未通过！";
                    $des="您申请成为老师的请求审核未成功请检查您所填写的信息或重新绑定";
                }
                $data['pic_url'] =  $this->getSchoolPic($sid);
                SendMsg::sendSHMsgToPa($openid,$title,$des,"",$data['pic_url']);
            }else{
                $at = '1';
            }
            return $at;
        }
        $pagination = new Pagination([
            'defaultPageSize' => 15,
            'totalCount' =>$models->count(),
        ]);
        $model = $models->orderBy('tname,ctime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $info['pages']=$pagination;
        $info['model'] =$model;
        return $this->render("dshjs",$info);
    }

    //已审核教师
    public function actionYshjs(){
        $this->actionIndex();
        $sid = $this->last_sid;
        $models = WpIschoolTeaclass::find()->where(['sid'=>$sid,'ispass'=>'y']);
        $post= \Yii::$app->request->post();
        if(!empty($post)){
            $id = $post['id'];
            $model = WpIschoolTeaclass::findOne($id);
            $res = $model->delete();
            if($res>0 || $res===0){
                $at = '0';
            }else{
                $at = '1';
            }
            return $at;
        }
        $pagination = new Pagination([
            'defaultPageSize' => 15,
            'totalCount' =>$models->count(),
        ]);
        $model = $models->orderBy('tname,ctime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $info['pages']=$pagination;
        $info['model'] =$model;
        return $this->render("yshjs",$info);
    }

    //权限分配
    public function actionQxfp(){

        return $this->render("qxfp");
    }

    //管理员分配
    public function actionGlyfp(){

        return $this->render("glyfp");
    }

    //编辑学校信息
    public function actionBjxxxx(){

        return $this->render("bjxxxx");
    }

    //作息时间设置
    public function actionZxsjsz(){

        return $this->render("zxsjsz");
    }

    //学校首页设置
    public function actionXxsysz(){
        $info = $this->init();
        $this->actionIndex();
        $sid = $this->last_sid;
        $info['lunbo'] = $this->getAllCarosBySid($sid);
        $model = new UploadForm();
        if (\Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file && $model->validate()) {
                if(!file_exists('ischool/uploads/picture/'.date('y/m/d',time()))){
                    mkdir('ischool/uploads/picture/'.date('y/m/d',time()),0777,true);
                }
                $picname = $this->create_random_string(13);
//                $model->file->saveAs('ischool/uploads/picture/'.date('y/m/d/',time()). $model->file->baseName . '.' . $model->file->extension);
                $model->file->saveAs('ischool/uploads/picture/'.date('y/m/d/',time()). $picname. '.' . $model->file->extension);
//                var_dump($model->file->baseName);exit();
//                $picurl = '/ischool/uploads/picture/'.date('y/m/d/',time()). $model->file->baseName . '.' . $model->file->extension;
                $picurl = '/ischool/uploads/picture/'.date('y/m/d/',time()).$picname. '.' . $model->file->extension;
                $picurl = '/ischool/uploads/picture/'.date('y/m/d/',time()).$picname. '.' . $model->file->extension;
                $sql ="insert into wp_ischool_hpage_lunbo(sid,picurl) values(:sid,:picurl)";
                $res = \Yii::$app->db->createCommand($sql,[':sid'=>$sid,':picurl'=>$picurl])->execute();
                if($res>0 || res===0){
                    echo "<script>alert('上传成功！');window.location='/guanli/xxsysz';</script>";
                }else{
                    echo "<script>alert('上传失败！');window.location='/guanli/xxsysz';</script>";
                }
            }
        }
        $info['colname'] = $this->getAllcolumn($sid);
        return $this->render("xxsysz",[
            'info'=>$info,
            'model'=>$model
        ]);
    }

    //删除幻灯片信息
    public function actionDelpic(){
        if (\Yii::$app->request->isPost)
        {
            $post = \Yii::$app->request->post();
            $ids = $post['chk_value'];
//            $ids =  join(',',$ids);

            $ids=(implode(',',$ids));
            $desql = 'select picurl from wp_ischool_hpage_lunbo WHERE FIND_IN_SET (ID,:ids)';
            $re = \Yii::$app->db->createCommand($desql,[':ids'=>$ids])->queryAll();
            foreach ($re as $k=>$v) {
                unlink($_SERVER['DOCUMENT_ROOT'].$v['picurl']);
            }
            unlink('E:\phpStudy\WWW\new\frontend\web\ischool\uploads\picture\17\11\03\pkfcb7l8b37u0.png');
            \Yii::trace($_SERVER['DOCUMENT_ROOT'].$v['picurl']);
            var_dump(__FILE__);exit();
            $sql = "DELETE FROM wp_ischool_hpage_lunbo WHERE  FIND_IN_SET (ID,:ids)";
//            $res = \Yii::$app->db->createCommand($sql,[':ids'=>$ids]);
            $res = \Yii::$app->db->createCommand($sql,[':ids'=>$ids])->execute();

            if($res>0 || $res===0){
                $at = '0';
            }else{
                $at = '1';
            }
            return $at;
        }
    }
    //获取幻灯片信息通过学校ID
    public function getAllCarosBySid($sid){
        $sql = "select * from wp_ischool_hpage_lunbo where sid = :sid order BY id DESC";
        $res = \Yii::$app->db->createCommand($sql,[':sid'=>$sid])->queryAll();
        return $res;
    }
    //获得所有的栏目信息
    public function getAllcolumn($sid)
    {
        $sql = "select * from wp_ischool_hpage_colname where sid = :sid order by id desc";
        $res = \Yii::$app->db->createCommand($sql,[':sid'=>$sid])->queryAll();
        return $res;
    }

    //编辑栏目
    public  function actionEditcol(){
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $id = $post['id'];
            $name = $post['name'];
            $sql = "update wp_ischool_hpage_colname set name = :name WHERE id=:id";
            $res = \Yii::$app->db->createCommand($sql,[':name' => $name,':id'=>$id])->execute();
            if($res>0 || $res===0){
                $at = '0';
            }else{
                $at = '1';
            }
            return $at;
        }
    }

    //删除栏目
    public  function actionDelcol(){
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $id = $post['id'];
            $sql = "delete from wp_ischool_hpage_colname  WHERE id=:id";
            $res = \Yii::$app->db->createCommand($sql,[':id'=>$id])->execute();
            if($res>0 || $res===0){
                $at = '0';
            }else{
                $at = '1';
            }
            return $at;
        }
    }

    //新增栏目
    public  function actionInsertcol(){
        $this->actionIndex();
        $sid = $this->last_sid;
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $name = $post['name'];
            $sql = "insert into wp_ischool_hpage_colname (name,sid) values(:name,:sid)";
            $res = \Yii::$app->db->createCommand($sql,[':name'=>$name,':sid'=>$sid])->execute();
            if($res>0 || $res===0){
                $at = '0';
            }else{
                $at = '1';
            }
            return $at;
        }
    }
    //外餐外宿时间设置
    public function actionWcwssjsz(){

        return $this->render("wcwssjsz");
    }

    //外餐外宿学生设置
    public function actionWcwsxssz(){

        return $this->render("wcwsxssz");
    }

    //查看外餐外宿学生
    public function actionCkwcwsxs(){

        return $this->render("ckwcwsxs");
    }

    public function actionTest()
    {
        $tos = ["oUMeDwLBklMzOqyGuxhuA-Pmzsu0"];
        $data['公告信息'] = "公告";
        $data['公告内容'] = "公告";
        $data['url'] = "";
        $result = SendMsg::broadMsgToManyUserTest($tos,$data);
        var_dump($result);
    }

    //群组交流
    public function actionQunzu(){
        $type= $_GET['type'];
        if(!empty($type)){
            $this->actionIndex();
            $userinfo = $this->userinfo;
//        var_dump($userinfo);exit();
            $openid = $userinfo[0]['openid'];
            $sid = $this->last_sid;
//            $cid = $userinfo[0]['last_cid']?:"";
            $grade_id = 0;   //年级ID
            if($sid !=1 && !empty($sid)){
                $info['chengyuan'] = paUser::find()->select('name')->where(['and','sid'=>$sid,['like','label',$type]])->asArray()->all();
                $inboxlist = $this->getqunzuinlist($sid, $grade_id,$type);    //收件人接受信息列表
                $info['pages'] = $inboxlist['pages'];
                $info['inboxlist'] = $inboxlist['dataprovider'];
                $outboxlist = $this->getqunzuoutlist($openid, $grade_id,$type);    //已发信息列表
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

    //执行群组交流信息发送
    public function actionDointerqunzu(){
        $this->actionIndex();
        $userinfo = $this->userinfo;
        $openid = $userinfo[0]['openid'];
        $sid = $this->last_sid;
        if($sid ==1){
            return json_encode(['status'=>2]);
        }
        $title = $userinfo[0]['name'];                //发件箱标题
        $grade_id = 0;   //年级ID
        $post = \yii::$app->request->post();
        if(!empty($post)){
            $type = $post['type'];
            $content = $post['content'];            //内容
            //正则去掉html标签
            if(!empty($content)){
                $preg = "/<\/?[^>]+>/i";
                $title = preg_replace($preg,'',$title);
                $des = preg_replace($preg,'',$content);
                $des = str_replace("&nbsp;","",$des);
            }
            $ctime = time();
            $ur[0]=URL_PATH."/group/index?openid=";
            $ur[1]="&sid=".$sid."&qunzu=".$type;
//            $ur[0]=URL_PATH."/group/index?openid=";
//            $ur[1]="&qunzu=".$type;
            $data['url']   = $ur;       //图文跳转链接
            $data['title'] = "来自".$userinfo[0]['name']."的消息";;  //图文消息标题
            $data['content'] = $des;    //待入库的原始消息
            $data['pic_url'] =  $this->getSchoolPic($sid);
            $qunzuopenid = $this->getAllqunzuopid($sid,$type);

//            $cid = $this->lastcid;
//            $sinfo = $this->getSchoolidbycid($cid);
//            $sid = !empty($sinfo[0]['sid'])?$sinfo[0]['sid']:1;
            $sql_inbox = "INSERT INTO `wp_ischool_qunzu` ( `content`, `outopenid`, `grade_id`,`sid`, `ctime` , `title` , `type` ) VALUES (:content,:outopenid,:grade_id,:sid,:ctime,:title,:type)";
            $inbox = \Yii::$app->db->createCommand($sql_inbox,[':content'=>$content,':outopenid'=>$openid,':grade_id'=>$grade_id,':sid'=>$sid,':ctime'=>$ctime,':title'=>$title,':type'=>$type])->execute();
            if($inbox){
                foreach($qunzuopenid as $newopid){
                    $tos[] = $newopid['openid'];
                }
                $result = SendMsg::muiltPostMsg($tos,$data);
                return json_encode(['status'=>0]);
            }else{
                return json_encode(['status'=>1]);
            }
        }
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
            $sid = $this->last_sid;
            $grade_id = 0;
            if($sid ==1){
                echo "<script>alert('您还不是校长，请先联系客服绑定校长，谢谢！');window.location='/guanli/index';</script>";
            }
            $model = new UploadForm();
            if (\Yii::$app->request->isPost)
            {
                $model->file = UploadedFile::getInstance($model, 'file');
//                var_dump($model->file);exit();
                if(empty($model->file)){
                    echo "<script>alert('请选择您要上传的文件！');window.location='/guanli/wdgl?type=".$type."';</script>";
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
                    // $picurl = '/ischool/uploads/wdgl/'.date('y/m/d/',time()).$picname. '.' . $model->file->extension;    //图片重命名
//                    $picurl = '/ischool/uploads/wdgl/'.date('y/m/d/',time()).$picname. '.' . $model->file->extension;
                    $sql ="insert into attachment(openid,create_time,sid,title,url,grade_id,type,name) values(:openid,:create_time,:sid,:title,:url,:grade_id,:type,:name)";
                    $res = \Yii::$app->db->createCommand($sql,[':openid'=>$openid,':create_time'=>time(),':sid'=>$sid,':title'=>$title,':url'=>$picurl,':grade_id'=>$grade_id,':type'=>$type,':name'=>$name])->execute();
                    if($res>0 || $res===0){
                        $model->file->saveAs('ischool/uploads/wdgl/'.date('y/m/d/',time()). $picname. '.' . $model->file->extension);
                        echo "<script>alert('上传成功！');window.location='/guanli/wdgl?type=".$type."';</script>";
                    }else{
                        echo "<script>alert('上传失败！');window.location='/guanli/wdgl';</script>";
                    }
                }
            }
            if($sid !=1 && !empty($sid)){
                $inboxlist = $this->getwdgllist($sid, $grade_id,$type);    //收件人接受信息列表
                $info['pages'] = $inboxlist['pages'];
                $info['inboxlist'] = $inboxlist['dataprovider'];
                $outboxlist = $this->getwdgltlist($openid, $grade_id,$type);    //已发信息列表
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

    //文档管理收件箱删除信息
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
        $this->actionIndex();
        $userinfo = $this->userinfo;
        $tid = $userinfo[0]['id'];
        $lastsid = $this->last_sid;
        $lastcid= $userinfo[0]['last_cid']?:"";
        if($lastsid ==1){
            echo "<script>alert('您还不是校长，请先联系客服绑定校长，谢谢！');window.location='/guanli/index';</script>";
        }
        $toname = $userinfo[0]['name'];
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
                $size = $model->file->size;
//                if ($size >5242880)     //  5兆
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
                    echo "<script>alert('添加成功！');window.location='/guanli/shenpi';</script>";
                }else{
                    echo "<script>alert('添加失败！');window.location='/guanli/shenpi';</script>";
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