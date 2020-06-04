<?php

namespace common\helpers;

use common\models\Image;
use common\models\MActiveRecord;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Imagine\Image\Point;
use modules\config\models\Config;
use modules\shop\models\Product;
use Yii;
use yii\base\DynamicModel;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\imagine\Image as Img;
use yii\web\UploadedFile;

class ImageHelper
{

    /**
     * @param $model
     * @param $forBackend boolean
     * @param  string  $attribute
     * @return array
     */
    public static function getImageLinks($model, $attribute = 'images', $forBackend = false): array
    {
        $links = ArrayHelper::getColumn($model->$attribute, 'image');
        if ($forBackend) {
            $front = Yii::$app->params['front'];
            foreach ($links as &$link) {
                $link = $front.$link;
            }
        }
        return $links;
    }

    /**
     * @param $model MActiveRecord|ActiveRecord
     * @param  string  $attribute
     * @return array
     */
    public static function getImagesLinksData($model, $attribute = 'images'): array
    {
        $data = $model->$attribute;
        $result = [];
        foreach ($data as $item) {
            /* @var $item Image */
            $result[] = [
                'caption' => $item->alt ?? '', 'key' => $item->id, 'class' => $item->class_full,
                'user_id' => $model->hasAttribute('user_id') ? $model->user_id : null
            ];
        }
        return $result;
    }


    /**
     * @param $model ActiveRecord
     * @param $image
     * @param $forBackend
     * @param $fieldName
     * @return array
     */
    public static function getOptionsSingle($model, $image, $fieldName = 'image', $forBackend = false): array
    {
        $pluginOptions = [
            'browseOnZoneClick'    => true,
            'dropZoneEnabled'      => true,
            'initialPreviewAsData' => true,
            'language'             => 'en',
            'showPreview'          => true,
            'showCaption'          => false,
            'showRemove'           => false,
            'showCancel'           => false,
            'showUpload'           => false,
            'showDrag'             => false,
            'showBrowse'           => false,
            'deleteUrl'            => '/file/delete-single-image?field='.$fieldName,
            'deleteExtraData'      => [
                'key'   => $model->id,
                'class' => $model->className()
            ],
            'browseLabel'          => 'Select photo'
        ];
        if ($image) {
            $pluginOptions['initialPreview'] = $forBackend ? Yii::$app->params['front'].$image : $image;
        }
        return $pluginOptions;
    }

    /**
     * @param $model ActiveRecord
     * @param $attribute
     * @param $forBackend boolean
     * @param $type
     * @return array
     * @throws InvalidConfigException
     */
    public static function getImageOptions(
        $model,
        $attribute = 'images',
        $type = Image::TYPE_IMAGE,
        $forBackend = false
    ): array {
        $pluginOptions = [
            'deleteUrl'       => Url::to(['/file/delete-image']),
            'deleteExtraData' => [

            ],

            'initialPreviewAsData' => true,
            'overwriteInitial'     => false,

            'uploadUrl'       => Url::to(['/file/upload-image']),
            'uploadExtraData' => [
                'Image[class]'   => $model->className(),
                'Image[item_id]' => $model->id,
                'Image[type]'    => $type,
            ],

            'browseOnZoneClick' => true,
            'dropZoneEnabled'   => true,
            'language'          => 'en',
            'showPreview'       => true,
            'showCaption'       => false,
            'showCancel'        => false,
            'showRemove'        => false,
            'showUpload'        => false,
            'showDrag'          => false,
            'showBrowse'        => false,
            'browseLabel'       => 'Select photo',
            'layoutTemplates'   => [
                'actionZoom' => '',
                'close'      => '',
                'footer'     => '<div class="file-thumbnail-footer">
                                <div class="file-caption-name">
                                    <input type="text" class="kv-input kv-new form-control input-sm form-control-sm text-center" name="header" value="{caption}" placeholder="Name" />
                                </div>
                                {progress} {actions}
                            </div>',
            ],
        ];
        $pluginOptions['initialPreview'] = self::getImageLinks($model, $attribute, $forBackend);
        $pluginOptions['initialPreviewConfig'] = self::getImagesLinksData($model, $attribute);
        return $pluginOptions;
    }

    /**
     * @param $model ActiveRecord
     * @param $file UploadedFile
     * @param  bool  $hasId
     * @param  bool  $addWatermark
     * @return string|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function uploadImage($model, $file, $hasId = false, $addWatermark = false)
    {
        if ($model && $file) {
            $dir = Yii::getAlias('@images/'.$model->formName());
            if ($hasId && $model->isNewRecord) {
                $model->save();
            }
            $path = $hasId ? '/'.$model->id.'/' : '/'.date('Ymd').'/';
            $dyn = new DynamicModel(compact('file'));
            $dyn->addRule('file', 'image')->validate();
            if ($dyn->hasErrors()) {
                Yii::$app->session->setFlash('warning', $dyn->getFirstError('file'));
                return null;
            }
            FileHelper::createDirectory($dir.$path, 0775, true);
            $fileName = time().'_'.Yii::$app->security->generateRandomString(2).'.'.$file->extension;
            if ($file->saveAs($dir.$path.$fileName)) {
                if ($addWatermark) {
                    self::watermark($dir.$path.$fileName);
                }
                return '/uploads/images/'.$model->formName().$path.$fileName;
            }
            Yii::$app->session->setFlash('warning', 'Upload error');
        }
        return null;
    }

    public static function watermark($path)
    {
        $watermark = Img::getImagine()->open(Yii::getAlias('@frontend/web/images/watermark.png'));
        $image = Img::getImagine()->open($path);
        $size = $image->getSize();
        $watermark = $watermark->resize(new Box($size->getWidth(), $size->getHeight()));
        $startPositionWatermark = new Point(0, 0);
        $image->paste($watermark, $startPositionWatermark);
        $image->save(null, ['quality' => 90]);
    }

    /**
     * @param $file
     * @param  boolean  $replace
     * @param  int  $quality
     * @param  int  $width
     * @param  int  $height
     * @param  bool  $isFullPath
     * @param  bool  $onlyBig
     * @param  bool  $saveRatio
     * @return mixed
     */
    public static function crop(
        $file,
        $replace = false,
        $quality = null,
        $width = null,
        $height = null,
        $isFullPath = false,
        $onlyBig = true,
        $saveRatio = true
    ) {
        if (!$width) {
            $width = self::getCropWidth();
        }
        if (!$height) {
            $height = self::getCropHeight();
        }
        if (!$quality) {
            $quality = self::getQuality();
        }
        $useCrop = $width || $height;
        $imgPath = $isFullPath ? $file : Yii::getAlias('@frontend/web').$file;
        if (file_exists($imgPath)) {
            if ($onlyBig) {
                $size = getimagesize($imgPath);
                $maxSize = (int) Config::getValue('maxImageSideSize');
                $startWidth = Html::take($size, 0);
                $startHeight = Html::take($size, 1);
                if ($startWidth <= $maxSize && $startHeight <= $maxSize) {
                    $useCrop = false;
                }
                if ($useCrop && $startWidth === $startHeight && $startWidth === (int) Config::getValue('noCropSideSize')) {
                    $useCrop = false;
                }
                if ($useCrop) {
                    $weight = filesize($imgPath);
                    $maxWeight = 1024 * (int) Config::getValue('maxImageWeight');
                    if ($weight < $maxWeight) {
                        $useCrop = false;
                    }
                }
            }
            if ($useCrop) {
                $path = explode('/', $file);
                $fileName = end($path);
                $image = Img::getImagine()->open($imgPath)->thumbnail(new Box($width, $height),
                    $saveRatio ? ManipulatorInterface::THUMBNAIL_INSET : ManipulatorInterface::THUMBNAIL_OUTBOUND);

                $image->save($replace ? $imgPath : str_replace($fileName, $width.'_'.$height.'_'.$fileName, $imgPath),
                    ['quality' => $quality]);
                return $replace ? $file : str_replace($fileName, $width.'_'.$height.'_'.$fileName, $file);
            }
        }
        return $file;
    }

    /**
     * @return int
     */
    public static function getCropWidth()
    {
        $width = (int) Config::getValue('cropWidth');
        return $width ?? 300;
    }

    /**
     * @return int
     */
    public static function getCropHeight()
    {
        $height = (int) Config::getValue('cropHeight');
        return $height ?? 300;
    }

    /**
     * @return int
     */
    public static function getQuality()
    {
        $quality = (int) Config::getValue('cropQuality');
        if (!$quality) {
            $quality = 60;
        }
        if ($quality > 90) {
            $quality = 90;
        }
        if ($quality < 50) {
            $quality = 50;
        }
        return $quality ?? 60;
    }
}