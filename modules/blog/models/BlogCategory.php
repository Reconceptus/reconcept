<?php

namespace modules\blog\models;

use common\models\MActiveRecord;
use paulzi\nestedsets\NestedSetsBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "blog_category".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $image
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 * @property int $sort
 * @property string $seo_title
 * @property string $seo_description
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 */
class BlogCategory extends MActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const STATUS_LIST = [
        self::STATUS_DISABLED => 'Отключена',
        self::STATUS_ACTIVE   => 'Активна',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_category';
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class'         => NestedSetsBehavior::class,
                'treeAttribute' => null
            ],
            'slug' => [
                'class'         => 'Zelenin\yii\behaviors\Slug',
                'slugAttribute' => 'slug',
                'attribute'     => 'name',
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

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new BlogCategoryQuery(get_called_class());
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status', 'sort', 'lft', 'rgt', 'depth'], 'integer'],
            [['name', 'slug', 'seo_title'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 1000],
            [['image', 'seo_description'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'name'            => 'Название',
            'slug'            => 'Url',
            'description'     => 'Описание',
            'image'           => 'Картинка',
            'status'          => 'Статус',
            'sort'            => 'Сортировка',
            'seo_title'       => 'Seo Title',
            'seo_description' => 'Seo Description',
            'lft'             => 'Lft',
            'rgt'             => 'Rgt',
            'depth'           => 'Depth',
            'created_at'      => 'Добавлена',
            'updated_at'      => 'Обновлена',
        ];
    }

    /**
     * @param bool $activeOnly
     * @return array
     */
    public static function getList($activeOnly = true)
    {
        $query = self::find();
        if ($activeOnly) {
            $query->andWhere(['status' => self::STATUS_ACTIVE]);
        };
        return ArrayHelper::map($query->all(), 'id', 'name');
    }
}
