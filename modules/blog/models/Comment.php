<?php

namespace modules\blog\models;

use common\models\MActiveRecord;
use common\models\User;
use modules\config\models\Config;
use paulzi\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "blog_comment".
 *
 * @property string $id
 * @property string $lft
 * @property string $rgt
 * @property string $depth
 * @property int $author_id
 * @property int $old_id
 * @property string $text
 * @property string $ip
 * @property string $name
 * @property string $email
 * @property int $accept
 * @property string $post_id
 * @property string $parent_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 *
 * @property User $author
 * @property Post $post
 */
class Comment extends MActiveRecord
{
    const STATUS_WAIT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_DELETED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lft', 'rgt', 'depth', 'author_id', 'accept', 'post_id', 'status', 'old_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'required'],
            [['text'], 'string', 'max' => 2000],
            [['name', 'email'], 'string', 'max' => 70],
            [['ip'], 'string', 'max' => 45],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'lft'        => 'Lft',
            'rgt'        => 'Rgt',
            'depth'      => 'Depth',
            'author_id'  => 'Автор',
            'ip'         => 'IP адрес',
            'text'       => 'Текст',
            'name'       => 'Название',
            'email'      => 'Email',
            'accept'     => 'Согласие с условиями',
            'post_id'    => 'Пост',
            'created_at' => 'Добавлен',
            'updated_at' => 'Обновлен',
            'status'     => 'Статус',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class'         => NestedSetsBehavior::class,
                'treeAttribute' => 'post_id',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CommentQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_WAIT      => 'Ждет модерации',
            self::STATUS_PUBLISHED => 'Опубликован',
            self::STATUS_DELETED   => 'Удален'
        ];
    }

    /**
     * @return mixed
     */
    public function getStatusName()
    {
        $list = self::getStatusList();
        return $list[$this->status];
    }

    /**
     * @param Comment $parent
     * @param string $name
     * @param string $text
     * @param string|null $email
     * @param int|null $author_id
     * @return Comment|null
     */
    public static function add(Comment $parent, string $name, string $text, string $email = null, int $author_id = null)
    {
        if ($author_id) {
            $userId = $author_id;
        } else {
            $userId = null;
//            $userId = \Yii::$app->user->isGuest ? null : \Yii::$app->user->id;
        }
        if ($parent) {
            $comment = new Comment([
                'name'       => $name,
                'text'       => $text,
                'email'      => $email,
                'ip'         => \Yii::$app->request->userIP,
                'author_id'  => $userId,
                'status'     => Config::getValue('pre_moderate_comments') ? self::STATUS_WAIT : self::STATUS_PUBLISHED,
            ]);
            if ($comment->appendTo($parent)->save()) {
                return $comment;
            }
        }
        return null;
    }
}
