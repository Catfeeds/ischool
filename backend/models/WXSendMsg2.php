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
class WXSendMsg{
	static $COM_KF_URL = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=";
	static $COM_MB_RUL = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=";
	static $COM_PIC_URL = "/upload/syspic/msg.jpg";

	/**
	 * @param $type [客服消息|模版消息]
	 * @return string 返回发消息的url
	 */
	static function getUrl($type){
		$access_token = self::getAccessToken();
		if ($type == 'kf') {
			$url = self::$COM_KF_URL.$access_token;
		}else{
			$url = self::$COM_MB_RUL.$access_token;
		}
		return $url;
	}

	/**
	 * @return mixed 返回ACCESS_TOKEN
	 */
	// static function getAccessToken(){
	// 	$appId     = \yii::$app->params["APPID"];
	// 	$appSecret = \yii::$app->params["APPSECRET"];
	// 	$token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appSecret;

	// 	// 直接存在全局缓存里面
	// 	$acc_token = \yii::$app->cache->get("access_token");
	// 	if( true ||  !$acc_token){
	// 		$json   = file_get_contents($token_url);
	// 		$result = json_decode($json);
	// 		$acc_token = $result->access_token;

	// 		\yii::$app->cache->set("access_token", $acc_token,$result->expires_in);
	// 	}
	// 	return $acc_token;
	// }
	
	static function getAccessToken(){
        	$appId     = \yii::$app->params["APPID"];
        	$appSecret = \yii::$app->params["APPSECRET"];
        	$token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appSecret;
	        $redis = new \redis();
                $result = $redis->connect('127.0.0.1', 6379);
                if ($result) {
                        $acc_token = $redis->get("my_access_token");
                        if (!$acc_token) {
                                $json = file_get_contents($token_url);
                                $result = json_decode($json);
                                $acc_token = $result->access_token;
                                $redis->set("my_access_token", $acc_token, 7100);
                        }

                } else {
                        $json = file_get_contents($token_url);
                        $result = json_decode($json);
                        $acc_token = $result->access_token;
                }

                return $acc_token;

	/*
        $sql="select * from wp_ischool_access_token limit 0,1 ";
        $my_token =WpIschoolAccessToken::findBySql($sql)->asArray()->all();
        $now = time();
        if(!empty($my_token)){
            if(($now-$my_token[0]['last_time']) < 1800 && !empty($my_token[0]['access_token'])){  //微信客户端认证access_token是否超过两小时，是就重新去微信抓去
                $acc_token = $my_token[0]['access_token'];
            }else{
                $json   = file_get_contents($token_url);
                $result = json_decode($json);
                $acc_token = $result->access_token;
                $id = $my_token[0]['id'];
                $d=WpIschoolAccessToken::findOne($id);
                $d->access_token=$acc_token;
                $d->last_time=$now;            
                $d->save();

            }
        }else{
            $json   = file_get_contents($token_url);
            $result = json_decode($json);
            $acc_token = $result->access_token;
            $d=new  WpIschoolAccessToken;
            $d->access_token = $acc_token;
            $d->last_time    = $now;
            $d->save();
        }*/

        //return $acc_token;
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

	/**
	 * @return string
	 * 获取模版id
	 */
	static function getTempid(){
		$yz = date("Ymd");

		$m = M("ischool_num");
		$con["name"] = array("neq","kefu");
		$con['time'] = $yz;
		$tempid = $m->where($con)->field('temid')->order('num asc')->limit(0,1)->select();

		if($tempid){
			return $tempid[0]['temid'];
		}else{
			$data['time'] = $yz;
			$data['num'] = 0;
			$upcon['name'] = array('neq','kefu');
			$m->where($upcon)->save($data);

			$tempid = $m->where($con)->field('temid')->order('num asc')->limit(0,1)->select();
			if($tempid){
				return $tempid[0]['temid'];
			}else{
				//此处应该设置一个默认的模版，防止查询出错的情况
				//但为了多账户（模版id都不一样）代码兼容，暂时只采取再查一次的策略
				$con2['name'] = array('neq','kefu');
				$tempid = $m->where($con2)->field('temid')->order('num asc')->limit(0,1)->select();
				return $tempid[0]['temid'];
			}
		}
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
	static function broadMsgToManyUsers(&$tos,$data){

		$title = $data['title'];
		$content = $data['content'];
		$url = $data['url'];
		$pic_url = empty($data['pic_url']) ? \yii::$app->params['URL_PATH'].self::$COM_PIC_URL : $data['pic_url'];
		$sendUrl = self::getUrl('kf');

		$rc = new \RollingCurl();
		$rc->window_size=10;
		foreach($tos as $v){
			$msg = self::createNewsMsg($v,$title,$content,$url.$v,$pic_url);
			$request = new \RollingCurlRequest($sendUrl);
			$request->method='POST';
			$request->post_data=$msg;

			$rc->add($request);
		}
		$the_fails = $rc->execute(10);  //首次执行全是图文消息，失败的用模版再来一次
		unset($tos);//销毁大数组
		if(!empty($the_fails)) {
			$temSize = count($the_fails);
			$theTempid = self::getTempid();
			$sendUrl = self::getUrl('mb');

			foreach ($the_fails as $op) {
				$msg = self::createTempMsg($op,$theTempid,$title,$content,$url.$op);
				$request = new \RollingCurlRequest($sendUrl);
				$request->method='POST';
				$request->post_data=$msg;

				$rc->add($request);
			}
			$rc->execute(10);
			self::resetTempNum($theTempid,$temSize);
		}

		return json_decode('{"errcode":0}');
	}

	/**
	 * @param $tos
	 * @param $data
	 * 家校通与校内交流等小批量发送，需要临时根据openid拼凑url
	 */
	static function muiltPostMsg(&$tos,$data){
		$title = $data['title'];
		$content = $data['content'];
		$url = $data['url'];  //需要临时拼接openid
		$pic_url = empty($data['pic_url']) ? \yii::$app->params['URL_PATH'].self::$COM_PIC_URL : $data['pic_url'];
		\yii::trace($pic_url);
		$sendUrl = self::getUrl('kf');

		$rc = new  RollingCurl();
		$rc->window_size=10;
		foreach($tos as $v){
			$msg = self::createNewsMsg($v,$title,$content,$url[0].$v.$url[1],$pic_url);
			$request = new RollingCurlRequest($sendUrl);
			$request->method='POST';
			$request->post_data=$msg;

			$rc->add($request);
		}

		$the_fails = $rc->execute(10);  //首次执行全是图文消息，失败的用模版再来一次
		unset($tos); //销毁大数组
		if(!empty($the_fails)) {
	                foreach($the_fails as $v){
                	        $msg = self::createNewsMsg($v,$title,$content,$url[0].$v.$url[1],$pic_url);
                        	$request = new RollingCurlRequest($sendUrl);
                        	$request->method='POST';
                        	$request->post_data=$msg;
                        	$rc->add($request);
        	        }

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
			$picurl = \yii::$app->params['URL_PATH']("URL_PATH").self::$COM_PIC_URL;
		}
		$sendUrl  = self::getUrl('kf');
		$data = self::createNewsMsg($openid,$title,$des,$url,$picurl);
		$result = self::singlePostMsg($sendUrl,$data);
		$result  = json_decode($result);

		if($result->errcode != 0){
			$theTempid = self::getTempid();
			$sendUrl = self::getUrl('mb');
			$data = self::createTempMsg($openid,$theTempid,$title,$des,$url);

			$result = self::singlePostMsg($sendUrl,$data);
			$result = json_decode($result);
			self::resetTempNum($theTempid,1);
		}
		return $result;
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

	/**
	 * @param $tos
	 * @param $stuid
	 * @param $stuname
	 * @param $msg
	 * @param $timeinfo
	 * @return mixed
	 * 监控机平安卡信息发送（apiservice模块）
	 */
	static public function sendSafe($tos,$stuname,$msg,$timeinfo,$picurl){
		$result = json_decode('{"errcode":0}');
		if(!empty($tos))
		{//如果学生绑定的有家长则给家长发信息否则直接返回一个成功的标志
			$inoutDate = date("Y年m月d日H时i分s秒",$timeinfo);
			$title   = "学生考勤信息";
			$content = "您的学生：".$stuname."于".$inoutDate."有一条".$msg."信息。\\n\\t\\n点击【家校互动】->【".C("YiCardName")."】查看学生进出校信息";

			foreach($tos as $to) {
				$i = $q = 0;
				$url  = self::getUrl('kf');
				$data = self::createNewsMsg($to,$title,$content,"",$picurl);
				while ($i < 2) {

					$Msgresult = json_decode(self::singlePostMsg($url,$data));
					$errcode = $Msgresult->errcode;
					if ($errcode == 0 || $errcode == 43004 || $errcode == 40003) {
						//如果信息发送成功或取消关注则跳出本次循环
						$i = $q = 10;
						break;
					}elseif($errcode == 45015 || $errcode == 45047){
						//如果长时间未交互则结束图文发送模版
						$i = 10;
						break;
					}
					$i++;
				}

				if ($q == 0) {
					$url  = self::getUrl('mb');
					$tempId = self::getTempid();
					$data = self::createTempMsg($to,$tempId,$title,$content);
					$result = json_decode(self::singlePostMsg($url,$data));
					self::resetTempNum($tempId,1);
				}
			}
		}

		return $result;
	}
	private function getSendTeacherOpenIds($params)
	{
		$data = ['and','openid is not null','ispass = "y"'];
		if(is_array($params['sid'])&&$params['sid']) array_push($data,['in','sid',$params['sid']]);
		if(is_array($params['cid'])&&$params['cid']) array_push($data,['in','cid',$params['cid']]);
		$query = WpIschoolTeaclass::find()->Where($data)->select("openid")->asArray()->all();
		return ArrayHelper::getColumn($query, "openid");
		
	}
//	private function getSendParentsOpenIds($params)
//{
//	$data = ['and','openid is not null','ispass = "y"'];
//	if(is_array($params['sid'])&&$params['sid']) array_push($data,['in','sid',$params['sid']]);
//	if(is_array($params['cid'])&&$params['cid']) array_push($data,['in','cid',$params['cid']]);
//
//	$query = WpIschoolPastudent::find()->where($data)->select("openid")->asArray()->all();
//	return ArrayHelper::getColumn($query, "openid");
//}
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

		$data.= $dat;
		$sql = "SELECT DISTINCT openid FROM wp_ischool_pastudent as t LEFT JOIN wp_ischool_student as t1 ON t.stu_id = t1.id  WHERE $data";
		$query = \Yii::$app->db->createCommand($sql)->queryAll();
		$openid = ArrayHelper::getColumn($query, "openid");
		return $openid;
	}
	private function getSendFenzuOpenIds($params)
	{
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
		return "正梵掌上学校";
	}
	public function getParentsOpenid($user_no)
	{
		$result = \yii::$app->db->createCommand("SELECT wp_ischool_pastudent.openid from wp_ischool_student,wp_ischool_pastudent WHERE wp_ischool_student.stuno2 = :user_no and wp_ischool_student.id = wp_ischool_pastudent.stu_id and wp_ischool_pastudent.openid is not null order by wp_ischool_pastudent.id desc limit 1",[":user_no"=>$user_no])->queryOne();
		return isset($result['openid'])?$result["openid"]:null;
		
	}
	public function doSendCard($post)
	{
		$path    = \yii::$app->params['URL_PATH'];
		$des = "您的孩子今天有一笔".$post['money']."元的消费。";       //消息内容
		$openid = $this->getParentsOpenid($post['user_no']);
		if(!$openid) return;
		//$openid = "oTzscuHxIwLl7Ejo5RJ4Mdug7z9k";
		//$openid = "oTzscuDfHZizVnFdnoBp6PJcB_BQ";
		//$openid = "oTzscuGNe8El7HiKxBkP4z9TaPH8"; //lee
		$openid = trim($openid);
		//$openid = "oTzscuGjNJ8lQLWGwzGbzq3ys1uw"; //liqq
		\yii::trace($openid);
		$title = "来自正梵掌上学校的消息";
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

		$ur[0]=$path."/index.php?s=/addon/Exchange/Exchange/index/openid/";
		$ur[1]="/sid/1.html";
		$username = $this->getSenderInfo($openid);
		
		$model = new WpIschoolGroupMessage();
		$model -> user_id = \yii::$app->user->getId();
		$model -> paramers = json_encode($post);
		$model -> send_role = $role_type;
		$model -> title = $title;
		$model -> content = $msg;
		$model -> save();
		
		if($role_type == "TEACHER")
			$tos = $this->getSendTeacherOpenIds(['sid'=>$post['WpIschoolGroupMessage']['sid'],"cid"=>$post['WpIschoolGroupMessage']['cid']]);
		else 
			$tos = $this->getSendParentsOpenIds(['sid'=>$post['WpIschoolGroupMessage']['sid'],"cid"=>$post['WpIschoolGroupMessage']['cid'],"fenzu"=>$post['WpIschoolGroupMessage']['fenzu']]);

		//图文信息的内容，与入库的原始html内容$msg不同
		if(!empty($des)){
			$preg = "/<\/?[^>]+>/i";
			$des = preg_replace($preg,'',$des);
		}
	
		$tos = array_unique($tos);
		$data['title'] = $username;  //图文消息标题
		$data['des']   = $des;       //图文消息内容
		$data['content'] = $msg;    //待入库的原始消息
		$data['zhuti'] = $title;     //待入库的消息主题
		$data['url']   = $ur;       //图文跳转链接
		$data['strpath'] = $strpath; //附件
		$result = $this->sendExchangeMsg($openid,$tos,$data);
		return true;
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
