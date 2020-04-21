<?php

namespace modules\feedback\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SupportSearch represents the model behind the search form of `modules\feedback\models\Support`.
 */
class SupportSearch extends Support
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'email', 'phone', 'contact', 'message', 'answer'], 'safe'],
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
     * @param  array  $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Support::find()->where(['!=', 'status', Support::STATUS_DELETE]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'     => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'answer', $this->answer])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
