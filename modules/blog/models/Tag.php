<?php

namespace modules\blog\models;

use common\models\MActiveRecord;

/**
 * This is the model class for table "blog_tag".
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property int $sort
 * @property string $language
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PostTag[] $blogPostTags
 */
class Tag extends MActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['slug'], 'string', 'max' => 160],
            [['language'], 'string', 'max' => 6],
            [['name', 'slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'name'       => 'Тег',
            'slug'       => 'Slug',
            'sort'       => 'Сортировка',
            'language'   => 'Язык',
            'created_at' => 'Добавлен',
            'updated_at' => 'Обновлен',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['tag_id' => 'id']);
    }
}
