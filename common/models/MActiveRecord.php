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
        if ($this->hasAttribute('created_at')) {
            if ($this->isNewRecord && !$this->created_at) {
                $this->created_at = $time;
            }
        }
        if ($this->hasAttribute('updated_at')) {
            $this->updated_at = $time;
            if ($this->errors) {
                \Yii::info($this->getErrorSummary(false)[0]);
            }
        }
        return parent::beforeSave($insert);
    }
}