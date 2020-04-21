<?php

namespace modules\services\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "service_category".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Service[] $services
 */
class ServiceCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'   => 'ID',
            'name' => 'Название',
            'slug' => 'Slug',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::className(), ['category_id' => 'id']);
    }

    /**
     * @param bool $activeOnly
     * @return array
     */
    public static function getList()
    {
        $query = self::find();
        return ArrayHelper::map($query->all(), 'id', 'name');
    }
}
