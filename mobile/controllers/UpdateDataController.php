<?php 
namespace mobile\controllers;

use Yii;
use yii\web\controller;
use mobile\models\ZfCardInfo;
use mobile\models\WpIschoolStudent;
use mobile\models\WpIschoolStudentCard;
use mobile\models\WpIschoolKaku;

class UpdateDataController extends BaseController{
	public function actionUpepctel(){
		$begintime='1528819200';
		$endtime='1529078400';
		$all=ZfCardInfo::find()->where(['between','created',$begintime,$endtime])->asArray()->all();
		echo '餐卡总数：'.count($all).'<br>';
		$i=0;
		foreach ($all as $key => $value) {
			$name=$value['user_name'];
			$user_no=$value['user_no'];
			if(strlen($user_no)==8){
				$stuno2='53'.$user_no;
			}else if(strlen($user_no)==7){
				$stuno2='T56651'.$user_no;
			}
			$res=WpIschoolStudent::find()->where(['stuno2'=>$stuno2])->one();
			if($res){
				$tel=WpIschoolStudentCard::find()->where(['stu_id'=>$res->id])->one();
				if($tel->card_no!=$value['phyid']){					
					if(strlen($value['phyid'])==9){
						$value['phyid']='0'.$value['phyid'];
					}
					$kaku=WpIschoolKaku::find()->where(['telid'=>$value['phyid']])->one();					
					if($kaku){
						$tel->card_no=$value['phyid'];
						$telres=$tel->save(false);						
						$res->cardid=$kaku->epc;
						$epcres=$res->save(false);
						if($telres && $epcres){
							$i++;
						}
						
					}else var_dump($stuno2);
					
				}
				
			}else{
				$result=WpIschoolStudent::find()->where(['stuno2'=>'T56650'.$user_no])->one();
				if($result){
					$tel=WpIschoolStudentCard::find()->where(['stu_id'=>$result->id])->one();
					if($tel->card_no!=$value['phyid']){					
						if(strlen($value['phyid'])==9){
							$value['phyid']='0'.$value['phyid'];
						}
						$kaku=WpIschoolKaku::find()->where(['telid'=>$value['phyid']])->one();					
						if($kaku){
							$tel->card_no=$value['phyid'];
							$telres=$tel->save(false);	
							$result->stuno2='T56651'.$user_no;					
							$result->cardid=$kaku->epc;
							$epcres=$result->save(false);
							if($telres && $epcres){
								$i++;
							}
							
						}else var_dump($stuno2);
						
					}
				} else var_dump($stuno2);
			}
		}
		echo '修改成功数：'.$i.'<br>';
		// echo '<pre>';
		// var_dump($all);
	}
}
