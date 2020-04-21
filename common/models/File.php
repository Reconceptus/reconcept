<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $class
 * @property int $item_id
 * @property string $file
 * @property string $alt
 * @property string $type
 * @property string $name
 * @property int $sort
 */
class File extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file', 'item_id', 'class'], 'required'],
            [['item_id', 'sort'], 'integer'],
            [['class'], 'string', 'max' => 100],
            [['sort'], 'default', 'value' => function ($model) {
                $count = File::find()->where(['class' => $model->class])->count();
                return ($count > 0) ? $count : 0;
            }],
            [['alt'], 'string', 'max' => 150],
            [['file'], 'file'],
            [['type'], 'string', 'max' => 10],
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
            'file'    => 'Файл',
            'alt'     => 'Название',
            'type'    => 'Тип',
            'sort'    => 'Сортировка',
        ];
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            File::updateAllCounters(['sort' => -1], ['and', ['class' => $this->class, 'item_id' => $this->item_id], ['>', 'sort', $this->sort]]);
            return true;
        }
        return false;
    }

    /**
     * @return mixed|string
     */
    public function getName()
    {
        if (!$this->alt) {
            $name = explode('/', $this->file);
            $this->alt = end($name);
        }
        return $this->alt;
    }
}
