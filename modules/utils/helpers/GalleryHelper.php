<?php

namespace modules\utils\helpers;


use common\helpers\FileHelper;
use common\helpers\ImageHelper;
use modules\utils\models\Gallery;
use common\models\Image;
use modules\config\models\Config;
use modules\utils\models\UtilsGallery;
use modules\utils\models\UtilsLayout;
use Yii;
use yii\helpers\ArrayHelper;

class GalleryHelper
{
    /**CREATING GALLERY**/
    public static function findBlocks($text)
    {
        $blocks = [];
        if ($text) {
            $matchesGallery = [];
            $patternGallery = '/{{(.*?)}}/';
            preg_match_all($patternGallery, $text, $matchesGallery);
            $galleries = ArrayHelper::getValue($matchesGallery, 0);

            foreach ($galleries as $gallery) {
                $blocks[] = ArrayHelper::getValue(explode(';', trim($gallery, "{}\t\n\r\0\x0B")), 0);
            }
        }
        return $blocks;
    }

    public static function processBlocks($text, $directory = null)
    {
        $blocks = self::findBlocks($text);
        foreach ($blocks as $gallery) {
            self::processGallery($gallery, $directory);
        }
    }

    public static function processGallery(string $gallery, $directory = null)
    {
        $galleryObj = UtilsGallery::findOne(['code' => $gallery]);
        if (!$galleryObj) {
            $galleryObj = new UtilsGallery();
            $galleryObj->code = $gallery;
            $galleryObj->name = $gallery;
            $galleryObj->save();
        }
        if (!$directory) {
            $directory = Yii::getAlias('@frontend/web/uploads/temp') . '/' . Yii::$app->session->id . '/' . $gallery . '/';
            if ($directory && is_dir($directory)) {
                $files = FileHelper::findFiles($directory, ['recursive' => false]);
                $moveTo = Yii::getAlias('@images/' . $galleryObj->formName() . '/') . $galleryObj->id . '/';
                foreach ($files as $file) {
                    if ($file && is_file($file)) {
                        $fileName = time() . '_' . Yii::$app->security->generateRandomString(3) . '.jpg';
                        $path = $moveTo . '/' . $fileName;
                        FileHelper::createDirectory($moveTo);
                        if (copy($file, $path)) {
                            $imgModel = new Image();
                            $imgModel->class = $galleryObj->formName();
                            $imgModel->class_full = UtilsGallery::className();
                            $imgModel->item_id = $galleryObj->id;
                            $imgModel->type = Image::TYPE_IMAGE;
                            $imgModel->image = '/uploads/images/' . $galleryObj->formName() . '/' . $galleryObj->id . '/' . $fileName;
                            $imgModel->thumb = ImageHelper::crop($imgModel->image, false, null,
                                Config::getValue('cropPreviewWidth'),
                                Config::getValue('cropPreviewHeight'), false, false, false);
                            if ($imgModel->validate()) {
                                $imgModel->save();
                            }
                        }
                    }
                }
                FileHelper::removeDirectory($directory);
            }
        }
    }
    /** END CREATING GALLERY**/


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
            $layout = null;
            if ($images) {
                if ($layCode) {
                    $layout = UtilsLayout::findOne(['code' => $layCode]);
                }
                if (!$layout) {
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
            if ($gallery->background) {
                $resultString = str_replace('#background#', $gallery->background, $resultString);
            }
            $params = ArrayHelper::getValue($block, 2);
            if ($params) {
                $paramsArr = explode(',', $params);
                $paramsArr = ArrayHelper::map($paramsArr, static function ($elem) {
                    return '#' . trim(ArrayHelper::getValue(explode('=', $elem), 0)) . '#';
                }, static function ($elem) {
                    return trim(ArrayHelper::getValue(explode('=', $elem), 1));
                });
                $resultString = str_replace(array_keys($paramsArr), array_values($paramsArr), $resultString);
            }
            return $resultString;
        }
    }
}