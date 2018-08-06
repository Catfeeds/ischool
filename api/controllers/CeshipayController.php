<?php

namespace api\controllers;

use api\models\WpIschoolOrderWater;
use api\models\WpIschoolOrderbk;
use api\models\WpIschoolOrderjf;
use api\models\WpIschoolOrderjx;
use api\models\WpIschoolSchool;
use api\models\WpIschoolUser;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
require_once "/data/lib/push.php";


class CeshipayController extends BaseActiveController
{

    private function actionTest()
    {
        if($this->post['info']==1)
        return  $this->formatAsjson("success");
        else return $this->errorHandler(0);
    }

    private function ToUrlParams($obj)
    {
    $buff = "";
    foreach ($obj as $k => $v)
    {
        if($k != "sign" && $v != "" && !is_array($v)){
            $buff .= $k . "=" . $v . "&";
        }
    }
        
    $buff = trim($buff, "&");
    return $buff;
    }
    private function makeSign($obj)
    {
    $ret = [];
    $ret['appid'] = $obj['appid'];
    $ret['partnerid'] = $obj['mch_id'];
    $ret['prepayid'] = $obj['prepay_id'];
    $ret['package'] = 'Sign=WXPay';
    $ret['noncestr'] = md5($obj['mch_id'].random_int(100000, 999999));
    $ret['timestamp'] = time()."";
    ksort($ret);
    $string = $this->ToUrlParams($ret);
    $string = $string . "&key=".\WxPayConfig::KEY;
    $string = md5($string);
    $ret['sign'] = strtoupper($string);
    return $ret;
    
    }
    public function actionWxpay()
    {
        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/easywechat/autoload.php";
    /*
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis").random_int(10000, 99999));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://api.jxqwt.cn/ceshipay/wxpaynotify");
        $input->SetTrade_type("APP");
        $result = \WxPayApi::unifiedOrder($input);
        //Log::DEBUG("unifiedorder:" . json_encode($result));
        //$ret = json_decode($result,true);
        //return $result;
    $ret = $this->makeSign($result);
        return $this->formatAsjson($ret);
    */
    //use EasyWeChat\Factory;
    //$app = \EasyWeChat\Factory::officialAccount($options);
    $config = [
            'app_id'             => 'wx3ac5dc6655ed35fd',
            'mch_id'             => '1507216061',
            'key'                => 've8Qv1GuLhI6m6OIHo3oQFYGaNn1pJHH',   // API 密钥
    ];
    $app = \EasyWeChat\Factory::payment($config);
    //$app->setSubMerchant('1505494721'); 
    $result = $app->order->unify([
        'body' => 'TTTTT',
        'out_trade_no' => '20150806125346',
        'total_fee' => 88,
        'notify_url' => 'http://api.jxqwt.cn/ceshipay/wxpaynotify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
        'trade_type' => 'APP',
    ]);
    var_dump($result);

    }

    //水卡发起支付
    public function actionWxpaysk()
    {
        $uid = $this->post['uid'];
        $paytype = "WXAPP";
        $money = $this->post['money']*100;
        $xuexiao = $this->post['school'];
        $banji = $this->post['class'];
        $xingming = $this->post['student'];
        $xuehao = $this->post['stu_id'];        //学生id
        $sid = $this->post['sid'];
        $type = $this->post['type'];    //water
        $pay_subject = $this->post['title'].'---'.$xingming;
        $passback_param = $type."|".$uid."|".$xuexiao."|".$banji."|".$xingming."|".$xuehao."|".$sid;
        if($uid == "59792"){
            $money = "1";
        }
        $vendorpath = \Yii::getAlias("@vendor");
         require_once $vendorpath."/lee/wxpaysdk/WxPay.Api.php";
        $input = new \WxPayUnifiedOrder();
        $pay_trade_no = \WxPayConfig::MCHID.date("YmdHis").random_int(10000, 99999);

        $input->SetBody($pay_subject);
        $input->SetAttach($passback_param);
        $input->SetOut_trade_no($pay_trade_no);
        $input->SetTotal_fee($money);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url("http://api.jxqwt.cn/ceshipay/wxpaynotify");
        $input->SetTrade_type("APP");
        $result = \WxPayApi::unifiedOrder($input);
         \Yii::trace(111111);
        \Yii::trace($result);
        // Log::DEBUG("unifiedorder:" . json_encode($result));
        // $ret = json_decode($result,true);
        // return $result;
        if($type !== "water"){
            return $this->errorHandler("1046");
        }
            $user_no = $this->stuinfo($xuehao)[0]['stuno2'];
            // $zfend_school=\yii::$app->params['zfend.school'];
            // if(in_array($sid,$zfend_school)){
            //     if(preg_match('/[a-zA-Z]/',$user_no)){
            //         $sid = substr($user_no, 1, 5);
            //         $user_no=substr($user_no,6);
            //     }else{
            //         $sid = '56651';
            //         $user_no=substr($user_no,2);
            //     }
            // }
            $sql1="SELECT card_no from zf_card_info where user_no =:user_no and school_id =:school_id ";
            $stmt=\Yii::$app->db3->createCommand($sql1,[":user_no"=>$user_no,":school_id"=>$sid])->queryAll();
            \Yii::trace($stmt);
            if(!$stmt){
                return $this->errorHandler("1038");
            }
        if($result['return_code'] == "SUCCESS" && $result['result_code'] == "SUCCESS")
        {
            $ret = $this->makeSign($result);
            \Yii::trace($ret);
            return $this->formatAsjson($ret);
        }
        else return $this->formatAsjson($result);
    }


//微信功能支付或补卡发起支付
    public function actionWxpaygn()
    {
        $uid = $this->post['uid'];
        $paytype = "WXAPPJSAPI";
        $money = ($this->post['money'])*100;
        $xuexiao = $this->post['school'];
        $banji = $this->post['class'];
        $xingming = $this->post['student'];
        $xuehao = $this->post['stu_id'];
        $shijian = $this->post['shijian'];
        $type = $this->post['type'];    //判断是功能支付还是补卡 gnzf buka
        $sid = $this->post['sid'];
        $patz = $this->post['patz']!=="null"?$this->post['patz']:"npatz";
        $jxgt = $this->post['jxgt']!=="null"?$this->post['jxgt']:"njxgt";
        $qqdh = $this->post['qqdh']!=="null"?$this->post['qqdh']:"nqqdh";
        $ckfw = $this->post['ckfw']!=="null"?$this->post['ckfw']:"nckfw";
        $zfzl = $patz."-".$qqdh."-".$jxgt."-".$ckfw."-".$shijian;
        $pay_subject = $this->post['title'].'---'.$xingming;;
//        $passback_param = $type."|".$uid;
        $passback_param = $type."|".$uid."|".$xuexiao."|".$banji."|".$xingming."|".$xuehao."|".$zfzl;  //支付类型|支付人uid|学校|班级|姓名|学号|支付种类
        if($type !== "gnzf" && $type !== "buka"){
            return $this->errorHandler("1046");
        }

        if ($type == "gnzf"){
            $moneyhs = $this->actionTotalhs($sid,$this->post['patz'],$this->post['jxgt'],$this->post['qqdh'],$this->post['ckfw'],$shijian)*100;
            \Yii::trace($moneyhs);
            if ($moneyhs != $money){
                return $this->errorHandler("1042");
            }
        }

        if (empty($shijian)){
            return $this->errorHandler("1061");
        }

        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/xclqsk_wxpaysdk/WxPay.Api.php";
        $input = new \WxPayUnifiedOrder();
        $pay_trade_no = \WxPayConfig::MCHID.date("YmdHis").random_int(10000, 99999);
        if($uid == "59792"){
            $money = "1";
        }
        $input->SetBody($pay_subject);
        $input->SetAttach($passback_param);
        $input->SetOut_trade_no($pay_trade_no);
        $input->SetTotal_fee($money);
        // $input->SetSpbill_create_ip($_SERVER['REMOTE_ADDR']);
        // $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url("http://api.jxqwt.cn/ceshipay/wxpaynotify");
        $input->SetTrade_type("APP");
        $result = \WxPayApi::unifiedOrder($input);
        \Yii::trace($result);
        // Log::DEBUG("unifiedorder:" . json_encode($result));
        // $ret = json_decode($result,true);
        // return $result;
        // 插入数据库订单
        if ($type ==="gnzf"){
            $orderjx = new WpIschoolOrderjx();
            $orderjx->openid = $this->openid;
            $orderjx->money = $money/100;
            $orderjx->trade_no = $pay_trade_no;
            $orderjx->trade_name =$xuexiao."|".$banji."|".$xingming."|".$xuehao;
            $orderjx->paytype = $paytype;
            $orderjx->ctime = time();
            $orderjx->stuid = $xuehao;
            $orderjx->uid = $uid;
            $orderjx->save();
        }elseif ($type ==="buka"){
            $orderjx = new WpIschoolOrderbk();
            $orderjx->openid = $this->openid;
            $orderjx->money = $money/100;
            $orderjx->trade_no = $pay_trade_no;
            $orderjx->trade_name =$xuexiao."|".$banji."|".$xingming."|".$xuehao;
            $orderjx->paytype = $paytype;
            $orderjx->ctime = time();
            $orderjx->stuid = $xuehao;
            $orderjx->uid = $uid;
            $orderjx->save();
        }
        if($result['return_code'] == "SUCCESS" && $result['result_code'] == "SUCCESS")
        {
            $ret = $this->makeSign($result);
            \Yii::trace($ret);
            return $this->formatAsjson($ret);
        }
        else return $this->formatAsjson($result);
    }

    //微信支付功能支付补卡回调更新数据
    private function wxgnbk($data)
    {
        $trans_id = $data['transaction_id']; //微信交易号
        $trade_no = $data['out_trade_no'];
        $body = $data['attach'];
        $zfopenid = $this->openid;
        $result = explode("|",$body);
        $type = $result[0];     //支付类型
        $zfuid = $result[1];      //支付人UID
        $money = $data['total_fee']/100;
        $result = explode('|', $body);  //支付类型|支付人uid|学校|班级|姓名|学生ID|支付种类

        //基类的方法支付方法需要重写
        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/wxpaysdk/WxPay.Notify.php";
        $notify = new \WxPayNotify();
        // $notify->Handle(true);

        if ($type === "gnzf"){
            $model = WpIschoolOrderjx::findOne(['trade_no'=>$trade_no]);
            if ($model){
                $num = 0;
                $xqxn = explode('-', $result[6]);
                $pdxq =$xqxn[4];//获取一学期还是一学年或一个月的值进行判断
                if ("yxq" == $pdxq) {
                    $num = "+6 month";
                } elseif ("yxn" == $pdxq) {
                    $num = "+12 month";
                } elseif ("ygy" == $pdxq) {
                    $num = "+1 month";
                }

                $num2 = "+14 month";
                $end = array();
                if ("yxn" == $pdxq && $xqxn[0] == "patz") {
                    $end['pa'] = $num2;
                }else{
                    $end['pa'] = (($xqxn[0] === "patz") ? $num : 0);
                }
                if ("yxn" == $pdxq && $xqxn[1] == "qqdh") {
                    $end['qq'] = $num2;
                }else{
                    $end["qq"] = (($xqxn[1] === "qqdh") ? $num : 0);
                }

                if ("yxn" == $pdxq && $xqxn[3] == "ckfw") {
                    $end['ck'] = $num2;
                }else{
                    $end["ck"] = (($xqxn[3] === "ckfw") ? $num : 0);
                }
                $end["jx"] = (($xqxn[2] === "jxgt") ? $num : 0);
                
                // $end = array();
                // $end['pa'] = (($xqxn[0] === "patz") ? $num : 0);
                // $end["qq"] = (($xqxn[1] === "qqdh") ? $num : 0);
                // $end["jx"] = (($xqxn[2] === "jxgt") ? $num : 0);
                // $end["ck"] = (($xqxn[3] === "ckfw") ? $num : 0);
                \Yii::trace($xqxn);
                \Yii::trace($end);
                $sql_student = "select enddatepa,enddatejx,enddateqq,enddateck,upendtimepa,upendtimejx,upendtimeqq,upendtimeck from wp_ischool_student where id=:id";
                $old_enddate = \Yii::$app->db->createCommand($sql_student,[':id'=>$result[5]])->queryAll();
                $enddatepa = ($end['pa'] == 0) ? $old_enddate[0]['enddatepa']: ((!$old_enddate || $old_enddate[0]['enddatepa'] < time())?strtotime($end['pa']):strtotime($end['pa'],$old_enddate[0]['enddatepa']));//有效期的时间
/*                if ("yxn" == $pdxq && $xqxn[0] == "patz") {
                    $enddatepa = strtotime("2019/8/31 23:59:59");
                }*/
                \Yii::trace($enddatepa);
                $enddatejx = ($end['jx'] == 0) ? $old_enddate[0]['enddatejx']: ((!$old_enddate || $old_enddate[0]['enddatejx'] < time())?strtotime($end['jx']):strtotime($end['jx'],$old_enddate[0]['enddatejx']));
                $enddateqq = ($end['qq'] == 0) ? $old_enddate[0]['enddateqq']: ((!$old_enddate || $old_enddate[0]['enddateqq'] < time())?strtotime($end['qq']):strtotime($end['qq'],$old_enddate[0]['enddateqq']));
                $enddateck = ($end['ck'] == 0) ? $old_enddate[0]['enddateck']: ((!$old_enddate || $old_enddate[0]['enddateck'] < time())?strtotime($end['ck']):strtotime($end['ck'],$old_enddate[0]['enddateck']));
                $untimepa = ($end['pa'] == 0) ? $old_enddate[0]['upendtimepa']:time(); //更新有效期的时间
                $untimejx = ($end['jx'] == 0) ? $old_enddate[0]['upendtimejx']:time();
                $untimeqq = ($end['qq'] == 0) ? $old_enddate[0]['upendtimeqq']:time();
                $untimeck = ($end['ck'] == 0) ? $old_enddate[0]['upendtimeck']:time();

                $up_student_sql = "update wp_ischool_student set enddatepa=:enddatepa,enddatejx=:enddatejx, enddateqq=:enddateqq, enddateck=:enddateck,upendtimepa=:upendtimepa,upendtimejx=:upendtimejx,upendtimeqq=:upendtimeqq,upendtimeck=:upendtimeck where id=:id";
                $resu = \Yii::$app->db->createCommand($up_student_sql,[':enddatepa'=>$enddatepa,':enddatejx'=>$enddatejx,':enddateqq'=>$enddateqq,':enddateck'=>$enddateck,':upendtimepa'=>$untimepa,':upendtimejx'=>$untimejx,':upendtimeqq'=>$untimeqq,':upendtimeck'=>$untimeck,":id"=>$result[5]])->execute();
                \Yii::trace($resu);
                if ($resu){
                    $ispa = (($xqxn[0] == "patz") ? 1 : 0); //根据支付传参确定是给哪一项产品支付
                    $isqq = (($xqxn[1] == "qqdh") ? 1 : 0);
                    $isjx = (($xqxn[2] == "jxgt") ? 1 : 0);
                    $isck = (($xqxn[3] == "ckfw") ? 1 : 0);
                    $up_order_sql = "update wp_ischool_orderjx set ispasspa=:ispasspa,ispassjx=:ispassjx,ispassqq=:ispassqq,ispassck=:ispassck,utime=:utime,zfopenid=:zfopenid,trans_id=:trans_id,zfuid=:zfuid where stuid=:stuid  and trade_no=:trade_no";
                    $resu = \Yii::$app->db->createCommand($up_order_sql,[':ispasspa'=>$ispa,':ispassjx'=>$isjx,':ispassqq'=>$isqq,':ispassck'=>$isck,':utime'=>time(),':zfopenid'=>$this->openid,':trans_id'=>$trans_id,':zfuid'=>$zfuid,':stuid'=>$result[5],':trade_no'=>$trade_no])->execute();
                    if ($resu) {
                        \Jpush::push($zfuid,"支付成功！","id");
                        return true;
                    }
                }
            }
        }elseif ($type === "buka"){
            $resu=WpIschoolOrderbk::findOne(['trade_no'=>$trade_no]);
            if($resu) {
                $resu->zfopenid = $this->openid;
                $resu->utime = time();
                $resu->trans_id = $trans_id;
                $resu->ispass = 1;
                $resu->uid = $zfuid;
                $resu->save(false);
                \Jpush::push($zfuid,"支付成功！","id");
                return true;
            }
        }
    }

    //微信支付餐卡学费住宿费微信支付发起支付
    public function actionWxpaywt()
    {
        $uid = $this->post['uid'];
        $paytype = "WXAPP";
        $money = $this->post['money']*100;
        $xuexiao = $this->post['school'];
        $banji = $this->post['class'];
        $xingming = $this->post['student'];
        $xuehao = $this->post['stu_id'];        //学生id
        $sid = $this->post['sid'];
        $type = $this->post['type'];    //canka餐卡|xuefei学费/shufei书费|zhusufei住宿费
        $pay_subject = $this->post['title'].'---'.$xingming;;
        $passback_param = $type."|".$uid."|".$xuexiao."|".$banji."|".$xingming."|".$xuehao."|".$sid;
        if($type !== "canka" && $type !== "xuefei" && $type !== "shufei" && $type !== "zhusufei"){
            return $this->errorHandler("1046");
        }

        $vendorpath = \Yii::getAlias("@vendor");
        if($sid == "56650"){
            require_once $vendorpath."/lee/xcsg_wxpaysdk/WxPay.Api.php";
        }else{
            require_once $vendorpath."/lee/wxpaysdk/WxPay.Api.php";
        }
        $input = new \WxPayUnifiedOrder();
        $pay_trade_no = \WxPayConfig::MCHID.date("YmdHis").random_int(10000, 99999);
        if($uid == "59792"){
            $money = "1";
        }
        $input->SetBody($pay_subject);
        $input->SetAttach($passback_param);
        $input->SetOut_trade_no($pay_trade_no);
        $input->SetTotal_fee($money);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url("http://api.jxqwt.cn/ceshipay/wxpaynotify");
        $input->SetTrade_type("APP");
        $result = \WxPayApi::unifiedOrder($input);
    
        \Yii::trace($result);
        //Log::DEBUG("unifiedorder:" . json_encode($result));
        //$ret = json_decode($result,true);
        //return $result;
        if ($type ==="canka"){
            $user_no = $this->stuinfo($xuehao)[0]['stuno2'];
            $zfend_school=\yii::$app->params['zfend.school'];
            if(in_array($sid,$zfend_school)){
                if(preg_match('/[a-zA-Z]/',$user_no)){
                    $sid = substr($user_no, 1, 5);
                    $user_no=substr($user_no,6);
                }else{
                    $sid = '56651';
                    $user_no=substr($user_no,2);
                }
            }
            $sql1="SELECT card_no from zf_card_info where user_no =:user_no and school_id =:school_id ";
            $stmt=\Yii::$app->db2->createCommand($sql1,[":user_no"=>$user_no,":school_id"=>$sid])->queryAll();
            \Yii::trace($stmt);
            if(!$stmt){
                return $this->errorHandler("1038");
            }
        }elseif ($type !=="canka"){
            if ($type === "xuefei"){
                $types = "学费";
            }elseif ($type === "shufei"){
                $types = "书费";
            }elseif ($type === "zhusufei"){
                $types = "住宿费";
            }else{
                return $this->errorHandler("1046");
            }
            $orderjf = new WpIschoolOrderjf();
            $orderjf->openid = $this->openid;
            $orderjf->total = $money/100;
            $orderjf->trade_no = $pay_trade_no;
            $orderjf->trade_name =$xuexiao."|".$banji."|".$xingming."|".$xuehao;
            $orderjf->paytype = $paytype;
            $orderjf->ctime = time();
            $orderjf->stuid = $xuehao;
            $orderjf->uid = $uid;
            $orderjf->type = $types;
            $orderjf->save();
        }
    if($result['return_code'] == "SUCCESS" && $result['result_code'] == "SUCCESS")
    {
         $ret = $this->makeSign($result);
         return $this->formatAsjson($ret);
    }
        else return $this->formatAsjson($result);
    }

    //微信支付统一回调总接口
    public function actionWxpaynotify()
    {
        //基类的方法支付方法需要重写
        $xml = file_get_contents("php://input");
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        $re = explode("|",$data['attach']);
        $type = $re['0'];
        $sid = $re['6'];
        \Yii::trace($type);
        \Yii::trace($data);
        $vendorpath = \Yii::getAlias("@vendor");
        if($sid == "56650"){
            require_once $vendorpath . "/lee/xcsg_wxpaysdk/WxPay.Api.php";
            require_once $vendorpath . "/lee/xcsg_wxpaysdk/WxPay.Notify.php";
        }else{
            require_once $vendorpath . "/lee/wxpaysdk/WxPay.Api.php";
            require_once $vendorpath . "/lee/wxpaysdk/WxPay.Notify.php";
        }

        $notify = new \WxPayNotify();
        $notify->Handle(true);
        if ($type ==="canka" || $type ==="xuefei" || $type ==="shufei" || $type ==="zhusufei"){
            $res = $this->upckxf($data);
        }elseif ($type ==="water"){
            $res = $this->upwxsk($data);
        }elseif ($type ==="gnzf" || $type ==="buka"){
            $res = $this->wxgnbk($data);    //功能补卡
        }
        \Yii::trace($res);
        if ($res) {
           $notify->Handle(true);
        }
    }

    //微信支付更新水卡支付回调
    private function upwxsk($data)
    {
        $trans_id = $data['transaction_id']; //微信交易号
        $trade_no = $data['out_trade_no'];
        $body = $data['attach'];
        $zfopenid = $this->openid;
        $passback_paramsa = explode("|",$body);
        $type = $passback_paramsa[0];     //支付种类
        $zfuid = $passback_paramsa[1];      //支付人UID
        $money = $data['total_fee']/100;

        $result = explode('|', $body);  //支付类型|支付人UID|学校|班级|姓名|学生ID|学校id
        if($type ==="water"){
            $sid = $result[6];
            $user_no = $this->stuinfo($result[5])[0]['stuno2'];
            // $zfend_school=\yii::$app->params['zfend.school'];
            // if(in_array($result[6],$zfend_school)){
            //     if(preg_match('/[a-zA-Z]/',$user_no)){
            //         $sid = substr($user_no, 1, 5);
            //         $user_no=substr($user_no,6);
            //     }else{
            //         $sid = '56651';
            //         $user_no=substr($user_no,2);
            //     }
            // }else{
            //     $sid = $result[6];
            // }
            $sql = "select card_no,balance,created_by from zf_card_info where  user_no=:user_no and school_id = :school_id";
            $resu=\Yii::$app->db3->createCommand($sql,[":user_no"=>$user_no,":school_id"=>$sid])->queryAll();
            \Yii::trace($resu);

            if($resu){
                $up_rechange_sql="insert into zf_recharge_detail (id,card_no,credit,type,balance,pos_no,created_by,time,note,is_active,school_id,trade_no) values ('',:card_no,:credit,'WXAPPJSAPI','0','0',:created_by,:time,'0','0',:school_id,:trade_no)";
                $stmt2=\Yii::$app->db3->createCommand($up_rechange_sql,[":card_no"=>$resu[0]['card_no'],":credit"=>$money,":created_by"=>$resu[0]['created_by'],":time"=>time(),":school_id"=>$sid,":trade_no"=>$trade_no])->execute();
                if ($resu) {
                    \Jpush::push($zfuid,"支付成功！","id");
                    return true;
                }
            }
        }
    }
    //更新餐卡学费订单状态逻辑
    private  function upckxf($data){
        $trans_id = $data['transaction_id']; //微信交易号
        $trade_no = $data['out_trade_no'];
        $body = $data['attach'];
        $zfopenid = $this->openid;
        $passback_paramsa = explode("|",$body);
        $type = $passback_paramsa[0];     //支付种类
        $zfuid = $passback_paramsa[1];      //支付人UID
        $money = $data['total_fee']/100;

        if ($type === "xuefei"){
            $types = "学费";
        }elseif ($type === "shufei"){
            $types = "书费";
        }elseif ($type === "zhsufei"){
            $types = "住宿费";
        }
//        $type."|".$uid."|".$xuexiao."|".$banji."|".$xingming."|".$xuehao."|".$sid;
        $result = explode('|', $body);  //支付类型|支付人UID|学校|班级|姓名|学生ID|学校id
        if($type ==="canka"){
            $user_no = $this->stuinfo($result[5])[0]['stuno2'];
            $zfend_school=\yii::$app->params['zfend.school'];
            if(in_array($result[6],$zfend_school)){
                if(preg_match('/[a-zA-Z]/',$user_no)){
                    $sid = substr($user_no, 1, 5);
                    $user_no=substr($user_no,6);
                }else{
                    $sid = '56651';
                    $user_no=substr($user_no,2);
                }
            }else{
                $sid = $result[6];
            }
            $sql = "select card_no,balance,created_by from zf_card_info where  user_no=:user_no and school_id = :school_id";
            $resu=\Yii::$app->db2->createCommand($sql,[":user_no"=>$user_no,":school_id"=>$sid])->queryAll();
            \Yii::trace($resu);

            if($resu){
                $up_rechange_sql="insert into zf_recharge_detail (card_no,credit,type,balance,pos_no,created_by,time,note,is_active,school_id,trade_no) values (:card_no,:credit,'WXAPPJSAPI','0','0',:created_by,:time,'0','0',:school_id,:trade_no)";
                $stmt2=\Yii::$app->db2->createCommand($up_rechange_sql,[":card_no"=>$resu[0]['card_no'],":credit"=>$money,":created_by"=>$resu[0]['created_by'],":time"=>time(),":school_id"=>$sid,":trade_no"=>$trade_no])->execute();
                    if ($resu) {
                    \Jpush::push($zfuid,"支付成功！","id");
                        return true;
                    }
            }
        }elseif ($type !=="canka"){
            $resus=WpIschoolOrderjf::findOne(['trade_no'=>$trade_no]);
            if($resus) {
                $resus->zfopenid = $this->openid;
                $resus->uptime = time();
                $resus->trans_id = $trans_id;
                $resus->issuccess = 1;
                $resus->uid = $zfuid;
                $resus->save(false);
                \Jpush::push($zfuid,"支付成功！","id");
                return true;
            }
        }

    }

    //支付宝功能支付或补卡发起支付
    public function actionAlipay()
    {
        $uid = $this->post['uid'];
        $paytype = "ZFBJSAPI";
        $money = $this->post['money'];
        $xuexiao = $this->post['school'];
        $banji = $this->post['class'];
        $xingming = $this->post['student'];
        $xuehao = $this->post['stu_id'];
        $shijian = $this->post['shijian'];
        $type = $this->post['type'];    //判断是功能支付还是补卡 gnzf buka
        $sid = $this->post['sid'];
        $patz = $this->post['patz']!=="null"?$this->post['patz']:"npatz";
        $jxgt = $this->post['jxgt']!=="null"?$this->post['jxgt']:"njxgt";
        $qqdh = $this->post['qqdh']!=="null"?$this->post['qqdh']:"nqqdh";
        $ckfw = $this->post['ckfw']!=="null"?$this->post['ckfw']:"nckfw";
        $zfzl = $patz."-".$qqdh."-".$jxgt."-".$ckfw."-".$shijian;
        if($type !== "gnzf" && $type !== "buka"){
            return $this->errorHandler("1046");
        }
        if ($type == "gnzf"){
            $moneyhs = $this->actionTotalhs($sid,$this->post['patz'],$this->post['jxgt'],$this->post['qqdh'],$this->post['ckfw'],$shijian);
            \Yii::trace($moneyhs);
            if ($moneyhs != $money){
                return $this->errorHandler("1042");
            }
        }
        
    if (empty($shijian)){
        return $this->errorHandler("1039");
    }
    $passback_param = urlencode($zfzl."|".$type."|".$uid);
    //需要核查金额的正确性，是否是真实的金额
    $vendorpath = \Yii::getAlias("@vendor");
    require_once $vendorpath."/lee/alipaysdk/AopSdk.php";
    $aop = new \AopClient;
    $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
    //$aop->appId = 2017050507130386;
    $aop->appId = \Yii::$app->params['alipay-app-id-zfjy'];
    $aop->rsaPrivateKey = \Yii::$app->params['alipay-app-prikey-zfjy'];
    $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey-zfjy'];
    if ($sid == 56739) {
        $aop->appId = \Yii::$app->params['alipay-app-id-shxxsj'];
        $aop->rsaPrivateKey = \Yii::$app->params['alipay-app-prikey-shxxsj'];
        $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey-shxxsj'];
    }
    $aop->format = "json";
    $aop->charset = "UTF-8";
    $aop->signType = "RSA2";
    //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
    $request = new \AlipayTradeAppPayRequest();
    /*
    $biz_array = [
        "body"=>"消息体",
        "subject"=>"功能费支付",
        "timeout_express"=>"15m",
        "total_amount"=>"0.01",
        "product_code"=>"QUICK_MSECURITY_PAY",
        //"out_trade_no"=>md5(microtime() . random_int(10000, 99999))
        "out_trade_no"=>"Z20180320"
    ];*/
    //SDK已经封装掉了公共参数，这里只需要传入业务参数
    //$bizcontent = json_encode($biz_array, JSON_UNESCAPED_UNICODE );
    
//  $pay_body = "测试数据";
    $pay_body = $xuexiao."|".$banji."|".$xingming."|".$xuehao."|".$zfzl;
    $pay_subject = $this->post['title'].'---'.$xingming;
        if($uid == "59792"){
            $money = "10";
        }
        \Yii::trace($money);
    $pay_amount = $money;
    // $pay_amount = 0.01;
    $pay_trade_no = date("YmdHis")."ZF".random_int(100000, 999999);
    $bizcontent = "{\"body\":\"$pay_body\"," 
                . "\"subject\": \"$pay_subject\","
                . "\"passback_params\": \"$passback_param\","
                . "\"paytype\": \"$paytype\","
                . "\"out_trade_no\": \"$pay_trade_no\","
                . "\"timeout_express\": \"30m\"," 
                . "\"total_amount\": \"$pay_amount\","
                . "\"product_code\":\"QUICK_MSECURITY_PAY\""
                . "}";
        $request->setNotifyUrl("http://api.jxqwt.cn/ceshipay/alipaynotify");
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);
        // 插入数据库订单
        if ($type ==="gnzf"){
            $orderjx = new WpIschoolOrderjx();
            $orderjx->openid = $this->openid;
            $orderjx->money = $money;
            $orderjx->trade_no = $pay_trade_no;
            $orderjx->trade_name =$xuexiao."|".$banji."|".$xingming."|".$xuehao;
            $orderjx->paytype = $paytype;
            $orderjx->ctime = time();
            $orderjx->stuid = $xuehao;
            $orderjx->uid = $uid;
            $orderjx->save();
        }elseif ($type ==="buka"){
            $orderjx = new WpIschoolOrderbk();
            $orderjx->openid = $this->openid;
            $orderjx->money = $money;
            $orderjx->trade_no = $pay_trade_no;
            $orderjx->trade_name =$xuexiao."|".$banji."|".$xingming."|".$xuehao;
            $orderjx->paytype = $paytype;
            $orderjx->ctime = time();
            $orderjx->stuid = $xuehao;
            $orderjx->uid = $uid;
            $orderjx->save();
        }
    return $this->formatAsjson($response);
    }

//支付宝功能支付回调
    public function actionAlipaynotify()
    {
        // $_post['body']
        $trans_id = $_POST['trade_no']; //支付宝交易号
        $modelss = WpIschoolOrderjx::findOne(['trans_id'=>$trans_id]);
        if ($modelss) {
            echo "success";
            exit;
        }
        $trade_no = $_POST['out_trade_no'];
        $body = $_POST['body'];     //学校|班级|姓名|学号学生ID|学校id
        $zfopenid = $this->openid;
        \Yii::trace($zfopenid);
        \Yii::trace($this->openid);
        $result = explode('|', $body);
        $passback_param = $_POST['passback_params'];
        $passback_params = urldecode($passback_param);
        $passback_paramsa = explode("|",$passback_params);
        $zfzl = $passback_paramsa[0];
        \Yii::trace($passback_params);
        \Yii::trace($passback_paramsa);
        $type = $passback_paramsa[1];     //支付种类
        $zfuid = $passback_paramsa[2];      //支付人UID

        if ($type === "gnzf"){
            $model = WpIschoolOrderjx::findOne(['trade_no'=>$trade_no]);
            if ($model){
                $num = 0;
                $xqxn = explode('-', $zfzl);
                $pdxq =$xqxn[4];//获取一学期还是一学年或一个月的值进行判断
                if ("yxq" == $pdxq) {
                    $num = "+6 month";
                } elseif ("yxn" == $pdxq) {
                    $num = "+12 month";
                } elseif ("ygy" == $pdxq) {
                    $num = "+1 month";
                }

                $num2 = "+14 month";
                $end = array();
                if ("yxn" == $pdxq && $xqxn[0] == "patz") {
                    $end['pa'] = $num2;
                }else{
                    $end['pa'] = (($xqxn[0] === "patz") ? $num : 0);
                }
                if ("yxn" == $pdxq && $xqxn[1] == "qqdh") {
                    $end['qq'] = $num2;
                }else{
                    $end["qq"] = (($xqxn[1] === "qqdh") ? $num : 0);
                }

                if ("yxn" == $pdxq && $xqxn[3] == "ckfw") {
                    $end['ck'] = $num2;
                }else{
                    $end["ck"] = (($xqxn[3] === "ckfw") ? $num : 0);
                }
                $end["jx"] = (($xqxn[2] === "jxgt") ? $num : 0);

                // $end = array();
                // $end['pa'] = (($xqxn[0] === "patz") ? $num : 0);
                // $end["qq"] = (($xqxn[1] === "qqdh") ? $num : 0);
                // $end["jx"] = (($xqxn[2] === "jxgt") ? $num : 0);
                // $end["ck"] = (($xqxn[3] === "ckfw") ? $num : 0);
                \Yii::trace($xqxn);
                \Yii::trace($end);
                $sql_student = "select enddatepa,enddatejx,enddateqq,enddateck,upendtimepa,upendtimejx,upendtimeqq,upendtimeck from wp_ischool_student where id=:id";
                $old_enddate = \Yii::$app->db->createCommand($sql_student,[':id'=>$result[3]])->queryAll();
                $enddatepa = ($end['pa'] == 0) ? $old_enddate[0]['enddatepa']: ((!$old_enddate || $old_enddate[0]['enddatepa'] < time())?strtotime($end['pa']):strtotime($end['pa'],$old_enddate[0]['enddatepa']));//有效期的时间
/*                                if ("yxn" == $pdxq && $xqxn[0] == "patz") {
                    $enddatepa = strtotime("2019/8/31 23:59:59");
                }*/
                $enddatejx = ($end['jx'] == 0) ? $old_enddate[0]['enddatejx']: ((!$old_enddate || $old_enddate[0]['enddatejx'] < time())?strtotime($end['jx']):strtotime($end['jx'],$old_enddate[0]['enddatejx']));
                $enddateqq = ($end['qq'] == 0) ? $old_enddate[0]['enddateqq']: ((!$old_enddate || $old_enddate[0]['enddateqq'] < time())?strtotime($end['qq']):strtotime($end['qq'],$old_enddate[0]['enddateqq']));
                $enddateck = ($end['ck'] == 0) ? $old_enddate[0]['enddateck']: ((!$old_enddate || $old_enddate[0]['enddateck'] < time())?strtotime($end['ck']):strtotime($end['ck'],$old_enddate[0]['enddateck']));
                $untimepa = ($end['pa'] == 0) ? $old_enddate[0]['upendtimepa']:time(); //更新有效期的时间
                $untimejx = ($end['jx'] == 0) ? $old_enddate[0]['upendtimejx']:time();
                $untimeqq = ($end['qq'] == 0) ? $old_enddate[0]['upendtimeqq']:time();
                $untimeck = ($end['ck'] == 0) ? $old_enddate[0]['upendtimeck']:time();

                $up_student_sql = "update wp_ischool_student set enddatepa=:enddatepa,enddatejx=:enddatejx, enddateqq=:enddateqq, enddateck=:enddateck,upendtimepa=:upendtimepa,upendtimejx=:upendtimejx,upendtimeqq=:upendtimeqq,upendtimeck=:upendtimeck where id=:id";
                $resu = \Yii::$app->db->createCommand($up_student_sql,[':enddatepa'=>$enddatepa,':enddatejx'=>$enddatejx,':enddateqq'=>$enddateqq,':enddateck'=>$enddateck,':upendtimepa'=>$untimepa,':upendtimejx'=>$untimejx,':upendtimeqq'=>$untimeqq,':upendtimeck'=>$untimeck,":id"=>$result[3]])->execute();
                \Yii::trace($resu);
                if ($resu){
                    $ispa = (($xqxn[0] == "patz") ? 1 : 0); //根据支付传参确定是给哪一项产品支付
                    $isqq = (($xqxn[1] == "qqdh") ? 1 : 0);
                    $isjx = (($xqxn[2] == "jxgt") ? 1 : 0);
                    $isck = (($xqxn[3] == "ckfw") ? 1 : 0);
                    $up_order_sql = "update wp_ischool_orderjx set ispasspa=:ispasspa,ispassjx=:ispassjx,ispassqq=:ispassqq,ispassck=:ispassck,utime=:utime,zfopenid=:zfopenid,trans_id=:trans_id,zfuid=:zfuid where stuid=:stuid  and trade_no=:trade_no";
                    $resu = \Yii::$app->db->createCommand($up_order_sql,[':ispasspa'=>$ispa,':ispassjx'=>$isjx,':ispassqq'=>$isqq,':ispassck'=>$isck,':utime'=>time(),':zfopenid'=>$this->openid,':trans_id'=>$trans_id,':zfuid'=>$zfuid,':stuid'=>$result[3],':trade_no'=>$trade_no])->execute();
                }
            }
        }elseif ($type === "buka"){
            $resu=WpIschoolOrderbk::findOne(['trade_no'=>$trade_no]);
            if($resu) {
                $resu->zfopenid = $this->openid;
                $resu->utime = time();
                $resu->trans_id = $trans_id;
                $resu->ispass = 1;
                $resu->uid = $zfuid;
                $resu->save();
            }
        }

        \Yii::trace($this->post);
        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/alipaysdk/AopSdk.php";
        $aop = new \AopClient;
        \Yii::trace($aop);
        $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey'];
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
        echo "success";
        \Jpush::push($zfuid,"支付成功！","id");
    }

    //功能支付页面接口
/*    public function actionRecharge(){
        $sid = $this->post['sid'];
        $payinfo = WpIschoolSchool::find()->select('papass,jxpass,qqpass,ckpass,is_youhui')->where(['id'=>$sid])->asArray()->all();
        $payinfo[0]['youhui'] = \Yii::$app->params['youhui'];
                \Yii::trace($payinfo);
        return $this->formatAsjson($payinfo);
    }*/
            //功能支付页面接口
    public function actionRecharge(){
        $sid = $this->post['sid'];
        $payinfo = WpIschoolSchool::find()->select('papass,jxpass,qqpass,ckpass,is_youhui')->where(['id'=>$sid])->asArray()->all();
        $payinfo[0]['youhui'] = \Yii::$app->params['youhui'];
        $payinfo[0]['show_jxhd'] = 'n';
        $payinfo[0]['show_jxgt'] = 'n';    //功能支付 家校沟通是否默认选中
        $payinfo[0]['show_patz'] = $payinfo[0]['papass']=="y"?'y':'n'; //功能支付 平安通知是否默认选中
        $payinfo[0]['show_qqdh'] = $payinfo[0]['qqpass']=="y"?'y':'n'; //功能支付 亲情电话是否默认选中
        $payinfo[0]['show_xyfw'] = $payinfo[0]['ckpass']=="y"?'y':'n'; //功能支付 校园服务是否默认选中
        if ($payinfo[0]['show_patz']=='n' || $payinfo[0]['show_qqdh'] =='n' || $payinfo[0]['show_xyfw'] =='n') {
            $payinfo[0]['show_ykt'] = 'n'; //功能支付 一卡通业务是否默认选中
        }else{
            $payinfo[0]['show_ykt'] = 'y';
        }
        $data['sid'] = $sid;
        $data['jxgt'] =$payinfo[0]['show_jxgt'];
        $data['patz'] =$payinfo[0]['show_patz'];
        $data['qqdh'] =$payinfo[0]['show_qqdh'];
        $data['ckfw'] =$payinfo[0]['show_xyfw'];
        $data['shijian'] ="yxn";
        $payinfo[0]['money'] = strval($this->actionTotaljrre($data)); //默认选中显示的价格
                \Yii::trace($payinfo);
        return $this->formatAsjson($payinfo);
    }

//总金额计算
    public function actionTotaljr(){
        $sid = $this->post['sid'];
        $patz = $this->post['patz']!=="null"?$this->post['patz']:"";
        $jxgt = $this->post['jxgt']!=="null"?$this->post['jxgt']:"";
        $qqdh = $this->post['qqdh']!=="null"?$this->post['qqdh']:"";
        $ckfw = $this->post['ckfw']!=="null"?$this->post['ckfw']:"";
        $shijian = $this->post['shijian'];
        if (empty($shijian)){
            return $this->errorHandler("1039");
        }
        $a = "";
        if ($shijian == "yxn"){
            $a = "year";
            $aa = "12";
        }elseif($shijian == "yxq"){
            $a = "half";
            $aa = "6";
        }elseif($shijian == "ygy"){
            $a = "month";
            $aa = "1";
        }
        \Yii::trace($shijian);
        \Yii::trace($a);
        $money1 = !empty($jxgt)?3*$aa:0;
        $zl['patz'] =  "s";
        $zl['qqdh'] =  "w";
        $zl['ckfw'] =  "wc";

        $tczh = (!empty($patz)?$zl[$patz]:"").(!empty($qqdh)?$zl[$qqdh]:"").(!empty($ckfw)?$zl[$ckfw]:"");
        $money2 =0;
        \Yii::trace($tczh);
        if (!empty($tczh)){
            //套餐种类分类组合
            $tc['swwc'] = "swwc";
            $tc['sw'] = "sw";
            $tc['swc'] = "swc";
            $tc['wwc'] = "wwc";
            $tc['s'] = "s";
            $tc['w'] = "w";
            $tc['wc'] = "wc";
            $b= $tc[$tczh];
            $money2 = $this->Pricecalculation($sid,$a,$b);
        }
        $data['money'] = $money1+$money2;
        return $this->formatAsjson($data);
    }

    //总金额核实
    public function actionTotalhs($sid,$patz,$jxgt,$qqdh,$ckfw,$shijian){
        $sid = $this->post['sid'];
        $patz = $this->post['patz']!=="null"?$this->post['patz']:"";
        $jxgt = $this->post['jxgt']!=="null"?$this->post['jxgt']:"";
        $qqdh = $this->post['qqdh']!=="null"?$this->post['qqdh']:"";
        $ckfw = $this->post['ckfw']!=="null"?$this->post['ckfw']:"";
        $shijian = $this->post['shijian'];
        if (empty($shijian)){
            return $this->errorHandler("1039");
        }
        $a = "";
        if ($shijian == "yxn"){
            $a = "year";
            $aa = "12";
        }elseif($shijian == "yxq"){
            $a = "half";
            $aa = "6";
        }elseif($shijian == "ygy"){
            $a = "month";
            $aa = "1";
        }
        \Yii::trace($shijian);
        \Yii::trace($a);
        $money1 = !empty($jxgt)?3*$aa:0;
        $zl['patz'] =  "s";
        $zl['qqdh'] =  "w";
        $zl['ckfw'] =  "wc";

        $tczh = (!empty($patz)?$zl[$patz]:"").(!empty($qqdh)?$zl[$qqdh]:"").(!empty($ckfw)?$zl[$ckfw]:"");
        $money2 =0;
        \Yii::trace($tczh);
        if (!empty($tczh)){
            //套餐种类分类组合
            $tc['swwc'] = "swwc";
            $tc['sw'] = "sw";
            $tc['swc'] = "swc";
            $tc['wwc'] = "wwc";
            $tc['s'] = "s";
            $tc['w'] = "w";
            $tc['wc'] = "wc";
            $b= $tc[$tczh];
            $money2 = $this->Pricecalculation($sid,$a,$b);
        }
        return $money1+$money2;
    }


    //套餐组合价格计算
    public function Pricecalculation($sid,$a,$b){
//        $sid = $this->post['sid'];
//        $sid = "56623";
        $payinfo = WpIschoolSchool::find()->select('apphalf_money,appone_money,appmonth_money')->where(['id'=>$sid])->asArray()->all();
        $res = array("half"=>json_decode($payinfo[0]['apphalf_money']),"year"=>json_decode($payinfo[0]['appone_money']),"month"=>json_decode($payinfo[0]['appmonth_money']));
        \Yii::trace($a);
        \Yii::trace($b);
        \Yii::trace($res);
        return $res[$a]->$b;    //返回套餐价格
//        return $this->formatAsjson($payinfo);
    }

    //支付宝餐卡充值或学费缴费发起支付
    public function actionSolution(){
        $uid = $this->post['uid'];
        $paytype = "ZFBJSAPICK";
        $money = $this->post['money'];
        $xuexiao = $this->post['school'];
        $banji = $this->post['class'];
        $xingming = $this->post['student'];
        $xuehao = $this->post['stu_id'];        //学生id
        $sid = $this->post['sid'];
        $type = $this->post['type'];    //canka餐卡|xuefei学费/shufei书费|zhusufei住宿费
        $pay_subject = $this->post['title'].'---'.$xingming;;
        if($type !== "canka" && $type !== "xuefei" && $type !== "shufei" && $type !== "zhusufei"){
            return $this->errorHandler("1046");
        }
        //需要核查金额的正确性，是否是真实的金额
        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/alipaysdk/AopSdk.php";
        $aop = new \AopClient;
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        //$aop->appId = 2017050507130386;
        $aop->appId = \Yii::$app->params['alipay-app-id'];
        $aop->rsaPrivateKey = \Yii::$app->params['alipay-app-prikey'];
        $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey'];
        if($sid == '56775'){
            $aop->appId = \Yii::$app->params['alipay-app-id-hejie'];
            $aop->rsaPrivateKey = \Yii::$app->params['alipay-app-prikey-hejie'];
            $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey-hejie'];
        }
        if($sid == '56758'){
            $aop->appId = \Yii::$app->params['alipay-app-id-wugang'];
            $aop->rsaPrivateKey = \Yii::$app->params['alipay-app-prikey-wugang'];
            $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey-wugang'];
        }
        if($sid == '56650'){
            $aop->appId = \Yii::$app->params['alipay-app-id-sangao'];
            $aop->rsaPrivateKey = \Yii::$app->params['alipay-app-prikey-sangao'];
            $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey-sangao'];
        }
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";

        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request = new \AlipayTradeAppPayRequest();
        $pay_body = $xuexiao."|".$banji."|".$xingming."|".$xuehao."|".$sid;

        // $money = "0.01";
        $pay_trade_no = date("YmdHis")."ZF".random_int(100000, 999999);
        if($uid == "59792"){
            $money = "0.01";
        }
        $passback_param = urlencode($type."|".$uid);
        $bizcontent = "{\"body\":\"$pay_body\","
            . "\"subject\": \"$pay_subject\","
            . "\"paytype\": \"$paytype\","
            . "\"type\": \"$type\","
            . "\"passback_params\": \"$passback_param\","
            . "\"out_trade_no\": \"$pay_trade_no\","
            . "\"timeout_express\": \"30m\","
            . "\"total_amount\": \"$money\","
            . "\"product_code\":\"QUICK_MSECURITY_PAY\""
            . "}";
        $request->setNotifyUrl("http://api.jxqwt.cn/ceshipay/alipaynotifyck");
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);
        if ($type ==="canka"){
            $user_no = $this->stuinfo($xuehao)[0]['stuno2'];
            $zfend_school=\yii::$app->params['zfend.school'];
            if(in_array($sid,$zfend_school)){
                if(preg_match('/[a-zA-Z]/',$user_no)){
                    $sid = substr($user_no, 1, 5);
                    $user_no=substr($user_no,6);
                }else{
                    $sid = '56651';
                    $user_no=substr($user_no,2);
                }
            }
            $sql1="SELECT card_no from zf_card_info where user_no =:user_no and school_id =:school_id ";
            $stmt=\Yii::$app->db2->createCommand($sql1,[":user_no"=>$user_no,":school_id"=>$sid])->queryAll();
            \Yii::trace($stmt);
            if(!$stmt){
                return $this->errorHandler("1038");
            }
        }elseif ($type !=="canka"){
            if ($type === "xuefei"){
                $types = "学费";
            }elseif ($type === "shufei"){
                $types = "书费";
            }elseif ($type === "zhusufei"){
                $types = "住宿费";
            }
            $orderjf = new WpIschoolOrderjf();
            $orderjf->openid = $this->openid;
            $orderjf->total = $money;
            $orderjf->trade_no = $pay_trade_no;
            $orderjf->trade_name =$xuexiao."|".$banji."|".$xingming."|".$xuehao;
            $orderjf->paytype = $paytype;
            $orderjf->ctime = time();
            $orderjf->stuid = $xuehao;
            $orderjf->uid = $uid;
            $orderjf->type = $types;
            $orderjf->save();
        }

        return $this->formatAsjson($response);
    }

    //支付宝餐卡或学费缴费回调
    public function actionAlipaynotifyck()
    {
        // $_post['body']
        \Yii::trace($_POST);
        $trans_id = $_POST['trade_no']; //支付宝交易号
        $modelss = WpIschoolOrderjf::findOne(['trans_id'=>$trans_id]);
        if ($modelss) {
            echo "success";
            exit;
        }
        $trade_no = $_POST['out_trade_no'];
        $body = $_POST['body'];
        $zfopenid = $this->openid;
        $passback_param = $_POST['passback_params'];
        $passback_params = urldecode($passback_param);
        $passback_paramsa = explode("|",$passback_params);
        $type = $passback_paramsa[0];     //支付种类
        $zfuid = $passback_paramsa[1];      //支付人UID
        $money = $_POST['total_amount'];
        \Yii::trace($type);
        if ($type === "xuefei"){
            $types = "学费";
        }elseif ($type === "shufei"){
            $types = "书费";
        }elseif ($type === "zhsufei"){
            $types = "住宿费";
        }
        $result = explode('|', $body);  //学校|班级|姓名|学号|学校id
        if($type ==="canka"){
            $user_no = $this->stuinfo($result[3])[0]['stuno2'];
            $zfend_school=\yii::$app->params['zfend.school'];
            if(in_array($result[4],$zfend_school)){
                if(preg_match('/[a-zA-Z]/',$user_no)){
                    $sid = substr($user_no, 1, 5);
                    $user_no=substr($user_no,6);
                }else{
                    $sid = '56651';
                    $user_no=substr($user_no,2);
                }
            }else{
                $sid =$result[4];
            }
            $sql = "select card_no,balance,created_by from zf_card_info where  user_no=:user_no and school_id = :school_id";
            $resu=\Yii::$app->db2->createCommand($sql,[":user_no"=>$user_no,":school_id"=>$sid])->queryAll();
            \Yii::trace($resu);

            if($resu){
                \Yii::trace(111111);
                $up_rechange_sql="insert into zf_recharge_detail (id,card_no,credit,type,balance,pos_no,created_by,time,note,is_active,school_id,trade_no) values ('',:card_no,:credit,'ZFBJSAPI','0','0',:created_by,:time,'0','0',:school_id,:trade_no)";
                $stmt2=\Yii::$app->db2->createCommand($up_rechange_sql,[":card_no"=>$resu[0]['card_no'],":credit"=>$money,":created_by"=>$resu[0]['created_by'],":time"=>time(),":school_id"=>$sid,":trade_no"=>$trade_no])->execute();
            }
        }elseif ($type !=="canka"){
            $resus=WpIschoolOrderjf::findOne(['trade_no'=>$trade_no]);
            if($resus) {
                $resus->zfopenid = $this->openid;
                $resus->uptime = time();
                $resus->trans_id = $trans_id;
                $resus->issuccess = 1;
                $resus->uid = $zfuid;
                $resus->save();
            }
        }

        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/alipaysdk/AopSdk.php";
        $aop = new \AopClient;
        \Yii::trace($aop);
        $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey'];
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
        echo "success";
        \Jpush::push($zfuid,"支付成功！","id");
    }



    //支付宝水卡缴费发起支付
    public function actionWater(){
        $uid = $this->post['uid'];
        $paytype = "ZFBJSAPICK";
        $money = $this->post['money'];
        $xuexiao = $this->post['school'];
        $banji = $this->post['class'];
        $xingming = $this->post['student'];
        $xuehao = $this->post['stu_id'];        //学生id
        $sid = $this->post['sid'];
        $type = $this->post['type'];    //water水卡
        $pay_subject = $this->post['title'].'---'.$xingming;;
        if($type !== "water"){
            return $this->errorHandler("1046");
        }
        //需要核查金额的正确性，是否是真实的金额
        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/alipaysdk/AopSdk.php";
        $aop = new \AopClient;
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        //$aop->appId = 2017050507130386;
        $aop->appId = \Yii::$app->params['alipay-app-id-shuika-sangao'];
        $aop->rsaPrivateKey = \Yii::$app->params['alipay-app-prikey-shuika-sangao'];
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey-shuika-sangao'];
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request = new \AlipayTradeAppPayRequest();
        $pay_body = $xuexiao."|".$banji."|".$xingming."|".$xuehao."|".$sid;

        if($uid == "59792"){
            $money = "0.01";
        }
        // $money = "0.01";
        $pay_trade_no = date("YmdHis")."ZF".random_int(100000, 999999);

        $passback_param = urlencode($type."|".$uid);
        $bizcontent = "{\"body\":\"$pay_body\","
            . "\"subject\": \"$pay_subject\","
            . "\"paytype\": \"$paytype\","
            . "\"type\": \"$type\","
            . "\"passback_params\": \"$passback_param\","
            . "\"out_trade_no\": \"$pay_trade_no\","
            . "\"timeout_express\": \"30m\","
            . "\"total_amount\": \"$money\","
            . "\"product_code\":\"QUICK_MSECURITY_PAY\""
            . "}";
        $request->setNotifyUrl("http://api.jxqwt.cn/ceshipay/alipaynotifywt");
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);
        if ($type ==="water"){
            $user_no = $this->stuinfo($xuehao)[0]['stuno2'];
            // $zfend_school=\yii::$app->params['zfend.school'];
            // if(in_array($sid,$zfend_school)){
            //     if(preg_match('/[a-zA-Z]/',$user_no)){
            //         $sid = substr($user_no, 1, 5);
            //         $user_no=substr($user_no,6);
            //     }else{
            //         $sid = '56651';
            //         $user_no=substr($user_no,2);
            //     }
            // }
            $sql1="SELECT card_no from zf_card_info where user_no =:user_no and school_id =:school_id ";
            $stmt=\Yii::$app->db3->createCommand($sql1,[":user_no"=>$user_no,":school_id"=>$sid])->queryAll();
            \Yii::trace($stmt);
            if(!$stmt){
                return $this->errorHandler("1038");
            }
        }
        return $this->formatAsjson($response);
    }

    //支付宝水卡缴费回调
    public function actionAlipaynotifywt()
    {
        // $_post['body']
        \Yii::trace($_POST);
        $trans_id = $_POST['trade_no']; //支付宝交易号
        $trade_no = $_POST['out_trade_no'];
        $body = $_POST['body'];
        $zfopenid = $this->openid;
        $passback_param = $_POST['passback_params'];
        $passback_params = urldecode($passback_param);
        $passback_paramsa = explode("|",$passback_params);
        $type = $passback_paramsa[0];     //支付种类
        $zfuid = $passback_paramsa[1];      //支付人UID
        $money = $_POST['total_amount'];

        $result = explode('|', $body);  //学校|班级|姓名|学号|学校id
        if($type ==="water"){
            $user_no = $this->stuinfo($result[3])[0]['stuno2'];
            $sid = $result[4];
            // $zfend_school=\yii::$app->params['zfend.school'];
            // if(in_array($result[4],$zfend_school)){
            //     if(preg_match('/[a-zA-Z]/',$user_no)){
            //         $sid = substr($user_no, 1, 5);
            //         $user_no=substr($user_no,6);
            //     }else{
            //         $sid = '56651';
            //         $user_no=substr($user_no,2);
            //     }
            // }else{
            //     $sid = $result[4];
            // }
            $sql = "select card_no,balance,created_by from zf_card_info where  user_no=:user_no and school_id = :school_id";
            $resu=\Yii::$app->db3->createCommand($sql,[":user_no"=>$user_no,":school_id"=>$sid])->queryAll();
            \Yii::trace($resu);

            if($resu){
                $up_rechange_sql="insert into zf_recharge_detail (id,card_no,credit,type,balance,pos_no,created_by,time,note,is_active,school_id,trade_no) values ('',:card_no,:credit,'ZFBJSAPI','0','0',:created_by,:time,'0','0',:school_id,:trade_no)";
                $stmt2=\Yii::$app->db3->createCommand($up_rechange_sql,[":card_no"=>$resu[0]['card_no'],":credit"=>$money,":created_by"=>$resu[0]['created_by'],":time"=>time(),":school_id"=>$sid,":trade_no"=>$trade_no])->execute();
            }
        }

        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/alipaysdk/AopSdk.php";
        $aop = new \AopClient;
        \Yii::trace($aop);
        $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey'];
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
        echo "success";
        \Jpush::push($zfuid,"支付成功！","id");
    }

    //餐卡支付点击接口
    public function actionCanka(){
        $sid = $this->post['sid'];
        $payinfo = WpIschoolSchool::find()->select('ispass as ckpass,ckczzfb,ckczwx')->where(['id'=>$sid])->asArray()->one();
        return $this->formatAsjson($payinfo);
    }

    //水卡支付点击接口
    public function actionShuika(){
        $sid = $this->post['sid'];
        $payinfo = WpIschoolSchool::find()->select('skpass')->where(['id'=>$sid])->asArray()->one();
        return $this->formatAsjson($payinfo);
    }

        //学费缴费点击接口
    public function actionXuefei(){
        $sid = $this->post['sid'];
        $payinfo = WpIschoolSchool::find()->select('xfpass')->where(['id'=>$sid])->asArray()->one();
        return $this->formatAsjson($payinfo);
    }


        //首次点击功能支付进入页面默认总金额计算
    public function actionTotaljrre($data){
        $sid = $data['sid'];
        $patz = ($data['patz']!=="n"?$data['patz']:"");
        $jxgt = ($data['jxgt']!=="n"?$data['jxgt']:"");
        $qqdh = ($data['qqdh']!=="n"?$data['qqdh']:"");
        $ckfw = ($data['ckfw']!=="n"?$data['ckfw']:"");
        $shijian = $data['shijian'];
        if (empty($shijian)){
            return $this->errorHandler("1039");
        }
        $a = "";
        if ($shijian == "yxn"){
            $a = "year";
            $aa = "12";
        }elseif($shijian == "yxq"){
            $a = "half";
            $aa = "6";
        }elseif($shijian == "ygy"){
            $a = "month";
            $aa = "1";
        }

        $money1 = !empty($jxgt)?3*$aa:0;
        $zl['patz'] =  "s";
        $zl['qqdh'] =  "w";
        $zl['ckfw'] =  "wc";
        \Yii::trace($patz);
        \Yii::trace($qqdh);
        
        $strs = "";
        $strs = ($patz=='y'?$zl["patz"]:"");
        $strs.= $qqdh=="y"?$zl["qqdh"]:"";
        $strs.= $ckfw=="y"?$zl["ckfw"]:"";
        \Yii::trace($strs);
        $tczh = $strs;
        $money2 =0;
        \Yii::trace($tczh);
        if (!empty($tczh)){
            //套餐种类分类组合
            $tc['swwc'] = "swwc";
            $tc['sw'] = "sw";
            $tc['swc'] = "swc";
            $tc['wwc'] = "wwc";
            $tc['s'] = "s";
            $tc['w'] = "w";
            $tc['wc'] = "wc";
            $b= $tc[$tczh];
            $money2 = $this->Pricecalculation($sid,$a,$b);
        }
        $data['money'] = $money1+$money2;
        return $data['money'];
    }

        //学生补卡点击接口
    public function actionBuka(){
        $sid = $this->post['sid'];
        $payinfo = WpIschoolSchool::find()->select('bkpass')->where(['id'=>$sid])->asArray()->one();
        return $this->formatAsjson($payinfo);
    }


    //瑞贝卡水世界微信发起支付接口
    public function actionWxpayrbk(){
        $params = $this->post;
        if (empty($params['sid']) || empty($params['time']) || empty($params['amount_adult']) || empty($params['amount_stu']) || empty($params['type']) || empty($params['uid']) || empty($params['money']) || empty($params['stu_id']) || empty($params['use_date']) || empty($params['title'])) {
                return $this->errorHandler("1061");
        }
        if (time()>strtotime($params['time'])) {
            return $this->errorHandler("1063");
        }
        $params['paytype'] = "WXAPP";
        $data['time'] = $params['time'];
        $data['amount_adult'] = $params['amount_adult'];
        $data['amount_stu'] = $params['amount_stu'];
        $data['type'] = $params['type'];
        $money = ($this->post['money'])*100;
        $moneyhs = $this->Total_hs($data)*100;
        \Yii::trace($moneyhs);
        if ($moneyhs != $money){
            return $this->errorHandler("1042");
        }

        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/wxpaysdk/WxPay.Api.php";
        $input = new \WxPayUnifiedOrder();
        $params['pay_trade_no'] = \WxPayConfig::MCHID.date("YmdHis").random_int(10000, 99999);
        // $passback_param = $type."|".$uid."|".$xuexiao."|".$banji."|".$xingming."|".$xuehao."|".$sid;
        $passback_param = $params['uid']."|".$params['sid']."|".$params['stu_id']."|".$params['pay_trade_no'];  //支付人uid|学校id|学生ID|内部单号
        if($params['uid'] == "59792"){
            $money = "1";
        }
        $input->SetBody($params['title']);
        $input->SetAttach($passback_param);
        $input->SetOut_trade_no($params['pay_trade_no']);
        $input->SetTotal_fee($money);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url("http://api.jxqwt.cn/ceshipay/wxpaynotifyrbk");
        $input->SetTrade_type("APP");
        $result = \WxPayApi::unifiedOrder($input);
        \Yii::trace($result);
        // Log::DEBUG("unifiedorder:" . json_encode($result));
        // $ret = json_decode($result,true);
        // return $result;       
        if($result['return_code'] == "SUCCESS" && $result['result_code'] == "SUCCESS")
        {
            // 插入数据库订单
            $res = $this->insert_order_rbk($params);
            $ret = $this->makeSign($result);
            \Yii::trace($ret);
            return $this->formatAsjson($ret);
        }
        else return $this->formatAsjson($result);

    }

        //瑞贝卡水世界支付宝发起支付接口
    public function actionAlipayrbk(){
        $params = $this->post;
        if (empty($params['sid']) || empty($params['time']) || empty($params['amount_adult']) || empty($params['amount_stu']) || empty($params['type']) || empty($params['uid']) || empty($params['money']) || empty($params['stu_id']) || empty($params['use_date']) || empty($params['title'])) {
                return $this->errorHandler("1061");
            }
        $params['paytype'] = $paytype = "ZFBAPP";
        $data['time'] = $params['time'];
        $data['amount_adult'] = $params['amount_adult'];
        $data['amount_stu'] = $params['amount_stu'];
        $data['type'] = $params['type'];
        $money = $this->post['money'];
        $moneyhs = $this->Total_hs($data);
        $title = $params['title'];
        \Yii::trace($moneyhs);
        if ($moneyhs != $money){
            return $this->errorHandler("1042");
        }

        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/alipaysdk/AopSdk.php";
        $aop = new \AopClient;
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        //$aop->appId = 2017050507130386;
        $aop->appId = \Yii::$app->params['alipay-app-id-zfjy'];
        $aop->rsaPrivateKey = \Yii::$app->params['alipay-app-prikey-zfjy'];
        $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey-zfjy'];
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request = new \AlipayTradeAppPayRequest();
        $pay_trade_no = $params['pay_trade_no'] = date("YmdHis")."ZF".random_int(100000, 999999);
        $pay_body = $params['uid']."|".$params['sid']."|".$params['stu_id']."|".$params['pay_trade_no']."|".$params['title'];  //支付人uid|学校id|学生ID|内部单号
        $passback_param = $params['uid']."|".$params['sid']."|".$params['stu_id']."|".$params['pay_trade_no'];  //支付人uid|学校id|学生ID|内部单号
            if($params['uid'] == "59792"){
                $money = "10";
            }
            \Yii::trace($money);
        // $pay_amount = 0.01;
        $pay_trade_no = date("YmdHis")."ZF".random_int(100000, 999999);
        $bizcontent = "{\"body\":\"$pay_body\"," 
                    . "\"subject\": \"$title\","
                    . "\"passback_params\": \"$passback_param\","
                    . "\"paytype\": \"$paytype\","
                    . "\"out_trade_no\": \"$pay_trade_no\","
                    . "\"timeout_express\": \"30m\"," 
                    . "\"total_amount\": \"$money\","
                    . "\"product_code\":\"QUICK_MSECURITY_PAY\""
                    . "}";
        $request->setNotifyUrl("http://api.jxqwt.cn/ceshipay/alipaynotifyrbk");
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);
        // 插入数据库订单  
        $res = $this->insert_order_rbk($params);
        return $this->formatAsjson($response);

    }

    //瑞贝卡发起支付插入订单数据
    public function insert_order_rbk($params){
        $models = new WpIschoolOrderWater();
        $models->money = $params['money'];
        $models->trade_name = $params['title'];
        $models->type = $params['type'];
        $models->paytype = $params['paytype'];
        $models->ctime = date("Y-m-d H:i:s",time());
        $models->stuid = $params['stu_id'];
        $models->uid = $params['uid'];
        $models->trade_no = $params['pay_trade_no'];
        $models->amount_adult = $params['amount_adult'];
        $models->amount_stu = $params['amount_stu'];
        $models->use_date = $params['time'];
        return $models->save(false);
    }

        //瑞贝卡支付回调更新订单数据
    public function update_order_rbk($params){
        $models = WpIschoolOrderWater::findOne(['trade_no'=>$params['trade_no']]);
        $models->ispass = 1;
        $models->utime = date("Y-m-d H:i:s",time());
        $models->trans_id = $params['trans_id'];
        return $models->save(false);
    }

//瑞贝卡微信支付回调
    public function actionWxpaynotifyrbk(){
        // 基类的方法支付方法需要重写
        $xml = file_get_contents("php://input");
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        $params['uid']."|".$params['sid']."|".$params['stu_id']."|".$params['pay_trade_no'];
        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath . "/lee/wxpaysdk/WxPay.Api.php";
        require_once $vendorpath . "/lee/wxpaysdk/WxPay.Notify.php";
        $notify = new \WxPayNotify();
        $notify->Handle(true);

        $params['trans_id'] = $_POST['trade_no'] = "564654654895132"; //微信交易号
        $params['trade_no'] = $_POST['out_trade_no'] = "15072160612018072915033663019";
        $res = $this->update_order_rbk($params);
    }

//瑞贝卡支付宝支付回调
    public function actionAlipaynotifyrbk(){
        // $_post['body']
        \Yii::trace($_POST);
        $params['trans_id'] = $_POST['trade_no'] = "32659685351651"; //支付宝交易号
        $params['trade_no'] = $_POST['out_trade_no'] = "20180729170414ZF759444";

        $res = $this->update_order_rbk($params);
        $vendorpath = \Yii::getAlias("@vendor");
        require_once $vendorpath."/lee/alipaysdk/AopSdk.php";
        $aop = new \AopClient;
        \Yii::trace($aop);
        $aop->alipayrsaPublicKey = \Yii::$app->params['alipay-pubkey'];
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
        echo "success";
        $res = explode("|",$_POST['passback_params']);
        \Yii::trace($res);
        \Jpush::push($res[0],"支付成功！","id");
    }

    //总金额返回
    public function actionTotalamount(){
        $params = $this->post;
        if (empty($params['amount_adult']) && empty($params['amount_stu'])){
            return $this->errorHandler("1062");
        }
        if (empty($params['time']) || empty($params['type'])){
            return $this->errorHandler("1061");
          }
        $data['money'] = strval($this->Total_hs($params));
        return $this->formatAsjson($data);
    }

        //总金额计算核实
    public function Total_hs($params){
        \Yii::trace($params);
        $time = $params['time'];
        $amount_adult = !empty($params['amount_adult'])?:0;
        $amount_stu = !empty($params['amount_stu'])?:0;
        $type = $params['type'];

        $inweek = date('w',strtotime($time));
        $week_one = [1,2,3,4];
        $week_two = [5,6,0];
        if($type == 'ssj'){                     //水世界
            if(in_array($inweek,$week_one)){
                $amount = $amount_adult*20 + $amount_stu*15;
            }elseif (in_array($inweek,$week_two)) {
                $amount = $amount_adult*30 + $amount_stu*25;
            }
        }elseif ($type == "skwg") {      //水卡王国
            if(in_array($inweek,$week_one)){
                \Yii::trace(1111);
                $amount = $amount_adult*60 + $amount_stu*45;
            }elseif (in_array($inweek,$week_two)) {
                \Yii::trace(2222);
                $amount = $amount_adult*50 + $amount_stu*35;
            }
        }
        \Yii::trace($amount);
        return $amount;
    }
//水世界订单列表页面
    public function actionOrderlist(){
        $params = $this->post;
        if (empty($params['uid'])) {
            return $this->errorHandler("1061");
        }
        $model = WpIschoolOrderWater::find()->where(['uid'=>$params['uid'],'ispass'=>'1'])->asArray()->all();
        $data = [];
        foreach ($model as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['ctime'] = $value['ctime'];
            $data[$key]['title'] = (($value['type']=="ssj")?"瑞贝卡水世界门票":"贝卡王国门票");
            $data[$key]['is_expired'] = (($value['is_expired']=="1")?"未使用":"已过期");
            $data[$key]['type'] = $value['type'];
        }
        return $this->formatAsjson($data);
    }
//水世界订单详情页面
    public function actionOrderview(){
        $params = $this->post;
        if (empty($params['id'])) {
            return $this->errorHandler("1061");
        }
        $model = WpIschoolOrderWater::findOne($params['id']);
        $data = [];
        $data['title'] = (($model->type=="ssj")?"瑞贝卡水世界门票":"贝卡王国门票");
        $data['trade_no'] = $model->trade_no;
        $data['ctime'] = $model->ctime;
        $data['amount_adult'] = $model->amount_adult;
        $data['amount_stu'] = strval($model->amount_stu);
        $data['use_date'] = strval(substr($model->use_date,0,10));
        $data['money'] = strval($model->money);
        $data['is_expired'] = (($model['is_expired']=="1")?"未使用":"已过期");
        return $this->formatAsjson($data);
    }

}
