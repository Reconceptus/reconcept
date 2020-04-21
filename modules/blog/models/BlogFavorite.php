<?php

namespace modules\blog\models;

use common\models\MActiveRecord;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "blog_favorite".
 *
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Post $post
 * @property User $user
 */
class BlogFavorite extends MActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_favorite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'post_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'user_id'    => 'User ID',
            'post_id'    => 'Post ID',
            'created_at' => 'Добавлен',
            'updated_at' => 'Обновлен',
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getFavorite()
    {
        if (\Yii::$app->user->isGuest) {
            return [];
        }
        return self::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }

    /**
     * @return array
     */
    public static function getFavoriteIds()
    {
        return ArrayHelper::map(self::getFavorite(), 'post_id', 'id');
    }

    /**
     * @return array|mixed
     */
    public static function getFavoritesCookie()
    {
        if (!array_key_exists('favorites', $_COOKIE)) {
            $favorites = [];
            setcookie("favorites", json_encode($favorites), time() + 60 * 60 * 24 * 365 * 5, "/");
        } else {
            $favorites = json_decode($_COOKIE['favorites']);
        }
        return $favorites;
    }
}
