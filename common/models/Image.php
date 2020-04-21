<?php

namespace common\models;

use common\helpers\FileHelper;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string $class
 * @property int $item_id
 * @property string $image
 * @property string $thumb
 * @property UploadedFile $file
 * @property string $alt
 * @property int $sort
 * @property bool $is_main
 */
class Image extends ActiveRecord
{
    public $file;

    const TYPE_IMAGE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            Image::updateAllCounters(['sort' => -1],
                ['and', ['class' => $this->class, 'item_id' => $this->item_id], ['>', 'sort', $this->sort]]);
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id', 'sort'], 'integer'],
            [
                ['sort'], 'default', 'value' => function ($model) {
                $count = Image::find()->where(['class' => $model->class])->count();
                return ($count > 0) ? $count : 0;
            }
            ],
            [['class', 'image', 'thumb'], 'string', 'max' => 150],
            [['file'], 'image'],
            [['is_main'], 'boolean'],
            [['alt'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'class'   => 'Класс',
            'item_id' => 'ID модели',
            'image'   => 'Файл',
            'thumb'   => 'Thumb',
            'alt'     => 'Подсказка',
            'sort'    => 'Сортировка',
            'is_main' => 'Главное',
        ];
    }

    /**
     * @param $model ActiveRecord
     * @param $image UploadedFile
     * @return int|null
     * @throws Exception
     */
    public function add($model, $image)
    {
        if ($this->validate(['file'])) {
            $this->image = FileHelper::uploadFile($model, $image);
            $this->class = $model->formName();
            $this->item_id = $model->id ?? null;
            if ($this->save()) {
                return $this->id;
            }
        }
        return null;
    }
}
