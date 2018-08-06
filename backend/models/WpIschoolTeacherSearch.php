<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WpIschoolTeacher;

/**
 * WpIschoolTeacherSearch represents the model behind the search form about `backend\models\WpIschoolTeacher`.
 */
class WpIschoolTeacherSearch extends WpIschoolTeacher
{
    /**
     * @inheritdoc
     */
    
    public function rules()
    {
        return [
            [['id', 'sid', 'ctime'], 'integer'],
            [['tname', 'school', 'tel', 'openid', 'ispass', 'epc','cid','class'], 'safe'],
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
        // 老师对应多个班级的情况下显示的时候会显示错误
//        $query = WpIschoolTeacher::find()->joinWith(["classes"])->where(['is_deleted'=>0]);
        $query = WpIschoolTeaclass::find();
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
            'wp_ischool_teaclass.sid' => $this->sid,
            'ctime' => $this->ctime,
            'wp_ischool_teaclass.cid' => $this->cid
        ]);

        $query
            ->andFilterWhere(['like', 'wp_ischool_teaclass.class', $this->class])
            ->andFilterWhere(['like', 'wp_ischool_teaclass.tname', $this->tname])
            ->andFilterWhere(['like', 'wp_ischool_teaclass.school', $this->school])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'ispass', $this->ispass])
            ->andFilterWhere(['like', 'epc', $this->epc]);

        return $dataProvider;
    }
}
