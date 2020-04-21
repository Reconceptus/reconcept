<?php
/**
 * Created by PhpStorm.
 * User: adm
 * Date: 10.12.2018
 * Time: 16:29
 */

namespace common\helpers;


use Yii;
use yii\base\DynamicModel;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * @param $model
     * @param $file
     * @param bool $usePath
     * @return string|null
     * @throws Exception
     */
    public static function uploadFile($model, $file, $usePath = false)
    {
        if ($model && $file) {
            $dir = Yii::getAlias('@files/' . $model->formName());
            $path = $usePath ? '/' . date('ymdHis') . '/' : '/';
            $dyn = new DynamicModel(compact('file'));
            $dyn->addRule('file', 'file')->validate();
            if ($dyn->hasErrors()) {
                Yii::$app->session->setFlash('warning', $dyn->getFirstError('file'));
                return null;
            } else {
                FileHelper::createDirectory($dir . $path, 0775, true);
                $fileName = time() . '_' . Yii::$app->security->generateRandomString(2) . '.' . $file->extension;
                if ($file->saveAs($dir . $path . $fileName)) {
                    $link = '/uploads/files/' . $model->formName() . $path . $fileName;
                    return $link;
                }
            }
            Yii::$app->session->setFlash('warning', 'Ошибка при загрузке файла');
        }
        return null;
    }

    /**
     * @param $model
     * @return array
     */
    public static function getFilesOptions($model)
    {
        $pluginOptions = [
            'deleteUrl'            => Url::to(['/file/delete-file']),
            'deleteExtraData'      => [
            ],
            'initialPreviewAsData' => true,
            'overwriteInitial'     => false,
            'initialPreview'       => self::getFilesLinks($model),
            'uploadExtraData'      => [
                'Image[class]'   => $model->formName(),
                'Image[item_id]' => $model->id,
            ],
            'browseOnZoneClick'    => true,
            'dropZoneEnabled'      => true,
            'language'             => 'ru',
            'showPreview'          => true,
            'showCaption'          => false,
            'showRemove'           => false,
            'showUpload'           => false,
            'showDrag'             => true,
            'showBrowse'           => false,
            'browseLabel'          => 'Выбрать файл',
            'layoutTemplates'      => [
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
        $pluginOptions['initialPreview'] = self::getFilesLinks($model);
        $pluginOptions['initialPreviewConfig'] = self::getFilesLinksData($model);
        return $pluginOptions;
    }

    /**
     * @param string $ext
     * @return mixed|string
     */
    public static function getFileType(string $ext)
    {
        $types = [
            'pdf'  => 'pdf',
            'xlsx' => 'office',
            'xls'  => 'office',
            'doc'  => 'office',
            'docx' => 'office',
            'jpg'  => 'image',
            'gif'  => 'image',
            'jpeg' => 'image',
            'png'  => 'image',
            'txt'  => 'text',
            'mp4'  => 'video',
            'html' => 'html',
        ];
        if (!array_key_exists($ext, $types)) {
            return 'other';
        }
        return $types[$ext];
    }

    /**
     * @param $model
     * @param string $attribute
     * @return array
     */
    public static function getFilesLinks($model, $attribute = 'files')
    {
        return ArrayHelper::getColumn($model->$attribute, 'file');
    }

    /**
     * @param $model
     * @param string $attribute
     * @return array
     */
    public static function getFilesLinksData($model, $attribute = 'files')
    {
        $data = $model->$attribute;
        $result = [];
        foreach ($data as $item) {
            $fileName = explode('.', $item->file);
            $ext = end($fileName);
            $result[] = ['type' => self::getFileType($ext), 'size' => 3550, 'caption' => $item->alt ?? '', 'key' => $item->id, 'class' => $item->class];
        }
        return $result;
    }
}