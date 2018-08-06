<?php
namespace mobile\assets;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use mobile\models\WpIschoolAccessToken;
use mobile\models\WpIschoolNum;
use mobile\assets\RollingCurlRequest;
use mobile\assets\RollingCurl;
class SendMsg {
    static $COM_PIC_URL = "/upload/syspic/msg.jpg";
    static $COM_KF_URL = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=";
    static $COM_MB_RUL = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=";
    /**
     * @param $tos
     * @param $data
     * 家校通与校内交流等小批量发送，需要临时根据openid拼凑url
     */
    static function muiltPostMsg(&$tos,$data){
        $title = $data['title'];
        $content = $data['content'];
        $url = $data['url'];  //需要临时拼接openid
        $pic_url = empty($data['pic_url']) ? URL_PATH.self::$COM_PIC_URL : $data['pic_url'];
        $sendUrl = self::getUrl('kf');

        $rc = new RollingCurl();
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
            $temSize = count($the_fails);
            $theTempid = self::getTempid();
            $sendUrl = self::getUrl('mb');

            foreach ($the_fails as $op) {
                $msg = self::createTempMsg($op,$theTempid,$title,$content,$url[0].$op.$url[1]);
                $request = new RollingCurlRequest($sendUrl);
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
     * @param $tempid
     * @param int $num
     * 更新模版条数
     */
    static function resetTempNum($tempid,$num=1){
//      $m = WpIschoolNum::find()->where(['temid'=>$tempid])->one();
//      $m->updateAllCounters(['num'=>$num]);
      WpIschoolNum::updateAllCounters(['num'=>$num],['temid'=>$tempid]);
     
    }
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
    static function getAccessToken(){
        $appId     = APPID;
        $appSecret = APPSECRET;
        $token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appSecret;
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
        }

        return $acc_token;
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
     * @return string
     * 获取模版id
     */
    static function getTempid(){
        $yz = date("Ymd");  
        $sql="select temid from wp_ischool_num where time=".$yz."  and name !='kefu'order by num asc limit 0,1 ";
        $tempid= WpIschoolNum::findBySql($sql)->asArray()->all();
//        $tempid = WpIschoolNum::find()->select('temid')->where(['time'=>$yz])->andwhere(['<>','name','kefu'])->orderBy('num asc')->asArray()->all();    
        if($tempid){
            return $tempid[0]['temid'];
        }else{
            $d= WpIschoolNum::find()->where(['<>','name','kefu'])->one();           
            $d-> time = $yz;
            $d-> num  = 0;
            $d->save();
            $sql="select temid from wp_ischool_num  where  name != 'kefu' order by num asc limit 0,1 ";
            $tempid= WpIschoolNum::findBySql($sql)->asArray()->all();          
            if($tempid){
                return $tempid[0]['temid'];
            }else{
                //此处应该设置一个默认的模版，防止查询出错的情况
                //但为了多账户（模版id都不一样）代码兼容，暂时只采取再查一次的策略
                $sql="select temid from wp_ischool_num where  name != 'kefu' order by num asc limit 0,1 ";
                $tempid= WpIschoolNum::findBySql($sql)->asArray()->all();  
                return $tempid[0]['temid'];
            }
        }
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
            $picurl = URL_PATH.self::$COM_PIC_URL;
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
     * @param $url
     * @param $data
     * @return array|mixed
     * 执行http_post请求的公用方法
     */
    static public function https_post($url,$data){
        return self::singlePostMsg($url,$data);
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
        $pic_url = empty($data['pic_url']) ? URL_PATH.self::$COM_PIC_URL : $data['pic_url'];
        $sendUrl = self::getUrl('kf');

        $rc = new RollingCurl();
        $rc->window_size=10;
        foreach($tos as $v){
            $msg = self::createNewsMsg($v,$title,$content,$url.$v,$pic_url);
            $request = new RollingCurlRequest($sendUrl);
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
                $request = new RollingCurlRequest($sendUrl);
                $request->method='POST';
                $request->post_data=$msg;

                $rc->add($request);
            }
            $rc->execute(10);
            self::resetTempNum($theTempid,$temSize);
        }

        return json_decode('{"errcode":0}');
    }

}
