<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_student".
 *
 * @property string $id
 * @property string $name
 * @property string $stuno2
 * @property string $sex
 * @property string $school
 * @property string $class
 * @property string $address
 * @property integer $ctime
 * @property integer $cid
 * @property string $cardid
 * @property string $stuno
 * @property string $outType
 * @property integer $type
 * @property string $carCode
 * @property integer $sid
 * @property integer $LastTime
 * @property integer $LastStatus
 * @property integer $enddate
 * @property integer $upendtime
 * @property integer $enddatejx
 * @property integer $upendtimejx
 */
class WpIschoolStudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $bupdate,$card_no;
    public static function tableName()
    {
        return 'wp_ischool_student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctime', 'cid', 'type', 'sid', 'LastTime', 'LastStatus', 'upendtime', 'upendtimejx'], 'integer'],
            [['name', 'outType'], 'string', 'max' => 10],
            [['stuno2', 'school', 'carCode'], 'string', 'max' => 20],
            [['sex'], 'string', 'max' => 2],
            [['class'], 'string', 'max' => 15],
            [['address'], 'string', 'max' => 50],
            [['cardid'], 'string', 'max' => 32],
            [['stuno'], 'string', 'max' => 25],
        	[['enddatepa','enddatejx', 'enddateqq', 'enddateck', 'bupdate','card_no','img'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'stuno2' => '学号',
            'school' => '学校',
            'class' => '班级',
            'cardid' => 'epc',
            'card_no' => '电话卡',
            'stuno' => '系统号',
            'enddatepa' => '平安通知截止时间',
            'enddateqq' => '亲情电话截止时间',
            'enddateck' => '餐卡充值截止时间',
            'enddatejx' => '家校沟通截止时间',
            'img'=>"二维码"
        ];
    }

    public function getCard()
    {
    	return $this->hasOne(WpIschoolStudentCard::className(), ['stu_id' => 'id'])->onCondition(['flag'=>1]);
    }
    public function beforeSave($insert)
    {
        // echo '112';die;
    	if(parent::beforeSave($insert)){
            if(!isset($this->is_deleted) || $this->is_deleted != 1 ) {
                $this->enddatepa = strtotime($this->enddatepa) + 86399;
                $this->enddateqq = strtotime($this->enddateqq) + 86399;
                $this->enddateck = strtotime($this->enddateck) + 86399;
                $this->enddatejx = strtotime($this->enddatejx) + 86399;
            }
                     
    		return true;
    	}else{
    		return false;
    	}
    }
    public function afterSave($insert, $changedAttributes)
    {
    	if($this->isNewRecord) return ;

    	if(!isset($changedAttributes['is_deleted']) && isset($changedAttributes['name']))
    	{
    		WpIschoolPastudent::updateAll(['stu_name'=>$this->name],['stu_id'=>$this->id]);
    	}
        if (isset($changedAttributes['is_deleted']) && $changedAttributes['is_deleted'] = 1)
    	{

    		WpIschoolPastudent::updateAll(['is_deleted'=>1],['stu_id'=>$this->id]);
    	}
        if (isset($changedAttributes['cid']) && $this->bupdate)
    	{
    		self::updateAll(['cid'=>$this->cid,"class"=>$this->class],['cid'=>$changedAttributes['cid']]);

    	}
        $orderSd = new WpIschoolOrder();
        $update_data = [];
        if(!isset($changedAttributes['is_deleted']) || $changedAttributes['is_deleted'] != 1 ) {
            if (isset($changedAttributes['enddatejx'])) {
                $update_data = array_merge($update_data, ['upendtimejx' => time()]);
            }
            if (isset($changedAttributes['enddatepa'])) {
                $update_data = array_merge($update_data, ['upendtimepa' => time()]);
            }
            if (isset($changedAttributes['enddateqq'])) {
                $update_data = array_merge($update_data, ['upendtimeqq' => time()]);
            }
            if (isset($changedAttributes['enddateck'])) {
                $update_data = array_merge($update_data, ['upendtimeck' => time()]);
            }
            if(!empty($update_data)){
                self::updateAll($update_data, ['id' => $this->id]);
            }
            if (isset($changedAttributes['enddatepa']) || isset($changedAttributes['enddateqq']) || isset($changedAttributes['enddatejx']) || isset($changedAttributes['enddateck'])) {
                $orderSd->ispasspa = isset($changedAttributes['enddatepa']) ? 1 : 0;
                $orderSd->ispassjx = isset($changedAttributes['enddatejx']) ? 1 : 0;
                $orderSd->ispassqq = isset($changedAttributes['enddateqq']) ? 1 : 0;
                $orderSd->ispassck = isset($changedAttributes['enddateck']) ? 1 : 0;
                $orderSd->openid = "sdtj" . $this->id;
                $orderSd->trade_no = $this->id . time();
                $orderSd->trade_name = $this->school . "|" . $this->class . "|" . $this->name . "|" . $this->id;
                $orderSd->paytype = "SDTJ";
                $orderSd->ctime = time();
                $orderSd->utime = time();
                $orderSd->zfopenid = "sdtj" . $this->id;
                $orderSd->stuid = $this->id;
                $orderSd->trans_id = "100" . time() . rand(100, 999);
                $orderSd->save();
            }
        }
        $coop_school=\yii::$app->params['cooperative.school'];
        if(in_array($this->sid, $coop_school)){
            //获取信息验证（用户信息同步获取）               
            //包体数据
            $requst_info=[
                'actionStr'=>'YKT_GET_CUSTOMER',
                'version'=>'200',
                'thirdCode'=>'',
                'page'=>'1',
                'pageSize'=>'1',
                'custNo'=>$this->stuno2,
                'idcard'=>'',
                'accoutld'=>'',
                'name'=>$this->name,
            ];
            $jsonInfo=$this->getPostInfo($requst_info);
            echo '<pre>';
            var_dump($jsonInfo);
            if($jsonInfo['resultCode']=='0000' && empty($jsonInfo['datas'])){
                $send_info=[
                    'actionStr'=>'YKT_SYN_CUSTOMER',
                    'version'=>'200',
                    'thirdCode'=>'',
                    'uniqueType'=>'X',
                    'custNo'=>$this->stuno2,
                    'name'=>$this->name,
                    'sex'=>'1',
                    'idcard'=>'',
                    'orgCode'=>$this->sid,
                    'phone'=>'',
                    'custType'=>'1',
                    'custState'=>'1', 
                ];
                $jsonInfo=$this->getPostInfo($send_info);
            }
          
        }
    }
    protected function PostCurl($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置header
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($data)));
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

    protected function getPostInfo($arrInfo){
        $url="http://60.205.148.191:8080/yqsh_third/api/onecard/message";
        //申请商户的时候生成的key
        $key="44EC6C5C17BA74D1759AB0AEB782E4EE";
        //第三方代码
        $thirdCode='510102180306020203';
        $arrInfo['thirdCode']=$thirdCode;
        $new_array=$arrInfo;
        if($new_array['name']!=''){
            unset($new_array['name']);
        }
        $mac=strtoupper(md5(implode('',$new_array).$key));          
        $arrInfo['mac']=$mac;       
        $data=json_encode($arrInfo , JSON_UNESCAPED_UNICODE);
         // return $data;
        $result=$this->PostCurl($url,$data);
        return $result;
    }
}
