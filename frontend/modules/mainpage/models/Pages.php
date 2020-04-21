<?php

namespace frontend\modules\mainpage\models;

use common\models\Image;

/**
 * This is the model class for table "main_page_pages".
 *
 * @property int $id
 * @property string $text
 * @property int $sort
 * @property Image[] $images
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'main_page_pages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['text'], 'string', 'max' => 50000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Текст',
            'sort' => 'Сортировка',
        ];
    }

    public function getImages()
    {
        return $this->hasMany(Image::className(), ['item_id' => 'id'])->andWhere([Image::tableName() . '.class' => $this->formName()])->orderBy('sort');
    }
}
