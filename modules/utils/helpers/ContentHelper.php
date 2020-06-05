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
}