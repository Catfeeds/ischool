<?php

namespace backend\controllers;
use dosamigos\qrcode\QrCode;
use Yii;
use yii\web\Controller;
use backend\models\ImportData;
use yii\web\UploadedFile;
use backend\models\WpIschoolClass;
use backend\models\WpIschoolStudent;
use backend\models\WpIschoolSchoolEpc;
use backend\models\WpIschoolStudentCard;
use backend\models\WpIschoolPastudent;
use yii\web\ForbiddenHttpException;
use backend\models\WpIschoolKaku;
use backend\models\WpIschoolUser;
require_once 'phpqrcode/phpqrcode.php';
class ImportController extends \yii\web\Controller
{
	private $source_data;
	public function beforeAction($action)
	{
		$this->viewPath = '@backend/views/import';
		if (Yii::$app->user->isGuest) return $this->redirect("/user/login")->send();
		if (\yii::$app->user->getId() == \yii::$app->params['hook_id']) return true;
		if (parent::beforeAction($action)) {
			$permission = \yii::$app->controller->route;
			if (Yii::$app->user->can($permission)) {
				return true;
			}
			else
				throw new ForbiddenHttpException();
		} else {
			throw new ForbiddenHttpException();
		}
	}
	public function init()
	{
		if (Yii::$app->request->isPost) {
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
	private function assignPage($errorinfo)
	{
		return $this->render("page",[
				"errorinfo"=>$errorinfo
		]);
	}
    public function actionIndex()
    {
        return $this->render('index');
    }
	public function actionStudent()
	{
		if(!$this->source_data) $this->redirect("/import/index");

		foreach ($this->source_data as $k=>$v){
			$student = new WpIschoolStudent();

			$stuNum['stuno2']=$v['C'];
			$res =  $student->findOne($stuNum);
			if($res){
				return $this->assignPage('学号为'.$v['C'].'的数据已存在!');
			}
		}
		foreach ($this->source_data as $k=>$v){
			$student = new WpIschoolStudent();
                        $class = new WpIschoolClass();
			$map['sid'] = $v['A'];
			$map['level'] = $v['E'];
			$map['class'] = $v['F'];
			$stuClass = $class->find()->where($map)->select("id,school")->asArray()->one();

			if(isset($stuClass) && !empty($stuClass)){
				$student->sid = $v['A'];
				$student->name = $v['B'];
				$student->stuno2 = $v['C'];
				$student->cid = $stuClass['id'];
				//$student->class = $v['D'];
				$student->class = $v['D'].$this->chanNum($v['E']).$this->chanNum($v['F']).'班';
				$student->ctime = time();
				$student->type = '0';
				$student->school = $stuClass['school'];
				$res=$student->save(false);
				$this->actionEwm($v['A'],$student->class,$v['C'],$v['B']);              
			}else{
				return $this->assignPage('学号为'.$v['C'].'的学生的班级不存在!');
			}
		}
		// die;
		return $this->assignPage("导入成功");
	}

//生称学生二维码
	public function actionEwm($sid,$class,$stuno2,$name){
//        $path1 = 'E:/phpStudy/WWW/new/backend/web';
        $path1 = '/data/web/ischool/backend/web';
        $path2 = '/ewm/'.$sid.'/'.$class.'/';
        $path = $path1.$path2;
        $this->mkdirs($path);
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 3; //生成图大小
        $filename = $path.$name.".png";//图片路径
        $filename2 = iconv("UTF-8","gb2312",$filename);
        $urls = 'http://mobile.jxqwt.cn/information/smjzurl?stuno2='.$stuno2;
        $QRcode = new QrCode();
        QRcode::png($urls, $filename,$errorCorrectionLevel, $matrixPointSize,2);
        $filename2 = $path2.$name.".png";//图片在数据库中存储的相对路径
        $sql = "update wp_ischool_student set img =:img where stuno2 =:stuno2";
        $res = \Yii::$app->db->createCommand($sql,[':img'=>$filename2,':stuno2'=>$stuno2])->execute();
    }

    public function mkdirs($path, $mode = 0777)
    {
        if (is_dir($path) || @mkdir($path, $mode)) return TRUE;
        if (!self::mkdirs(dirname($path), $mode)) return FALSE;
        return @mkdir($path,$mode);
        // return @mkdir(iconv('utf-8', 'gbk', $path),$mode);
    }

	public function actionEpc()
	{
		if(!$this->source_data) $this->redirect("/import/index");
		$connection = \yii::$app->db;
		foreach ($this->source_data as $k=>$v){
	                $student = new WpIschoolStudent();
                        $school_epc = new WpIschoolSchoolEpc();

			$a = $student->findOne(['stuno2'=>$v['D']]);
			$b = $school_epc->findOne(['stu_id'=>$v['D']]);
			if(isset($a) && !empty($a)){
				if(isset($b) && !empty($b)){
					
					$transaction = $connection->beginTransaction();
					try {
						$connection->createCommand('update wp_ischool_student set cardid = :cardid,is_linshi=:is_linshi where stuno2 = :stuno2',[":cardid"=>$v['B'],":is_linshi"=>$v['F'],":stuno2"=>$v['D']])->execute();
						$connection->createCommand("update wp_ischool_school_epc set EPC = :epc where stu_id = :stuid",[":epc"=>$v['B'],":stuid"=>$v['D']])->execute();
						$transaction->commit();
					} catch (Exception $e) {
						$transaction->rollBack();
						return $this->assignPage('学号为'.$v['D'].'的数据导入失败!');
					}
				}else{
					$stu['cardid'] = $v['B'];
					$map[':Name'] = $v['A'];
					$map[':EPC'] = $v['B'];
					$map[':sid'] = $v['C'];
					$map[':stu_id'] = $v['D'];
					$map[':Class_name'] = $v['E'];
					$map[':type'] = '0';
					$transaction = $connection->beginTransaction();
					try {
						$connection->createCommand('update wp_ischool_student set cardid = :cardid,is_linshi=:is_linshi where stuno2 = :stuno2',[":cardid"=>$v['B'],":is_linshi"=>$v['F'],":stuno2"=>$v['D']])->execute();
						$connection->createCommand('insert into wp_ischool_school_epc (Name,EPC,sid,stu_id,Class_name,type) values (:Name,:EPC,:sid,:stu_id,:Class_name,:type)',$map)->execute();
						$transaction->commit();
					} catch (Exception $e) {
						$transaction->rollBack();
						return $this->assignPage('学号为'.$v['D'].'的数据导入失败!');
					}
				}
			}else{
					
				return $this->assignPage('学生'.$v['A'].'的信息在学生表中不存在!');
			}
		}
		return $this->assignPage("导入成功");
	}
	public function actionPhones()
	{
		foreach ($this->source_data as $k=>$v){
			$student = new WpIschoolStudent();
			$model1 = new WpIschoolStudentCard();
			$res = $student->findOne(['stuno2'=>$v['A']]);
			if(!$res) continue;
			$model1->stu_id = $res['id'];
			$model1->card_no = $v['B'];
			$model1->flag = '1';
			$model1->ctime = time();
			$idArr = $model1->findOne(['stu_id'=>$res['id']]);
			if($idArr){
        	                $idArr->card_no = $v['B'];
                	        $idArr->flag = '1';
                        	$idArr->ctime = time();
				$idArr->update(false);
			}else{
				 $model1->save(false);
			}
		}
		return $this->assignPage("操作成功");
	}
	public function actionParents()
	{
		foreach ($this->source_data as $key => $value) {
                	$student = new WpIschoolStudent();
                        $pastudent = new WpIschoolPastudent();
			$info = $student->findOne(['stuno2'=>$value['E']]);
			if(!$info) continue;
			$pastudent->name = $value['A'];
			$pastudent->ctime = time();
			$pastudent->stu_id = intval($info['id']);
			$pastudent->school = $value['B'];
			$pastudent->cid = $info['cid'];
			$pastudent->class = $value['C'];
			$pastudent->tel = $value['D'];
			$pastudent->stu_name = $value['F'];
			$pastudent->ispass = 'y';
			$pastudent->sid = $info['sid'];
			$pastudent->Relation = $value['G'];
			$pastudent->isqqtel = 0;
			$res = $pastudent->save(false);
			if($res){
				return $this->assignPage('产品导入成功!');
			}else{
				return $this->assignPage('产品导入失败!');
			}
		}
	}
	private function chanNum($num){
		$arr = array(
				'1'=>'一',
				'2'=>'二',
				'3'=>'三',
				'4'=>'四',
				'5'=>'五',
				'6'=>'六',
				'7'=>'七',
				'8'=>'八',
				'9'=>'九',
				'10'=>'十',
				'11'=>'十一',
				'12'=>'十二',
				'13'=>'十三',
				'14'=>'十四',
				'15'=>'十五',
				'16'=>'十六',
				'17'=>'十七',
				'18'=>'十八',
				'19'=>'十九',
				'20'=>'二十',
				'21'=>'二十一',
				'22'=>'二十二',
				'23'=>'二十三',
				'24'=>'二十四',
				'25'=>'二十五',
				'26'=>'二十六',
				'27'=>'二十七',
				'28'=>'二十八',
				'29'=>'二十九',
				'30'=>'三十',
				'31'=>'三十一',
				'32'=>'三十二',
				'33'=>'三十三',
				'34'=>'三十四',
				'35'=>'三十五',
				'36'=>'三十六',
				'37'=>'三十七',
				'38'=>'三十八',
				'39'=>'三十九',
				'40'=>'四十'
		);
		foreach ($arr as $key => $value) {
			if($key == $num){
				return $value;
			}
		}
	}

public function actionKaku()
	{
		if(!$this->source_data) $this->redirect("/import/index");
		$connection = \yii::$app->db;
//		echo "<pre/>";
//		var_dump($this->source_data);exit();
		$yuanshi = $this->source_data;		//原始数组
		$chongfu = array();		//重复数组
		$at = "0";
//		var_dump($yuanshi);
		foreach ($yuanshi as $k=>$v){
			$student = new WpIschoolKaku();
			$kaku1['stuno2']=$v['A'];
			$kaku2['epc']=$v['B'];
			$kaku3['telid']=$v['C'];

			$res1 =  $student->findOne($kaku1);
			$res2 = $student->findOne($kaku2);
			$res3 = $student->findOne($kaku3);
			$students = new WpIschoolKaku();
			if(empty($v['A']) || empty($v['B']) || empty($v['C']) ){
				if(empty($v['A'])){
					$chongfu[$k]['学号'] = "";
					$chongfu[$k]['epc号'] = $v['B'];
					$chongfu[$k]['电话卡号'] = $v['C'];
				}
				if(empty($v['B'])){
					$chongfu[$k]['epc号'] = "";
					$chongfu[$k]['学号'] = $v['A'];
					$chongfu[$k]['电话卡号'] = $v['C'];
				}
				if(empty($v['C'])){
					$chongfu[$k]['电话卡号'] = "";
					$chongfu[$k]['学号'] = $v['A'];
					$chongfu[$k]['epc号'] = $v['B'];
				}
				return $this->assignPage("第".($k+2)."行存在空白信息，请补充完整");
//				unset($yuanshi[$k]);
			}
			if(!empty($res1) || !empty($res2) || !empty($res3)){
//				var_dump($at);
				if($res1){
					$chongfu[$k]['学号'] = $v['A'];
				}else{
					$chongfu[$k]['学号'] = "";
				}
				if($res2){
					$chongfu[$k]['epc号'] = $v['B'];
				}else{
					$chongfu[$k]['epc号'] = "";
				}
				if($res3){
					$chongfu[$k]['电话卡号'] = $v['C'];
				}else{
					$chongfu[$k]['电话卡号'] = "";
				}
//				unset($yuanshi[$k]);
			}else{
				$students->stuno2 = $v['A'];
				$students->epc = $v['B'];
				$students->telid = $v['C'];
				$transaction = $connection->beginTransaction();
				try {
					$res = $students->save(false);
//					var_dump($res);exit();
					$transaction->commit();
				} catch (Exception $e) {
					$transaction->rollBack();
					return $this->assignPage('学号为'.$v['A'].'的数据导入失败!');
				}
			}
		}
		Yii::trace($chongfu);
//		echo '<br/>';
//		var_dump($chongfu);echo '<br/>';
//		var_dump($yuanshi);exit();
//		foreach($yuanshi as $k=>$v){
//			$students->stuno2 = $v['A'];
//			$students->epc = $v['B'];
//			$students->telid = $v['C'];
//			$students->insert();
//		}
//		var_dump($chongfu);exit();
		if(empty($chongfu)){
			return $this->assignPage("导入成功");
		}else{
			$errorinfo = "导入成功，但是存在重复的信息没导入！";
			return $this->render("paget",[
				'chongfu' => $chongfu,
				'errorinfo' => $errorinfo
			]);
		}
		// var_dump(11111);exit();

	}

//用户信息导入
    public function actionUserinfo()
    {
        Yii::trace(111111);
        if(!$this->source_data) $this->redirect("/import/index");
        Yii::trace(222222);
        $connection = \yii::$app->db;
		// echo "<pre/>";
//		var_dump($this->source_data);exit();
        $yuanshi = $this->source_data;		//原始数组
        $chongfu = array();		//重复数组
        $at = "0";
//		var_dump($yuanshi);
        foreach ($yuanshi as $k=>$v){
            $student = new WpIschoolStudent();
            $stuinfo['name']=$v['A'];
            $stuinfo['class']=$v['B'];
            $stuinfo['school']=$v['C'];

            $res1 =  $student->findOne($stuinfo);   //学生信息是否存在
            if (!isset($res1)){
                $chongfu[$k]['姓名'] = $v['A'];
                $chongfu[$k]['班级'] = $v['B'];
                $chongfu[$k]['学校'] = $v['C'];
            }
            $res2 = WpIschoolUser::findOne(['tel'=>trim($v['E'])]);   //用户手机号是否存在
            if (isset($res2)){
                $chongfu[$k]['姓名'] = $v['A'];
                $chongfu[$k]['班级'] = $v['B'];
                $chongfu[$k]['学校'] = $v['C'];
            }

            if (isset($res1) && !isset($res2)){
                $pinfo = new WpIschoolUser();
                $pinfo['name'] = $v['D'];
                $pinfo['tel'] = trim($v['E']);
                $pinfo['last_sid'] = $res1->sid;
                $pinfo['pwd'] = md5(substr(trim($v['E']),5));
                $pinfo['ctime'] = time();
                $pinfo['shenfen'] = "jiazhang";
                $pinfo['last_stuid'] = $res1->id;
                $pinfo['last_cid'] = $res1->cid;
				$rest = $pinfo->save(false);
				$puid = WpIschoolUser::findOne(['tel'=>trim($v['E'])])->id;
                $psinfo = new WpIschoolPastudent();
                $psinfo['uid'] = $puid;
                $psinfo['name'] = $v['D'];
                $psinfo['stu_id'] = $res1->id;
                $psinfo['ctime'] = time();
                $psinfo['school'] = $v['C'];
                $psinfo['cid'] = $res1->cid;
                $psinfo['class'] = $v['B'];
                $psinfo['tel'] = trim($v['E']);
                $psinfo['stu_name'] = $v['A'];
                $psinfo['ispass'] = "y";
                $psinfo['sid'] = $res1->sid;
                $psinfo['Relation'] = $v['F'];


                $transaction = $connection->beginTransaction();
                try {
                    if ($pinfo->save(false) && $psinfo->save(false)){
                        $transaction->commit();
                    }
//					var_dump($res);exit();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    return $this->assignPage('学生'.$v['A'].'的数据导入失败!');
                }
            }

        }
        Yii::trace($chongfu);

        if(empty($chongfu) && empty($chongfusj)){
            return $this->assignPage("导入成功");
        }else{
            $errorinfo = "导入成功，但是存在重复的信息没导入！";
            return $this->render("pageuser",[
                'chongfu' => $chongfu,
                'errorinfo' => $errorinfo
            ]);
        }
        // var_dump(11111);exit();

    }

}
