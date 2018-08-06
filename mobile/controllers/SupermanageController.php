<?php

namespace mobile\controllers;
use Yii;
use backend\models\WpIschoolTeacher;
use mobile\models\WpIschoolUserRole;
class SupermanageController extends BaseController
{
	public function actionGetteabyschool(){
		$sid = $this->sid;
		$openid=$this->openid;
		$res = WpIschoolTeacher::find()->where("sid = $sid and openid != '$openid' ")->select("id,openid,tname,school,sid")->asArray()->all();
		foreach ($res as &$v) {
			
			//$this->saveAccessList($v["openid"],$sid);
			$this->checkAccess("Root");
			if($de)
			{
				$v["rid"]=1;
			}
			else
			{
				$v["rid"]=2;
			}
		}
		$this->ajaxReturn($res,'json');
	}
         /*  设置管理员 */
        public function actionSetmanager(){
            $para=\yii::$app->request->get("para");
            $cg=\yii::$app->request->get("cg");
            $para=explode(";", $para);
            $d=new WpIschoolUserRole;
          
            if($cg==1){   //设为超管
                $d->name=$para[0];
                $d->openid=$para[1];
                $d->sid=$para[2];
                $d->school=$para[3];
                $d->rid=1;
                $res=$d->save(false);
            }else{        //取消超管
               
                $con['sid']=$para[2];
                $con['openid']=$para[1];
                $con['rid']=1;
                $res = WpIschoolUserRole::deleteAll($con);
            }

            if($res>0 || $res===0){
                $this->ajaxReturn('success','json');
            }else{
                $this->ajaxReturn('fail','json');
            }
        }

}
