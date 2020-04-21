<?php

namespace modules\utils\models;

/**
 * This is the model class for table "utils_block".
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $content
 */
class UtilsBlock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'utils_block';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['type'], 'string', 'max' => 10],
            [['slug', 'name'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'slug'    => 'Код',
            'type'    => 'Тип',
            'name'    => 'Название',
            'content' => 'Контент',
        ];
    }
}
