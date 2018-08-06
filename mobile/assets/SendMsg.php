<?php
namespace mobile\assets;
class SendMsg {
    static $COM_PIC_URL = "/upload/syspic/msg.jpg";
    static function push2Queue($data) {
    		$conn_args = array(
    				'host' => '127.0.0.1', //rabbitmq 服务器host
    				'port' => 5672, //rabbitmq 服务器端口
    				'login' => 'guest', //登录用户
    				'password' => 'hnzf55030687', //登录密码
    				'vhost' => '/', //虚拟主机
    		);
    		$e_name = 'school';
    		$q_name = 'school';
    
    		$conn = new \AMQPConnection($conn_args);
    		if (!$conn->connect()) {
    			die('Cannot connect to the broker');
    		}
    		$channel = new \AMQPChannel($conn);
    
    		$ex = new \AMQPExchange($channel);
    		$ex->setName($e_name);
    		$ex->setType(AMQP_EX_TYPE_DIRECT);
    		$ex->setFlags(AMQP_DURABLE);
    		$status = $ex->declareExchange(); //声明一个新交换机，如果这个交换机已经存在了，就不需要再调用declareExchange()方法了.
    		$q = new \AMQPQueue($channel);
    		$q->setName($q_name);
    		//$status = $q->declareQueue(); //同理如果该队列已经存在不用再调用这个方法了。
    		$q->bind($e_name,$e_name);
    		$ex->publish($data, $q_name);
    }
    
    /**
     * @param $tos
     * @param $data
     * 家校通与校内交流等小批量发送，需要临时根据openid拼凑url
     */
    static function muiltPostMsg($tos,$data){
        $title = $data['title'];
        $content = $data['content'];
        $url = $data['url'];  //需要临时拼接openid
	$pic_url = empty($data['pic_url'])?"":$data['pic_url'];
	$pic_url = empty($data['picurl'])?$pic_url:$data['picurl'];
	$pic_url = empty($pic_url) ? URL_PATH.self::$COM_PIC_URL : $pic_url;
        foreach($tos as $v){
            $msg = self::createNewsMsg($v,$title,$content,$url[0].$v.$url[1],$pic_url);
	    self::push2Queue($msg);
        }
        return json_decode('{"errcode":0}');
    }


    
     /**
     * @param $openid
     * @param $title
     * @param $content
     * @param $url
     * @param $picurl
     * @return string
     * 创建客服消息的消息实体
     */
    static function  createNewsMsg($openid,$title,$content,$url,$picurl){
        if(empty($picurl) || $picurl == ""){
            $picurl = URL_PATH.self::$COM_PIC_URL;
        }
        return '{
            "touser":"'.$openid.'",
            "msgtype":"news",
            "news":
                {
                  "articles":[
                    {
                      "title":"'.$title.'",
                      "description":"'.$content.'",
                      "url":"'.$url.'",
                      "picurl":"'.$picurl.'"
                    }
                  ]
                }
            }';
    }
    /**
     * @param $openid
     * @param $title
     * @param $des
     * @param string $ur
     * @param string $picurl
     * @return mixed
     * 一般的审核信息
     */
    static public function sendSHMsgToPa($openid,$title,$des,$url="",$picurl){
        if(empty($picurl)){
            $picurl = URL_PATH.self::$COM_PIC_URL;
        }
        $data = self::createNewsMsg($openid,$title,$des,$url,$picurl);
        self::push2Queue($data);
        return true;
    }
    
    /**
     * @param $tos
     * @param $data
     * @return mixed
     * 学校公告等大批量信息发送
     */
    // broadMsgToManyUsers
    static function broadMsgToManyUsers($tos,$data){
    		$title = $data['title'];
    		$content = $data['content'];
	 	$url = $data['url'];
	        $pic_url = empty($data['pic_url'])?"":$data['pic_url'];
        	$pic_url = empty($data['picurl'])?$pic_url:$data['picurl'];
        	$pic_url = empty($pic_url) ? URL_PATH.self::$COM_PIC_URL : $pic_url;
    		foreach($tos as $v){
        		$msg = self::createNewsMsg($v,$title,$content,$url.$v,$pic_url);
        		self::push2Queue($msg);
    		}
    		return true;
	}
}

