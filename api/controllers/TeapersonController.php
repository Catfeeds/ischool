<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-03-20
 * Time: 18:04
 */

namespace api\controllers;

use api\models\WpIschoolPastudent;
use api\models\WpIschoolSafecard;
use api\models\WpIschoolStudent;
use api\models\WpIschoolStuLeave;
use Yii;
use api\models\WpIschoolUser;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\widgets\LinkPager;

class TeapersonController extends BaseActiveController
{
    //平安通知当天的
    public function actionSafecard(){
        $cid = $this->post['cid'];
        $model = $this->getAllstuinfo($cid);
        $res = [];
        $data = [];
        foreach($model as $k=>$v){
            $data['stu_id'][] = $v['id'];
            $data['stu_name'][$v['id']] = $v['name'];
        }
        $begintm = $this->getBeginTimestamp("today");       //获得今天的时间戳
//        $models = WpIschoolSafecard::find()->where(['and',['in', 'stuid', $data['stu_id']],['>','ctime',$begintm],['<>','info','未到']])->all();
        $models = $this->getdklist($data['stu_id'],$begintm);
        $resc = WpIschoolSafecard::find()->where(['and',['in', 'stuid', $data['stu_id']],['>','ctime',$begintm],['<>','info','未到']])->groupBy('stuid')->orderBy('ctime desc')->asArray()->all();
        /*$res['count_dkstu'] = strval(count($resc));
        $res['count_stu'] = strval(count($model));*/
        foreach ($models as $k=>$v){
            $res[$k]['stu_name'] = $data['stu_name'][$v['stuid']];
            $res[$k]['xinxi'] = $v['info'];
            $res[$k]['ctime'] = date("Y.m.d H:i",$v['ctime']);
        }
        return $this->formatAsjson($res);
    }

    //平安通知学生列表 本周和本月的
    public function actionMonthsafa(){
        $cid = $this->post['cid'];
        $model = $this->getAllstuinfo($cid);
        $data = [];
        foreach ($model as $k=>$v){
            $data[$k]['stu_name'] = $v['name'];
            $data[$k]['stu_id'] = $v['id'];
        }
        return $this->formatAsjson($data);
    }

    //本周或本月的某一学生平安通知列表
    public function actionSafacont(){
        $stuid = $this->post['stu_id'];
        $type = $this->post['type'];
        $dklist =[];
        if ($type == "week"){
            $beginwk = $this->getBeginTimestamp("week");       //获得本周的时间戳
            $dklist = $this->getdklist($stuid,$beginwk);        //本周的打卡信息列表
        }elseif ($type == "monty"){
            $beginmh = $this->getBeginTimestamp("month");       //获得月的时间戳
            $dklist = $this->getdklist($stuid,$beginmh);        //本周的打卡信息列表
        }else{
            return $this->errorHandler("1001");
        }

        $data = [];
        foreach ($dklist as $k =>$v){
            $data[$k]['time'] = date("Y-m-d H:i:s",$v['ctime']);
            $data[$k]['infos'] = $v['info'];
            $data[$k]['stu_name'] =$this->getStunamebystuid($v['stuid'])['name'];
        }
        return $this->formatAsjson($data);
    }


    //请假待审核信息列表
    public function actionQingjiadsh(){
        $cid = $this->post['cid'];
        $lev_sql = "select t1.id,FROM_UNIXTIME(t1.ctime,'%m-%d %H:%i') ctime,FROM_UNIXTIME(t1.begin_time,'%m-%d %H:%i') sta_time,FROM_UNIXTIME(t1.stop_time,'%m-%d %H:%i') sto_time,t2.name from wp_ischool_stu_leave t1 left join wp_ischool_student t2 on t1.stu_id=t2.id where t2.cid=:cid and t1.flag=2  order by t1.ctime DESC ";     //请假待审核学生
        $data = \Yii::$app->db->createCommand($lev_sql,[':cid'=>$cid])->queryAll();
        return $this->formatAsjson($data);
    }

    //请假已审核信息列表
    public function actionQingjiaysh(){
        $cid = $this->post['cid'];
        $name = $this->post['stu_name'];
        if ($name =="null"){
            $lev_sql = "select t1.flag,FROM_UNIXTIME(t1.ctime,'%m-%d %H:%i') ctime,FROM_UNIXTIME(t1.oktime,'%m-%d %H:%i') oktime,t1.id,FROM_UNIXTIME(t1.begin_time,'%m-%d %H:%i') sta_time,FROM_UNIXTIME(t1.stop_time,'%m-%d %H:%i') sto_time,t2.name from wp_ischool_stu_leave t1 left join wp_ischool_student t2 on t1.stu_id=t2.id where t2.cid=:cid and t1.flag in (0,1) order by t1.oktime DESC ";     //请假已审核学生
            $data = \Yii::$app->db->createCommand($lev_sql,[':cid'=>$cid])->queryAll();
        }else{
            $lev_sql = "select t1.flag,FROM_UNIXTIME(t1.ctime,'%m-%d %H:%i') ctime,FROM_UNIXTIME(t1.oktime,'%m-%d %H:%i') oktime,t1.id,FROM_UNIXTIME(t1.begin_time,'%m-%d %H:%i') sta_time,FROM_UNIXTIME(t1.stop_time,'%m-%d %H:%i') sto_time,t2.name from wp_ischool_stu_leave t1 left join wp_ischool_student t2 on t1.stu_id=t2.id where t2.cid=:cid and t2.name like :name and t1.flag in (0,1)  order by t1.oktime DESC ";     //请假已审核学生
            $data = \Yii::$app->db->createCommand($lev_sql,[':cid'=>$cid,':name'=>'%'.$name.'%'])->queryAll();
        }
        return $this->formatAsjson($data);
    }

    //请假详情接口
    public function actionQingjiaxq(){
        $id = $this->post['id'];
        $model = WpIschoolStuLeave::findOne($id);
        $data = [];
        $data['stu_name'] = $this->getStunamebystuid($model['stu_id'])['name'];
        $data['ctime'] = date("Y-m-d H:i:s",$model['ctime']);
        $data['sta_time'] = date("Y-m-d H:i:s",$model['begin_time']);
        $data['stop_time'] = date("Y-m-d H:i:s",$model['stop_time']);
        $data['reason'] = $model['reason'];
        return $this->formatAsjson($data);
    }

    //请假拒绝
    public function actionQingjiacz(){
        $id = $this->post['id'];
        $type = $this->post['type'];
        $model = WpIschoolStuLeave::findOne($id);
        if ($type =="tongguo"){
            $model->flag = 1;
            $model->oktime = time();
            $cont = "您的孩子请假申请已通过！";
        }elseif ($type =="jujue"){
            $model->flag = 0;
            $model->oktime = time();
            $cont = "您的孩子请假申请已拒绝！";
        }elseif ($type == "shanchu"){
            $model->flag =3;
            $model->oktime = time();
        }
        if ($model->save(false)){
            return $this->formatAsjson("success");
            if (isset($cont)){
                \Jpush::push($model->uid,$cont,"id");
            }
        }else{
            return $this->errorHandler("1045");
        }
    }

    //版本升级
    public function actionUpgrade(){
        $data['version'] = TEABBHAO;
        $data['bburl'] = "http://www.jxqwt.cn/tapkupdate.txt?r=".random_int(1000,9999);
        return $this->formatAsjson($data);
    }
    
//学生打卡信息统计
       public function actionSafecardhz(){
        $cid = $this->post['cid'];
        $shijian = $this->post['shijian'];
        if($shijian == "null"){
            $begintm = strtotime(date('Y-m-d',time()));
            $endtime = time();
        }else{
          $begintm = strtotime(date($shijian));       //获得某一天开始的时间戳
          $endtime = $begintm+86400;              //获得某一天结束的时间戳  
        }

        $sql = "select count(DISTINCT stuid) dkrs from wp_ischool_safecard a left JOIN wp_ischool_student  b ON a.stuid=b.id WHERE b.cid=:cid and a.ctime BETWEEN :begintm AND :endtime and a.info LIKE '%进校%'";
        $sql2 = "SELECT a.name,'未打卡' as dktime FROM wp_ischool_student a WHERE a.cid=:cid and a.id not IN(SELECT DISTINCT stuid from wp_ischool_safecard c LEFT JOIN wp_ischool_student b ON c.stuid=b.id where b.cid=:cid and a.ctime BETWEEN :begintm AND :endtime and c.info LIKE '%进校%')";
        $sql3 = "SELECT a.`name`,FROM_UNIXTIME(b.ctime) dktime FROM wp_ischool_safecard b LEFT JOIN wp_ischool_student a on a.id=b.stuid WHERE a.cid = :cid and b.ctime BETWEEN :begintm  AND :endtime and b.info LIKE '%进校%' GROUP BY b.stuid";
        $data['tongji'] = \Yii::$app->db->createCommand($sql,[":cid"=>$cid,":begintm"=>$begintm,":endtime"=>$endtime])->queryOne();
        $data['tongji']['total'] = WpIschoolStudent::find()->select("id")->where(["cid"=>$cid])->count();
        $data['tongji']['wdkrs'] = strval($data['tongji']['total']-$data['tongji']['dkrs']);
        if($data['tongji']['total'] !=0){
            $data['tongji']['daoxiaolv'] = round(strval($data['tongji']['dkrs'])/$data['tongji']['total']*100,2)."％";
        }else{
            $data['tongji']['daoxiaolv'] = '0%';
        }

        $data1 = \Yii::$app->db->createCommand($sql2,[":cid"=>$cid,":cid"=>$cid,":begintm"=>$begintm,":endtime"=>$endtime])->queryAll();
        $data2 = \Yii::$app->db->createCommand($sql3,[":cid"=>$cid,":begintm"=>$begintm,":endtime"=>$endtime])->queryAll();
        $data['allstu'] = array_merge($data1,$data2);
        return $this->formatAsjson($data);
    }

}
