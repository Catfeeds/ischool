<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WpIschoolSchool;

/**
 * WpIschoolSchoolSearch represents the model behind the search form about `backend\models\WpIschoolSchool`.
 */
class WpIschoolSchoolSearch extends WpIschoolSchool
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ctime'], 'integer'],
            [['name', 'pro', 'city', 'county', 'address', 'jcname', 'schtype', 'pic', 'ispass'], 'safe'],
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
        // $query = WpIschoolSchool::find()->where(['is_deleted'=>0])->orderBy('id desc');
        $query = WpIschoolSchool::find()->orderBy('id desc');
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'pro', $this->pro])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'county', $this->county])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'jcname', $this->jcname])
            ->andFilterWhere(['like', 'schtype', $this->schtype])
            ->andFilterWhere(['like', 'pic', $this->pic])
            ->andFilterWhere(['like', 'ispass', $this->ispass]);
        return $dataProvider;
    }
}
