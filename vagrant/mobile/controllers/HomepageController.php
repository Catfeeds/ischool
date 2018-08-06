<?php
namespace mobile\controllers;

use Yii;
use yii\web\Controller;
use mobile\models\WpIschoolPicschool;
use mobile\models\WpIschoolHpageLunbo;
use mobile\models\WpIschoolGonggao;
use mobile\models\WpIschoolNews;
use backend\models\WpIschoolSchool;
use mobile\models\WpIschoolHpageColname;
use mobile\models\WpIschoolHpageColcontent;
/**
 * Site controller
 */
class HomepageController extends BaseController {
	/**
	 * @inheritdoc
	 */
	public function init(){
		parent::init();
		$this->layout = "main1";
	}
	public function actions() {
		return [
				'error' => [
						'class' => 'yii\web\ErrorAction',
				],
		];
	}

	public function actionEdit()
	{
		$id=\yii::$app->request->get("id");
		$openid=$this->openid;
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
		$m = new WpIschoolSchool();
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
	public function actionDes()
	{
		$id=\yii::$app->request->get("id");
		$openid=$this->openid;
		
		//$m=M("ischool_hpage_colcontent");
		$m = new WpIschoolHpageColcontent();
		$res=$m->findOne(["id"=>$id]);
		
		$title=$res["title"];
		$content=$res["content"];
		$toppicture=$res["toppicture"];
		$sketch=$res["sketch"];
		$sid=$res["sid"];
		
		//$m=M("ischool_school");
		$m = new WpIschoolSchool();
		$where["id"]=$sid;
		$res=$m->findOne($where);
		$render_array = array();
		$render_array['ischool']=$res["name"];
		$render_array['title']=$title;
		$render_array['openid']=$openid;
		$render_array['content']=$content;
		$render_array['toppicture']=$toppicture;
		$render_array['sketch']=$sketch;
		$render_array['sid']=$sid;
		return $this->render("des",$render_array);
	}
	public function actionDoadd()
	{
		$openid=$this->openid;
		$sid=$this->sid;
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
		$m->save(false);
		
		$at["one"]=1;
		\Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
		return $at;
	}
	public function actionDoedit()
	{
		$openid=$this->openid;
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
		$m->save(false);
		$at["one"]=1;
		\Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
		return $at;
	}
	public function actionAdd()
	{
		$openid=$this->openid;
		$sid=$this->sid;
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
		
		$res=WpIschoolSchool::findOne($sid);
		$render_array['ischool']=$res["name"];
		$render_array['openid']=$openid;
		$render_array['sid']=$sid;
		$render_array['cid']=$cid;
		$render_array['tem']=$tem;
		$render_array['type']=$type;
		//$render_array['path']=$path;
		/*
		$this->pak = C("YiCardName");
		$this->jxt = C("TongZhiName");
		$this->homepage = C("HomePage");
		*/
		return $this->render("add",$render_array);
	}
	/**
	 * Displays homepage.
	 *
	 * @return string
	 */
	public function actionIndex() {
		$sid = $this->sid;
		$render_array = [];
		$schoolinfo = \backend\models\WpIschoolSchool::findOne($sid);
		$qrcode = WpIschoolPicschool::findOne(['schoolid'=>$sid]);
		$render_array['pic'] = $qrcode?$qrcode['pic']:"";
		$render_array['lunbos'] = WpIschoolHpageLunbo::find()->where(['sid'=>$sid])->asArray()->all();
		$render_array['gonggao'] = WpIschoolGonggao::find()->where(['sid'=>$sid])->orderBy("ctime desc")->asArray()->all();
		$render_array['news'] = WpIschoolNews::find()->where(['sid'=>$sid])->orderBy("ctime desc")->asArray()->all();

		$render_array['ischool'] = $schoolinfo['name'];
		$render_array['columns'] = \yii::$app->db->createCommand("select t3.* from(SELECT t2.id,t2.title,t2.toppicture,t2.content,t2.sketch,t2.sid,t.id as cid,t.name from wp_ischool_hpage_colname t LEFT JOIN wp_ischool_hpage_colcontent t2 on t.id=t2.cid where t.sid=".$sid." ORDER BY t2.id desc) t3 GROUP BY t3.name order by cid asc ")->queryAll();
		$render_array['colflag'] = empty($render_array['columns'])?0:1;
		
		//$de = Yii::$app->user->can("Root");
		//$do = Yii::$app->user->can("Homepage");
		
		//$render_array['bol']  = 1;
		//$render_array['bool'] = 1;
		/*
		 if($de)
		 {
			$render_array['bol']  = 1;
			$render_array['bool'] = 1;
			}
			if($do)
			{
			$render_array['bol']  = 1;
		}*/
		$de = $this->checkAccess("Root");
		$do = $this->checkAccess("Homepage");
		$render_array['bol']  = 0;
		$render_array['bool'] = 0;
		
		 if($de)
		 {
			$render_array['bol']  = 1;
			$render_array['bool'] = 1;
		}
		if($do)
		{
			$render_array['bol']  = 1;
		}
		return $this->render('index',$render_array);
	}
	

}
