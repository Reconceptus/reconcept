<?php

namespace modules\blog\models;

use Yii;

/**
 * This is the model class for table "blog_hash_posts".
 *
 * @property string $id
 * @property int $post_id
 * @property string $hashtag_id
 *
 * @property Hashtag $hashtag
 * @property Post $post
 */
class HashPost extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_hash_post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'hashtag_id'], 'required'],
            [['post_id', 'hashtag_id'], 'integer'],
            [['hashtag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hashtag::className(), 'targetAttribute' => ['hashtag_id' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'hashtag_id' => 'Hashtag ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHashtag()
    {
        return $this->hasOne(Hashtag::className(), ['id' => 'hashtag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
}
