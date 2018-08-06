<?php
use mobile\assets\Redis;
use mobile\assets\Mysqli;
/*家校沟通支付脚本运行日志*/
include 'jiekou.php';
$log_file = __DIR__.'/run.log';
$config = include __DIR__.'/pay/config.php';
//本地配置
//$log_file = 'run.log';
//$config = include 'config2.php';

/*实例化redis*/
function getRedis()
{
    global $config;
    $redis = new Redis();
    $redis->pconnect($config['DB_HOST'], $config['REDIS_PORT'], 5);//连接redis

    $redis->auth($config['REDIS_PASS']);  //鉴权
    $redis->select($config['ZF_PAY_DB']);  //2库
    return $redis;
}

/*实例化mysql*/
function getMysql()
{
    global $config;
    $mysql = new Mysqli($config['DB_HOST'], $config['DB_USER'], $config['DB_PWD'], $config['DB_NAME']);
    return $mysql;
}

/*写日志*/
function putLog($msg)
{
    global $log_file;
    file_put_contents($log_file, $msg, FILE_APPEND);
}

function getTime()
{
    return date('Y-m-d H:i:s', time());
}

function run()
{
    $redis = getRedis();
    $mysql = getMysql();

    while (true) {
        echo "程序扫描中"."\r\n";
        try {
            $redis->ping();
        } catch (Exception $e) {
            putLog("Redis is want away -- " . getTime() . "\r\n");
            $redis = getRedis();
        }

        if (false == @$mysql->ping()) {
            $mysql = getMysql();
        }
        $key = $redis->keys('orderjx_*');
        if ($key) // redis中存在支付数据的键
        {
            foreach ($key as $k => $v)
            {
                try {
                    $order = $redis->get($v);
                    putLog("Read order info: $order -- " . getTime() . "\r\n");

                    if (!$order) {
                        $redis->delete($v);
                        continue;
                    }
//                    参数以“|”分割，分别为“学校|班级|姓名|学号|内部订单号|总价|支付种类|微信单号|支付人openid”
                    $order = explode('|', $order);
                    $sql = "select id from wp_ischool_orderjx where stuid={$order[3]} and trade_no='" .$order[4]. "' and ispass=0";
                    $result = $mysql->query($sql);
                    if($result->num_rows) {
                        $num = 0;
                        $pee = $order[6];
                        $xqxn = explode('-', $pee);
                        $pdxq =$xqxn[4];//获取一学期还是一学年或一个月的值进行判断
                        if ("yxqi" == $pdxq) {
                            $num = "+6 month";
                        } elseif ("yxni" == $pdxq) {
                            $num = "+12 month";
                        } elseif ("ygyu" == $pdxq) {
                            $num = "+1 month";
                        }
                        $end = array();
                        $end['pa'] = (($xqxn[0] == "pa") ? $num : 0);
                        $end["jx"] = (($xqxn[1] == "jx") ? $num : 0);
                        $end["qq"] = (($xqxn[2] == "qq") ? $num : 0);
                        $end["ck"] = (($xqxn[3] == "ck") ? $num : 0);
                        $sql_student = "select enddatepa,enddatejx,enddateqq,enddateck from wp_ischool_student where id=". $order[3];
                        $old_end = $mysql->query($sql_student);
                        $old_enddate = $old_end->fetch_assoc();

                        $enddatepa = ($end['pa'] == 0) ? 0: ((!$old_enddate || $old_enddate['enddatepa'] < time())?strtotime($end['pa']):strtotime($end['pa'],$old_enddate['enddatepa']));//有效期的时间
                        $enddatejx = ($end['jx'] == 0) ? 0: ((!$old_enddate || $old_enddate['enddatejx'] < time())?strtotime($end['jx']):strtotime($end['jx'],$old_enddate['enddatejx']));
                        $enddateqq = ($end['qq'] == 0) ? 0: ((!$old_enddate || $old_enddate['enddateqq'] < time())?strtotime($end['qq']):strtotime($end['qq'],$old_enddate['enddateqq']));
                        $enddateck = ($end['ck'] == 0) ? 0: ((!$old_enddate || $old_enddate['enddateck'] < time())?strtotime($end['ck']):strtotime($end['ck'],$old_enddate['enddateck']));
                        $untimepa = ($end['pa'] == 0) ? 0:time(); //更新有效期的时间
                        $untimejx = ($end['jx'] == 0) ? 0:time();
                        $untimeqq = ($end['qq'] == 0) ? 0:time();
                        $untimeck = ($end['ck'] == 0) ? 0:time();
                        $up_student_sql = "update wp_ischool_student set enddatepa=".$enddatepa.",enddatejx=".$enddatejx.", enddateqq=".$enddateqq.", enddateck=".$enddateck.",upendtimepa=".$untimepa.",upendtimejx=".$untimejx.",upendtimeqq=".$untimeqq.",upendtimeck=".$untimeck." where id=".$order[3];

                        if($mysql->query($up_student_sql))
                        {
                            $up_order_sql = "update wp_ischool_orderjx set ispass=1,utime=".time().
                                ",zfopenid='".$order[8]."',trans_id=".$order[7]." where stuid=".$order[3].
                                " and trade_no=".$order[4]." and ispass=0";
                            $up_order_sql = $mysql->query($up_order_sql);
                            // $syx = $mysql->affected_rows; //记录影响行数
                            $openid = $order[8];
                            if($up_order_sql){
                                $conpa = ($xqxn[0] == "pa") ? "平安通知有效期更新至".date("Y年m月d日",$enddatepa)."。": "";
                                $conjx = ($xqxn[1] == "jx") ? "家校沟通有效期更新至".date("Y年m月d日",$enddatejx)."。": "";
                                $conqq = ($xqxn[2] == "qq") ? "亲情电话有效期更新至".date("Y年m月d日",$enddateqq)."。": "";
                                $conck = ($xqxn[3] == "ck") ? "餐卡微信充值有效期更新至".date("Y年m月d日",$enddateck)."。": "";
                                $content = "尊敬的家长您好!"."您已为学生".$order[2]."缴费".$order[5]."元，".$conpa.$conjx.$conqq.$conck;
                                $data = '{
                                            "touser":"'.$openid.'",
                                            "msgtype":"text",
                                            "text":
                                                {
                                                    "content":"'.$content.'"
                                                }
                                          }';
                                singlePostMsg(getUrl(),$data);
                            }
                            $redis->delete($v);
                        }
                    } else {//没有查询到结果
                        putLog("Has no order or has been processed named as: $v -- " . getTime() . "\r\n");
                        $redis->delete($v);
                    }
                    @$mysql->commit();
                } catch (Exception $e) {
                    putLog("Abnormal error... -- " . getTime() . "\r\n");
                    $mysql->rollback();
                }
            }
            @$mysql->close();
        }
        sleep(2);
    }
}
/*开启脚本*/
run();
