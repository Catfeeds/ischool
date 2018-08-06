<?php
namespace mobile\controllers;

use mobile\models\WpIschoolStudent;
use mobile\models\WpIschoolUserRole;
use yii\web\HttpException;
use backend\models\WpIschoolSchool;
use backend\models\WpIschoolClass;
use backend\models\WpIschoolTeacher;
use mobile\models\WpIschoolTeaclass;
use lee\utils\Excel;
use mobile\models\WpIschoolRole;
use mobile\models\WpIschoolRolePurview;
use mobile\models\WpIschoolPurview;
use mobile\models\WpIschoolProvince;
use mobile\models\WpIschoolSchooltype;
use mobile\models\WpIschoolCity;
use mobile\models\WpIschoolArea;
use mobile\models\WpIschoolUser;
use mobile\assets\Helper;
use mobile\models\WpIschoolAccessToken;
use mobile\models\WpIschoolPastudent;
use mobile\models\WpIschoolHpageLunbo;
use mobile\models\WpIschoolAdminer;
use mobile\models\WpIschoolHpageColname;
use mobile\models\WpIschoolHpageColcontent;
use common\models\WeiXinSendMsg;
use mobile\models\WpIschoolMsgcount;
use mobile\models\WpIschoolSchoolManageEpc;
use mobile\assets\SendMsg;

class ManagerController extends \mobile\controllers\BaseController
{


	public function init(){
		parent::init();
	}
	public function beforeAction($action)
	{
		parent::beforeAction($action);

		if($action->id == "index")
		 $this->layout = "manager";
		 else $this->layout = false;
		 return true;
	}
	public function actionIndex()
	{
		$sid=\yii::$app->request->get("sid");
		$where["openid"]=$this->openid;
		$where["rid"]=1;
		$where["shenfen"]="school";

		$res= WpIschoolUserRole::find()->where($where)->asArray()->all();
		//select * from wp_ischool_user_role where openid='oTzscuHxIwLl7Ejo5RJ4Mdug7z9k' and rid=1;
		//if(empty($res)) throw new HttpException(404);
		$flag = true;
		$render_array = array();

		foreach($res as $k=>$v){
			if($v['shenfen']=="school"){
				$render_array['school'] = $v['shenfen'];
				$flag = false;
				break;
			}
		}
		if($flag){
			$render_array['school']=$res[0]["shenfen"];
		}
		$helper = new Helper();
		$school_info = $helper->getSchool($sid);
		$render_array['ischool']=$school_info['name'];
		$render_array['sid']=$sid;
		return $this->render('index',$render_array);
	}
	public function actionAddclass()
	{
		$sid=$this->sid;
		$render_array = [];
		$render_array['sid']=$sid;
		$where["id"]=$sid;
		$res= WpIschoolSchool::findOne($where);
		//if(empty($res)) throw  new HttpException(404);
		$type=$res["schtype"];
		$num = array();
		for($i=1;$i<41;$i++){
			$num[] = $i;
		}
		$render_array['num'] = $num;
		$render_array['type']=$type;

		return $this->render("addclass",$render_array);
	}
	public function actionGetteaclass()
	{
		$cid = \yii::$app->request->get("cid");
		$con['cid']=$cid;
		//$d=D('ischool_teaclass');
		$res= WpIschoolTeaclass::find()->where($con)->select('id,tname,role')->asArray()->all();
		$res2['result']='success';
		$res2['data']=$res;
		\Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
		return $res2;
	}
	public function actionSaveconfigclass()
	{
		//$data['school']=I('get.school');
		//$data['school'] = \yii::$app->request->get("school");
		$data['class']=\yii::$app->request->get("classname");
		$data['cid']=\yii::$app->request->get("cid");
		$data['ctime']=time();
		$data['sid']=$this->sid;
		
		$res=WpIschoolSchool::findOne($this->sid);
		$data['school']=$res["name"];

		$data['tname']=\yii::$app->request->get("tname");
		$data['openid']=$this->openid;
		$data['role']=\yii::$app->request->get("role");
		$data['ispass']="y";
		//$d=D('ischool_teaclass');

		$where["openid"]=$data["openid"];
		$where["role"]=$data["role"];
		$where["cid"]=$data["cid"];
		//$res=$d->where($where)->select();
		$res = WpIschoolTeaclass::find()->where($where)->asArray()->all();

		if(!empty($res))
		{
			$data2['flag']='tre';
		}
		else
		{
			$d = new WpIschoolTeaclass();
			$d->setAttributes($data);
			$d->save(false);
			if($res>1||$res===0){
				$data['id']=$res;
				$data2['data']=$data;
				$data2['flag']='success';
				//$this->ajaxReturn($data2,'json');
			}else{
				$data2['flag']='fail';
				//$this->ajaxReturn($data2,'json');
			}
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data2;
	}
	public function actionDownkaoqin()
	{
		return $this->render ( "downkaoqin" );
	}
	private function downKaoqinExcel($sid, $from_time, $to_time) {
		$filename = "kq-" . $sid . "-" . substr ( time (), - 5, 5 ) . ".xls";
		$excel_settings = array (
				"savePath" => \yii::$app->basePath. "/web/upload/kaoqin/" . $filename,
				"title" => "考勤信息导出"
		);
		$excelTitle = array (
				array (
						'班级',
						'姓名',
						'进出时间',
						'进出状态'
				)
		); // 首行标题
		$sql = "select b.class,b.name,from_unixtime(a.ctime,'%Y-%m-%d %H:%i:%s') ts,a.info from wp_ischool_safecard a " . " left join wp_ischool_student b on b.id=a.stuid where b.sid=" . $sid . " and " . " a.ctime between " . $from_time . " and  " . $to_time . " order by b.class,b.name,a.ctime asc ";
		//$data = M ()->query ( $sql );
                
		$data = \yii::$app->db->createCommand($sql)->queryAll();
                
		if ($data) {
			array_splice ( $data, 0, 0, $excelTitle );
		} else {
			$data = $excelTitle;
		}
		$excel = new Excel ();
		$excel->write_to_file ( $excel_settings, $data );

		return   "/upload/kaoqin/" . $filename;
	}
	public function actionDodownkaoqin()
	{
		$sid = $this->sid;
		$openid = $this->openid;
		//$downtime = I("param.downtime");
		$downtime = \yii::$app->request->get('downtime');
		$endtime = \yii::$app->request->get('endtTime');
                
		//$date = new Date();
		$from = strtotime($downtime);
		$to = strtotime($endtime)+ 3600*24;
		$result = array("flag"=>0,"url"=>$this->downKaoqinExcel($sid,$from,$to));
		$this->ajaxReturn($result,'json');
	}
	private function downKaoqinhzExcel($sid,$from_time,$to_time){

		$filename = "kqhz-".$sid."-".substr(time(),-5,5).".xls";
		$excel_settings = array(
				"savePath" => \yii::$app->basePath. "/web/upload/kaoqin/" . $filename,
				"title"=>"考勤信息导出"
		);

		$excelTitle = array(array('班级','总次数','总进校次数','总出校次数','总进宿舍次数','总出宿舍次数')); //首行标题
		$sql = "SELECT   b.class 班级,count(info) 总使用数,
		COUNT(CASE WHEN info like '%进校%' THEN 1
		ELSE NULL
		END) 进校总数,
		COUNT(CASE WHEN  info like '%出校%' THEN 2
		ELSE NULL
		END) 出校总数,
		COUNT(CASE WHEN  info like '%进宿舍%' THEN 3
		ELSE NULL
		END) 进宿舍总数,
		COUNT(CASE WHEN  info like '%出宿舍%' THEN 4
		ELSE NULL
		END) 出宿舍总数
		FROM wp_ischool_safecard a LEFT JOIN wp_ischool_student b ON a.stuid=b.id AND b.sid='$sid'
		and a.ctime BETWEEN '$from_time' AND '$to_time' GROUP BY b.class order by b.cid asc";
		//$data = M()->query($sql);
		$data = \yii::$app->db->createCommand($sql)->queryAll();
		unset($data[0]);
		/*       echo M()->getLastSql();
		 exit();*/
		/*     echo "<pre>";
		 print_r($data);*/
		if($data) {
			array_splice($data, 0, 0, $excelTitle);
		}else{
			$data = $excelTitle;
		}

		$excel = new Excel();
		$excel->write_to_file($excel_settings,$data);
		return "/upload/kaoqin/" . $filename;
	}
	public function actionDodownkaoqinhz()
	{
		$sid = $this->sid;
		$openid = $this->openid;
		$downtime = \yii::$app->request->get('downtime');
		$endtime = \yii::$app->request->get('endtTime');

		$from = strtotime($downtime);
		$to = strtotime($endtime)+ 3600*24;
		$result = array("flag"=>0,"url"=>$this->downKaoqinhzExcel($sid,$from,$to));
		$this->ajaxReturn($result,'json');
	}
	public function actionDoaddclass()
	{
		$sid=$this->sid;
		//$classname=I('get.classname');
		$classname = \yii::$app->request->get("classname");
		$grade = \yii::$app->request->get("grade");
		$ctime = time();
		//$d=D('ischool_class');
		//幼教的年级
		$youjiao[1]="小";
		$youjiao[2]="中";
		$youjiao[3]="大";
		//年级
		$level[1]="一";
		$level[2]="二";
		$level[3]="三";
		$level[4]="四";
		$level[5]="五";
		$level[6]="六";
		$level[7]="七";
		$level[8]="八";
		$level[9]="九";
		//班级
		$class[1]="一";
		$class[2]="二";
		$class[3]="三";
		$class[4]="四";
		$class[5]="五";
		$class[6]="六";
		$class[7]="七";
		$class[8]="八";
		$class[9]="九";
		$class[10]="十";
		$class[11]="十一";
		$class[12]="十二";
		$class[13]="十三";
		$class[14]="十四";
		$class[15]="十五";
		$class[16]="十六";
		$class[17]="十七";
		$class[18]="十八";
		$class[19]="十九";
		$class[20]="二十";
		$class[21]="二十一";
		$class[22]="二十二";
		$class[23]="二十三";
		$class[24]="二十四";
		$class[25]="二十五";
		$class[26]="二十六";
		$class[27]="二十七";
		$class[28]="二十八";
		$class[29]="二十九";
		$class[30]="三十";
		$class[31]="三十一";
		$class[32]="三十二";
		$class[33]="三十三";
		$class[34]="三十四";
		$class[35]="三十五";
		$class[36]="三十六";
		$class[37]="三十七";
		$class[38]="三十八";
		$class[39]="三十九";
		$class[40]="四十";
		$helper = new Helper();
		$schoolinfo = $helper->getSchool($sid);
		$school=$schoolinfo['name'];
		$type=$schoolinfo["schtype"];
		 
		foreach ($class as $k => $v) {
			if($k<=$classname)
			{
				if($type=="幼教")
				{
					$name=$youjiao[$grade].$v."班";
				}
				else
				{
					$name=$level[$grade].$v."班";
				}
				$con['name'] = $name;
				$con['sid']  = $sid;
				$oneClass = \yii::$app->db->createCommand("select * from wp_ischool_class where name='".$name."' and sid =".$sid)->queryOne();
				//$oneClass = $d->where($con)->field('id')->select();
				if(empty($oneClass)){

					//$d=D('ischool_class');
					//$d->add($data);
					$d = new WpIschoolClass();
					$d->class = $k;
					$d->name = $name;
					$d->sid = $sid;
					$d->school = $school;
					$d->ctime = $ctime;
					$d->flag = 'c';
					$d->level = $grade;
					$d->save(false);
				}
			}
		}
		\Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
		return 0;
	}

	public function actionConfigclass()
	{
		$openid=$this->openid;
		$sid=$this->sid;
		$con['sid']=$sid;
                $con['ispass']='y';
		//$d=D('ischool_teacher');
		//$res=$d->where($con)->order("convert(tname using'gbk') asc")->select();
		$res = WpIschoolTeaclass::find()->where($con)->groupBy('openid')->orderBy('convert(tname using gbk) asc')->asArray()->all();
		//$m = M();
		//$res2 = $m->query("select * from wp_ischool_class where sid=".$sid." and (flag='k' or flag='c')");
		$res2 = \yii::$app->db->createCommand("select * from wp_ischool_class where sid=".$sid." and (flag='k' or flag='c')")->queryAll();
		$render = [];
		$render['openid'] = $openid;
		$render['list_class']=$res2;
		$render['list_teacher']=$res;
		$render['sid']=$sid;
		//$this->display();
		return $this->render("configclass",$render);
	}

	public function actionDoaddclasscustomer()
	{
		$sid = \yii::$app->request->get("sid");
		$classname = \yii::$app->request->get('classname');
		$grade     = \yii::$app->request->get('grade');
		$school    = \yii::$app->request->get('school');
		$ctime     = time();
		//$d=D('ischool_class');
		$wh["sid"]  = $sid;
		$wh["name"] = $classname;
		$res= WpIschoolClass::findOne($wh);
		\Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
		if(empty($res)){
			$d = new WpIschoolClass();

			//$d=D('ischool_class');
			$d->sid = $sid;
			$d->school = $school;
			$d->name = $classname;
			$d->ctime = $ctime;
			$d->flag = 'c';
			$d->level = $grade;
			$d->class = 0;
			$result = $d->save(false);
			if($result>0 || $result===0){
				//$this->ajaxReturn(0,'json');
				return 0;
			}else{
				//$this->ajaxReturn(2,'json');
				return 2;
			}
			 
		}else{
			//$this->ajaxReturn(1,'json');
			return 1;
		}
	}
	public function actionGotoallclass()
	{
		return $this->actionAllclass();
	}
	public function actionAllclass()
	{
		//$path=C("URL_PATH");
		$openid = $this->openid;
		$sid=$this->sid;
		$where["sid"]=$sid;
		 
		$nm = WpIschoolClass::find()->where($where)->count();
		//$page=I('get.page');
		$page = \yii::$app->request->get("page");
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
		 
		$render_array = [];
		$render_array['sum']=$nm;
		$render_array['totalPage']=$sum;
		$render_array['start']="/manager/gotoallclass?page=1";
		$render_array['up']="/manager/gotoallclass?page=".$last;
		$render_array['down']="/manager/gotoallclass?page=".$next;
		$render_array['end']="/manager/gotoallclass?page=".$sum;
		 	
		$res = WpIschoolClass::find()->where($where)->limit($num)->offset($star)->orderBy('level,id')->asArray()->all();
		//$res=$m->where($where)->limit($star,$num)->order("level,class")->select();
		//$m=M("ischool_teaclass");
		 // var_dump($res);
		foreach ($res as $k=>$v) {
			$wh["cid"]=$v["id"];
			//             $wh["role"]="班主任";
			$wh["ispass"]="y";
			//$rq=$m->where($wh)->select();
			$rq = WpIschoolTeaclass::find()->where($wh)->asArray()->all();
			$tnameStr = "";
			foreach ($rq as $v){
				$tnameStr .= $v['tname'].'/';
			}
			$res[$k]['tname'] = substr($tnameStr, 0, -1);
		}
		 
		$render_array['list_class'] = $res;

		$render_array['sid'] = $sid;
		return $this->render("allclass",$render_array);
		//$this->display();
	}
	public function actionGetpasseduser()
	{
		$openid=$this->openid;
		$sid=$this->sid;
		//$m=D('ischool_teaclass');
		$con['ispass'] = 'y';
		$con['sid'] = $sid;
		$nm = WpIschoolTeaclass::find()->where($con)->count();
		//$nm=$m->where($con)->count();

		$page = \yii::$app->request->get("page");
		//每页显示的条数
		$num=5;
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
		 
		 
		 
		$render_array = [];
		$render_array['count']=$nm;
		$render_array['totalPage']=$totalPage;
		$render_array['start']="/manager/getpasseduser?page=1&uname=".$uname;
		$render_array['up']="/manager/getpasseduser?page=$last&uname=".$uname;
		$render_array['down']="/manager/getpasseduser?page=$next&uname=".$uname;
		$render_array['end']="/manager/getpasseduser?page=$sum&uname=".$uname;


		$res = WpIschoolTeaclass::find()                       
		->where($con)
                ->andwhere(['<>','class','管理'])
		->limit($num)
		->offset($star)	
		->asArray()
		->all();
		 
		$render_array['list_user']=$res;
		//$this->display();
		return $this->render("getpasseduser",$render_array);
	}
	public function actionGetnopassuser(){
		$openid=$this->openid;
		$sid=$this->sid;

		//$m=D('ischool_teaclass');
		$con['ispass'] = '0';
		$con['sid'] = $sid;
		$nm=WpIschoolTeaclass::find()->where($con)->count();
		$page=\yii::$app->request->get("page");
		//每页显示的条数
		$num=5;
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
		 
		$render_array = [];
		$render_array['count']=$nm;
		$render_array['totalPage']=$totalPage;
		$render_array['start']="/manager/getnopassuser?page=1";
		$render_array['up']="/manager/getnopassuser?page=$last";
		$render_array['down']="/manager/getnopassuser?page=$next";
		$render_array['end']="/manager/getnopassuser?page=$sum";
		 
		 

		$res = WpIschoolTeaclass::find()
		->where($con)
		->limit($num)
		->offset($star)
		//->orderBy('convert(tname using gbk)')
		->asArray()
		->all();

		$render_array['list_user'] = $res;

		return $this->render("getnopasseduser",$render_array);
		//$this->display();
	}
	public function actionAlluser()
	{
		$openid = $this->openid;
		$sid    = $this->sid;
		$uname  = \yii::$app->request->get("uname");

		//$m=D('ischool_teacher');
		$con = " sid = $sid and ispass = 'y'";
		if(!empty($uname)){
			//$con  .= ('like','tname','%'.$uname.'%');
			$con .= " and tname like '%$uname%'";
		}
		$nm=\mobile\models\WpIschoolTeaclass::find()->where($con)->count();

		$page = \yii::$app->request->get("page");
		//$page=I('get.page');
		//每页显示的条数
		$num=10;
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
		$render_array = [];
		$render_array['count']=$nm;
		$render_array['totalPage']=$totalPage;
		$render_array['start']="/manager/alluser?page=1&uname=".$uname;
		$render_array['up']="/manager/alluser?page=$last&uname=".$uname;
		$render_array['down']="/manager/alluser?page=$next&uname=".$uname;
		$render_array['end']="/manager/alluser?page=$sum&uname=".$uname;

		$res = \mobile\models\WpIschoolTeaclass::find()
		->where($con)
		->select("id,tname,tel,openid")
		->limit($num)
		->offset($star)
		->groupBy('openid')
		->asArray()
		->all();

		//$res=$m->where($con)->field("id,tname,tel,openid")->limit($star,$num)->order('convert(tname using gbk)')->select();

		$render_array['openid'] = $this->openid;
		$render_array['sid'] = $this->sid;
		$render_array['uname'] = $uname;
			

		foreach($res as $k=>$v){
			$role = $this->getRole($sid,$res[$k]['tname'],$res[$k]['openid']);
			$role1 = $this->arr2str($role);
			if(empty($role1)){
				$res[$k]['role'] = "老师";
			}else{
				$res[$k]['role'] = $role1;
			}
		}
		//$this->list_user=$res;
		$render_array['list_user'] = $res;
		return $this->render("alluser",$render_array);
	}
	function arr2str ($arr)
	{
		foreach ($arr as $v)
		{
			$v = join(",",$v); //可以用implode将一维数组转换为用逗号连接的字符串
			$temp[] = $v;
		}
		$t="";
		foreach($temp as $v){
			$t.=$v."-";
		}
		$t=substr($t,0,-1);
		return $t;
	}
	function getRole($sid,$tname,$openid){
		$where['tname'] = $tname;
		$where['openid'] = $openid;
		$where['sid'] = $sid;
		//$m=D('ischool_teaclass');
		//$res = $m->where($where)->field('DISTINCT(role)')->select();
		$res = WpIschoolTeaclass::find()->where($where)->select('DISTINCT(role)')->asArray()->all();
		return $res;
	}

	public function actionCheckuser(){
              
		$tid=\yii::$app->request->post("tid");              
		$ispass=\yii::$app->request->post("ispass");              
		$con['id'] = $tid;              
		$d = WpIschoolTeaclass::findOne($con);
               
		$d->ispass =$ispass == 'y' ? "y":"n";
		$res = $d->save(false);
               
                $p= WpIschoolTeaclass::find()->select('openid,sid,cid')->where($con)->asArray()->one();
                
		$openid = $p["openid"];
		$sid = $p["sid"];                
		if($ispass=='y'){
            //将用户表sid变更
            $mn = WpIschoolUser::find()->where(['openid'=>$openid])->one();           
            $mn->last_sid=$sid;
            $mn->last_cid=$p['cid'];
            $mn->save(false);         
			$con2['openid'] = $openid;
			$con2['sid'] = $sid;
			$m = WpIschoolTeacher::findOne($con2);
                        if($m){             
                            $m->ispass =$ispass;
                            $m->save(false);
                          }

			$title="审核通过！";
			$des="您绑定成为老师的请求审核成功,请在点击我的服务>我的资料>我的班级中进行查看";
                     
		}else{
			$con2['openid'] = $openid;
			$con2['sid'] = $sid;
			$con2['ispass'] = 0;
			//$m = M("ischool_teacher");
			$m = WpIschoolTeacher::findOne($con2);
			if($m)$m->delete();
			$title="审核未通过！";
			$des="您申请成为老师的请求审核未成功请检查您所填写的信息或重新绑定";
		}
		$data['picurl'] =$this->getSchoolPics($sid);
		SendMsg::sendSHMsgToPa($openid,$title,$des,"",$data['picurl']);
             
		if($res){
			$this->ajaxReturn('success','json');
		}else{
			$this->ajaxReturn('fail','json');
		}
	}

	public function actionDeleteuser(){
		$tid = \yii::$app->request->post("tid");
		$con['id'] = $tid;

		$res = WpIschoolTeaclass::findOne($con)->delete();
		if($res>0||$res===0){
			$this->ajaxReturn('success','json');
		}else{
			$this->ajaxReturn('fail','json');
		}
	}

	public function actionEditrolepro()
	{
		$sid=$this->sid;
		$con['sid']=$sid;
		//$d=D('ischool_role');
		//$res=$d->where($con)->select();
		$res = WpIschoolRole::find()->where($con)->asArray()->all();
		//若当前学校没有任何角色，默认插入首页管理2，公告管理3，动态管理7
		if(!$res){
			//role-pid
			$sysRole = "首页管理员-2;公告管理员-3;动态管理员-7";
			$sysRoleArr = explode(";",$sysRole);
			foreach($sysRoleArr as $k=>$v){
				$role = explode("-",$v);
				$pid  = $role[1];
				$role = $role[0];
				$data = array();
				$data['name'] = $role;
				$data['sid']  = $sid;
				$model = new WpIschoolRole();
				$model->setAttributes($data);
				$rid = $model->save(false);
				//$rid = $d->add($data);
				if($rid){
					$data2 = array();
					$data2['rid'] = $rid;
					$data2['pid'] = $pid;
					//$d2 = M("ischool_role_purview");
					$d2 = new WpIschoolRolePurview();
					$d2->setAttributes($data2);
					$d2->save(false);
				}
			}
			//$res=$d->where($con)->select();
			$res = WpIschoolRole::find()->where($con)->asArray()->all();
		}
		//$d=D('ischool_purview');
		//$where["id"]=array("neq",1);
		//$where['type']='xx';  //和教育局角色区分
		//$res2=$d->where($where)->select();
		
		$res2 = WpIschoolPurview::find()->where("id !=1 and type='xx'")->asArray()->all();

		$render_array = [];
		$render_array['list_purview']=$res2;
		$render_array['list_role']=$res;
		$render_array['sid']=$sid;
		//$this->display();
		return $this->render("editrolepro",$render_array);
	}

	public function actionGetrolepro(){
		//$rid = I('get.rid');
		$rid = \yii::$app->request->get("rid");
		$con['rid']=$rid;
		//$d=D('ischool_role_purview');
		//$res=$d->where($con)->field('pid')->select();
		$res = WpIschoolRolePurview::find()->where($con)->select('pid')->asArray()->all();
		$res2['result']='success';
		$res2['data']=$res;
		$this->ajaxReturn($res2,'json');
	}

	/*  保存角色对应权限 */
	public function actionSaverolepro(){

		$role=\yii::$app->request->get("role");
		$con['rid'] = $role;
		//$m=M("ischool_role_purview");
		WpIschoolRolePurview::deleteAll($con);
		$purview=\yii::$app->request->get("purview");
		$purview = explode('-',substr($purview, 0,-1));
		//$d=D('ischool_role_purview');
		$data['rid']=$role;
		foreach ($purview as $pid) {
			$d = new WpIschoolRolePurview();
			$data['pid']=$pid;
			$d->setAttributes($data);
			$d->save(false);
		}

		$this->ajaxReturn('success','json');
	}

	public function actionDeleteconfigclass(){
		$con['id']=\yii::$app->request->get("tcid");
		//$m=M('ischool_teaclass');
		//$res=$m->where($con)->delete();
		$res = WpIschoolTeaclass::deleteAll($con);
		if($res>0||$res===0){
			$data['result']='success';
		}else{
			$data['result']='fail';
		}
		$this->ajaxReturn($data,'json');
	}

	/*  新建角色 */
	public function actionSaverole(){
		$role=\yii::$app->request->get("role");
		$sid=$this->sid;
		$helper = new Helper();
		$school=$helper->getSchool($sid);
		//$d=D('ischool_role');
		$d = new WpIschoolRole();
		$data['name']=$role;
		$data['sid']=$sid;
		$data['school']=$school;
		$d->setAttributes($data);
		$d->save(false);
		$res = $d->attributes['id'];

		$con['id']=$res;
		//$res2=$d->where($con)->field('id,name')->select();
		$res2 = WpIschoolRole::find()->where($con)->asArray()->all();
		if($res2){
			$res3['f']='success';
			$res3['data']=$res2;
		}else{
			$res3['f']='fail';
		}
		$this->ajaxReturn($res3,'json');
	}

	/*  删除角色 */
	public function actionDeleterole(){
		$rid=\yii::$app->request->post("rid");
		//$m=M('ischool_role');
		$con['id']=$rid;
		//$res = $m->where($con)->delete();
		$res = WpIschoolRole::deleteAll($con);
		if($res>0||$res===0){
			$this->ajaxReturn('success','json');
		}else{
			$this->ajaxReturn('fail','json');
		}
	}

	public function actionSaveuserrole() {
		$sid = \yii::$app->request->get("sid");
		$user = \yii::$app->request->get("uid");
		$uname = \yii::$app->request->get("uname");
		$con ['openid'] = $user;
		$openid = $user;
		//$m = M ( "ischool_user_role" );
		//$m->where ( $con )->delete ();
		WpIschoolUserRole::deleteAll("sid = $sid and rid != 1");
		$roles = \yii::$app->request->get("roleids");
		$school = \yii::$app->request->get("school");
		$roles = explode ( '-', substr ( $roles, 0, - 1 ) );

		//$d = D ( 'ischool_user_role' );
		$puid = "";
		foreach ( $roles as $rid ) {
			$daat = new WpIschoolUserRole();
			$daat->sid = $sid;
			$daat->school = $school;
			$daat->openid = $user;
			$daat->name = $uname;
			$daat ->rid = $rid;
			$daat->save(false);
			//$d->add ( $daat );
		}
		$pl = "";
		self::saveAccessList ( $openid, $sid );
		$de = self::checkAccess ( "ManageCard" );

		if ($de) {
			$this->GiveEpcrole ( $pl, $user, $sid );
		} else {
			$this->CancelEpc ( $pl, $user, $sid );
		}

		$this->ajaxReturn ( "success", 'json' );
	}
	public function GiveEpcrole($pl,$user,$sid){
		//$m=D('ischool_school_manage_epc');
		$where['openid']=$user;
		//$arr=$m->where($where)->select();
		$arr = WpIschoolSchoolManageEpc::find()->where($where)->asArray()->all();
		if(empty($arr)){
			//$u=D('ischool_user');
			$uu['openid']=$user;
			//$ar=$u->where($uu)->field('tel')->select();
			$ar = WpIschoolUser::find()->where($uu)->asArray()->all();
			$pwd=substr($ar[0]['tel'],-6);
			$m = new WpIschoolSchoolManageEpc();
				
			$f->name=$ar[0]['tel'];
			$f->pwd=md5($pwd);
			$f->openid = $user;
			//$f['openid']=$user;
			//$f['ctime']=time();
			$f->sid = $sid;
			$f->ctime = time();
			$pin = $m->save(false);
			//$pin=$m->add($f);
			$ss['id']=$sid;
				
			//$o=D('ischool_school');
			$oo = \mobile\models\WpIschoolSchool::find()->where($ss)->asArray()->all();
			//$oo=$o->where($ss)->field('name')->select();
			$school=$oo[0]['name'];
			if($pin){
				$title="来自正梵智慧校园的消息通知";
				$openid=$user;
				$des="尊敬的".$ar[0]['tel']."用户,您已成为".$school."的平安通知管理员。";                              
				$data = '{
                        "touser":"'.$openid.'",
                        "msgtype":"text",
                         "text":
                          {
                               "content":"'.$msg.'"
                          }
                        }';
			}
			$data['picurl'] =$this->getSchoolPics($sid);
			SendMsg::sendSHMsgToPa($user,$title,$des,$url="",$data['picurl']);
			// SendMsg::https_post(SendMsg::getUrl('kf'),$data);
		}
	}
	public function actionGetuserrole(){
		$openid = \yii::$app->request->get("uid");
		$sid =\yii::$app->request->get("sid");
		//$d=D('ischool_user_role');
		$res = WpIschoolUserRole::find()->where("openid = '$openid' and sid = $sid and rid != 1 ")->asArray()->all();
		//$res=$d->where($con)->field('rid')->select();
		$res2['result']='success';
		$res2['data']=$res;
		$this->ajaxReturn($res2,'json');
	}

	/** 取消平安卡管理员身份*/
	public function CancelEpc($pid,$user,$sid){
		//$m=D('ischool_school_manage_epc');
		$where['openid']=$user;
		//$arr=$m->where($where)->select();
		$arr = WpIschoolSchoolManageEpc::find()->where($where)->asArray()->all();
		if($arr){
			//$da=$m->where($where)->delete();
			WpIschoolSchoolManageEpc::deleteAll($where);
			$ss['id']=$sid;
				
			$oo = \mobile\models\WpIschoolSchool::find()->where($ss)->asArray()->all();
			$school=$oo[0]['name'];
			if($da){
				$open=$user;
				$des="尊敬的".$arr[0]['name']."用户,您已被取消".$school."5、平安通知管理员的资格,如有疑问，请联系".$school."管理员";
				$data = '{
                    "touser":"'.$open.'",
                    "msgtype":"text",
                    "text":
                    {
                    "content":"'.$msg.'"
                    }
                    }';
			}
			 $title="来自正梵智慧校园的消息通知";
			$data['picurl'] =$this->getSchoolPics($sid);
        	 SendMsg::sendSHMsgToPa($user,$title,$des,$url="",$data['picurl']);
			// SendMsg::https_post(SendMsg::getUrl('kf'),$data);
		}
	}

	/*  用户角色分配*/
	public function actionEdituserrole(){
		$sid=$this->sid;
		$con['sid']=$sid;
		//$d=D('ischool_teacher');
		//$res=$d->where($con)->order("convert(tname using'gbk') asc")->select();
		$res = WpIschoolTeacher::find()->where($con)->asArray()->all();

		//$d=D('ischool_role');
		//$res2=$d->where($con)->select();
		$res2 =  WpIschoolRole::find()->where($con)->asArray()->all();
		//若该校没有尚没有角色，默认插入几个
		if(!$res2){
			//role-pid
			$sysRole = "首页管理员-2;公告管理员-3;动态管理员-7";
			$sysRoleArr = explode(";",$sysRole);
			foreach($sysRoleArr as $k=>$v){
				$role = explode("-",$v);
				$pid  = $role[1];
				$role = $role[0];
				//$rid = $d->add($data);
				$d = new WpIschoolRole();
				$d -> name = $role;
				$d ->sid = $sid;
				$d->save(false);
				if($rid){
					//$d2 = M("ischool_role_purview");
					//$d2->add($data2);
					$d2 = new WpIschoolRolePurview();
					$d2->rid = $rid;
					$d2->pid = $pid;
					$d2->save(false);
				}
			}
			//$res2=$d->where($con)->select();
			$res2 = WpIschoolRole::find()->where($con)->asArray()->all();
		}
		$render_array = [];
		$render_array['list_role'] = $res2;
		$render_array['list_teacher'] = $res;
		$render_array['sid'] = $sid;
		//$this->display();
		return $this->render("edituserrole",$render_array);
	}
	public function actionSuper()
	{
		$openid=$this->openid;
		$sid=$this->sid;
             
		$render_array = array();
		$render_array['openid']=$openid;
		$render_array['sid']=$sid;
		return $this->render("super",$render_array);
	}
	function actionGetcitybyprovince(){
		$code = \yii::$app->request->get("code");
		//$code=I('get.code');
		//$m=M('ischool_city');
		$con['provincecode']=$code;
		//$res=$m->where($con)->select();
		$res = WpIschoolCity::find()->where($con)->asArray()->all();
		$this->ajaxReturn($res,'json');
	}

	/*  新增学校页面由市抓县 */
	public function actionGetcountrybycity(){
		$code = \yii::$app->request->get("code");

		//$m=M('ischool_area');
		$con['citycode']=$code;
		$res = WpIschoolArea::find()->where($con)->asArray()->all();
		//$res=$m->where($con)->select();
		$this->ajaxReturn($res,'json');
	}
	public function actionSaveschool(){

		$sccity = \yii::$app->request->get("sccity");
		$scarea = \yii::$app->request->get("scarea");
		$sctype = \yii::$app->request->get("sctype");
		$scname = \yii::$app->request->get("scname");
		$scpro = \yii::$app->request->get("scpro");
		$scsid = \yii::$app->request->get("scsid");
		//根据编号查询出市的名字
		//$m=M("ischool_city");
		$where["code"]=$sccity;
		//$city=$m->where($where)->select();
		$city = WpIschoolCity::find()->where($where)->asArray()->all();
		$sccity=$city[0]["name"];
		$where="";
		//根据编号查询出省的名字
		//$m=M("ischool_province");
		$where["code"]=$scpro;
		//$pro=$m->where($where)->select();
		$pro = WpIschoolProvince::find()->where($where)->asArray()->all();
		$scpro=$pro[0]["name"];
		$where="";

		//更新学校表的信息
		//$m=M("ischool_school");
		$where[] = "and";
		$where[]=array("=","pro",$scpro);
		$where[]=array("=","name",$scname);
		$where[]=array("=","city",$sccity);
		$where[]=array("=","county",$scarea);
		$where[]=array("=","schtype",$sctype);
		$where[]=array("<>","id",$scsid);
		//$res=$m->where($where)->select();
		$res = WpIschoolSchool::find()->where($where)->asArray()->all();
		$where="";
		$ar="";
		if(empty($res))
		{
			$where["id"]=$scsid;
			$m = \mobile\models\WpIschoolSchool::findOne($where);
			$m->name=$scname;
			$m->pro=$scpro;
			$m->city=$sccity;
			$m->county=$scarea;
			$m->schtype=$sctype;
			$m->save(false);
				
				
			//$mm=M("ischool_pastudent");
			$whe["sid"]=$scsid;
			$mm = WpIschoolPastudent::findOne($whe);
			$mm->school=$scname;
			$mm->save(false);
				
				
			//$mn=M("ischool_teaclass");
			$mn = WpIschoolTeaclass::findOne($whe);
			$mn->school=$scname;
			$mn->save(false);
			$ar="success";
		}
		else
		{
			$ar="fail";
		}

		$this->ajaxReturn($ar,"json");
	}
	public function actionDeleteclass(){
		$cid = \yii::$app->request->post("cid");
		 // var_dump($cid);die;
		$con['cid']=$cid;
		$students = WpIschoolStudent::find()->where($con)->asArray()->all();
		if(!empty($students)){
			$this->ajaxReturn('has','json');
		}
		WpIschoolTeaclass::deleteAll($con);

		//$m=M("ischool_class");
		$con2['id'] = $cid;
		//$res=$m->where($con2)->delete();
		$res = \mobile\models\WpIschoolClass::deleteAll($con2);

		if($res>0||$res===0){
			$this->ajaxReturn('success','json');
		}else{
			$this->ajaxReturn('fail','json');
		}
	}
	public function actionOutschool(){
		$sid = \yii::$app->request->get("sid");
		$openid=$this->openid;	
		$con['openid']=$openid;
		$con['sid']=$sid;
                $con['class']='管理';
                $con['cid']=0;
                $con['role']='校长';
                WpIschoolTeaclass::deleteAll($con);
//                WpIschoolTeacher::deleteAll($con);
                $conl['openid']=$openid;
		$conl['sid']=$sid;
		WpIschoolUserRole::deleteAll($conl);
		$admin=WpIschoolAdminer::find()->where(['sid'=>$sid,'openid'=>$openid])->one();
		if($admin){
			$admin->role=0;
			$admin->save(false);
		}
		unset($_SESSION['_ACCESS_LIST']);
		$this->ajaxReturn("success",'json');
	}
	public function actionSchoolinfo()
	{
		$sid=$this->sid;
                
		$openid=$this->openid;
              
		//$m=M("ischool_school");
		$where["id"]=$sid;
		$res=WpIschoolSchool::find()->where($where)->asArray()->all();
		$ry= WpIschoolProvince::find()->asArray()->all();
               
		$un="";
		foreach ($ry as $k=>$v) {
			if($v["name"]==$res[0]["pro"])
			{
				$un=$k;
			}
		}
		$na["name"]=$ry[$un]["name"];
		//省编号
		$na["code"]=$ry[$un]["code"];
		$proid=$na["code"];
		unset($ry[$un]);
		array_unshift($ry,$na);
		//$m=M("ischool_schooltype");
		$reu=WpIschoolSchooltype::find()->asArray()->all();
                 
		$uk="";
		foreach ($reu as $k=>$v) {
			if($v["name"]==$res[0]["schtype"])
			{
				$uk=$k;
			}
		}
		$na="";
		unset($reu[$un]);
		$na["name"]=$res[0]["schtype"];
		array_unshift($reu,$na);

		$render_array = [];

		$render_array['ry']=$ry;

		$where="";
		//$m=M("ischool_city");
		$where["name"]=$res[0]["city"];
		//$city=$m->where($where)->select();
		$city = WpIschoolCity::find()->where($where)->asArray()->all();
              
		$city=$city[0]["code"];

		//根据省编号查询这个省下面的市
		
		//$rl=$m->where($where)->select();
		$rl= WpIschoolCity::find()->where(['provincecode'=>$proid])->asArray()->all();
              
		$mk="";
		foreach ($rl as $k => $v) {
			if($v["name"]==$res[0]["city"])
			{
				$mk=$k;
			}
		}
		$na="";
		$na["name"]=$rl[$mk]["name"];
		$na["code"]=$rl[$mk]["code"];
		$cityid=$na["code"];
		unset($rl[$mk]);
		array_unshift($rl,$na);

		$render_array['rl']=$rl;
		//根据市编号查询这个市下面的县
		//$m=M("ischool_area");
		$where="";
		$where["citycode"]=$cityid;
		//$rl=$m->where($where)->select();
		$r1 = WpIschoolArea::find()->where($where)->asArray()->all();

		$mk="";
		foreach ($r1 as $k => $v) {
			if($v["name"]==$res[0]["county"])
			{
				$mk=$k;
			}
		}
		$na="";
		
		$na["name"]=$r1[$mk]["name"];
		unset($r1[$mk]);
		array_unshift($r1,$na);

		

		$render_array['ra'] = $r1;
		//$this->ra=$rl;
		//市编号
		$render_array['citycode'] = $city;
		//$this->citycode=$city;
		//$this->type=$reu;
		$render_array['type'] = $reu;
		$render_array['schname'] = $res[0]["name"];
	
		//\yii::trace($render_array);

		return $this->render("schoolinfo",$render_array);
		//$this->sid=$sid;
		//$this->display();
	}
	function actionClassmsgcount(){
		$sid = $this->sid;
		$cid = \yii::$app->request->get("cid");
		$openid = $this->openid;
		$ym = date('Ym');
		//$m=M('ischool_msgcount');
		$con['cid']=$cid;
		$con['ym']=$ym;
		$con['type']=0;
		//$ggcount = $m->where($con)->field('num')->select();
		$ggcount = WpIschoolMsgcount::find()->where($con)->asArray()->all();
		$con['type']=1;
		//$lycount = $m->where($con)->select();
		$lycount = WpIschoolMsgcount::find()->where($con)->asArray()->all();
		//$helper = new Helper();
		$classinfo = \mobile\models\WpIschoolClass::findOne($cid);
		$class = $classinfo['name'];
		$render_array = [];
		$render_array['class']=$class;
		$render_array['ggcount']=empty($ggcount)?0:$ggcount[0]['num'];
		$render_array['lycount']=empty($lycount)?0:$lycount[0]['num'];
		//$this->display();
		return $this->render("classmsgcount",$render_array);
	}
	function actionClassleave(){
		$sid = $this->sid;
		$cid = \yii::$app->request->get("cid");
		$openid = $this->openid;

		$lev_sql = "select t1.id,t2.name,t1.begin_time,t1.stop_time from wp_ischool_stu_leave t1 left join wp_ischool_student t2 on t1.stu_id=t2.id where t2.cid=".$cid." and t1.flag=1 order by t1.id desc";
		//$leave_stu = M()->query($lev_sql);
		$leave_stu = \yii::$app->db->createCommand($lev_sql)->queryAll();

		//$helper = new Helper();
		//$cname = $helper->getClass($cid)[0]['name'];
		$classinfo = \mobile\models\WpIschoolClass::findOne($cid);
		$cname = $classinfo['name'];

		$render_array = [];
		$render_array['cname']=$cname;
		$render_array['list_stu']=$leave_stu;
		$render_array['cid']=$cid;

		//$this->display();
		return $this->render("classleave",$render_array);
	}
	function actionClasskaoqin(){
		$sid = $this->sid;
		$cid = \yii::$app->request->get("cid");
		$type = \yii::$app->request->get("type");
		$openid = $this->openid;

		$timeType = \yii::$app->request->get("ttype");
		if($timeType=="today"){
			$timeType = date("Y-m-d");
		}

		$classinfo = \mobile\models\WpIschoolClass::findOne($cid);
		$cname = $classinfo['name'];

		//$kaoqing = $this->kaoqinInfo($sid,$cid,$type,$timeType);
		$kaoqing = [];
		$render_array = [];
		$render_array['kaoqing']=$kaoqing;
		$render_array['type']=$type;
		$render_array['ttype']=$timeType;
		$render_array['cid']=$cid;
		$render_array['class']=$class;
		return $this->render("classkaoqin",$render_array);
	}
	private function kaoqinInfo($sid,$cid,$type,$timeType){
		//$helper = new Helper();
		//$school_type = $helper->getSchoolType($sid);
		$schoolinfo = \mobile\models\WpIschoolSchool::findOne($sid);
		$school_type = $schoolinfo['schtype'];
		//if($school_type == '幼教'){
		//$kaoqin = $this->kaoqin_yey($sid,$cid,$type,$timeType);
		//}else{
		$kaoqin = $this->kaoqin_qt($sid,$cid,$type,$timeType);
		//}
		return $kaoqin;
	}
	private function kaoqin_yey($sid,$cid,$type,$timeType){

		if($type=='all'){
			//$date = new Date();
			$from = strtotime($timeType);
			$to = $from + 3600*24;
			$sql = "select t1.id,t1.name,(select count(id) from wp_ischool_safecard where ctime>".$from." and ctime<".$to." and stuid=t1.id) as counter from wp_ischool_student t1 where t1.cid=".$cid;
			//            $sql = "call sp_banjikaoqin(".$from.",".$to.",".$cid.")";
			//$safe = M()->query($sql);
			//$safe = M()->getLastSql();
			$safe = \yii::$app->db->createCommand($sql)->queryAll();
		}elseif($type=='later'){
			$sch = $this->getLaterTime_yey($sid);
			$sch = $this->set_hm_to_stamp($timeType,$sch);
			//            $sql = "select count(t2.id) as counter,t1.name,t1.id from wp_ischool_student t1".
			//                    " left join".
			//                    " wp_ischool_safecard t2 on t2.stuid=t1.id ".
			//                    " where t1.cid=731 and ((t2.ctime>".$sch[0]." and t2.ctime<".$sch[1].") or (t2.ctime>".$sch[2]." and t2.ctime<".$sch[3].")) group by t1.name";
			$sql = "call sp_banjichidao_yey(".$sch[0].",".$sch[1].",".$sch[2].",".$sch[3].",".$cid.")";
			$safe = M()->query($sql);

		}

		return $safe;
	}
	private function kaoqin_qt($sid,$cid,$type,$timeType){
		$safe = array();
		//$date = new Date();
		//$from = $date->fromYMDToStamp($timeType);
		$from = strtotime($timeType);
		$to = $from + 3600*24;
		if($type=='all'){
			$sql = "call sp_banjikaoqin(".$from.",".$to.",".$cid.")";
			$sql = "select t1.id,t1.name,(select count(id) from wp_ischool_safecard where ctime>".$from." and ctime<".$to." and stuid=t1.id and info !='未到') as counter from wp_ischool_student t1 where t1.cid=".$cid;
			//$safe = M()->query($sql);
			$safe = \yii::$app->db->createCommand($sql)->queryAll();
		}elseif($type=='later'){

			$sql = "select t1.id,t1.name,(select count(id) from wp_ischool_safecard where ctime>".$from." and ctime<".$to." and stuid=t1.id and info ='未到') as counter from wp_ischool_student t1 where t1.cid=".$cid;
			//$sql = "call sp_banjichidao(".$from.",".$to.",".$cid.")";
			//$safe = M()->query($sql);
			$safe = \yii::$app->db->createCommand($sql)->queryAll();
		}

		return $safe;
	}

	private function stuKaoqingInfo_qt($sid,$stuid,$type,$timeType){

		$date = new Date();
		$from = $date->fromYMDToStamp($timeType);
		$to = $from + 3600*24;
		if($type=='all'){
			$sql = "select * from wp_ischool_safecard where ctime>".$from." and ctime<".$to." and stuid=".$stuid." and info!='未到' order by ctime asc";
			$safe = M()->query($sql);
		}elseif($type=='later'){
			$sql = "select * from wp_ischool_safecard where ctime>".$from." and ctime<".$to." and stuid=".$stuid." and info=='未到' order by ctime asc";
			$safe = M()->query($sql);
		}

		return $safe;
	}
	private function getLaterTime_yey($sid){
		$con['sid']=$sid;
		$con['type']=0;
		$sch = array();
		$schedule = $m=M('ischool_schedule')->where($con)->select();
		if(empty($schedule)){
			$sch[0] = '9:00';
			$sch[1] = '12:00';
			$sch[2] = '18:00';
			$sch[3] = '23:59';
		}else{
			$date = new Date();
			foreach($schedule as $v){
				$stop_time = $date->get_hm_from_stamp($v['StopTime']);
				$stop_time = explode(":",$stop_time);
				$hour = $stop_time[0];
				$minute = $stop_time[1];
				if($v['Name']=='早晨'||$v['Name']=='上午'){

					if($hour > 12){
						$sch[] = $hour.":".$minute;
						$sch[] = "23:59";
					}else{
						$sch[] = $hour.":".$minute;
						$sch[] = "12:00";
					}

				}else if($v['Name']=='下午'||$v['Name']=='晚上'){

					if($hour < 12){
						$hour = $hour + 12;
					}
					$sch[] = $hour.":".$minute;
					$sch[] = "23:59";
				}
			}
		}
		if(count($sch)==2){
			$sch[] = "22:00";
			$sch[] = "23:00";
		}
		return $sch;
	}
	//////////////////////
	function actionDeleteteacher(){

		$openid = \yii::$app->request->post("openid");;
		$sid = $this->sid;

		$data['sid']=$sid;
		$data['openid']=$openid;
		$data['shenfen']="school";
		//$m=M("ischool_user_role");
		//$shenfen = $m->where($data)->select();
		$shenfen = WpIschoolUserRole::find()->where($data)->asArray()->all();
		$result = "xiaozhang";
		if(empty($shenfen)){
			$con['sid']=$sid;
			$con['openid']=$openid;

			//$m=M("ischool_teacher");
			//$m->where($con)->delete();
			WpIschoolTeacher::deleteAll($con);
			WpIschoolTeaclass::deleteAll($con);
			$res = WpIschoolUserRole::deleteAll($con);

			if($res>0||$res===0){
				$result="success";
			}else{
				$result = "fail";
			}
		}

		$this->ajaxReturn($result,'json');
	}      
        //学校首页设置页面，用于设置轮播和栏目
        public function actionHpagesetting(){
            $sid=\yii::$app->request->get("sid"); 
            $return_arr['sid']=$sid;
            return $this->render('hpagesetting',$return_arr); 
        }
            //轮播设置页面
        public function actionListcarousel(){
            $sid=\yii::$app->request->get("sid"); 
            $openid=\yii::$app->request->get("openid");   
            //查询已有轮播图片
            $res = $this->getAllCarosBySid($sid);
           
            $return_arr['sid']=$sid;
            $return_arr['openid']=$openid;
            $return_arr['carousels']=$res;
            return $this->render('listcarousel',$return_arr);
        }
         //增加轮播图片
        public function actionDoaddcarousel(){
            $sid=\yii::$app->request->get("sid"); 
            $picurl=\yii::$app->request->get("picurl"); 

            $m= new  WpIschoolHpageLunbo;         
            $m->sid = $sid;
            $m->picurl = $picurl;
            $isdo = $m->save(false);
            if($isdo>0 || $isdo===0){
            $this->ajaxReturn('success','json');
            }else{
            $this->ajaxReturn('fail','json');
            }
        }
            //增加轮播图片
        public function actionDodeletecarousel(){          
            $cid=\yii::$app->request->get("cid");                   
            $con['id']=$cid;
            $isdo =WpIschoolHpageLunbo::deleteAll($con);       
            if($isdo>0 || $isdo===0){
                $this->ajaxReturn('success','json');
            }else{
                $this->ajaxReturn('fail','json');
            }
        }
            //栏目设置页面
        public function actionSetcolumn(){
            $sid=$this->sid; 
            $openid=\yii::$app->request->get("openid");           
            //查询已有栏目
            $res = $this->getAllColsBySid($sid);
            $return_arr['sid']=$sid;
            $return_arr['openid']=$openid;
            $return_arr['columns']=$res;
            return $this->render('setcolumn',$return_arr);
        }
        //公用帮助方法
        public function getAllCarosBySid($sid){
           $res = WpIschoolHpageLunbo::find()->where(['sid'=>$sid])->asArray()->all();               
           return $res;
        }
        //公用帮助方法
        public function getAllColsBySid($sid){
           $res = WpIschoolHpageColname::find()->where(['sid'=>$sid])->asArray()->all();  
            return $res;
        }
        //新增栏目操作
        public function actionDoaddcolumn(){
            $sid=\yii::$app->request->get("sid"); 
            $columnName=\yii::$app->request->get("columnName");        
            //查询是否重名
            $con['sid'] = $sid;
            $con['name'] = $columnName;
            $res = WpIschoolHpageColname::find()->select('id')->where($con)->asArray()->all();  
            if($res){
                $result = 'dupname';
            }else{
                $m= new WpIschoolHpageColname;                      
                $m->name = $columnName;
		$m->sid = $sid;
                $m->save(false);          
                $result = 'success';
            }
            $this->ajaxReturn($result,'json');
        }
        //编辑栏目
        public function actionDoeditcolumn(){
            $sid=\yii::$app->request->get("sid"); 
            $columnId=\yii::$app->request->get("columnId"); 
            $columnName=\yii::$app->request->get("columnName");
            //查询是否重名             
            $con['sid'] = $sid;
            $con['name'] = $columnName;
            $res = WpIschoolHpageColname::find()->select('id')->where($con)->asArray()->all();  
          
            if($res){
                $result = 'dupname';
            }else{
                $m=WpIschoolHpageColname::find()->where(['id'=>$columnId])->one();                      
                $m->name = $columnName;
                $m->save(false);
                $result = 'success';
            }
            $this->ajaxReturn($result,'json');
        }
        //删除栏目
        public function actionDodelcolumn(){
            $columnId=\yii::$app->request->get("columnId");         
            //先删除栏目内容表该栏目所有内容
            $con['cid'] = $columnId;
            WpIschoolHpageColcontent::deleteAll($con);
            //删除栏目表栏目
            $whe['id'] = $columnId;
            WpIschoolHpageColname::deleteAll($whe);
            $result = "success";
            $this->ajaxReturn($result,'json');
        }
        
}
