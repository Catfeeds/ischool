<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WpIschoolOrder;

/**
 * WpIschoolOrderSearch represents the model behind the search form about `backend\models\WpIschoolOrder`.
 */
class WpIschoolOrderSearch extends WpIschoolOrder
{
    /**
     * @inheritdoc
     */
	//$school,$class,$name,$stuno2,$enddate;
    public function rules()
    {
        return [
            [['id', 'ispass', 'ctime', 'utime', 'stuid'], 'integer'],
            [['openid', 'trade_no', 'trade_name', 'paytype', 'zfopenid', 'trans_id'], 'safe'],
            [['money'], 'number'],
        	[['school','class','name','stuno2','enddate'],'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WpIschoolOrder::find()->joinWith("studentinfo")
        ->where("stuid > 0")
        ->orderBy('ctime desc');
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'money' => $this->money,
            'ispass' => $this->ispass,
            'ctime' => $this->ctime,
            'utime' => $this->utime,
            'stuid' => $this->stuid,
        	'wp_ischool_student.stuno2'=>$this->stuno2
        ]);
		//school','class','name','stuno2','enddate
        $query
        	->andFilterWhere(['like', 'wp_ischool_student.school', $this->school])
        	->andFilterWhere(['like', 'wp_ischool_student.class', $this->class])
        	->andFilterWhere(['like', 'wp_ischool_student.name', $this->name])
        	->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'trade_no', $this->trade_no])
            ->andFilterWhere(['like', 'trade_name', $this->trade_name])
            ->andFilterWhere(['like', 'paytype', $this->paytype])
            ->andFilterWhere(['like', 'zfopenid', $this->zfopenid])
            ->andFilterWhere(['like', 'trans_id', $this->trans_id]);

        return $dataProvider;
    }
}
