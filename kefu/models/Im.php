<?php
namespace kefu\models;
use yii;
use kefu\models\WpImMessage;
use kefu\models\WpIschoolUser;
class Im extends \yii\base\Model
{
    public function getKefuInfo(){
        $uid=\yii::$app->params['KEFU_UID'];
        $name=\yii::$app->params['KEFU_NAME'];
        $picurl=\yii::$app->params['KEFU_PICURL'];
        $result['token']=self::getToken($uid,$name,$picurl);
        $result['appkey']=\yii::$app->params['IM_APPKEY'];
        return $result;
    }

    protected function getToken($uid,$name,$picurl){
        $url="http://api.cn.ronghub.com/user/getToken.json";       
        $data="userId=".$uid."&name=".$name."&portraitUri=".$picurl;        
        $result=self::PostCurl($url,$data);
        if(isset($result) && $result['code']=='200'){   
            $token=$result['token'];
        } 
        return $token;
    }

    protected function PostCurl($url,$data){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $appSecret = \yii::$app->params['IM_APPSECRET']; // 开发者平台分配的 App Secret。       
        $timestamp = time()*1000; // 获取时间戳（毫秒）。
        $nonce = rand(); // 获取随机数。
        $signature = sha1($appSecret.$nonce.$timestamp); 
        //设置header
        $header = array();
        $header[] = 'App-Key:'.\yii::$app->params['IM_APPKEY'];
        $header[] = 'Timestamp:'.$timestamp;
        $header[] = 'Nonce:'.$nonce;
        $header[] = 'Signature:'.$signature;
        $header[] = 'Content-Length:'.strlen($data);
        $header[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return array("errcode"=>-1,"errmsg"=>'发送错误号'.curl_errno($curl).'错误信息'.curl_error($curl));
        }
        curl_close($curl);
        return json_decode($result, true);
    }

    //保存发送消息
    public function saveMes($post){
        $im=new WpImMessage;
        $im->messageType=$post['messageType'];       
        $im->converType=$post['converType'];
        $im->sendId=\yii::$app->params['KEFU_UID'];
        $im->targetId=$post['targetId'];
        $im->sendtime=$post['sendtime'];
        $im->messageDirection=1;
        if($post['messageType']=='ImageMessage'){
           $im->url=urldecode($post['imageUri']);
        }else{
           $im->content=$post['content'];
        }
        $res=$im->save(false);
        return ($res) ?'success':'fail';

    }
    //保存接收消息
    public function saveMesRec($post){
        $im=new WpImMessage;
        if($post['messageType']=='TypingStatusMessage'){
            return 'fail';
        }
        if($post['messageType']=='ImageMessage'){
           $im->url=urldecode($post['imageUri']);
        }else{
           $im->content=$post['content'];
        }
        $im->messageType=$post['messageType'];       
        $im->converType=$post['converType'];
        $im->sendId=\yii::$app->params['KEFU_UID'];
        $im->targetId=$post['targetId'];
        $im->sendtime=$post['sendtime'];
        $im->messageDirection=2;
        $res=$im->save(false);
        $user=WpIschoolUser::find()->where(['tel'=>$post['targetId']])->one();
        $user->im_last_conver_time=$post['sendtime'];
        $res1=$user->save(false);
        return ($res && $res1) ?'success':'fail';
    }
    //获取历史消息
    public function getHisMes($post){
    	$res=WpImMessage::find()->where(['targetId'=>$post['targetId']])->orderBy('sendtime DESC')->limit(50)->asArray()->all();
    	if($res){
            $res=array_reverse($res);
    		$data=[];
    		foreach($res as $k=>$v){
    			$data[$k]['content']['messageName']=$v['messageType'];
                $data[$k]['content']['content']= ($v['messageType']=='ImageMessage') ? preg_replace('/\s+/', '', $v['content']) : $v['content'];   			
    			if($v['messageType']=='ImageMessage'){
                   $data[$k]['content']['imageUri']=$v['url'];
                }
                $data[$k]['content']['extra']="附加信息";
    			$data[$k]['conversationType']=$v['converType'];
    			$data[$k]['sentTime']=intval($v['sendtime']);
    			$data[$k]['targetId']=$v['targetId'];
    			$data[$k]['messageType']=$v['messageType'];
    			$data[$k]['messageDirection']=$v['messageDirection'];
    		}
    	}
    	return ($data) ? $data : null;
    }
    //获取会话列表
    public function getCoveList($post){
        if($post['data']==1){
            $user=WpIschoolUser::find()->select(['targetId'=>'tel','sentTime'=>'im_last_conver_time'])->orderBy('im_last_conver_time DESC')->limit(100)->asArray()->all();
            $k=0;
            foreach($user as $key=>$v){
                $conver=WpImMessage::find()->where(['targetId'=>$v['targetId']])->orderBy('sendtime DESC')->asArray()->one();
                if($conver){
                    $data[$k]['conversationType']=$conver['converType'];
                    $data[$k]['latestMessage']['content']['content']=$conver['content'];
                    $data[$k]['latestMessage']['content']['messageName']=$conver['messageType'];
                    $data[$k]['latestMessage']['conversationType']=$conver['converType'];
                    $data[$k]['latestMessage']['messageDirection']=$conver['messageDirection'];
                    $data[$k]['latestMessage']['receivedStatus']=1;
                    $data[$k]['latestMessage']['sentTime']=$conver['sendtime'];
                    $data[$k]['latestMessage']['messageType']=$conver['messageType'];
                    $data[$k]['receivedStatus']=1;
                    $data[$k]['sentTime']=$conver['sendtime'];
                    $data[$k]['targetId']=$conver['targetId'];
                    $data[$k]['unreadMessageCount']=0;
                    $k++;
                }                
            }
        }
        return ($data) ? $data : null;
    }
}

