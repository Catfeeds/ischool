<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WpIschoolStudent;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * WpIschoolStudentSearch represents the model behind the search form about `backend\models\WpIschoolStudent`.
 */
class WpIschoolStudentSearch extends WpIschoolStudent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ctime', 'cid', 'type', 'sid', 'LastTime', 'LastStatus', 'upendtime', 'upendtimejx'], 'integer'],
            [['name', 'stuno2', 'sex', 'school', 'class', 'address', 'cardid', 'stuno', 'outType', 'carCode','card_no','enddatepa','enddatejx','enddateqq','enddateck','img'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
//
    public function batchUpdate($params,$enddatepa,$enddateqq,$enddatejx,$enddateck)
    {
    	$query = WpIschoolStudent::find();
    	$this->load($params);
    	if (!$this->validate()) {
    		return false;
    	}

    	$query->andFilterWhere([
    			'id' => $this->id,
    			'stuno2' => $this->stuno2,
    			'cardid' => $this->cardid,
    			'stuno' => $this->stuno,
                "FROM_UNIXTIME(enddatepa, '%Y-%m-%d' )" => $this->enddatepa,
                "FROM_UNIXTIME(enddateqq, '%Y-%m-%d' )" => $this->enddateqq,
                "FROM_UNIXTIME(enddatejx, '%Y-%m-%d' )" => $this->enddatejx,
                "FROM_UNIXTIME(enddateck, '%Y-%m-%d' )" => $this->enddateck,
    	]);
    	
    	$query->andFilterWhere(['like', 'name', $this->name])
    	->andFilterWhere(['like', 'school', $this->school])
    	->andFilterWhere(['like', 'class', $this->class]);
//    	$query->select("id");
        $all_ids = $query->asArray()->all();
    	$format_ids = ArrayHelper::getColumn($all_ids, "id");

//        WpIschoolStudent::updateAll(['enddatepa'=>strtotime($enddatepa)+86399,'enddateqq'=>strtotime($enddateqq)+86399,'enddatejx'=>strtotime($enddatejx)+86399,'enddateck'=>strtotime($enddateck)+86399],["id"=>$all_ids]);

        $update_data = [];
        if($enddatejx)
        {
            $update_data =array_merge($update_data,['enddatejx'=>strtotime($enddatejx)+86399,'upendtimejx' => time()]);
        }
        if($enddatepa){
            $update_data =array_merge($update_data,['enddatepa'=>strtotime($enddatepa)+86399,'upendtimepa' => time()]);
        }
        if($enddateqq){
            $update_data =array_merge($update_data,['enddateqq'=>strtotime($enddateqq)+86399,'upendtimeqq' => time()]);
        }
        if($enddateck){
            $update_data =array_merge($update_data,['enddateck'=>strtotime($enddateck)+86399,'upendtimeck' => time()]);
        }

    	WpIschoolStudent::updateAll($update_data,["id"=>$all_ids]);


        if(($enddatepa != "") || ($enddateqq != "") || ($enddatejx != "") || ($enddateck != "")){
            foreach($all_ids as $key=>$val){
                $orderSd = new WpIschoolOrder();
                $orderSd->openid = "sdtj".$val['id'];
                $orderSd->trade_no  = $val['id'].time();
    //            $orderSd->trade_name = $this->school."|".$this->class."|".$this->name."|".$key;
                $orderSd->trade_name = $val['school']."|".$val['class']."|".$val['name']."|".$val['id'];
                $orderSd->paytype ="SDTJ";
                $orderSd->ispasspa = ($enddatepa != "")?1:0;
                $orderSd->ispassqq = ($enddateqq != "")?1:0;
                $orderSd->ispassjx = ($enddatejx != "")?1:0;
                $orderSd->ispassck = ($enddateck != "")?1:0;
                $orderSd->ctime =time();
                $orderSd->utime =time();
                $orderSd->zfopenid ="sdtj".$val['id'];
                $orderSd->stuid =$val['id'];
                $orderSd->trans_id ="100".time().rand(100,999);
                $orderSd->save();
            }
        }
        return true;

    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        // $query = WpIschoolStudent::find()->joinWith("card")->where(['is_deleted'=>0])->orderBy('id asc');
        $query = WpIschoolStudent::find()->joinWith("card")->where(['is_deleted'=>0]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'wp_ischool_student.id' => $this->id,
            'ctime' => $this->ctime,
            'cid' => $this->cid,
            'type' => $this->type,
            'sid' => $this->sid,
            'LastTime' => $this->LastTime,
            'LastStatus' => $this->LastStatus,
            "FROM_UNIXTIME(enddatepa, '%Y-%m-%d' )" => $this->enddatepa,
            'upendtimepa' => $this->upendtimepa,
            "FROM_UNIXTIME(enddateqq, '%Y-%m-%d' )" => $this->enddateqq,
            'upendtimeqq' => $this->upendtimeqq,
            "FROM_UNIXTIME(enddatejx, '%Y-%m-%d' )" => $this->enddatejx,
            'upendtimejx' => $this->upendtimejx,
            "FROM_UNIXTIME(enddateck, '%Y-%m-%d' )" => $this->enddateck,
            'upendtimeck' => $this->upendtimeck,
            'wp_ischool_student_card.card_no'=>$this->card_no
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'stuno2', $this->stuno2])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'school', $this->school])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'stuno', $this->stuno])
            ->andFilterWhere(['like', 'outType', $this->outType])
            ->andFilterWhere(['like', 'carCode', $this->carCode]);
        return $dataProvider;
    }
}
