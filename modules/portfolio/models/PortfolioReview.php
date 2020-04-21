<?php

namespace modules\portfolio\models;

/**
 * This is the model class for table "portfolio_review".
 *
 * @property int $id
 * @property int $portfolio_id
 * @property string $fio
 * @property string $position
 * @property string $image
 * @property string $text
 * @property int $to_main
 *
 * @property Portfolio $portfolio
 */
class PortfolioReview extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'portfolio_review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['portfolio_id', 'to_main'], 'integer'],
            [['text'], 'string'],
            [['fio', 'position', 'image'], 'string', 'max' => 255],
            [['portfolio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Portfolio::className(), 'targetAttribute' => ['portfolio_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'portfolio_id' => 'Портфолио',
            'fio'          => 'ФИО',
            'position'     => 'Должность',
            'image'        => 'Фото',
            'text'         => 'Отзыв',
            'to_main'      => 'На главной',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPortfolio()
    {
        return $this->hasOne(Portfolio::className(), ['id' => 'portfolio_id']);
    }
}
