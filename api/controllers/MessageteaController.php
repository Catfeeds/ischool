<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use api\models\WpIschoolUser;
use api\models\WpIschoolPastudent;
use api\models\WpIschoolClass;
class MessageteaController extends BaseActiveController
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

    //获取班级家长列表
    public function actionParlist(){
        $cid = $this->post['cid'];
        $uid = $this->uid;       
        $model = $this->getAllstuinf($cid);
        $dataList = [];
        $i=0;
        $userInfo = WpIschoolUser::findOne($uid);  
        $postData="userId=".$userInfo['tel']."&";
        $refuseUser="";
        foreach ($model as $k=>$v){
           $res = WpIschoolPastudent::find(['stu_id'=>$v['id']])->select('name,tel as userId,stu_name')->where("stu_id=:stu_id and tel is NOT NULL and isqqtel=0 and uid != :uid")->addParams([':stu_id'=>$v['id'],':uid'=>$uid])->groupby("tel")->asArray()->all();
           if($res){
                foreach($res as $k1=>$v1){
                    $postData.=$refuseUser.="userId=".$v1['userId']."&";
                    $dataList[$i]['userId']=$v1['userId'];
                    $dataList[$i]['name']=$v1['name'];
                    $dataList[$i]['stu_name']=$v1['stu_name'];
                    $userImg=WpIschoolUser::findOne(["tel"=>$v1['userId']])['user_img'];
                    $dataList[$i]['portraitUri']=($userImg)? \yii::$app->params['BASE_URL'].$userImg : "";
                    $dataList[$i]['is_jx'] = ($v['enddatejx']>time())?"y":"n";
                    $i++;

                }
           }          
        }
        $key ='userId';
        $dataList =$this->second_array_unique_bykey($dataList,$key);
        //获取班级名称，并创建群聊
        $modelClass=WpIschoolClass::findOne($cid);
        $groupArr=[];
        $groupArr['groupName']=$modelClass['name'].'群组';
        $groupArr['groupId']=$cid.'0000000';
        $url="http://api.cn.ronghub.com/group/create.json";
        $postData.="groupId=".$groupArr['groupId']."&groupName=".$groupArr['groupName'];        
        $result=$this->PostCurl($url,$postData);
        $this->ImLogs("create:".json_encode($result));
        if(isset($result) && $result['code']=='200'){ 
            $url2="http://api.cn.ronghub.com/group/user/gag/rollback.json";
            $allowUser="userId=".$userInfo['tel']."&groupId=".$groupArr['groupId'];        
            $this->PostCurl($url2,$allowUser);           
            $url1="http://api.cn.ronghub.com/group/user/gag/add.json";
            $refuseUser.="groupId=".$groupArr['groupId']."&minute=0";        
            $this->PostCurl($url1,$refuseUser);
            $data['group']=$groupArr;
            $data['painfo']=$dataList;
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

}

