<?php

namespace api\controllers;

use api\models\WpIschoolStudent;
use api\models\WpIschoolGonggao;
use api\models\WpIschoolInbox;
use api\models\WpIschoolNews;
use api\models\WpIschoolOutbox;
use api\models\WpIschoolPastudent;
use api\models\ZfCardInfo;
use api\models\ZfDealDetail;
use Yii;
use api\models\WpIschoolUser;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\widgets\LinkPager;

class TeaclassController extends BaseActiveController
{
        //班级管理开通人数统计接口
    //班级管理开通人数统计接口
    public function actionTotalstu(){
        $cid = $this->post['cid'];
        $data = [];
        $a = 0; $b = 0; $c = 0; $d = 0;
       $model = WpIschoolStudent::find()->select('enddateqq,enddatejx,enddateck,enddatepa')->where(['cid'=>$cid])->orderBy('name asc')->asArray()->all();
       foreach ($model as $k=>$v){
            if($v['enddateqq']>time()){
                $a++;
            }
            if($v['enddatejx']>time()){
                $b++;
            }
            if($v['enddateck']>time()){
                $c++;
            }
            if($v['enddatepa']>time()){
                $d++;
            }
        }
        $data['tj']['total_qq'] = strval($a);
        $data['tj']['total_jx'] = strval($b);
        $data['tj']['total_ck'] = strval($c);
        $data['tj']['total_pa'] = strval($d);
        $data['tj']['total'] = strval(count($model));
        \Yii::trace($data);
        return $this->formatAsjson($data);
      }

    //班级管理学生列表接口
    public function actionStulist(){
        $cid = $this->post['cid'];
        $name = $this->post['stu_name'];
        if ($name == "null"){
            $model = $this->getAllstuinf($cid);
        }else{
            $model = $this->getAllstuinfos($cid,$name);
        }
        $data = [];
        foreach ($model as $k=>$v){
            $data[$k]['stu_name'] = $v['name'];
            $data[$k]['stu_id'] = $v['id'];
            $data[$k]['stuno2'] = $v['stuno2'];
            $data[$k]['is_qq'] = ($v['enddateqq']>time())?"y":"n";
            $data[$k]['is_jx'] = ($v['enddatejx']>time())?"y":"n";
            $data[$k]['is_ck'] = ($v['enddateck']>time())?"y":"n";
            $data[$k]['is_pa'] = ($v['enddatepa']>time())?"y":"n";
            $data[$k]['is_try'] = $v['is_try'];
        }
        return $this->formatAsjson($data);
    }

    //学生信息联系人
    public function actionLxr(){
        $name = $this->post['stuname'];
        $stu_id =$this->post['stu_id'];
        $stuno2 = $this->post['stuno2'];
        $data =[];
        $model = WpIschoolPastudent::find()->where(['stu_id'=>$stu_id,'ispass'=>'y','isqqtel'=>1])->select("Relation,id,tel")->asArray()->all();
        $data['stu_name'] = $name;
        $data['stuno2'] = $stuno2;
        $data['lxr']= $model;
        return $this->formatAsjson($data);
    }

    //添加亲情号码
    public function actionAddqqtel(){
        $uid = $this->post['uid'];
        $stu_id = $this->post['stu_id'];
        $Relation = $this->post['Relation'];
        $tel = $this->post['tel'];
        $cid = $this->post['cid'];
        $class = $this->post['class'];
        $stuname = $this->post['student'];
        if(!preg_match("/^1[34578]\d{9}$/", $tel)){
            return $this->errorHandler("1004");
        }
        $res = WpIschoolPastudent::find()->where(['stu_id'=>$stu_id,'ispass'=>'y','isqqtel'=>1])->asArray()->all();
        if(count($res) >=5){
            return $this->errorHandler("1025");
        }
        $model = new WpIschoolPastudent();
        $model->name= $this->getusername($uid);
        $model->ctime= time();
        $model->stu_id= $stu_id;
        $model->cid= $cid;
        $model->class= $class;
        $model->tel= $tel;
        $model->stu_name= $stuname;
        $model->ispass= "y";
        $sinfo = $this->getSchoolidbystuid($stu_id);
        $model->sid = empty($sinfo[0]['sid'])?1:$sinfo[0]['sid'];
        $model->school= empty($sinfo[0]['school'])?1:$sinfo[0]['school'];
        $model->Relation= $Relation;
        $model->isqqtel= 1;
        $model->uid = $uid;

        if ($model->save(false)){
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1026");
        }
    }

    //修改亲情号码
    public function actionUpqqh()
    {
        $id = $this->post['id'];
        $Relation = $this->post['Relation'];
        $tel = $this->post['tel'];
        if(!preg_match("/^1[34578]\d{9}$/", $tel)){
            return $this->errorHandler("1004");
        }
        $model = WpIschoolPastudent::findOne($id);
        $model->tel = $tel;
        $model->Relation = $Relation;
        $res = $model->save(false);
        if($res){
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1027");
        }
    }

    //删除亲情号码
    public  function actionDelqqh(){
        $id = $this->post['id'];
        $model = WpIschoolPastudent::findOne($id);
        $res = $model->delete();
        if($res){
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1028");
        }
    }


}
