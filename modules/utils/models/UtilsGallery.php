<?php

namespace modules\utils\models;

use common\models\Image;

/**
 * This is the model class for table "utils_gallery".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $layout_id
 * @property Image[] $images
 * @property UtilsLayout[] $layout
 */
class UtilsGallery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'utils_gallery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'string', 'max' => 150],
            [['layout_id'], 'integer'],
            [['layout_id'], 'exist', 'skipOnError' => true, 'targetClass' => UtilsLayout::className(), 'targetAttribute' => ['layout_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'code'      => 'Код',
            'name'      => 'Название',
            'layout_id' => 'Шаблон',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['item_id' => 'id'])->andWhere([Image::tableName() . '.class' => $this->formName()])->orderBy('sort');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLayout()
    {
        return $this->hasOne(UtilsLayout::className(), ['id' => 'layout_id']);
    }
}
