<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
/**
 * This is the model class for table "wp_ischool_worksh".
 *
 * @property integer $id
 * @property string $name
 * @property integer $work_id
 * @property integer $oktime
 * @property integer $reason
 * @property integer $tid
 * @property integer $next_tid
 * @property integer $xuhao
 * @property integer $status
 * @property integer $is_deleted
 * @property integer $tjr_id
 */
class WpIschoolWorksh extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_worksh';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['work_id', 'oktime', 'tid', 'next_tid', 'xuhao', 'status','tjr_id', 'is_deleted'], 'integer'],
            [['name','reason'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'work_id' => 'Work ID',
            'oktime' => 'Oktime',
            'tid' => 'Tid',
            'next_tid' => 'Next Tid',
            'xuhao' => 'Xuhao',
            'status' => 'Status',
            'is_deleted' => 'Is Deleted',
            'reason' => 'reason',
            'tjr_id' => 'tjr_id',
        ];
    }

    public function getWork(){
        return $this->hasOne(WpIschoolWork::className(), ['id' => 'work_id']);
    }

    public function search($sid,$tid,$flag)
    {
        $query = WpIschoolWorksh::find()->joinWith("work")
            ->where("wp_ischool_work.sid =".$sid." and wp_ischool_worksh.tid=".$tid." and wp_ischool_worksh.is_deleted=0 and wp_ischool_worksh.status !=3");
        // add conditions that should always apply here
        if ($flag ==1){
            $model = $query->andFilterWhere(['!=','status',  0]);
        }elseif ($flag ==2){
            $model = $query->andFilterWhere(['status' => 0]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

//        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
/*        $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
            'work_id' => $this->work_id,
            'oktime' => $this->oktime,
            'tid' => $this->tid,
            'next_tid' => $this->next_tid,
            'xuhao' => $this->xuhao,
            'status' => $this->status,
            'is_deleted' => $this->is_deleted,
        ]);*/

        $pagination = new Pagination([
            'defaultPageSize' => 15,
            'totalCount' =>$query->count(),
        ]);
        $model = $query->orderBy('wp_ischool_work.ctime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $res['pages'] = $pagination;
        $res['dataprovider']=$model;
        return $res;
    }


}
