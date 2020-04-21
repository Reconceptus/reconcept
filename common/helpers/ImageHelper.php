<?php
/**
 * Created by PhpStorm.
 * User: adm
 * Date: 12.12.2018
 * Time: 21:53
 */

namespace common\helpers;


use modules\config\models\Config;
use Yii;
use yii\base\DynamicModel;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\image\drivers\Image;

class ImageHelper
{
    /**
     * Опции для изображений, которых в модели одно
     * @param $model
     * @param $image
     * @param $fieldName
     * @return array
     */
    public static function getOptionsSingle($model, $image, $fieldName = 'image')
    {
        $pluginOptions = [
            'browseOnZoneClick'    => true,
            'dropZoneEnabled'      => true,
            'initialPreviewAsData' => true,
            'language'             => 'ru',
            'showPreview'          => true,
            'showCaption'          => false,
            'showRemove'           => false,
            'showUpload'           => false,
            'showDrag'             => false,
            'showBrowse'           => false,
            'deleteUrl'            => '/file/delete-single-image?field=' . $fieldName,
            'deleteExtraData'      => [
                'key'   => $model->id,
                'class' => $model->className()
            ],
            'browseLabel'          => 'Выбрать фото'
        ];
        if ($image) {
            $pluginOptions['initialPreview'] = $image;
        }
        return $pluginOptions;
    }

    /**
     * @param $model
     * @param $attribute
     * @return array
     */
    public static function getImageOptions($model, $attribute = 'images')
    {
        $pluginOptions = [
            'deleteUrl'       => Url::to(['/file/delete-image']),
            'deleteExtraData' => [

            ],

            'initialPreviewAsData' => true,
            'overwriteInitial'     => false,
            'initialPreview'       => ImageHelper::getImageLinks($model),
            'initialPreviewConfig' => ImageHelper::getImagesLinksData($model),

            'uploadUrl'       => Url::to(['/file/upload-image']),
            'uploadExtraData' => [
                'Image[class]'   => $model->formName(),
                'Image[item_id]' => $model->id,
            ],

            'browseOnZoneClick' => true,
            'dropZoneEnabled'   => true,
            'language'          => 'ru',
            'showPreview'       => true,
            'showCaption'       => false,
            'showRemove'        => false,
            'showUpload'        => false,
            'showDrag'          => true,
            'showBrowse'        => false,
            'browseLabel'       => 'Выбрать фото',
            'layoutTemplates'   => [
                'actionZoom' => '',
                'close'      => '',
                'footer'     => '<div class="file-thumbnail-footer">
                                <div class="file-caption-name">
                                    <input type="text" class="kv-input kv-new form-control input-sm form-control-sm text-center" name="header" value="{caption}" placeholder="Название" />
                                </div>
                                {progress} {actions}
                            </div>',
            ],
        ];
        $pluginOptions['initialPreview'] = self::getImageLinks($model, $attribute);
        $pluginOptions['initialPreviewConfig'] = self::getImagesLinksData($model, $attribute);
        return $pluginOptions;
    }

    /**
     * @param $model
     * @param $file
     * @param bool $usePath
     * @return string|null
     * @throws Exception
     */
    public static function uploadImage($model, $file, $usePath = false)
    {
        if ($model && $file) {
            $dir = Yii::getAlias('@images/' . $model->formName());
            $path = $usePath ? '/' . date('ymdHis') . '/' : '/';
            $dyn = new DynamicModel(compact('file'));
            $dyn->addRule('file', 'image')->validate();
            if ($dyn->hasErrors()) {
                Yii::$app->session->setFlash('warning', $dyn->getFirstError('file'));
                return null;
            } else {
                FileHelper::createDirectory($dir . $path, 0775, true);
                $fileName = time() . '_' . Yii::$app->security->generateRandomString(2) . '.' . $file->extension;
                if ($file->saveAs($dir . $path . $fileName)) {
                    $link = '/uploads/images/' . $model->formName() . $path . $fileName;
                    return $link;
                }
            }
            Yii::$app->session->setFlash('warning', 'Ошибка при загрузке файла');
        }
        return null;
    }

    /**
     * @param $model
     * @param string $attribute
     * @return array
     */
    public static function getImageLinks($model, $attribute = 'images')
    {
        return ArrayHelper::getColumn($model->$attribute, 'image');
    }

    /**
     * @param $model
     * @param string $attribute
     * @return array
     */
    public static function getImagesLinksData($model, $attribute = 'images')
    {
        $data = $model->$attribute;
        $result = [];
        foreach ($data as $item) {
            $result[] = ['caption' => $item->alt ?? '', 'key' => $item->id, 'class' => $item->class];
        }
        return $result;
    }


    /**
     * @param $file
     * @param int $width
     * @param int $height
     * @param int $quality
     * @return mixed
     */
    public static function cropImage($file, $quality = 60, $width = null, $height = null)
    {
        if (!$width) {
            $width = self::getCropWidth();
        }
        if (!$height) {
            $height = self::getCropHeight();
        }
        $useCrop = $width || $height;
        $imgPath = Yii::getAlias('@frontend/web') . $file;
        $image = Yii::$app->image->load($imgPath);
        if ($image && $useCrop) {
            $path = explode('/', $file);
            $fileName = end($path);
            $image->resize($width, $height, Image::INVERSE)->crop($width, $height)->save(str_replace($fileName, $width . '_' . $height . '_' . $fileName, $imgPath), $quality);
            return str_replace($fileName, $width . '_' . $height . '_' . $fileName, $file);
        }
        return null;
    }

    public static function getCropWidth()
    {
        $width = intval(Config::getValue('cropWidth'));
        return $width;
    }

    public static function getCropHeight()
    {
        $height = intval(Config::getValue('cropHeight'));
        return $height;
    }
}