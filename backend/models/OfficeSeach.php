<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Office;

/**
 * OfficeSeach represents the model behind the search form of `common\models\Office`.
 */
class OfficeSeach extends Office
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'op_user', 'status', 'created_at', 'updated_at'], 'integer'],
            [['file', 'name', 'type'], 'safe'],
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
        $query = Office::find();

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
            'op_user' => $this->op_user,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
