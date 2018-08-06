<?php
namespace kefu\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use mobile\models\WpIschoolSchool;
use yii\data\Pagination;
use kefu\models\ZfCardInfo;
use kefu\models\ZfDealDetail;
use kefu\models\ImportData;
use yii\web\UploadedFile;
use kefu\models\WpIschoolClass;
/**
 * Customer controller
 */
class CustomerController extends Controller {
   private $source_data;
   private $export_data;
	 public $enableCsrfValidation = false;
   //存取控制权限
   public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['qcstatus','upclass'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['qcstatus', 'upclass'],
                        'roles' => ['@'],
                    ],
               
                ],
            ],
        ];
    }

	//圈存状态查询
    public function actionQcstatus(){
	      if(\yii::$app->request->isPost){
	        $trade_no=\yii::$app->request->post('trade_no');       
	        $pdo = $this->getdb();
          $sql="select * from zf_recharge_detail as a inner join zf_card_info as b on a.card_no=b.card_no and a.school_id=b.school_id where a.trade_no = :trade_no";
          $stmt=$pdo->prepare($sql);
          $stmt->bindParam(':trade_no', $trade_no);
	        if($stmt->execute()){
	          $row=$stmt->fetch();
	          if($row['school_id']==56651){
	            $sid=56650;
	          }else{
	            $sid=$row['school_id'];
	          }
	          //查学校
	         $school= WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->one();
	         $row['school_name']=$school['name'];


	        }else{
	          $row=0;
	        }
	        $pdo->null;
	        $return_arr['result']=$row;
	      }
	      return $this->render('qcstatus',$return_arr);
    }
    //改圈存状态
    public function actionEditqc(){
          $danhao=\yii::$app->request->post('danhao');
          $sql="update zf_recharge_detail set balance=0,is_active=0,qctime=0 where trade_no = ?";
          $pdo = $this->getdb();
          $stmt = $pdo->prepare($sql);          
          if($stmt->execute(array($danhao))){
            $a=$danhao;
          }else{
            $a=0;
          }
          $pdo->null;
           return json_encode($a);
     }
    public function actionSearchConsume(){
        // var_dump(\yii::$app->request->post());die;
          $post=\yii::$app->request->post();
         
          $starttime=strtotime($post['starttime']);
          $endtime=strtotime($post['endtime'])+86399;
          try{
              $pdo = $this->getdb();
             
              if(empty($post['stuname']) || empty($post['stuno2']) ){
              	    $sql="select * from zf_deal_detail where card_no = ? and school_id = ? and created < ? and created > ? order by created desc";
		            $stmt = $pdo->prepare($sql);
		            $stmt->execute([$post['card_no'],$post['school_id'],$endtime,$starttime]); 
		            yii::trace($sql);
                    $result=$stmt->fetchAll(\PDO::FETCH_ASSOC);             		
          	  }else{
          	  		if(preg_match('/[a-zA-Z]/',$post['stuno2'])){
		               $user_no=substr($post['stuno2'],6);
		            }else{
		               $user_no=substr($post['stuno2'],2);
		            }
		            // echo $user_no;die;
		            $stuname=$post['stuname'];
	             //    $sql = "select a.name,a.card_no,b.amount,b.balance,b.created  from card.zf_recharge_detail as a LEFT JOIN card.zf_deal_detail as b ON ".
	             //    "  a.school_id=b.school_id AND a.card_no=b.card_no WHERE  b.created BETWEEN $starttime AND $endtime AND a.name=$stuname ";       
              //       $array = Yii::$app->db->createCommand($sql)->queryAll();
                    $sql="SELECT a.amount,a.balance,a.created,a.pos_sn,a.school_id,b.user_name,b.card_no,b.phyid from zf_deal_detail as a left JOIN zf_card_info as b on a.card_no = b.card_no and a.school_id=b.school_id where    b.user_no = ? and b.user_name = ? and a.created < ? and a.created > ? order by a.created desc";
		            $stmt =$pdo->prepare($sql);
		            $stmt->execute(array($user_no,$stuname,$endtime,$starttime));
		            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);             
          	  }
             
              $pdo->null;
          } catch (PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();        
          }
         
          if($result){
              if($result[0]['school_id']==56651){
                $sid=56650;
              }else{
                $sid=$result[0]['school_id'];
              }
              $school= WpIschoolSchool::find()->select('name')->where(['id'=>$sid])->asArray()->one();
             
              foreach($result as &$v){                      
                      $v['time'] = date("Y-m-d H:i:s",$v['created']); 
                      $v['school_name']=$school['name'];                    
              }
              $result = array("flag"=>0,"ckshuju"=>$result);
          }else{
              $result = array("flag"=>1);
          }
          return json_encode($result);
    }
    //调整班级
    public function actionUpclass(){
      if (\Yii::$app->request->isPost) {
         $this->initExcel();
         $post= yii::$app->request->post();
         $i=0;
         foreach($this->source_data as $k=>$v){
              $na=WpIschoolClass::find()->select('name')->where(['id'=>$v['B']])->asArray()->one();
              if($na){
              
                // $sql="update wp_ischool_student set class='".$na['name']."',cid=".$v['B']."  where name = '".$v['A']."' and sid ='".$post['schoolid']."' and class like '".$post['level']."%'";
                // $res =Yii::$app->db->createCommand($sql)->execute();
                 $sql="update wp_ischool_student set class=:class,cid=:cid  where name = :name and sid =:sid and class like :level";
                $res =Yii::$app->db->createCommand($sql,[':class'=>$na['name'],':cid'=>$v['B'],':name'=>$v['A'],':sid'=>$post['schoolid'],':level'=>$post['level']])->execute();             
                if($res){
                  $i++;
                }
              }
                  
        }
        echo "修改条数为：".$i."</br>";
        echo "<pre>";

        
      }      
      return $this->render('upclass');
    }
    //查询消费
    public function actionConsum(){
    	if (\Yii::$app->request->isPost) {
    		$stuno2=yii::$app->request->post['stuno2'];
            if(preg_match('/[a-zA-Z]/',$stuno2)){
               $user_no=substr($user_no,6);
            }else{
               $user_no=substr($user_no,2);
            }

    		$query = ZfDealDetail::find();
    		$query->where(['user_no'=>$user_no,'school_id'=>$school_id]);
    		$query->asArray();
    		$result=$query->all();
    	}
		return $this->render("consum",$return_arr);
    }
    protected function getdb(){
	      try{
	         $pdo=new \PDO('mysql:host=127.0.0.1;dbname=card','root','hnzf123456');
	         $pdo->query("set character set 'utf8'");
	      }catch (PDOException $e) {
	         print "Error!: " . $e->getMessage() . "<br/>";
	         die();        
	      }   
	      return $pdo;
    }
    private function initExcel() {
      if (\Yii::$app->request->isPost) {
          $model = new ImportData();         
          $model->upload =UploadedFile::getInstance($model, 'upload');                    
          if ($model->validate()) {                                   
               $data = \moonland\phpexcel\Excel::widget([
                  'mode' => 'import',
                  'fileName' => $model->upload->tempName,
                  'setFirstRecordAsKeys' => false,
                  'setIndexSheetByName' => false,

              ]);
              $data = isset($data[0])?$data[0]:$data;
               // var_dump($data);die;
              if(count($data) > 1)
              {
                $this->source_data=$data;                                        
              }
              
          }
      }else{
          return false;
      }
   }
}
