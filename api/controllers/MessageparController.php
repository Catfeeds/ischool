<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use api\models\WpIschoolUser;
use api\models\WpIschoolTeaclass;
use api\models\WpIschoolClass;
use api\models\WpImMessage;
class MessageparController extends BaseActiveController
{
    // public $enableCsrfValidation = false;
    //获取用户token
    public function actionGettoken(){
        $request = Yii::$app->request;
        $this->ImLogs('gettoken:'.json_encode($request->post()));    
        $userId= $request->post('userId');
        $name= $request->post('name');
        $portraitUri= $request->post('portraitUri');       
        $url="http://api.cn.ronghub.com/user/getToken.json";
        $data="userId=".$userId."&name=".$name."&portraitUri=".$portraitUri;
        $this->ImLogs($data);
        $result=$this->PostCurl($url,$data);
        $this->ImLogs(json_encode($result));
        $ret = [];
        if(isset($result) && $result['code']=='200'){   
            $user=WpIschoolUser::findOne(['tel'=>$userId]); 
            if($user){
                $user->im_token=$result['token'];
                $user->save(false);
            }                      
            $ret['userId'] = $result['userId'];
            $ret['token'] = $result['token'];
            $ret['status'] = '0';
            $ret['info'] = '操作成功';
            return $this->Formatjson($ret);
        }else{           
            $ret['status'] = $result['code'];
            $ret['info'] = '获取token失败';
            return $this->Formatjson($ret);
        }
    }

    //获取该班老师信息
    public function actionTealist(){
        $cid = $this->post['cid'];
        $uid = $this->uid;       
        $dataList = [];
        $i=0;
        $userInfo = WpIschoolUser::findOne($uid);  
        $postData="userId=".$userInfo['tel']."&";
        $res = WpIschoolTeaclass::find()->where("cid=:cid and ispass='y' and uid!=:uid")->addParams([':cid'=>$cid,':uid'=>$uid])->groupby("uid")->asArray()->all();
        if($res){
            foreach($res as $k1=>$v1){
                $user=WpIschoolUser::findOne(["id"=>$v1['uid']]);
                if($user['tel']){
                    $userId=$user['tel'];
                    $postData.="userId=".$userId."&";
                    $dataList[$i]['userId']=$userId;
                    $dataList[$i]['name']=$v1['tname'];
                    $dataList[$i]['role']=$v1['role'];
                    $userImg=$user['user_img'];
                    $imageUrl=($userImg)? \yii::$app->params['BASE_URL'].$userImg : "";                        
                    $dataList[$i]['portraitUri']=$imageUrl;
                    $i++;
                }              
            }
        }          
        $key ='userId';
        $dataList =$this->second_array_unique_bykey($dataList,$key);
        //获取班级名称，并创建群聊
        $modelClass=WpIschoolClass::findOne($cid);
        $groupArr=[];
        $groupArr['groupName']=$modelClass['name'].'群消息通知';
        $groupArr['groupId']=$cid.'0000000';
        $url="http://api.cn.ronghub.com/group/create.json";
        $postData.="groupId=".$groupArr['groupId']."&groupName=".$groupArr['groupName'];        
        $result=$this->PostCurl($url,$postData);
        $this->ImLogs("create:".json_encode($result));
        if(isset($result) && $result['code']=='200'){ 
            $url1="http://api.cn.ronghub.com/group/user/gag/add.json";
            $refuseUser="userId=".$userInfo['tel']."&groupId=".$groupArr['groupId']."&minute=0";        
            $this->PostCurl($url1,$refuseUser);       
            $data['group']=$groupArr;
            $data['teainfo']=$dataList;
        }
        return $this->formatAsjson($data);
    }
    //获取群成员
    public function actionQueryuser(){
        $groupId = $this->post['groupId'];
        $url="http://api.cn.ronghub.com/group/user/query.json";
        $postData="groupId=".$groupId;        
        $result=$this->PostCurl($url,$postData);
        return $this->formatAsjson($result);
    }

    public function actionDelgroup(){
        $groupId = $this->post['groupId'];
        $userId = $this->post['userId'];
        $url="http://api.cn.ronghub.com/group/dismiss.json";
        $postData="userId=".$userId."&groupId=".$groupId;        
        $result=$this->PostCurl($url,$postData);
        return $this->formatAsjson($result);
    }
    //获取客服信息接口
    public function actionGetkfinfo(){
        $data['userId']=\yii::$app->params['KEFU_UID'];
        $data['name']=\yii::$app->params['KEFU_NAME'];
        $data['portraitUri']=\yii::$app->params['KEFU_PICURL'];
        return $this->formatAsjson($data);
    }

    public function actionSavecovermes(){
        $messageType=$this->post['messageType'];
        $content=$this->post['content'];
        $converType=$this->post['converType'];
        $targetId=$this->post['targetId'];
        $sendtime=$this->post['sendtime'];
        $im=new WpImMessage;
        $im->messageType=$messageType;
        $im->content=$content;
        $im->converType=$converType;
        $im->sendId=\yii::$app->params['KEFU_UID'];
        $im->targetId=$targetId;
        $im->sendtime=$sendtime;
        $im->messageDirection=2;
        $res=$im->save(false);
        if(res){
            $ret['status'] = '0';
            $ret['info'] = '操作成功';
        }else{
            $ret['status'] = '1';
            $ret['info'] = '保存失败';
        }
        return $this->formatAsjson($ret);
    }
}


