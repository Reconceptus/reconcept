<?php

namespace modules\services\models;

/**
 * This is the ActiveQuery class for [[Post]].
 *
 * @see Service
 */
class ServiceQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        $this->andWhere(['status' => Service::STATUS_ACTIVE]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @return Service[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Service|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
