<?php

namespace modules\portfolio\models;

/**
 * This is the model class for table "portfolio_portfolio_hidden_tag".
 *
 * @property string $id
 * @property int $portfolio_id
 * @property int $tag_id
 *
 * @property Portfolio $portfolio
 * @property HiddenTag $tag
 */
class PortfolioHiddenTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'portfolio_portfolio_hidden_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['portfolio_id', 'tag_id'], 'integer'],
            [['portfolio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Portfolio::className(), 'targetAttribute' => ['portfolio_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => HiddenTag::className(), 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'portfolio_id' => 'Portfolio ID',
            'tag_id' => 'Tag ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPortfolio()
    {
        return $this->hasOne(Portfolio::className(), ['id' => 'portfolio_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(HiddenTag::className(), ['id' => 'tag_id']);
    }
}
