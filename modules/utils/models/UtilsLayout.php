<?php

namespace modules\utils\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "utils_gallery_layout".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $layout
 * @property string $item
 */
class UtilsLayout extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'utils_gallery_layout';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['layout', 'item'], 'string'],
            [['code', 'name'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'     => 'ID',
            'code'   => 'Код',
            'name'   => 'Название',
            'layout' => 'Шаблон галереи',
            'item'   => 'Шаблон отдельной записи',
        ];
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
