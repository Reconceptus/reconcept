<?php

namespace modules\portfolio\models;

/**
 * This is the ActiveQuery class for [[Portfolio]].
 *
 * @see Portfolio
 */
class PortfolioQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        $this->andWhere(['status' => Portfolio::STATUS_ACTIVE]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @return Portfolio[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Portfolio|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
