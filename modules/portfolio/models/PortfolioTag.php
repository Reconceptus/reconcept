<?php

namespace modules\portfolio\models;

/**
 * This is the model class for table "portfolio_tag".
 *
 * @property int $id
 * @property string $name
 * @property int $sort
 * @property string $language
 *
 * @property PortfolioPortfolioTag[] $portfolioTags
 * @property Portfolio[] $portfolio
 */
class PortfolioTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'portfolio_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['name'], 'unique'],
            [['language'], 'string', 'max' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'sort' => 'Сортировка',
            'language' => 'Язык',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPortfolioTags()
    {
        return $this->hasMany(PortfolioPortfolioTag::className(), ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPortfolio()
    {
        return $this->hasMany(Portfolio::className(), ['id' => 'portfolio_id'])->via('portfolioTags');
    }
}
