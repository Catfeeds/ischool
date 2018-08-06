<?php
namespace mobile\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use mobile\controllers\BaseController;
use mobile\models\WpIschoolStudent;
use mobile\models\WpIschoolPastudent;
use mobile\models\WpIschoolSchool;
use mobile\models\WpIschoolOrderjx;
use mobile\models\WpIschoolOrderbk;
/**
 * Site controller
 */
class ZfendController extends BaseController {
 
    public function actionQqdh(){
       
//   var_dump($return_arr );die;
           return $this->renderPartial('qqdh');
    }
    public function actionCkcz(){

//   var_dump($return_arr );die;
           return $this->renderPartial('ckcz');
    }
     public function actionPay(){
//         //判断当前是否是微信浏览器
//         if(!$this->actionIs_weixin()){
//            $redirect_url = URL_PATH."/302.html";
//            Header("Location: $redirect_url");
//            exit;
//         }else{
             $openid=\yii::$app->view->params['openid'];
             $leixing=\yii::$app->request->get('lx');
            
             $childs =  WpIschoolPastudent::find()->select('id,stu_id,stu_name,school,class,sid')->where(['openid'=>$openid,'ispass'=>'y'])->orderBy('stu_id asc')->asArray()->all();
            
             foreach($childs as $v){
                $b[] = $v['stu_id'];
             }
	      $b[] = 0;
              $num = "id in (".join(',',$b).")";
              $child=WpIschoolStudent::find()->select('enddateck,stuno2')->where( $num)->orderBy('id asc')->asArray()->all();
                           
              foreach($child as $k=>$v){
                    if( $v['enddateck'] < time()){
                        $childs[$k]['endck'] = "mkt"; //有效期小于当前时间 则为没开通
                    }else{
                        $childs[$k]['endck'] = "ykt";
                    }
                    $childs[$k]['stuno2'] = $v['stuno2'];
              }
             
              $return_arr['leixing']= $leixing;   
              $return_arr['childs']= $childs;   
//              var_dump($return_arr);die;
         return $this->renderPartial('pay',$return_arr);
     }
     
      public function actionIs_weixin(){
           if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
            }
           return false;
      
      } 
    
       public function actionRecharge(){
           
            $xinxi =\yii::$app->request->post("tc");       
             $xinxif = explode('|', $xinxi);
//                var_dump($xinxif);die;
             $pass=WpIschoolSchool::find()->select('papass,jxpass,qqpass,ckpass,half_money,one_money,jgxsh,jgxsy')->where(['id'=>$xinxif[3]])->asArray()->all();
             foreach($pass as $k){
                  $pass =$k;
             }
             $tc=array(half=>json_decode($pass['half_money']),year=>json_decode($pass['one_money']),jgxsh=>array(jgxsh=>$pass['jgxsh']),jgxsy=>array(jgxsy=>$pass['jgxsy']));
             $nxinxi = $xinxif[0]."|".$xinxif[1]."|".$xinxif[2]."|".$xinxif[4];
             $return_arr['nxinxi']=$nxinxi;
             $return_arr['openid']=$xinxif[5];
             $return_arr['pass']=$pass;
             $return_arr['tc']=$tc;       
            return $this->renderPartial('recharge',$return_arr);
      } 
      //套餐支付信息跳转页面
      public function actionRedirectpay(){
          $openid=\yii::$app->request->get("openid");
          $total=\yii::$app->request->get("total");
          if(!is_numeric($total)){  //总价不合法
            $retdata['retcode'] = 1;
            $retdata['retmsg'] = "总价不合法";
            $this->ajaxReturn($retdata,'json');
            return 0;
         }
         $zfzl=\yii::$app->request->get("zfzl");
         $trade_name=\yii::$app->request->get("trade_name");
         $ntrade_name = explode('|', $trade_name);
         $xingming =$ntrade_name[2];     //姓名
         $xuehao = $ntrade_name[3];      //学号
         $stu_id = $ntrade_name[3];
         $pay_url =URL_PATH."/pay/jsapijx.php";
         $d=new WpIschoolOrderjx;       
         $d->openid =$openid;
         $d->paytype = "JSAPI";
         $d->money = $total;
         $d->trade_no=$data=$stu_id.time().rand(100,999);
         $d->trade_name = $trade_name;
         $d->stuid = $stu_id;
         $d->ctime = time();
         $d->save();
         //拼接跳转url，参数以“|”分割，分别为“学校|班级|姓名|学生id|内部订单号|总价|支付种类”
         $pay_url .= "?payInfo=||".$xingming."|".$xuehao."|".$data."|".$total.$zfzl;
         $retdata['retcode'] = 0;
         $retdata['url'] = $pay_url;
         $this->ajaxReturn($retdata,'json');
         return 0;
      }
       //补卡信息提交跳转页面
     public function actionRedirectcpay(){
        $total=\yii::$app->request->post("total");//价格
        if(!is_numeric($total)){  //总价不合法
            $retdata['retcode'] = 1;
            $retdata['retmsg'] = "总价不合法";
            $this->ajaxReturn($retdata,'json');
            return 0;
        }
        $xinxi=\yii::$app->request->post("tc");   
        $xinxif = explode('|', $xinxi);//学校 班级 姓名 学校ID 学生ID openid
        $xuexiao = $xinxif[0];      //学校
        $banji = $xinxif[1];          //班级
        $xingming = $xinxif[2];    //姓名
        $xuehao = $xinxif[4];;        //学生ID
        $pay_url =URL_PATH."/pay/jsapibk.php";
        $d=new WpIschoolOrderbk;
        $d->openid = $xinxif[5];
        $d->paytype = "JSAPI";
        $d->money = $total;
        $d->trade_no =$data1=date('Ym').time().rand(100,999);
        $d->trade_name= $xuexiao."|".$banji."|".$xingming."|".$xuehao;
        $d->ctime= time();
        $d->stuid = $xuehao;
        $d->save();
        //拼接跳转url，参数以“|”分割，分别为“姓名|学号|内部订单号|总价”
        $pay_url .= "?payInfo=||".$xingming."|".$xuehao."|".$data1."|".$total;
        $retdata['retcode'] = 0;
        $retdata['url'] = $pay_url;
        $this->ajaxReturn($retdata,'json');
        return 0;  
     }
     
     public function actionSolution(){
        if (\Yii::$app->request->isPost) {
             $xinxi=\yii::$app->request->post("tc");  //学校|班级|姓名|学校ID|学生ID|openid|学号 
        }else{
             $xinxi=\yii::$app->request->get("tc"); 
        } 
        $xinxif = explode('|', $xinxi);
        $nxinxi = $xinxif[2]."|".$xinxif[6];
        $return_arr['sid']=$xinxif[3];
        $return_arr['nxinxi']=$nxinxi;
       
        return $this->renderPartial('solution',$return_arr);
     }
     //学生补卡操作
    public function actionBuka(){      
        $xinxi=\yii::$app->request->post("tc");  //学校|班级|姓名|学校ID|学生ID|openid|学号   
        $return_arr['xinxi']=$xinxi;
        $xinxif = explode('|', $xinxi);
        
        $nxinxi = $xinxif[2]."|".$xinxif[6];
        $return_arr['name']=$xinxif[2];
        $return_arr['sid']=$xinxif[3];
        $return_arr['nxinxi']=$nxinxi;
     
//        var_dump($return_arr);die;
        return $this->renderPartial('buka',$return_arr);
     }
     public function actionBucard(){  
        $xinxi=\yii::$app->request->post("tc");       
        $cardId=\yii::$app->request->post("cardId");  
        $sid=\yii::$app->request->post("sid"); 
        $xinxif = explode('|', $xinxi);
        $where=[];
        $where['name']= $xinxif[0];
        $where['stuno2']= $xinxif[1];
        $student= WpIschoolStudent::find()->where($where)->one();
        $student->cardid=$cardId;
        $m=$student->save(false);
        if($m){
           $at['retcode']=0; 
        }
        $this->ajaxReturn($at,'json'); 
     }
     public function actionCkredirecpay(){
        $total=\yii::$app->request->post("total"); 
        if(!is_numeric($total)){  //总价不合法
            $retdata['retcode'] = 1;
            $retdata['retmsg'] = "总价不合法";
            $this->ajaxReturn($retdata,'json');
            return 0;
        }
        $pay_url = URL_PATH."/pay/jsapick.php";
        $trade_name=\yii::$app->request->post("tc"); 
        $sid=\yii::$app->request->post("sid"); 
        $data['trade_no'] = date('Ym').time().rand(100,999);
        //拼接跳转url，参数以“|”分割，分别为“姓名|学号|总价”
        $pay_url .= "?payInfo=||".$trade_name."|".$data['trade_no']."|".$total."|".$sid."|ckcz";
        $retdata['retcode'] = 0;
        $retdata['url'] = $pay_url;
        $this->ajaxReturn($retdata,'json');
        return 0; 
     }
     public function actionYue(){
        return $this->renderPartial('yue');
     }
}






