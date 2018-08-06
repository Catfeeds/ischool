<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WpIschoolOrderbk;

/**
 * WpIschoolOrderckSearch represents the model behind the search form about `backend\models\WpIschoolOrderck`.
 */
class WpIschoolOrderbkSearch extends WpIschoolOrderbk
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ispass', 'ctime', 'utime'], 'integer'],
            [['openid', 'trade_no', 'trade_name', 'paytype', 'zfopenid', 'stuid', 'trans_id','sfbk'], 'safe'],
            [['money'], 'number'],
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

//        $query = WpIschoolOrderbk::find()->where('sfbk = 1 and ispass = 1');
        $query = WpIschoolOrderbk::find()
            ->where('ispass = 1')
            ->orderBy('utime desc');;

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
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'trade_no', $this->trade_no])
            ->andFilterWhere(['like', 'trade_name', $this->trade_name])
            ->andFilterWhere(['like', 'paytype', $this->paytype])
            ->andFilterWhere(['like', 'zfopenid', $this->zfopenid])
            ->andFilterWhere(['like', 'stuid', $this->stuid])
            ->andFilterWhere(['like', 'trans_id', $this->trans_id])
            ->andFilterWhere(['like', 'sfbk', $this->sfbk]);
        return $dataProvider;
    }
}
