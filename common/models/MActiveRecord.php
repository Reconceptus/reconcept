<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 15.03.2019
 * Time: 14:26
 */

namespace common\models;


class MActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $time = date('Y-m-d H:i:s');
        if ($this->isNewRecord && !$this->created_at) {
            $this->created_at = $time;
        }
        $this->updated_at = $time;
        return parent::beforeSave($insert);
    }
}