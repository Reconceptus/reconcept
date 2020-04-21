<?php

namespace modules\blog\models;

/**
 * This is the model class for table "blog_post_tag".
 *
 * @property string $id
 * @property string $post_id
 * @property string $tag_id
 *
 * @property Post $post
 * @property Tag $tag
 */
class PostTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_post_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'tag_id'], 'integer'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::className(), 'targetAttribute' => ['tag_id' => 'id']],
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
            'tag_id' => 'Tag ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }

    /**
     * @param int $postId
     * @param int $tagId
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteTag(int $postId, int $tagId)
    {
        return self::find()->where(['post_id' => $postId, 'tag_id' => $tagId])->one()->delete();
    }
}
