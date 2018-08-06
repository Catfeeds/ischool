<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WpIschoolUser;

/**
 * WpIschoolUserSearch represents the model behind the search form about `backend\models\WpIschoolUser`.
 */
class WpIschoolUserSearch extends WpIschoolUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'last_sid', 'ctime', 'score', 'level', 'last_login_time', 'last_stuid', 'last_cid', 'login_time'], 'integer'],
            [['name', 'tel', 'openid', 'pwd', 'login_ip', 'last_login_ip', 'shenfen'], 'safe'],
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
        $query = WpIschoolUser::find();

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
            'last_sid' => $this->last_sid,
            'ctime' => $this->ctime,
            'score' => $this->score,
            'level' => $this->level,
            'last_login_time' => $this->last_login_time,
            'last_stuid' => $this->last_stuid,
            'last_cid' => $this->last_cid,
            'login_time' => $this->login_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'pwd', $this->pwd])
            ->andFilterWhere(['like', 'login_ip', $this->login_ip])
            ->andFilterWhere(['like', 'last_login_ip', $this->last_login_ip])
            ->andFilterWhere(['like', 'shenfen', $this->shenfen]);

        return $dataProvider;
    }
}
