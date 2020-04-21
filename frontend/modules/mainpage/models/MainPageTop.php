<?php

namespace frontend\modules\mainpage\models;

/**
 * This is the model class for table "main_page_top".
 *
 * @property int $id
 * @property string $image
 * @property string $image_preview
 * @property string $quote
 * @property string $sign
 */
class MainPageTop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'main_page_top';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image', 'image_preview'], 'string', 'max' => 255],
            [['quote', 'sign'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'image'        => 'Фон',
            'image_preview' => 'Фон мобильной версии',
            'quote'        => 'Цитата',
            'sign'         => 'Подпись',
        ];
    }
}
