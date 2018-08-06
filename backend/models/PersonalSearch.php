<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WpIschoolPastudent;

/**
 * PersonalSearch represents the model behind the search form about `backend\models\WpIschoolPastudent`.
 */
class PersonalSearch extends WpIschoolPastudent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ctime', 'stu_id', 'cid', 'sid', 'isqqtel', 'is_deleted'], 'integer'],
            [['name', 'openid', 'school', 'class', 'tel', 'stu_name', 'ispass', 'email', 'Relation'], 'safe'],
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
        $query = WpIschoolPastudent::find();

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
            'ctime' => $this->ctime,
            'stu_id' => $this->stu_id,
            'cid' => $this->cid,
            'sid' => $this->sid,
            'isqqtel' => $this->isqqtel,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'school', $this->school])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'stu_name', $this->stu_name])
            ->andFilterWhere(['like', 'ispass', $this->ispass])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'Relation', $this->Relation]);

        return $dataProvider;
    }
}
