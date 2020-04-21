<?php


namespace common\models;

class Html extends \yii\helpers\Html
{
    /**
     * @param $var
     * @return |null
     */
    public static function take($var)
    {
        return !empty($var) ? $var : null;
    }

    /**
     * @param $var
     * @return |null
     */
    public static function eTake($var)
    {
        return !empty($var) ? Html::encode($var) : null;
    }
}