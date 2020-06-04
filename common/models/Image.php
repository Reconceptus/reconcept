<?php

namespace common\models;

use common\helpers\FileHelper;
use common\helpers\ImageHelper;
use modules\config\models\Config;
use modules\shop\models\Product;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string $class
 * @property string $class_full
 * @property int $item_id
 * @property string $image
 * @property string $thumb
 * @property UploadedFile $file
 * @property string $alt
 * @property int $sort
 * @property int $type
 * @property bool $is_main
 * @property ActiveRecord $model
 */
class Image extends ActiveRecord
{
    public $file;

    public const TYPE_IMAGE = 1;
    public const TYPE_SCHEME = 2;
    public const TYPE_DOCUMENT = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'image';
    }

    public function beforeDelete(): bool
    {
        if (parent::beforeDelete()) {
            self::updateAllCounters(['sort' => -1],
                [
                    'and', ['class' => $this->class, 'item_id' => $this->item_id, 'type' => $this->type],
                    ['>', 'sort', $this->sort]
                ]);
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['item_id', 'sort'], 'integer'],
            [
                ['sort'], 'default', 'value' => static function ($model) {
                $count = Image::find()->where(['class' => $model->class, 'type' => $model->type, 'item_id' => $model->item_id])->count();
                return ($count > 0) ? $count : 0;
            }
            ],
            [['class', 'class_full', 'image', 'thumb'], 'string', 'max' => 150],
            [['file'], 'image'],
            [['is_main'], 'boolean'],
            [['alt'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'class'      => 'Class',
            'class_full' => 'Class Full Path',
            'item_id'    => 'Model ID',
            'image'      => 'File',
            'thumb'      => 'Thumb',
            'alt'        => 'Alt',
            'sort'       => 'Sort',
            'type'       => 'Type',
            'is_main'    => 'Is Main',
        ];
    }

    /**
     * @param $model ActiveRecord
     * @param  array  $images
     * @param  int  $type
     * @param  string  $guid
     * @param  bool  $addWatermark
     * @throws ErrorException
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function addImages(
        $model,
        array $images,
        int $type,
        string $guid = '',
        bool $addWatermark = false
    ): void {
        foreach ($images as $im) {
            $imgModel = new self();
            $imgModel->class = $model->formName();
            $imgModel->class_full = $model->className();
            $imgModel->item_id = $model->id;
            $imgModel->type = $type;
            $imgModel->image = ImageHelper::uploadImage($imgModel, $im, false, $addWatermark);
            $imgModel->thumb = ImageHelper::crop($imgModel->image, false, null, Config::getValue('cropPreviewWidth'),
                Config::getValue('cropPreviewHeight'), false, false, false);
            if ($imgModel->validate()) {
                $imgModel->save();
            }
        }
        if ($guid) {
            $directory = Yii::getAlias('@frontend/web/uploads/temp/').Yii::$app->session->id.'/'.$guid.'/'.$type.'/';
            if ($directory && is_dir($directory)) {
                $files = FileHelper::findFiles($directory, ['recursive' => false]);
                $moveTo = Yii::getAlias('@images/').$model->formName().'/';
                foreach ($files as $file) {
                    if ($file && is_file($file)) {
                        $fileName = time().'_'.Yii::$app->security->generateRandomString(2).'.jpg';
                        $path = $moveTo.$model->id.'/'.$fileName;
                        FileHelper::createDirectory($moveTo.$model->id);
                        if (copy($file, $path)) {
                            $imgModel = new self();
                            $imgModel->class = $model->formName();
                            $imgModel->class_full = $model->className();
                            $imgModel->item_id = $model->id;
                            $imgModel->type = $type;
                            $imgModel->image = '/uploads/images/'.$model->formName().'/'.$model->id.'/'.$fileName;
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

    /**
     * @return ActiveQuery
     */
    public function getModel(): ActiveQuery
    {
        return $this->hasOne($this->class, ['id' => 'item_id']);
    }

    /**
     * @param $model ActiveRecord|Product
     * @param $image UploadedFile
     * @return int|null
     * @throws Exception
     */
    public function add($model, $image)
    {
        if ($this->validate(['file'])) {
            $this->image = FileHelper::uploadFile($model, $image);
            $this->class = $model->formName();
            $this->class_full = $model->className();
            $this->item_id = $model->id ?? null;
            if ($this->save()) {
                return $this->id;
            }
        }
        return null;
    }
}
