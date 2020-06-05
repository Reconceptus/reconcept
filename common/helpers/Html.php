<?php


namespace common\helpers;

use common\models\Currency;
use Yii;
use yii\helpers\Url;

class Html extends \yii\helpers\Html
{
    /**
     * @param  array|null  $array
     * @param  string  $key
     * @return mixed|null
     */
    public static function take($array, string $key)
    {
        if (is_array($array)) {
            if (!empty($array[$key])) {
                return $array[$key];
            }
        }
        return null;
    }

    /**
     * @param  array  $array
     * @param  string  $key
     * @return string|null
     */
    public static function eTake(?array $array, string $key): ?string
    {
        if (!empty($array[$key])) {
            return self::encode((string) $array[$key]);
        }
        return '';
    }

    /**
     * @param  array|string  $src
     * @param  array  $options
     * @return string
     */
    public static function image($src, $options = []): string
    {
        $options['src'] = Url::to(Yii::$app->params['front'].$src);
        if (isset($options['srcset']) && is_array($options['srcset'])) {
            $srcset = [];
            foreach ($options['srcset'] as $descriptor => $url) {
                $srcset[] = Url::to($url).' '.$descriptor;
            }
            $options['srcset'] = implode(',', $srcset);
        }
        if (!isset($options['alt'])) {
            $options['alt'] = '';
        }
        return static::tag('img', '', $options);
    }

    /**
     * @param $text
     * @return string|string[]|null
     */
    public static function makeTypo($text)
    {
//        $result = preg_replace_callback(
//            '#(([\"]{2,})|(?![^\W])(\"))|([^\s][\"]+(?![\w]))#u',
//            function ($matches) {
//                if (count($matches) === 3) return "«»";
//                else if ($matches[1]) return str_replace('"', "«", $matches[1]);
//                else return str_replace('"', "»", $matches[4]);
//            },
//            $text
//        );
        return str_replace(' - ', ' — ', $text);
    }
}