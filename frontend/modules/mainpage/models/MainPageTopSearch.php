<?php

namespace frontend\modules\mainpage\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MainPageTopSearch represents the model behind the search form of `frontend\modules\mainpage\models\MainPageTop`.
 */
class MainPageTopSearch extends MainPageTop
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['image', 'image_preview', 'quote', 'sign'], 'safe'],
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
        $query = MainPageTop::find();

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
        ]);

        $query->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'image_preview', $this->image_preview])
            ->andFilterWhere(['like', 'quote', $this->quote])
            ->andFilterWhere(['like', 'sign', $this->sign]);

        return $dataProvider;
    }
}
