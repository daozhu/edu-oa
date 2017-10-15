<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StuScore;
use yii\helpers\HtmlPurifier;

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
            [['id', 'age', 'batch', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['stu_name', 'mobile', 'sex', 'grade', 'school', 'subject', 'batch_name', 'export_file'], 'safe'],
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
    public function search_old($params)
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
            'batch' => $this->batch,
            'score' => $this->score,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'stu_name', $this->stu_name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'grade', $this->grade])
            ->andFilterWhere(['like', 'school', $this->school])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'batch_name', $this->batch_name])
            ->andFilterWhere(['like', 'export_file', $this->export_file]);

        return $dataProvider;
    }

    public function findData($param)
    {
        //...
        $tel = $param['tel'] ?? '';
        $name = $param['name'] ?? '';
        $grade = $param['grade'] ?? '';

        $query = StuScore::find()->select([
            'stu_name','mobile','grade', 'school', 'subject','batch','batch_name',
            'score'
        ]);
        if (!empty($tel)) {
            $query->andWhere([
                'mobile' => HtmlPurifier::process($tel),
            ]);
        }
        if (!empty($tel)) {
            $query->andFilterWhere(['like', 'stu_name', HtmlPurifier::process($name),]);
        }
        if (!empty($tel)) {
            $query->andFilterWhere(['like', 'grade', HtmlPurifier::process($grade),]);
        }

        $ret = $query->orderBy([
            'batch' => SORT_DESC,
            'id'    => SORT_ASC
        ])->limit(30)->asArray()->all();

        if (empty($ret)) return ['code' => 900];

        $data = [];
        foreach($ret as $v) {
            $data[$v['batch']][] = $v;
        }

        $data = array_shift($data);

        $ret = [];
        foreach($data as $v) {
            $ret['info'] = [
                'stu_name' => $v['stu_name'],
                'mobile'   => $v['mobile'],
                'grade'    => $v['grade'],
                'school'   => $v['school'],
                'subject'  => $v['subject'],
                'batch'    => $v['batch'],
                'batch_name'=> $v['subject']
            ];
            $ret['data'][] = [
                'subject' => $v['subject'],
                'score'   => $v['score']
            ];
        }

        if(!empty($ret['data'])) {
            $ret['code'] = 200;
        } else {
            $ret['code'] = 900;
        }

        return $ret;
    }
}
