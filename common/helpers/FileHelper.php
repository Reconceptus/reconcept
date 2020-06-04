<?php

namespace common\helpers;


use modules\config\models\Config;
use Yii;
use yii\base\DynamicModel;
use yii\db\ActiveRecord;

class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * @param $model ActiveRecord
     * @param $file
     * @param $width
     * @param $height
     * @param bool $usePath
     * @param bool $crop
     * @return string|null
     * @throws \yii\base\Exception
     */
    public static function uploadFile($model, $file, $usePath = false)
    {
        if ($model && $file) {
            $dir = Yii::getAlias('@files/' . $model->formName());
            $path = $usePath ? '/' . date('ymdHis') . '/' : '/';
            $dyn = new DynamicModel(compact('file'));
            $maxSize = 1024 * 1024 * 30;
            $extensions = ['jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'odt', 'ods', 'odp', 'ppt', 'pptx'];
            $dyn->addRule('file', 'file',
                ['maxSize' => $maxSize, 'tooBig' => 'Файл не может быть больше '.$maxSize / (1024 * 1024 * 30).'MB', 'extensions' => $extensions, 'checkExtensionByMimeType' => true, 'wrongExtension' => 'Разрешены только файлы с расширениями '. implode(', ',$extensions)])->validate();
            if ($dyn->hasErrors()) {
                Yii::$app->session->setFlash('warning', $dyn->getFirstError('file'));
                return null;
            }
            self::createDirectory($dir . $path, 0775, true);
            $fileName = time() . '_' . Yii::$app->security->generateRandomString(2) . '.' . $file->extension;
            if ($file->saveAs($dir . $path . $fileName)) {
                return '/uploads/files/' . $model->formName() . $path . $fileName;
            }
            Yii::$app->session->setFlash('warning', 'Download error');
        }
        return null;
    }

    /**
     * @param $model
     * @param $value
     * @param $fieldName
     * @return array
     */
    public static function getOptionsConfig($model, $value, $fieldName = 'value')
    {
        $pluginOptions = [
            'browseOnZoneClick'    => true,
            'dropZoneEnabled'      => true,
            'initialPreviewAsData' => true,
            'language'             => 'en',
            'showPreview'          => true,
            'showCaption'          => false,
            'showRemove'           => false,
            'showUpload'           => false,
            'showDrag'             => false,
            'showBrowse'           => false,
            'deleteUrl'            => '/file/delete-file?field=' . $fieldName,
            'deleteExtraData'      => [
                'key'   => $model->id,
                'class' => $model->className()
            ],
            'browseLabel'          => 'Select file'
        ];
        if ($value) {
            $pluginOptions['initialPreview'] = $value;
        }
        if ($model->type === Config::TYPE_FILE) {
            $fileName = explode('.', $model->value);
            $ext = end($fileName);
            $pluginOptions['initialPreviewConfig'] = [
                ['type' => self::getFileType($ext), 'size' => 3550, 'caption' => $model->name]
            ];
        }

        return $pluginOptions;
    }

    public static function getFileType(string $ext)
    {
        $types = [
            'pdf'  => 'pdf',
            'xlsx' => 'office',
            'xls'  => 'office',
            'txt'  => 'text',
            'mp4'  => 'video',
            'html' => 'html',
        ];
        if (!array_key_exists($ext, $types)) {
            return 'object';
        }
        return $types[$ext];
    }

    /**
     * @param string $file
     * @param bool $deletePath
     * @return bool
     */
    public static function delete($file, bool $deletePath = false)
    {
        $file = Yii::getAlias('@frontend/web') . $file;
        try {
            unlink($file);
            if ($deletePath) {
                $fileNameArr = explode('/', $file);
                unset($fileNameArr[count($fileNameArr) - 1]);
                $path = implode('/', $fileNameArr);
                rmdir($path);
            }
            return true;
        } catch (\Exception $e) {
            Yii::info($e->getMessage());
            return false;
        }
    }
}