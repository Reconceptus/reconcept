<?php

namespace modules\portfolio\models;

/**
 * This is the model class for table "portfolio_hidden_tag".
 *
 * @property int $id
 * @property string $name
 *
 * @property PortfolioHiddenTag[] $portfolioHiddenTags
 */
class HiddenTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'portfolio_hidden_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 150],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPortfolioHiddenTags()
    {
        return $this->hasMany(PortfolioHiddenTag::className(), ['tag_id' => 'id']);
    }
}
