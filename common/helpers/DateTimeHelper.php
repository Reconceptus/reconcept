<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 30.08.2018
 * Time: 15:24
 */

namespace common\helpers;


class DateTimeHelper
{
    const months = [
        1  => 'января',
        2  => 'февраля',
        3  => 'марта',
        4  => 'апреля',
        5  => 'мая',
        6  => 'июня',
        7  => 'июля',
        8  => 'августа',
        9  => 'сентября',
        10 => 'октября',
        11 => 'ноября',
        12 => 'февраля',
    ];

    public static function getDateRuFormat($date)
    {
        $date = strtotime($date);
        return date('d ' . self::months[date('n', $date)] . ' Y', $date);
    }

    public static function getDateTimeRuFormat($date)
    {
        $date = strtotime($date);
        return date('d ' . self::months[date('n', $date)] . ' Y, H:i', $date);
    }
}