<?php

namespace modules\feedback\models;

/**
 * This is the ActiveQuery class for [[Support]].
 *
 * @see Support
 */
class SupportQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Support[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Support|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
