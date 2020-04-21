<?php
/**
 * Created by PhpStorm.
 * User: adm
 * Date: 10.12.2018
 * Time: 12:27
 */

namespace common\helpers;


class StringHelper
{
    /**
     * @param $st
     * @param bool $toLower
     * @return false|mixed|string|string[]|null
     */
    public static function translitString($st, $toLower = true)
    {
        $translit = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'yo', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'j', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'x', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh',
            'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'YO', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'J', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'X', 'Ц' => 'C',
            'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SHH',
            'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA',
            ' ' => '_', '-' => '_'
        );

        $str = strtr($st, $translit);
        $result = preg_replace("/[^a-zA-Z0-9\_]/", "", $str);
        return $toLower ? mb_strtolower($result) : $result;
    }

    /**
     * @param string $string Строка
     * @param int $num
     * @return string
     */
    public static function truncateWords(string $string, int $num)
    {
        $workString = mb_substr($string, 0, $num * 15);
        $wordsArr = explode(' ', $workString);
        $slicedArr = array_slice($wordsArr, 0, $num);
        $string = trim(implode(' ', $slicedArr));
        if (count($wordsArr) > count($slicedArr)) $string .= '...';
        return $string;
    }
}