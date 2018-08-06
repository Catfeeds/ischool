<?php
namespace backend\models;
use \lee\utils\RollingCurlRequest;
use \lee\utils\RollingCurl;
use backend\models\WpIschoolSafecard;
use backend\models\WpIschoolTeaclass;
use backend\models\WpIschoolPastudent;
use mobile\models\WpIschoolAccessToken;
use yii\helpers\ArrayHelper;
use backend\models\WpIschoolGroupMessage;
use Yii;
class WXSendMsg{
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
		/*
    		$q = new \AMQPQueue($channel);
    		$q->setName($q_name);
    		//$status = $q->declareQueue(); //同理如果该队列已经存在不用再调用这个方法了。
    		$q->bind($e_name,$e_name);
		*/
    		$ex->publish($data, $q_name);
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
		\yii::trace($openid);
		if(empty($picurl) || $picurl == ""){
			$picurl = \yii::$app->params["URL_PATH"].self::$COM_PIC_URL;
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
	 * @param $tempid
	 * @param $title
	 * @param $content
	 * @return string
	 * 创建模版消息的消息实体
	 */
	static function createTempMsg($openid,$tempid,$title,$content,$url=""){
		return '{
                       "touser":"'.$openid.'",
                       "template_id":"'.$tempid.'",
                       "url":"'.$url.'",
                       "topcolor":"#FF6666",
                       "data":{
                           "first":{
                               "value":"'.$title.'\n",
                               "color":"#000000"
                           },
                            "keyword1":{
                               "value":"'.$content.'\n",
                               "color":"#000000"
                           },
                            "keyword2":{
                               "value":"系统管理员\n",
                               "color":"#000000"
                           },
                            "keyword3":{
                               "value":"'.date("Y年m月d日H时i分s秒").'\n",
                               "color":"#000000"
                           },
                          "remark":{
                               "value":"",
                               "color":"#000000"
                           }
                       }
              }' ;
	}

    static function createTempMsg2($openid,$tempid,$title,$content,$url=""){
        return '{
                       "touser":"'.$openid.'",
                       "template_id":"'.$tempid.'",
                       "url":"'.$url.'",
                       "topcolor":"#FF6666",
                       "data":{
                           "first":{
                               "value":"'.$title.'\n",
                               "color":"#000000"
                           },
                            "keyword1":{
                               "value":"'.$content.'\n",
                               "color":"#000000"
                           },
                            "keyword2":{
                               "value":"'.date("Y-m-d H:i:s").'\n",
                               "color":"#000000"
                           },
                           "keyword3":{
                               "value":"系统管理员\n",
                               "color":"#000000"
                           },
                          "remark":{
                               "value":"",
                               "color":"#000000"
                           }
                       }
              }' ;
    }


	/**
	 * @param $tempid
	 * @param int $num
	 * 更新模版条数
	 */
	static function resetTempNum($tempid,$num=1){
		\yii::$app->db->createCommand("update wp_ischool_num set num = num + :inc_num where temid = :temid",[":inc_num"=>$num,":temid"=>$tempid]);
	}

	static function singlePostMsg($url,$data){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		//设置header
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		$result = curl_exec($curl);
		if (curl_errno($curl)) {
			return array("errcode"=>-1,"errmsg"=>'发送错误号'.curl_errno($curl).'错误信息'.curl_error($curl));
		}

		curl_close($curl);
		return $result;
	}

	/**
	 * @param $tos
	 * @param $data
	 * @return mixed
	 * 学校公告等大批量信息发送
	 */
	static function broadMsgToManyUsers($tos,$data){
    		$title = $data['title'];
    		$content = $data['content'];
   		 	$url = $data['url'];
    		$pic_url = empty($data['pic_url']) ? \yii::$app->params['URL_PATH'].self::$COM_PIC_URL : $data['pic_url'];
    		foreach($tos as $v){
        		$msg = self::createNewsMsg($v,$title,$content,$url.$v,$pic_url);
        		self::push2Queue($msg);
    		}
    		return true;
	}

	/**
	 * @param $tos
	 * @param $data
	 * 家校通与校内交流等小批量发送，需要临时根据openid拼凑url
	 */
	static function muiltPostMsg(&$tos,$data){
		$title = $data['title'];
		$content = $data['content'];
		$tempid = "Jo5e9BQrm1GksvByvxM9Qyd9S9DFqLUkCXuR8vpLRQg";
//		$url = $data['url'];  //需要临时拼接openid
		$url = !empty($data['url'])?$data['url']:"";  //需要临时拼接openid
		$pic_url = empty($data['pic_url']) ? \yii::$app->params['URL_PATH'].self::$COM_PIC_URL : $data['pic_url'];
		foreach($tos as $v){
			//客服消息实体
			if (empty($url)) {
                                  $msg = self::createNewsMsg($v,$title,$content,'',$pic_url);
                          }else{
                                  $msg = self::createNewsMsg($v,$title,$content,$url[0].$v.$url[1],$pic_url);
                          }
          //管理员紧急通知消息实体
           // if (empty($url)) {
           //     $msg = self::createTempMsg2($v,$tempid,$title,$content,"");
           // }else{
           //     $msg = self::createTempMsg2($v,$tempid,$title,$content,"");
           // }

//			$msg = self::createNewsMsg($v,$title,$content,$url[0].$v.$url[1],$pic_url);
			self::push2Queue($msg);
		}
		return json_decode('{"errcode":0}');
	}


	static function muiltPostMsg2(&$tos,$data){
		$title = $data['title'];
		$content = $data['content'];
		$tempid = "Jo5e9BQrm1GksvByvxM9Qyd9S9DFqLUkCXuR8vpLRQg";
//		$url = $data['url'];  //需要临时拼接openid
		$url = !empty($data['url'])?$data['url']:"";  //需要临时拼接openid
		$pic_url = empty($data['pic_url']) ? \yii::$app->params['URL_PATH'].self::$COM_PIC_URL : $data['pic_url'];
		foreach($tos as $v){
/*			if (empty($url)) {
                                  $msg = self::createNewsMsg($v,$title,$content,'',$pic_url);
                          }else{
                                  $msg = self::createNewsMsg($v,$title,$content,$url[0].$v.$url[1],$pic_url);
                          }*/
           if (empty($url)) {
               $msg = self::createNewsMsg($v,$tempid,$title,$content,"");
           }else{
               $msg = self::createNewsMsg($v,$tempid,$title,$content,"");
           }

//			$msg = self::createNewsMsg($v,$title,$content,$url[0].$v.$url[1],$pic_url);
			self::push2Queue($msg);
		}
		return json_decode('{"errcode":0}');
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
	static public function sendSHMsgToPa($openid,$title,$des,$url="",$picurl=""){
		if(empty($picurl)){
            $picurl = \yii::$app->params['URL_PATH'].self::$COM_PIC_URL;
        }
        $sendUrl  = self::getUrl('kf');
        $data = self::createNewsMsg($openid,$title,$des,$url,$picurl);
        self::push2Queue($data);
        return true;
	}

	/**
	 * @param $url
	 * @param $data
	 * @return array|mixed
	 * 执行http_post请求的公用方法
	 */
	static public function https_post($url,$data){
		return self::singlePostMsg($url,$data);
	}

	/**
	 * @param $stuid学生id
	 * @param $time时间
	 * @param $msg进出信息
	 * @return int
	 * 平安卡信息入库
	 */
	static public function addSafeMsgIntoDb($stuid,$time,$msg){

		$d = new WpIschoolSafecard();
		$data['stuid']=$stuid;
		$data['info']=$msg;
		$data['ctime']=$time;
		//$safecaed = $d->where($data)->field('id')->select();
		$safecaed = $d->findOne($data);
		//去重，防止因网络问题下位机重复发
		if(empty($safecaed)){
			//当前年份和月份的组合
			$ym=date("ym");
			//当前年份和第几周的组合
			$yw=date("yW");
			//本星期中的第几天
			$day=date("w");
			$d['yearmonth']=$ym;
			$d['yearweek']=$yw;
			$d["weekday"]=$day;
			$d['receivetime']=time();
			$d->save();
			
		}

		return 0;
	}


	private function getSendTeacherOpenIds($params)
	{
		$data = ['and','openid is not null','ispass = "y"'];
		if(is_array($params['sid'])&&$params['sid']) array_push($data,['in','sid',$params['sid']]);
		if(is_array($params['cid'])&&$params['cid']) array_push($data,['in','cid',$params['cid']]);
		$query = WpIschoolTeaclass::find()->Where($data)->select("openid")->asArray()->all();
		return ArrayHelper::getColumn($query, "openid");
		
	}

	private function getSendParentsOpenIds($params)
	{
		$data = "t.openid!='' and ispass = 'y'";
		if(is_array($params['sid'])&&$params['sid'])
		{
			$sid = implode(',',$params['sid']);
			$data .= " and t1.sid in (".$sid.")";
		}
		if(is_array($params['cid'])&&$params['cid'])
		{
			$cid = implode(',',$params['cid']);
			$data.= " and t1.cid in (".$cid.")";
		}
		if(!empty($params['fenzu']))
		{
			$dat = $this->getSendFenzuOpenIds($params['fenzu']);
		}
//		var_dump($dat);exit();
		$dat = !empty($dat)?$dat:"";
		$data.= $dat;
		$sql = "SELECT DISTINCT openid,t.uid  FROM wp_ischool_pastudent as t LEFT JOIN wp_ischool_student as t1 ON t.stu_id = t1.id  WHERE $data";
		$query = \Yii::$app->db->createCommand($sql)->queryAll();
//		$openid = ArrayHelper::getColumn($query, "openid");
//        $in_uid = ArrayHelper::getColumn($query, "uid");
//        yii::trace($query);
//        yii::trace($openid);
//        yii::trace($in_uid);
		return $query;
	}
	private function getSendFenzuOpenIds($params)
	{
		// oUMeDwLBklMzOqyGuxhuA-Pmzsu0 智慧
		// okr7Gv1lnOY4nPIqkReKL94aeVCw
		switch($params){
			case "csxx";
				$data = " and t.openid = 'oUMeDwLBklMzOqyGuxhuA-Pmzsu0'";
				break;
			case "ckwjf";
				$data = ' and t1.enddateck < unix_timestamp(now())';
				break;
			case "ckyjf";
				$data = ' and t1.enddateck > unix_timestamp(now())';
				break;
			case "pawjf";
				$data = ' and t1.enddatepa < unix_timestamp(now())';
				break;
			case "payjf";
				$data = ' and t1.enddatepa > unix_timestamp(now())';
				break;
			case "qqwjf";
				$data = ' and t1.enddateqq < unix_timestamp(now())';
				break;
			case "qqyjf";
				$data = ' and t1.enddateqq > unix_timestamp(now())';
				break;
			case "jxwjf";
				$data = ' and t1.enddatejx < unix_timestamp(now())';
				break;
			case "jxyjf";
				$data = ' and t1.enddatejx < unix_timestamp(now())';
				break;
			default:
				$data = " and t.openid = 'oUMeDwLBklMzOqyGuxhuA-Pmzsu0'";
		}
//		var_dump($openid);exit();
		return $data;
	}

	public function getSenderInfo($openid){
		return "正梵智慧校园";
	}
	public function getParentsOpenid($user_no)
	{
		$result = \yii::$app->db->createCommand("SELECT wp_ischool_pastudent.openid from wp_ischool_student,wp_ischool_pastudent WHERE wp_ischool_student.stuno2 = :user_no and wp_ischool_student.id = wp_ischool_pastudent.stu_id and wp_ischool_pastudent.openid is not null order by wp_ischool_pastudent.id desc limit 1",[":user_no"=>$user_no])->queryOne();
		return isset($result['openid'])?$result["openid"]:null;
		
	}
	public function doSendCard($post)
	{
		if(!isset($post['monry'])) return;
		$path    = \yii::$app->params['URL_PATH'];
		$des = "您的孩子今天有一笔".$post['money']."元的消费。";       //消息内容
		$openid = $this->getParentsOpenid($post['user_no']);
		if(!$openid) return;
		$openid = trim($openid);
		\yii::trace($openid);
		$title = "来自正梵智慧校园的消息";
		$tos = [$openid];


		$data['des']   = $des;       //图文消息内容
		$data['zhuti'] = $title;     //待入库的消息主题
		$data['strpath'] = ""; //附件
		$data['title'] = $title;
		$data['content'] = $des;



                $title = $data['title'];
                $content = $data['content'];
                $url = "http://www.henanzhengfan.com/ischool/cardcx/index.php?userno=".$post['user_no'];
                $pic_url = empty($data['pic_url']) ? \yii::$app->params['URL_PATH'].self::$COM_PIC_URL : $data['pic_url'];
                $sendUrl = self::getUrl('kf');

		
                $rc = new  RollingCurl();
                $rc->window_size=10;
                foreach($tos as $v){

                        $msg = self::createNewsMsg($v,$title,$content,$url,$pic_url);
                        $request = new RollingCurlRequest($sendUrl);
                        $request->method='POST';
                        $request->post_data=$msg;

                        $rc->add($request);
                }
		$rc->execute(10);
		return true;
		
	}
	
	public function doSendMsg($post,$role_type){
		
		$path    = \yii::$app->params['URL_PATH'];
		$strpath = "";  //附件
		$openid  = \yii::$app->user->getId();    //发送人
		$msg = $des = $post['WpIschoolGroupMessage']['content'];       //消息内容
		$title   = $post['WpIschoolGroupMessage']['title'];     //消息主题

	//	$ur[0]=$path."/index.php?s=/addon/Exchange/Exchange/index/openid/";
	//	$ur[1]="/sid/1.html";
		$username = $this->getSenderInfo($openid);
		
/*		$model = new WpIschoolGroupMessage();
		$model -> user_id = \yii::$app->user->getId();
		$model -> paramers = json_encode($post);
		$model -> send_role = $role_type;
		$model -> title = $title;
		$model -> content = $msg;
		$model -> save();*/
		
		if($role_type == "TEACHER")
			$tos = $this->getSendTeacherOpenIds(['sid'=>$post['WpIschoolGroupMessage']['sid'],"cid"=>$post['WpIschoolGroupMessage']['cid']]);
		elseif($role_type == "PARENT"){
            if (!empty($post['WpIschoolGroupMessage']['jzopid'])){
                $tos[] = $post['WpIschoolGroupMessage']['jzopid'];
            }else{
                $query = $this->getSendParentsOpenIds(['sid'=>$post['WpIschoolGroupMessage']['sid'],"cid"=>$post['WpIschoolGroupMessage']['cid'],"fenzu"=>$post['WpIschoolGroupMessage']['fenzu']]);
                $tos = ArrayHelper::getColumn($query, "openid");
                $in_uid = ArrayHelper::getColumn($query, "uid");
            }
        }

		//图文信息的内容，与入库的原始html内容$msg不同
		if(!empty($des)){
			$preg = "/<\/?[^>]+>/i";
			$des = preg_replace($preg,'',$des);
		}
	
		$tos = array_unique($tos);
        $in_uid = array_unique($in_uid);
        if (isset($in_uid)){
            foreach ($in_uid as $k=>$v){
                if (!empty($v)){
                    $this->addGroupMessage($post,$role_type,$title,$msg,$v);
                }
            }
        }
		$data['title'] = $username;  //图文消息标题
		$data['des']   = $des;       //图文消息内容
		$data['content'] = $msg;    //待入库的原始消息
		$data['zhuti'] = $title;     //待入库的消息主题
	//	$data['url']   = '';       //图文跳转链接
		$data['strpath'] = $strpath; //附件
		$result = $this->sendExchangeMsg($openid,$tos,$data);
		return true;
	}
	private  function addGroupMessage($post,$role_type,$title,$msg,$in_uid){
        $model = new WpIschoolGroupMessage();
        $model -> user_id = \yii::$app->user->getId();
        $model -> paramers = json_encode($post);
        $model -> send_role = $role_type;
        $model -> title = $title;
        $model -> content = $msg;
        $model -> in_uid = $in_uid;
        $model -> save();
    }
	private function addToInboxBeforeSend($from,$to,$msg,$uname,$strpath=""){
		
		$data['content']   = $msg;
		$data['outopenid'] = $from;
		$data['inopenid']  = $to;
		$data['title']     = "来自".$uname."的消息";
		$data['ctime']     = time();;
		$data['fujian']    = $strpath;
		$data['type']      = 1;
		return \yii::$app->db->createCommand()->insert('wp_ischool_inbox', $data)->execute();

	}

	private function sendExchangeMsg($from,$tos,$data){
		$des     = $data['des'];        //纯内容
		$msg     = $data['content']; 	//原始内容附带html标签
		$zhuti   = $data['zhuti'];
		$strPath = $data['strpath'];
		$uname   = $data['title'];

		/*
		foreach($tos as $to){
			$this->addToInboxBeforeSend($from,$to,$msg,$uname,$strPath);
		}*/
		$title = "来自".$uname."的消息";
		$data['title'] = $title;
		$data['content'] = $des;
		return self::muiltPostMsg($tos,$data);
	}

	static  function broadMsgToManyUserTest(&$tos,$data){
        $title = $data['title'];
        $content = $data['content'];
        $url = $data['url'];
        $pic_url = empty($data['pic_url']) ? \yii::$app->params['URL_PATH'].self::$COM_PIC_URL : $data['pic_url'];
        // $pic_url = empty($data['pic_url']) ? URL_PATH.self::$COM_PIC_URL : $data['pic_url'];
        $sendUrl = self::getUrl('kf');

        foreach($tos as $v){
            $msg = self::createNewsMsg($v,$title,$content,$url.$v,$pic_url);
            $result = self::singlePostMsg($sendUrl,$msg);
            $result  = json_decode($result);
        }
        return $result;
    }
}
