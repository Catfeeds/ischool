<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-03-20
 * Time: 18:04
 */

namespace api\controllers;

use api\models\WpIschoolPastudent;
use Yii;
use api\models\WpIschoolUser;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\widgets\LinkPager;
require_once "/data/lib/push.php";
class ApipersonController extends BaseActiveController
{
    //个人中心首页
    public function actionIndex(){
        $uid = $this->post['uid'];
//        $uid = $this->uid;
        $stu_id = $this->post['stu_id'];    //当前学生ID last_stuid
        $sinfo = $this->getSchoolidbystuid($stu_id);
        $data['stuid'] = $stu_id;
        $data['stuname'] =$this->getStunamebystuid($stu_id)['name'];
        $data['school'] =empty($sinfo[0]['school'])?1:$sinfo[0]['school'];
        $model = new WpIschoolPastudent();
        $res = $model->getPastudent($uid);
        $i=0;
        foreach ($res as $k=>$v){
            $stuname = $this->getStunamebystuid($v['stu_id'])['name'];
            if (!empty($stuname)) {
                $data['stuinfo'][$i]['stu_name'] = $this->getStunamebystuid($v['stu_id'])['name'];
                $data['stuinfo'][$i]['stu_id'] = $v['stu_id'];
                $data['stuinfo'][$i]['schoolname'] = $v['school'];
                $data['stuinfo'][$i]['school_id'] = $v['sid'];
                $data['stuinfo'][$i]['class'] = $v['class'];
                $data['stuinfo'][$i]['cid'] = $v['cid'];
                $i++;
            }
        }
        Yii::trace($data);
        return $this->formatAsjson($data);
    }

    //个人中心切换学生
    public function actionQiehuanstu(){
        $stu_id = $this->post['stu_id'];
        $uid = $this->post['uid'];
        $model = WpIschoolUser::findOne($uid);
        $model->last_stuid = $stu_id;
        $sinfo = $this->getSchoolidbystuid($stu_id);
        if (empty($sinfo)){
            return $this->errorHandler("1030");
        }
        $model->last_sid = empty($sinfo[0]['sid'])?1:$sinfo[0]['sid'];
        $model->last_cid = empty($sinfo[0]['cid'])?1:$sinfo[0]['cid'];
        $data['stuname'] = $this->getStunamebystuid($stu_id)['name'];
        $data['schoolname'] = $this->getSchoolname($sinfo[0]['sid']);
        $data['sid'] = $sinfo[0]['sid'];
        if ($model->save(false)){
            return $this->formatAsjson($data);
        }else{
            return $this->errorHandler("1029");
        }
    }

    //个人中心学生列表信息
    public function actionStulist(){
        $last_stuid = $this->post['stu_id'];    //当前学生ID
        $uid =$this->post['uid'];
        $model = new WpIschoolPastudent();
        $res = $model->getPastudent($uid);
        $data =[];
/*        foreach ($res as $k=>$v) {
            if ($v['stu_id'] == $last_stuid) {
                unset($res[$k]);
            }
        }
        Yii::trace($res);*/
        $i = 0;
        foreach ($res as $k=>$v){
            $data[$i]['id'] = $v['id'];
            $data[$i]['name'] = $v['stu_name'];
            $data[$i]['class'] = $this->stuinfo($v['stu_id'])[0]['class'];
            $data[$i]['school'] = $this->stuinfo($v['stu_id'])[0]['school'];
            $data[$i]['tel'] = isset($this->getHeadmaster($v['cid'])[0]['tel'])?$this->getHeadmaster($v['cid'])[0]['tel']:"暂无号码";
            $name = isset($this->getHeadmaster($v['cid'])[0]['tname'])?$this->getHeadmaster($v['cid'])[0]['tname']:"暂无班主任";
            $data[$i]['bzr'] = $name;
            $data[$i]['stu_id'] = $v['stu_id'];
            Yii::trace(11111);
            $i++;
        }
        Yii::trace($data);
        return $this->formatAsjson($data);
    }

    //个人中心删除学生信息
    public function actionDelstu(){
        $id = $this->post['id'];
        $models = new WpIschoolUser();
        $pastuinfo = WpIschoolPastudent::findOne($id);
        if($id == $this->users['last_stuid']){
            $models->last_stuid = Null;
            $transaction = \yii::$app->db->beginTransaction();
            try{
                if( $pastuinfo->delete() && $models->save(false))
                {
                    return $this->formatAsjson("success");
                    $transaction->commit();
                }else {
                    return $this->errorHandler("1031");
                    $transaction->rollBack();
                }
            }catch (Exception $e)
            {
                return $this->errorHandler("1031");
                $transaction->rollBack();
            }
        }else if($pastuinfo->delete())
        {
            return $this->formatAsjson("success");
        }
    }

    //个人中心请假申请
    public function actionQingjia(){
        $uid = $this->post['uid'];
//        $uid = $this->uid;
        $stu_id = $this->post['stu_id'];
        $begin_time = strtotime($this->post['statime']);
        $stop_time = strtotime($this->post['endtime']);
        $openid = $this->openid;
        $ctime = time();
        $reason = $this->post['reason'];
        $sql = "insert into wp_ischool_stu_leave ( `stu_id` , `begin_time`, `stop_time` , `openid` , `ctime`, `flag`, `reason`, `uid` ) VALUES( :stu_id, :begin_time, :stop_time,:openid, :ctime,2,:reason,:uid)";
        $res = \Yii::$app->db->createCommand($sql,[":stu_id"=>$stu_id,":begin_time"=>$begin_time,":stop_time"=>$stop_time,":openid"=>$openid,":ctime"=>time(),":reason"=>$reason,":uid"=>$uid,])->execute();
        $bzr_info = $this->Banzhuren($stu_id);
        $stuname =$this->getStunamebystuid($stu_id)['name'];
        if ($res){
            foreach ($bzr_info as $key => $value) {
                \Jpush::push($value['uid'],"您的学生".$stuname."有一条请假信息，请及时审批！","id");
            }
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1032");
        }
    }

    //根据学生ID获取对应的班主任信息
    public function Banzhuren($stu_id){
        // $stuid = 127718;
        $cid = $this->stuinfo($stu_id)[0]['cid'];
        $banzhuren_id = $this->getHeadmaster($cid);
        Yii::trace($banzhuren_id);
        return $banzhuren_id;
        // \Jpush::push($zfuid,"支付成功！","id");
    }

    //个人中心家长查询成绩界面
    public function actionScorequery(){
        $cid = $this->post['cid'];
        $sql = "SELECT cjdid,cjdname,ctime FROM wp_ischool_class_chengjidan WHERE cid=:cid order by id ASC ";
        $data = \Yii::$app->db->createCommand($sql,[':cid'=>$cid])->queryAll();
        return $this->formatAsjson($data);
    }

    //个人中信成绩显示界面 5446 228  127686
    public function actionScoreview(){
        $cid = $this->post['cid'];
        $cjdid = $this->post['cjdid'];
        $stuid = $this->post['stu_id'];
        $sql = "SELECT c.stuname,c.kmname,c.score FROM wp_ischool_chengji c  WHERE c.cid=:cid AND c.cjdid=:cjdid AND c.stuid=:stuid ";
        $data = \Yii::$app->db->createCommand($sql,[':cid'=>$cid,':cjdid'=>$cjdid,':stuid'=>$stuid])->queryAll();
        return $this->formatAsjson($data);
    }

    //个人中信界面投诉建议提交接口
    public function actionSuggest(){

        $content = $this->post['content'];
        $sid = $this->post['sid'];
        $title = $this->post['title'];
        $school = $this->post['schoolname'];
        $uid = $this->post['uid'];
        $openid = $this->openid;
        if(isset($_FILES['img'])){
            $res = $this->Uploadimgs($_FILES);
            Yii::trace($res);
            $content.= $res;
        }
        if (empty($title)){
            return $this->errorHandler("1047");
        }
        $sql = "insert into wp_ischool_suggest (`content`,`outopenid`,`sid`,`ctime`,`title`,`school`,`uid`) values (:content,:outopenid,:sid,:ctime,:title,:school,:uid)";
        $res = \Yii::$app->db->createCommand($sql,[":content"=>$content,":outopenid"=>$openid,":sid"=>$sid,":ctime"=>time(),":title"=>$title,":school"=>$school,":uid"=>$uid,])->execute();
        if ($res){
            return $this->formatAsjson("success");
        }else{
            return $this->errorHandler("1032");
        }
    }

    /***
    修改家长用户名字
     */
    public function actionUpname()
    {
        $name = $this->post['newyhm'];
        $id = $this->post['uid'];
        $res = $this->Upname($name,$id);
        return $res;
    }

    /***
    修改用户手机号
     */
    public function actionUptel()
    {
        $tel = $this->post['newtel'];
        $id = $this->post['uid'];
        $yzm = $this->post['yzm'];
        $yz = $this->Dxyanzheng($tel,$yzm);
        Yii::trace($yz);
        if ($yz !== true){
            return $yz;
        }
        $res = $this->Uptel($tel,$id);
        return $res;
    }

    //版本升级
    public function actionUpgrade(){
        $data['version'] = BBHAO;
        $data['bburl'] = "http://www.jxqwt.cn/apkupdate.txt?r=".random_int(10000,99999);
        return $this->formatAsjson($data);
    }

    public function actionUpdateinfo(){
        $info = '
                {
                  "update": "Yes",
                  "new_version": "1.0.1",
                  "apk_file_url": "http://www.jxqwt.cn/img/zhihuixiaoyuan_jiazhang.apk?r=' . rand() . '",
                  "update_log": "1，增加系统消息。\r\n2，优化界面显示效果。",
                  "target_size": "5.8M",
                  "new_md5":"e89557a83df5390e0aac83227254bfd6",
                  "constraint": false
                }
            ';
        echo $info;
    }

    public function actionUserimg(){
        $id = $this->post['uid'];
        $res = $this->Uploadimg($_FILES);
        \yii::trace($res);
        if (!isset($res['status'])) {
            $model = WpIschoolUser::findOne($id);
            $model->user_img = $res;
            if ($model->save(false)) {
                return $this->formatAsjson("success");
            }
            else{
                return $this->errorHandler("1058");
            }
        }else{
                return $res;
        }

        Yii::trace($res['status']);
    }

}
