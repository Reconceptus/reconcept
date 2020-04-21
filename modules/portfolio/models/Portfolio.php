<?php

namespace modules\portfolio\models;

use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "portfolio_portfolio".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $full_name
 * @property string $alt
 * @property string $url
 * @property int $status
 * @property int $author_id
 * @property string $image
 * @property string $horizontal_preview
 * @property string $vertical_preview
 * @property string $content
 * @property int $to_main
 * @property int $to_footer
 * @property int $sort
 * @property int $views
 * @property string $created_at
 * @property string $updated_at
 * @property string $seo_title
 * @property string $seo_description
 * @property string $description
 *
 * @property User $author
 * @property PortfolioTag[] $tags
 * @property HiddenTag[] $hiddenTags
 * @property PortfolioReview[] $review
 */
class Portfolio extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;
    const STATUS_DELETED = 2;

    const STATUS_LIST = [
        self::STATUS_DISABLED => 'Отключен',
        self::STATUS_ACTIVE   => 'Активен'
    ];

    private $_url;

    public function getUrl()
    {
        if ($this->_url === null)
            $this->_url = Url::to('@web/portfolio/' . $this->slug);
        return $this->_url;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'slug' => [
                'class'         => 'Zelenin\yii\behaviors\Slug',
                'slugAttribute' => 'slug',
                'attribute'     => 'full_name',
                // optional params
                'ensureUnique'  => true,
                'replacement'   => '-',
                'lowercase'     => true,
                'immutable'     => true,
                // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general.
//                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ]
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $time = date('Y-m-d H:i:s');
        if ($this->isNewRecord && !$this->created_at) {
            $this->created_at = $time;
        }
        $this->updated_at = $time;
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'portfolio_portfolio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['author_id', 'status'], 'required'],
            [['slug'], 'unique'],
            [['author_id', 'to_main', 'to_footer', 'status', 'views', 'sort'], 'integer'],
            [['content'], 'string'],
            [['name', 'slug', 'full_name', 'alt', 'url', 'image', 'horizontal_preview', 'vertical_preview', 'seo_title', 'seo_description', 'description'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                 => 'ID',
            'name'               => 'Название',
            'slug'               => 'Код',
            'description'        => 'Описание',
            'status'             => 'Статус',
            'tags'               => 'Теги',
            'hiddenTags'         => 'Скрытые теги',
            'full_name'          => 'Полное название',
            'alt'                => 'Подпись',
            'url'                => 'Url сайта',
            'author_id'          => 'Автор',
            'image'              => 'Картинка',
            'views'              => 'Просмотров',
            'horizontal_preview' => 'Горизонтальное превью',
            'vertical_preview'   => 'Вертикальное превью',
            'content'            => 'Контент',
            'to_main'            => 'На главной',
            'to_footer'          => 'В футере',
            'seo_title'          => 'Seo Title',
            'seo_description'    => 'Seo Description',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PortfolioQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PortfolioQuery(get_called_class());
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
    public function getPortfolioTags()
    {
        return $this->hasMany(PortfolioPortfolioTag::className(), ['portfolio_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(PortfolioTag::className(), ['id' => 'tag_id'])->via('portfolioTags');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPortfolioHiddenTags()
    {
        return $this->hasMany(PortfolioHiddenTag::className(), ['portfolio_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHiddenTags()
    {
        return $this->hasMany(HiddenTag::className(), ['id' => 'tag_id'])->via('portfolioHiddenTags');
    }

    /**l
     * @return \yii\db\ActiveQuery
     */
    public function getReview()
    {
        return $this->hasOne(PortfolioReview::className(), ['portfolio_id' => 'id']);
    }

    /**
     * @param $tags
     */
    public function updateTags($tags)
    {
        $oldTags = ArrayHelper::map($this->tags, 'name', 'id');
        $tagsToInsert = array_diff($tags, $oldTags);
        $tagsToDelete = array_diff($oldTags, $tags);
        PortfolioPortfolioTag::deleteAll(['and', ['portfolio_id' => $this->id], ['tag_id' => $tagsToDelete]]);
        foreach ($tagsToInsert as $ins) {
            if (intval($ins) == $ins) {
                $tag = PortfolioTag::findOne(['id' => $ins]);
            } else {
                $tag = PortfolioTag::findOne(['name' => mb_strtolower(Html::encode($ins))]);
            }
            if (!$tag) {
                $tag = new PortfolioTag();
                $tag->name = mb_strtolower($ins);
                $tag->save();
            }
            $postTag = new PortfolioPortfolioTag(['portfolio_id' => $this->id, 'tag_id' => $tag->id]);
            $postTag->save();
        }
    }

    /**
     * @param $tags
     */
    public function updateHiddenTags($tags)
    {
        $oldTags = ArrayHelper::map($this->hiddenTags, 'name', 'id');
        $tagsToInsert = array_diff($tags, $oldTags);
        $tagsToDelete = array_diff($oldTags, $tags);
        PortfolioHiddenTag::deleteAll(['and', ['portfolio_id' => $this->id], ['tag_id' => $tagsToDelete]]);
        foreach ($tagsToInsert as $ins) {
            if (intval($ins) == $ins) {
                $tag = HiddenTag::findOne(['id' => $ins]);
            } else {
                $tag = HiddenTag::findOne(['name' => mb_strtolower(Html::encode($ins))]);
            }
            if (!$tag) {
                $tag = new HiddenTag();
                $tag->name = mb_strtolower($ins);
                $tag->save();
            }
            $postTag = new PortfolioHiddenTag(['portfolio_id' => $this->id, 'tag_id' => $tag->id]);
            $postTag->save();
        }
    }
}
