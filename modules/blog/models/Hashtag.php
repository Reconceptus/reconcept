<?php

namespace modules\blog\models;

use common\models\MActiveRecord;
use Yii;

/**
 * This is the model class for table "blog_hashtag".
 *
 * @property string $id
 * @property string $name
 * @property int $number_of_clicks
 * @property string $created_at
 *
 * @property HashPost[] $hashPosts
 * @property Post[] $posts
 */
class Hashtag extends MActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_hashtag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number_of_clicks'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'unique'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Хэштег',
            'number_of_clicks' => 'Количество кликов',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHashPosts()
    {
        return $this->hasMany(HashPost::className(), ['hashtag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])->via('hashPosts');
    }
}
