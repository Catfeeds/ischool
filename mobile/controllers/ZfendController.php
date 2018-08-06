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
use mobile\models\WpIschoolUser;
use mobile\models\WpIschoolOrderjf;
use mobile\models\WpIschoolKaku;
use mobile\models\WpIschoolStudentCard;
use mobile\models\WpIschoolSchoolEpc;
/**
 * Site controller
 */
class ZfendController extends BaseController {
 
    public function actionQqdh(){
           return $this->renderPartial('qqdh');
    }
    //水卡服务
    public function actionSkfw(){
          $openid=\yii::$app->view->params['openid'];          
          $res= WpIschoolUser::find()->select('last_sid')->where(['openid'=>$openid])->asArray()->all();   
          $sid= $res[0]['last_sid'];
          // $sql="select a.ckpass from wp_ischool_school as a inner join wp_ischool_pastudent as b where a.id=b.sid and b.sid=".$sid." and b.openid= '".$openid."'";
          $sql="select a.enddateck from wp_ischool_student as a inner join wp_ischool_pastudent as b where a.id=b.stu_id and b.sid=".$sid." and b.openid= '".$openid."'";
          $pass=WpIschoolSchool::findBySql($sql)->asArray()->all();
          $childs=$this->getAllstu();
          $return_arr['childs']=$childs;
          if($pass){
            foreach($pass as $v){
                if($v['enddateck']>time()){
                  $return_arr['ckpass']=1;
                  break;
                }else{
                  $return_arr['ckpass']=0;
                }
            }
          }else{
            $return_arr['ckpass']=0;
          } 
          // echo "<pre>";  
          // var_dump($return_arr);die;
          return $this->renderPartial('skfw',$return_arr);
    }
     public function actionSkcx(){
        $openid=\yii::$app->view->params['openid'];
        $sid=\yii::$app->view->params['sid'];                 
        $downmenu=\yii::$app->request->get('downmenu');
        $downtime = \yii::$app->request->get('downtime');
        $endtime = \yii::$app->request->get('endttime');
        $tc=explode('|',$downmenu);
        $sid=$tc[0];
        $stuno=$tc[1];
        $jinmai_school=\yii::$app->params['cooperative.school'];
        if(in_array($sid,$jinmai_school)){
            //调用交易明细查询接口             
            //包体数据
            $requst_info=[
                'actionStr'=>'YKT_QUERY_FIN',
                'version'=>'200',
                'thirdCode'=>'',
                'page'=>'1',
                'pageSize'=>'100',
                'serialType'=>'1',
                'serialValue'=>$stuno,
                'beginTime'=>preg_replace('/[-]/','', $downtime),
                'endTime'=>preg_replace('/[-]/','', $endtime),
                'tradeType'=>'X'
            ];
            $jsonInfo=$this->getPostInfo($requst_info);
            if($jsonInfo['resultCode']=='0000' && empty($jsonInfo['datas'])){
                $result = array("flag"=>1);
            }else{
                $result=array();       
                foreach($jsonInfo['datas']  as $k=>$v){
                    $result[$k]['year']=substr($v['tradeTime'],0,4)."-".substr($v['tradeTime'],4,2)."-".substr($v['tradeTime'],6,2);
                    $result[$k]['time']=substr($v['tradeTime'],8,2).":".substr($v['tradeTime'],10,2).":".substr($v['tradeTime'],12,2);
                    $result[$k]['position']=$v['tradeAdd'];
                    $result[$k]['amount']=number_format($v['amount']/100, 2);
                    $result[$k]['user_name']=$v['custName'];
                    $result[$k]['balance']=number_format($v['curBalance']/100, 2);
                }
                $result = array("flag"=>0,"ckshuju"=>$result);
                
            }

        }else{
            $user_no=$stuno;
            // if (preg_match('/[a-zA-Z]/',$stuno)){
            //     $user_no= substr($stuno,6);
            //     $sid=substr($stuno,1,5);
            // }else{
            //     $user_no= substr($stuno,2);
            // }     
            $downtime=strtotime($downtime);
            $endtime=strtotime($endtime)+86399;

            try {
                $dbh = new  \PDO('mysql:host=127.0.0.1;dbname=card_water','root','hnzf123456');
                $sql="SELECT a.amount,a.balance,a.created,a.pos_sn,a.school_id,b.user_name from zf_deal_detail as a left JOIN zf_card_info as b on a.card_no = b.card_no and a.school_id=b.school_id where    b.user_no = ? and b.school_id = ? and a.created < ? and a.created > ? order by a.created desc";
                $dbh->query("set names utf8");
                $bs =$dbh->prepare($sql);
                $bs->execute(array($user_no,$sid,$endtime,$downtime));
                yii::trace($sql);
                $result = $bs->fetchAll(\PDO::FETCH_ASSOC);
                $dbh=null;
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();        
            }
            if($result){
                foreach($result as &$v){
                        $v['year'] = date("Y-m-d",$v['created']);
                        $v['time'] = date("H:i:s",$v['created']);
                        $v['position']=$this->SKconfig($v['school_id'],$v['pos_sn']);

                }
                $result = array("flag"=>0,"ckshuju"=>$result);
            }else{
                $result = array("flag"=>1);
            }
        }
        $this->ajaxReturn($result,'json');
    }
    //返回水卡消费机地点
    function SKconfig($sid, $pos_no) {
        $config = [
            '56740' => [
                '100' => "超市",
            ],
            
        ];
        if (isset($config[$sid]) && isset($config[$sid][$pos_no])) {
            return $config[$sid][$pos_no];
        } else {
            return "水卡消费";
        }

    }
    //餐卡服务
    public function actionCkfw(){  
          $openid=\yii::$app->view->params['openid'];          
          $res= WpIschoolUser::find()->select('last_sid')->where(['openid'=>$openid])->asArray()->all();   
          $sid= $res[0]['last_sid'];
          // $sql="select a.ckpass from wp_ischool_school as a inner join wp_ischool_pastudent as b where a.id=b.sid and b.sid=".$sid." and b.openid= '".$openid."'";
          $sql="select a.enddateck from wp_ischool_student as a inner join wp_ischool_pastudent as b where a.id=b.stu_id and b.sid=".$sid." and b.openid= '".$openid."'";
          $pass=WpIschoolSchool::findBySql($sql)->asArray()->all();
          $childs=$this->getAllstu();
          $return_arr['childs']=$childs;
          if($pass){
            foreach($pass as $v){
                if($v['enddateck']>time()){
                  $return_arr['ckpass']=1;
                  break;
                }else{
                  $return_arr['ckpass']=0;
                }
            }
          }   
          return $this->renderPartial('ckfw',$return_arr);
    }
    public function actionCkcx(){
        $openid=\yii::$app->view->params['openid'];
        $sid=\yii::$app->view->params['sid'];                 
        $downmenu=\yii::$app->request->get('downmenu');
        $downtime = \yii::$app->request->get('downtime');
        $endtime = \yii::$app->request->get('endttime');
        $tc=explode('|',$downmenu);
        $sid=$tc[0];
        $stuno=$tc[1];
        $jinmai_school=\yii::$app->params['cooperative.school'];
        if(in_array($sid,$jinmai_school)){
            //调用交易明细查询接口             
            //包体数据
            $requst_info=[
                'actionStr'=>'YKT_QUERY_FIN',
                'version'=>'200',
                'thirdCode'=>'',
                'page'=>'1',
                'pageSize'=>'100',
                'serialType'=>'1',
                'serialValue'=>$stuno,
                'beginTime'=>preg_replace('/[-]/','', $downtime),
                'endTime'=>preg_replace('/[-]/','', $endtime),
                'tradeType'=>'X'
            ];
            $jsonInfo=$this->getPostInfo($requst_info);
            if($jsonInfo['resultCode']=='0000' && empty($jsonInfo['datas'])){
                $result = array("flag"=>1);
            }else{
                $result=array();       
                foreach($jsonInfo['datas']  as $k=>$v){
                    $result[$k]['year']=substr($v['tradeTime'],0,4)."-".substr($v['tradeTime'],4,2)."-".substr($v['tradeTime'],6,2);
                    $result[$k]['time']=substr($v['tradeTime'],8,2).":".substr($v['tradeTime'],10,2).":".substr($v['tradeTime'],12,2);
                    $result[$k]['position']=$v['tradeAdd'];
                    $result[$k]['amount']=number_format($v['amount']/100, 2);
                    $result[$k]['user_name']=$v['custName'];
                    $result[$k]['balance']=number_format($v['curBalance']/100, 2);
                }
                $result = array("flag"=>0,"ckshuju"=>$result);
                
            }

        }else{
            if (preg_match('/[a-zA-Z]/',$stuno)){
                $user_no= substr($stuno,6);
                $sid=substr($stuno,1,5);
            }else{
                $sid='56651';
                $user_no= substr($stuno,2);
            }     
            $downtime=strtotime($downtime);
            $endtime=strtotime($endtime)+86399;

            try {
                $dbh = new  \PDO('mysql:host=127.0.0.1;dbname=card','root','hnzf123456');
                $sql="SELECT a.amount,a.balance,a.created,a.pos_sn,a.school_id,b.user_name from zf_deal_detail as a left JOIN zf_card_info as b on a.card_no = b.card_no and a.school_id=b.school_id where    b.user_no = ? and b.school_id = ? and a.created < ? and a.created > ? order by a.created desc";
                $dbh->query("set names utf8");
                $bs =$dbh->prepare($sql);
                $bs->execute(array($user_no,$sid,$endtime,$downtime));
                yii::trace($sql);
                $result = $bs->fetchAll(\PDO::FETCH_ASSOC);
                $dbh=null;
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();        
            }
            if($result){
                foreach($result as &$v){
                        $v['year'] = date("Y-m-d",$v['created']);
                        $v['time'] = date("H:i:s",$v['created']);
                        $v['position']=$this->config($v['school_id'],$v['pos_sn']);

                }
                $result = array("flag"=>0,"ckshuju"=>$result);
            }else{
                $result = array("flag"=>1);
            }
        }
        $this->ajaxReturn($result,'json');
    }
    //返回消费机地点
    function config($sid, $pos_no) {
        $config = [
            '56758' => [
                '241' => "超市",
                '242'=>"超市",
                '251' => "医务室",
            ],
            '56650' => [
                '71' => "超市",
                '72' => "超市",
                '81' => "面包房",
                '91' => "超市",
                '92' => "超市",
                '155' => "超市",
                '219' => "超市",
                '151' => "超市",
                '221' => "超市",
                '220' => "超市",
                '152' => "超市",
                '222' => "超市",
                '154' => "超市",
                '153' => "超市",
                '223' => "面包房",
                '224' => "面包房",
            ],
            '56651' => [
                '155' => "超市",
                '219' => "超市",
                '151' => "超市",
                '221' => "超市",
                '220' => "超市",
                '152' => "超市",
                '222' => "超市",
                '154' => "超市",
                '153' => "超市",
                '223' => "面包房",
                '224' => "面包房",
           ],
        ];
        if (isset($config[$sid]) && isset($config[$sid][$pos_no])) {
            return $config[$sid][$pos_no];
        } else {
            return "餐厅";
        }

    }
     public function actionPay(){
         $leixing=\yii::$app->request->get('lx');
         $childs=$this->getAllstu();       
         $return_arr['leixing']= $leixing;   
         $return_arr['childs']= $childs;   
         return $this->renderPartial('pay',$return_arr);
     }
     public function getAllstu(){
         $openid=\yii::$app->view->params['openid'];
         $childs =  WpIschoolPastudent::find()->select('id,stu_id,stu_name,school,class,sid')->where(['openid'=>$openid,'ispass'=>'y'])->orderBy('stu_id asc')->asArray()->all();
            
         foreach($childs as $v){
            $b[] = $v['stu_id'];
         }
         $b[] = 0;
         $num = "id in (".join(',',$b).")";
         $child=WpIschoolStudent::find()->select('enddateck,stuno2,sid')->where( $num)->orderBy('id asc')->asArray()->all();                       
         foreach($child as $k=>$v){
                if( $v['enddateck'] < time() && $v['sid']!='56650'){
                    $childs[$k]['endck'] = "mkt"; //有效期小于当前时间 则为没开通
                }else{
                    if($v['sid']=='56650' && $v['enddateck']<time()){
                        $childs[$k]['endck'] = "ykt1";
                    }else{
                        $childs[$k]['endck'] = "ykt";
                    }
                    
                }
                $childs[$k]['stuno2'] = $v['stuno2'];
         }
         return $childs;
     }
      public function actionIs_weixin(){
           if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
            }
           return false;
      
      } 
    
       public function actionRecharge(){
            if (\Yii::$app->request->isPost) {
                 $xinxi=\yii::$app->request->post("tc");  //学校|班级|姓名|学校ID|学生ID|openid|学号 
            }else{
                 $xinxi=\yii::$app->request->get("tc"); 
            }
             $xinxif = explode('|', $xinxi);
            // if($xinxif[3]== '56758' ){
            //     $return_arr['ykt']=2;
            //  }else if($xinxif[3]== '56757'){
            //     $return_arr['ykt']=1;
            //  }else if($xinxif[3]== '56759'){
            //     $return_arr['ykt']=3;
            //  }else if($xinxif[3]== '56650'){
            //     $return_arr['ykt']=4;
            //  }else if($xinxif[3]== '56738'){
            //    //商水一高
            //     $return_arr['ykt']=5;
            //  }else if($xinxif[3]== '56739'){
            //     //商水新世纪
            //     $return_arr['ykt']=6;
            //  }else if( $xinxif[3]== '56742' || $xinxif[3]== '56741' ||
            //   $xinxif[3]== '56732' || $xinxif[3]== '56707' || $xinxif[3]== '56698' || $xinxif[3]== '56689' || $xinxif[3]== '56684' ||
            //   $xinxif[3]== '56683' || $xinxif[3]== '56682' || $xinxif[3]== '56681' || $xinxif[3]== '56675' || $xinxif[3]== '56670' ||
            //   $xinxif[3]== '56666' || $xinxif[3]== '56665' || $xinxif[3]== '56665' || $xinxif[3]== '56664' || $xinxif[3]== '56654' ||
            //   $xinxif[3]== '56653' || $xinxif[3]== '56652' || $xinxif[3]== '56623'){
            //    //和许昌大同街小学/临颍县窝城镇中心小学/漯河市邓襄镇第一初级中学/临颍县王孟中心小学/西平县人和育才小学/王孟镇范庙小学/漯河市第五高级中学/台陈一中
            //   //河南省临颍县职业教育中心/窝城二中/王岗二中/漯河市艺术学校/襄城县玉成学校/石桥一中/窝城一中/王孟一中/马庙小学/巨陵二中/巨陵一中/许昌市建安区实验中学
            //     $return_arr['ykt']=7;
            //  }else if($xinxif[3]== '56740'){
            //     //许昌新区实验学校
            //     $return_arr['ykt']=8;
            //  }else if($xinxif[3]== '56649'){
            //     //许昌市大同街小学
            //     $return_arr['ykt']=9;
            //  }else if($xinxif[3]== '56748'){
            //     //最新支付模式
            //     $return_arr['ykt']=10;
            //  }else{
            //     $return_arr['ykt']=0;
            //  }
             if($xinxif[3]== '56758' ){
                $return_arr['ykt']=2;
             }else if($xinxif[3]== '56650'){
                $return_arr['ykt']=4;
             }else{
                $return_arr['ykt']=10;
             }
             //56739商水新世纪
            // $xinxif[3]=='56739'?$return_arr['qqkt']=0:$return_arr['qqkt']=1;
            //商水一高
              // $xinxif[3]=='56738'?$return_arr['xyxf']=1:$return_arr['xyxf']=0;
             //查学生
             $endtime=WpIschoolStudent::find()->select('enddateqq')->where(['id'=>$xinxif[4]])->asArray()->one();
//                var_dump($return_arr['ykt']);die;
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
             $return_arr['endtime']=$endtime['enddateqq'];
             $return_arr['sid']=$xinxif[3];
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
         $d->uid=$this->getUid();
         $d->save();
         $school=WpIschoolStudent::find()->select('sid')->where(['id'=>$xuehao])->asArray()->one();
         $sid=$school['sid'];
         //拼接跳转url，参数以“|”分割，分别为“学校|班级|姓名|学生id|内部订单号|总价|支付种类”
         $pay_url .= "?payInfo=".$openid."|".$sid."|".$xingming."|".$xuehao."|".$data."|".$total.$zfzl;
         $retdata['retcode'] = 0;
         $retdata['url'] = $pay_url;
         $this->ajaxReturn($retdata,'json');
         return 0;
      }
      //
      //交学费跳转支付页面
     public function actionRedirectxfpay(){
//         $post=\yii::$app->request->post();
//         var_dump($post);die;
         $tc=\yii::$app->request->post("tc");
         $total=\yii::$app->request->post("total");
         $type=\yii::$app->request->post("type");
         $xinxif = explode('|', $tc);//学校 班级 姓名 学校ID 学生ID openid
         $xuexiao = $xinxif[0];      //学校
         $banji = $xinxif[1];          //班级
         $xingming = $xinxif[2];    //姓名
         $xuehao = $xinxif[4];   //学生id
         $sid= $xinxif[3];//学校id
         $pay_url =URL_PATH."/pay/jsapijf.php";
         $d=new WpIschoolOrderjf;
         $d->openid = $xinxif[5];
         $d->paytype = "JSAPI";
         $d->total = $total;
         $d->trade_no =$data1=date('Ym').time().rand(100,999);
         $d->trade_name= $xuexiao."|".$banji."|".$xingming."|".$xuehao;
         $d->ctime= time();
         $d->stuid = $xuehao;
         $d->type =$type;
         $d->save(false);
          //拼接跳转url，参数以“|”分割，分别为“openid|姓名|学号|内部订单号|总价”
         $pay_url .= "?payInfo=|".$sid."|".$xingming."|".$xuehao."|".$data1."|".$total."|". $xinxif[5]."|jffw";
         $retdata['retcode'] = 0;
         $retdata['url'] = $pay_url;
         $this->ajaxReturn($retdata,'json');
       
       
     }
       //补卡信息提交跳转页面
     public function actionRedirectcpay(){
        if (\Yii::$app->request->isPost) {
             $total=\yii::$app->request->post("total"); //价格
             $xinxi=\yii::$app->request->post("tc"); 
        }else{
             $total=\yii::$app->request->get("total"); 
             $xinxi=\yii::$app->request->get("tc"); 
        } 
        // $total=\yii::$app->request->post("total");//价格
        if(!is_numeric($total)){  //总价不合法
            $retdata['retcode'] = 1;
            $retdata['retmsg'] = "总价不合法";
            $this->ajaxReturn($retdata,'json');
            return 0;
        }
          
        $xinxif = explode('|', $xinxi);//学校 班级 姓名 学校ID 学生ID openid
        $xuexiao = $xinxif[0];      //学校
        $banji = $xinxif[1];          //班级
        $xingming = $xinxif[2];    //姓名
        $xuehao = $xinxif[4];        //学生ID      
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
        $school=WpIschoolStudent::find()->select('sid')->where(['id'=>$xuehao])->asArray()->one();
        $sid=$school['sid'];
        //拼接跳转url，参数以“|”分割，分别为“姓名|学号|内部订单号|总价”
        $pay_url .= "?payInfo=|".$sid."|".$xingming."|".$xuehao."|".$data1."|".$total."|". $xinxif[5];
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
        $return_arr['openid']=$xinxif[5];

        return $this->renderPartial('solution',$return_arr);
     }
     public function actionWaterSolution(){
        if (\Yii::$app->request->isPost) {
             $xinxi=\yii::$app->request->post("tc");  //学校|班级|姓名|学校ID|学生ID|openid|学号 
        }else{
             $xinxi=\yii::$app->request->get("tc"); 
        } 
        $xinxif = explode('|', $xinxi);
        $nxinxi = $xinxif[2]."|".$xinxif[6];
        $return_arr['sid']=$xinxif[3];
        $return_arr['nxinxi']=$nxinxi; 
        $return_arr['openid']=$xinxif[5];
        // var_dump($return_arr);die;

        return $this->renderPartial('water-solution',$return_arr);
     }
     //学费缴费页面
      public function actionXuefei(){    
        $xinxi=\yii::$app->request->post("tc");  //学校|班级|姓名|学校ID|学生ID|openid|学号      
        $xinxif = explode('|', $xinxi);
        $nxinxi = $xinxif[2]."|".$xinxif[6];
        $return_arr['sid']=$xinxif[3];
        $return_arr['nxinxi']=$nxinxi; 
        $return_arr['openid']=$xinxif[5];
        return $this->renderPartial('xuefei',$return_arr);
     }
     //获取所有绑定学生的基本信息
     protected function getstuinfo(){  
        $openid=\yii::$app->view->params['openid'];                         
        $childs =  WpIschoolPastudent::find()->select('id,stu_id,stu_name,school,class,sid')->where(['openid'=>$openid,'ispass'=>'y'])->orderBy('stu_id asc')->asArray()->all();
        foreach($childs as $v){
                $b[] = $v['stu_id'];
        }
        $b[] = 0;
        $num = "id in (".join(',',$b).")";
        $child=WpIschoolStudent::find()->select('stuno2')->where( $num)->orderBy('id asc')->asArray()->all();

        foreach($child as $k=>$v){
            $childs[$k]['stuno2'] = $v['stuno2'];
        }
        return  $childs; 
     
     }
     //交学费
     public function actionXf(){
        $childs = $this->getstuinfo();
        $sid = \yii::$app->view->params['sid'];
        $return_arr['sid']=$sid;
//       var_dump($childs);die;
        $return_arr['childs']=$childs;
        return $this->renderPartial('xf',$return_arr); 
     }
     //交住宿费
     public function actionZsf(){
        $childs = $this->getstuinfo();
        $sid = \yii::$app->view->params['sid'];
        $return_arr['sid']=$sid;
        $return_arr['childs']=$childs;
        return $this->renderPartial('zsf',$return_arr); 
     }
     //交书费
     public function actionSf(){
        $childs = $this->getstuinfo();
        $return_arr['childs']=$childs;
        return $this->renderPartial('sf',$return_arr); 
     }
     //缴费记录
     public function actionJfjl(){
        $childs = $this->getstuinfo();
        foreach($childs as $k=>$v){
            $childs[$k]=$v['stu_id'];
        }
        $child=  WpIschoolOrderjf::find()->select('total,stuid,type,ctime')->where(['in','stuid',$childs])->andWhere(['issuccess'=>1])->orderBy('ctime desc')->asArray()->all();
        foreach($child as $k=>$v){
            $stuname=WpIschoolStudent::find()->select('name')->where(['id'=>$v['stuid']])->asArray()->one();
            $child[$k]['tname']=$stuname['name'];
        }
//        var_dump($child);die;
        $return_arr['childs']=$child;
        return $this->renderPartial('jfjl',$return_arr); 
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
        $stuid= WpIschoolStudent::find()->select('id')->where($where)->one();
        $stu_id=$stuid['id'];
        $info=WpIschoolKaku::find()->where(['stuno2'=>$cardId])->asArray()->one();
        if($info){
            //卡号
             $stuno2=$info['stuno2'];
             //epc
             $epc=$info['epc'];
             //电话号
             $tel=$info['telid'];
             //学生表更改
            $d = WpIschoolStudent::findOne($stu_id);
            $d->stuno2= $stuno2;
            $d->cardid=$epc;
            $leve=$d->save(false);
            $t=WpIschoolStudentCard::find()->where(['stu_id'=>$stu_id])->one();
            if($t){
                $t->card_no=$tel;
                $t->save(false);
            }           
            $e=WpIschoolSchoolEpc::find()->where(['stu_id'=>$stu_id])->one();
            if($e){
                $e->EPC=$epc;
                $e->save(false);
            }
            if($leve === 0 || $leve > 0){
                 $at['retcode']=0; 
            }else{
                 $at['retcode']=1; 
            }
        }else{
            $at['retcode']=3; 
        }
//        $xinxif = explode('|', $xinxi);
//        $where=[];
//        $where['name']= $xinxif[0];
//        $where['stuno2']= $xinxif[1];
//        $student= WpIschoolStudent::find()->where($where)->one();
//        $student->cardid=$cardId;
//        $m=$student->save(false);
//        if($m){
//           $at['retcode']=0; 
//        }
        $this->ajaxReturn($at,'json'); 
     }
     public function actionCkredirecpay(){
        $total=\yii::$app->request->post("total"); 
        $trade_name=\yii::$app->request->post("tc");
        $sid=\yii::$app->request->post("sid"); 

        if(!is_numeric($total)){  //总价不合法
            $retdata['retcode'] = 1;
            $retdata['retmsg'] = "总价不合法";
            $this->ajaxReturn($retdata,'json');
            return 0;
        } 
        // if(\yii::$app->view->params['sid']=='56650'){
        $jinmai_school=\yii::$app->params['cooperative.school'];
        $zfend_school=\yii::$app->params['zfend.school'];
        $user_arr=explode('|',$trade_name);
        $user_name=$user_arr[0];
        $user_no=$user_arr[1];
        if(in_array($sid,$jinmai_school)){
            //身份认证               
            //包体数据
            $requst_info=[
                'actionStr'=>'YKT_AUTHENTICATION',
                'version'=>'200',
                'thirdCode'=>'',
                'serialType'=>'1',
                'serialValue'=>$user_no,
                'name'=>$user_name
            ];
            $jsonInfo=$this->getPostInfo($requst_info);
            // var_dump( $jsonInfo);die;
            switch ($jsonInfo['resultCode']) {
              case '0200':
                $actInfo='用户信息未导入';
                break;
              case '0201':
                $actInfo='账户处于非正常状态';
                // $num=1;
                break;
              case '0202':
                $actInfo='编号验证未通过';
                break;
              case '0203':
                $actInfo='姓名验证未通过';
                break;
              case '0000':
                $num=1;
                break;
              default:
                $actInfo='验证未通过,请联系客服';
                break;
            }
         }else{
            if(in_array($sid,$zfend_school)){
              if(preg_match('/[a-zA-Z]/',$user_no)){
                 $user_no=substr($user_no,6);
              }else{
                 $user_no=substr($user_no,2);
              }
            }
            try {
              $pdo=new \PDO('mysql:host=127.0.0.1;dbname=card','root','hnzf123456');
              $pdo->query("set character set 'utf8'");
                                                                         
            } catch (PDOException $e) {
                 print "Error!: " . $e->getMessage() . "<br/>";
                 die();        
            }        
            $sql1="SELECT card_no from zf_card_info where user_no =:user_no and user_name =:user_name "; 
            $stmt=$pdo->prepare($sql1);
            $stmt->execute(array(':user_no'=>$user_no,':user_name'=>$user_name));
            $num=$stmt->rowCount();
            $actInfo='学生卡暂未导入！';
         } 
         if(!$num){
            $retdata['retmsg'] = $actInfo;
            $this->ajaxReturn($retdata,'json');
         }    
        // }   
       
        $opid=\yii::$app->request->post("openid"); 
        $pay_url = URL_PATH."/pay/jsapick.php";
        $data['trade_no'] = date('Ym').time().rand(100,999);
        //拼接跳转url，参数以“|”分割，分别为“姓名|学号|总价”
        $pay_url .= "?payInfo=".$opid."|".$sid."|".$trade_name."|".$data['trade_no']."|".$total."|".$sid."|ckcz";
       
        $retdata['retcode'] = 0;
        $retdata['url'] = $pay_url;
        $this->ajaxReturn($retdata,'json'); 
     }
     public function actionWaterCkredirecpay(){
        // var_dump(\yii::$app->request->post());die;
        $total=\yii::$app->request->post("total"); 
        $trade_name=\yii::$app->request->post("tc");
        $sid=\yii::$app->request->post("sid"); 

        if(!is_numeric($total)){  //总价不合法
            $retdata['retcode'] = 1;
            $retdata['retmsg'] = "总价不合法";
            $this->ajaxReturn($retdata,'json');
            return 0;
        } 
       
        $opid=\yii::$app->request->post("openid"); 
        $pay_url = URL_PATH."/pay/jsapisk.php";
        $data['trade_no'] = date('Ym').time().rand(100,999);
        //拼接跳转url，参数以“|”分割，分别为“姓名|学号|总价”
        $pay_url .= "?payInfo=".$opid."|".$sid."|".$trade_name."|".$data['trade_no']."|".$total."|".$sid."|skcz";
       
        $retdata['retcode'] = 0;
        $retdata['url'] = $pay_url;
        $this->ajaxReturn($retdata,'json');
        return 0; 
     }
     public function actionYue(){
        return $this->renderPartial('yue');
     }
     public function actionSendmsg(){      
        $receiveInfo=file_get_contents("php://input");
        // file_put_contents("xiaofei.txt",$receiveInfo."\r\n",FILE_APPEND);
        if(!is_array($receiveInfo)){
            file_put_contents("xiaofei.txt",$receiveInfo."\r\n",FILE_APPEND);
            $receiveInfo=json_decode($receiveInfo,true);
        }      
        $post = [];
        $post['money'] = number_format($receiveInfo['amount']/100, 2);
        $post['user_no'] = $receiveInfo['custNo'];
        $post['balance'] = number_format($receiveInfo['curBalance']/100, 2);
        $stu=WpIschoolStudent::find()->select('name,sid')->where(['stuno2'=>$receiveInfo['custNo']])->asArray()->one();
        $post['name'] = $stu['name'];
        $post['sid'] = $stu['sid'];
        $year=substr($receiveInfo['tradeTime'],0,4)."-".substr($receiveInfo['tradeTime'],4,2)."-".substr($receiveInfo['tradeTime'],6,2);
        $time=substr($receiveInfo['tradeTime'],8,2).":".substr($receiveInfo['tradeTime'],10,2).":".substr($receiveInfo['tradeTime'],12,2);
        $createtime=strtotime($year." ".$time);
        $post['time'] = $createtime;
        $post['pos_no'] = "1号消费机";
        require_once("/data/web/ischool/mobile/assets/sendCardWeixin.class.php");
        $ret = \sendWeiXin::sendCard($post);
        // file_put_contents("xiaofei.txt",json_encode($ret , JSON_UNESCAPED_UNICODE),FILE_APPEND);
        if(is_array($ret)){
            $return_arr=['resultCode'=>'0000','resultMsg'=>'成功'];            
        }else{
            $return_arr=['resultCode'=>'0001','resultMsg'=>'失败'];
        }
        return json_encode($return_arr, JSON_UNESCAPED_UNICODE);
     }
}






