<?php

namespace modules\position\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use modules\position\models\PositionLog;

/**
 * PositionLogSearch represents the model behind the search form of `modules\position\models\PositionLog`.
 */
class PositionLogSearch extends PositionLog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'request_id', 'position', 'depth', 'status'], 'integer'],
            [['query', 'domain', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = PositionLog::find();

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
            'request_id' => $this->request_id,
            'position' => $this->position,
            'depth' => $this->depth,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'query', $this->query])
            ->andFilterWhere(['like', 'domain', $this->domain]);

        return $dataProvider;
    }
}
