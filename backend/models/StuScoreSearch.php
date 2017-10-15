<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StuScore;

/**
 * StuScoreSearch represents the model behind the search form about `common\models\StuScore`.
 */
class StuScoreSearch extends StuScore
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'age','batch', 'status', 'created_at', 'updated_at'], 'integer'],
            [['stu_name', 'mobile', 'sex', 'grade', 'subject', 'batch_name'], 'safe'],
            [['score'], 'number'],
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
        $query = StuScore::find();

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
            'age' => $this->age,
            'score' => $this->score,
            'batch' => $this->batch,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'stu_name', $this->stu_name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'grade', $this->grade])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'batch_name', $this->batch_name]);

        return $dataProvider;
    }
}
