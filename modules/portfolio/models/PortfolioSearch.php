<?php

namespace modules\portfolio\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PortfolioSearch represents the model behind the search form of `modules\portfolio\models\Portfolio`.
 */
class PortfolioSearch extends Portfolio
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'author_id', 'to_main', 'to_footer', 'status', 'views'], 'integer'],
            [['name', 'slug', 'full_name', 'alt', 'url', 'image', 'horizontal_preview', 'vertical_preview', 'content', 'seo_title', 'seo_description'], 'safe'],
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
        $query = Portfolio::find()->where(['!=', 'status', Portfolio::STATUS_DELETED]);

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
            'id'        => $this->id,
            'author_id' => $this->author_id,
            'to_main'   => $this->to_main,
            'to_footer' => $this->to_footer,
            'status'    => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
//            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'alt', $this->alt])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'horizontal_preview', $this->horizontal_preview])
            ->andFilterWhere(['like', 'vertical_preview', $this->vertical_preview])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'seo_title', $this->seo_title])
            ->andFilterWhere(['like', 'seo_description', $this->seo_description]);

        return $dataProvider;
    }
}
