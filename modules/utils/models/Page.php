<?php

namespace modules\utils\models;

use yii\helpers\Url;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $text
 * @property string $seo_title
 * @property string $seo_description
 */
class Page extends \yii\db\ActiveRecord
{
    private $_url;

    public function getUrl()
    {
        if ($this->_url === null)
            $this->_url = Url::to('@web/site/' . $this->slug);
        return $this->_url;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page';
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
        return parent::beforeSave($insert);
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
            [['slug', 'name', 'seo_title', 'seo_description'], 'string', 'max' => 255],
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
            'created_at'      => 'Created At',
            'updated_at'      => 'Updated At',
            'name'            => 'Название',
            'text'            => 'Текст',
            'seo_title'       => 'Seo Title',
            'seo_description' => 'Seo Description',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageQuery(get_called_class());
    }

}
