<?php
namespace mobile\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use mobile\models\WpIschoolPastudent;
class ZhifuController extends BaseController {
    public function actionGopay(){  
        $openid=\yii::$app->request->get("openid");
        $leixing=\yii::$app->request->get("lx");
        $con['openid'] = $openid;
        $con['ispass'] = 'y';
        $childs =WpIschoolPastudent::find()->select('id,stu_id,stu_name,school,class,sid')->where($con)->asArray()->all();
        $return_arr['childs']=$childs;
        $return_arr['openid']=$openid;
        $return_arr['leixing']=$leixing;
        return $this->renderPartial('gopay',$return_arr);
    }
    public function actionGopayjx(){  
       //判断是否微信浏览器
        if(!$this->is_weixin()){
            $redirect_url = URL_PATH."/302.html";
            Header("Location: $redirect_url");
            exit;
        }else{
            $openid=\yii::$app->request->get("openid");
            $leixing=\yii::$app->request->get("lx");
            $state=\yii::$app->request->get("state");         
            $con['openid'] = $openid;
            $con['ispass'] = 'y';
            $childs =WpIschoolPastudent::find()->select('id,stu_id,stu_name,school,class,sid')->where($con)->asArray()->all();
            $return_arr['childs']=$childs;
            $return_arr['openid']=$openid;
            $return_arr['leixing']=$leixing;      
            return $this->renderPartial('gopayjx',$return_arr);
        } 
    }
    private function is_weixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }
}

