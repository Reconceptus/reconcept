<?php

namespace modules\services\models;

use common\helpers\StringHelper;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "service".
 *
 * @property int $id
 * @property int $category_id
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $text
 * @property string $url
 * @property string $image
 * @property int $to_footer
 * @property int $to_menu
 * @property int $status
 * @property string $seo_title
 * @property string $seo_description
 * @property ServiceCategory $category
 */
class Service extends ActiveRecord
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
            $this->_url = Url::to('@web/services/' . $this->slug);
        return $this->_url;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $time = date('Y-m-d H:i:s');
        if ($this->isNewRecord) {
            $this->created_at = $time;
        }
        $this->updated_at = $time;
        if (!$this->slug) {
            $this->slug = StringHelper::translitString($this->name);
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     * @return ServiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ServiceQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'text'], 'required'],
            [['text'], 'string'],
            [['to_footer', 'to_menu', 'category_id', 'status'], 'integer'],
            [['image'], 'image'],
            [['slug', 'name', 'seo_title', 'seo_description'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ServiceCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'slug'            => 'Slug',
            'category_id'     => 'Категория',
            'created_at'      => 'Добавлено',
            'updated_at'      => 'Изменено',
            'name'            => 'Название',
            'image'           => 'Картинка',
            'text'            => 'Текст',
            'to_footer'       => 'Показывать в футере',
            'to_menu'         => 'Показывать в меню',
            'status'          => 'Статус',
            'seo_title'       => 'Seo Title',
            'seo_description' => 'Seo Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ServiceCategory::className(), ['id' => 'category_id']);
    }
}
