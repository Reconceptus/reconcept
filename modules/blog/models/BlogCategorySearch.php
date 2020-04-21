<?php

namespace modules\blog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BlogCategorySearch represents the model behind the search form of `modules\blog\models\BlogCategory`.
 */
class BlogCategorySearch extends BlogCategory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'sort', 'lft', 'rgt', 'depth'], 'integer'],
            [['name', 'slug', 'description', 'image', 'seo_title', 'seo_description'], 'safe'],
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
        $query = BlogCategory::find()->where(['>', 'depth', 0]);

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
            'id'     => $this->id,
            'status' => $this->status,
            'sort'   => $this->sort,
            'lft'    => $this->lft,
            'rgt'    => $this->rgt,
            'depth'  => $this->depth,
        ]);

        $query->andFilterWhere(['!=', 'status', 2]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'seo_title', $this->seo_title])
            ->andFilterWhere(['like', 'seo_description', $this->seo_description]);

        return $dataProvider;
    }
}
