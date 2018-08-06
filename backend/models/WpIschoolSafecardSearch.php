<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WpIschoolSafecard;

/**
 * WpIschoolSafecardSearch represents the model behind the search form about `backend\models\WpIschoolSafecard`.
 */
class WpIschoolSafecardSearch extends WpIschoolSafecard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'stuid', 'ctime', 'yearmonth', 'yearweek', 'weekday', 'receivetime'], 'integer'],
            [['info'], 'safe'],
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
        $query = WpIschoolSafecard::find();

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
            'stuid' => $this->stuid,
            'ctime' => $this->ctime,
            'yearmonth' => $this->yearmonth,
            'yearweek' => $this->yearweek,
            'weekday' => $this->weekday,
            'receivetime' => $this->receivetime,
        ]);

        $query->andFilterWhere(['like', 'info', $this->info]);
$query->orderBy("id desc");
        return $dataProvider;
    }
}
