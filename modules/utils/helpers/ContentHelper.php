<?php
/**
 * Created by PhpStorm.
 * User: venodon
 * Date: 21.01.2019
 * Time: 12:32
 */

namespace modules\utils\helpers;


use modules\blog\models\Post;
use modules\utils\models\UtilsBlock;
use modules\utils\models\UtilsGallery;
use modules\utils\models\UtilsLayout;
use yii\helpers\ArrayHelper;

class ContentHelper
{
    /**
     * @param $content
     * @return string|string[]|null
     */
    public static function parseGallery($content)
    {
        if ($content) {
            $pattern = '/{{(.*?)}}/';
            $result = preg_replace_callback($pattern,
                "self::gallery_replace",
                $content);
            return $result;
        }
        return null;
    }

    /**
     * @param $matches
     * @return string
     */
    public static function gallery_replace($matches)
    {
        $block = explode(';', $matches[1]);
        $galCode = mb_strtolower(trim($block[0]));
        if (empty($block[1])) {
            $layCode = null;
        } else {
            $layCode = mb_strtolower(trim($block[1]));
        }
        $gallery = UtilsGallery::findOne(['code' => $galCode]);
        /* @var $gallery UtilsGallery */
        if ($gallery) {
            $images = $gallery->images;
            $resultString = '';
            if ($images) {
                if ($layCode) {
                    $layout = UtilsLayout::findOne(['code' => $layCode]);
                }
                if (empty($layout)) {
                    $layout = $gallery->layout;
                }
                if (!$layout) {
                    $layout = UtilsLayout::find()->all()[0];
                }
                $itemsContent = '';
                foreach ($images as $image) {
                    $itemsContent .= str_replace('%item%', $image->image, $layout->item);
                }
                $resultString = str_replace('%items%', $itemsContent, $layout->layout);
            }
            if (!empty($block[2])) {
                $paramsArr = explode(',', $block[2]);
                $paramsArr = ArrayHelper::map($paramsArr, function ($elem) {
                    return '#' . trim(explode('=', $elem)[0]) . '#';
                }, function ($elem) {
                    return trim(explode('=', $elem)[1]);
                });
                $resultString = str_replace(array_keys($paramsArr), array_values($paramsArr), $resultString);
            }
            return $resultString;
        }
    }

    /**
     * @param $content
     * @return string|string[]|null
     */
    public static function parseBlock($content)
    {
        if ($content) {
            $pattern = '/\[(.*?)\]/';
            $result = preg_replace_callback($pattern,
                "self::block_replace",
                $content);
            return $result;
        }
        return null;
    }

    /**
     * @param $matches
     * @return string
     */
    public static function block_replace($matches)
    {
        $data = explode(',', $matches[1]);
        $blockCode = mb_strtolower(trim($data[0]));
        $type = empty($data[1]) ? 'form' : mb_strtolower(trim($data[1]));
        $block = UtilsBlock::findOne(['slug' => $blockCode, 'type' => $type]);
        $resultString = '';
        if ($block) {
            $resultString = $block->content;
        }
        return $resultString;
    }

    /**
     * @param $content
     * @return string|string[]|null
     */
    public static function parseLink($content)
    {
        if ($content) {
            $pattern = '/\*(.*?)\*/';
            $result = preg_replace_callback($pattern,
                "self::link_replace",
                $content);
            return $result;
        }
        return null;
    }

    /**
     * @param $matches
     * @return mixed|string
     */
    public static function link_replace($matches)
    {
        $data = explode(',', $matches[1]);
        $postId = mb_strtolower(trim($data[0]));
        $post = Post::find()->select(['slug', 'name'])->where(['id' => intval($postId)])->one();
        $form = UtilsBlock::findOne(['slug' => 'left-link']);
        if ($form && $post) {
            $result = str_replace(['%%', '##'], [$post->name, '/blog/' . $post->slug], $form->content);
        } else {
            $result = '';
        }
        return $result;
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