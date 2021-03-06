<?php

namespace modules\utils\models;

use common\helpers\ImageHelper;
use common\models\Image;
use modules\config\models\Config;
use Yii;
use yii\base\Exception;
use yii\web\UploadedFile;

/**
 * This is the model class for table "utils_gallery".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $background
 * @property int $layout_id
 * @property Image[] $images
 * @property UtilsLayout $layout
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
            [['background'], 'string', 'max' => 10],
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
            'id'         => 'ID',
            'code'       => 'Код',
            'name'       => 'Название',
            'background' => 'Фон',
            'layout_id'  => 'Шаблон',
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

    /**
     * @return int
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\ErrorException
     */
    public function updateImages(): int
    {
        $guid = Yii::$app->request->post('guid');
        $images = UploadedFile::getInstancesByName('images');
        Image::addImages($this, $images, Image::TYPE_IMAGE, $guid);
        return count($images);
    }

    public function getPreview()
    {
        $images = $this->images;
        if ($images) {
            $image = $this->images[0];
            if (!$image->thumb) {
                $image->thumb = ImageHelper::crop($image->image, true, null, Config::getValue('cropPreviewWidth'),
                    Config::getValue('cropPreviewHeight'));
                $image->save();
            }
            return $image->thumb;
        }
        return Yii::$app->params['defaultImage'];
    }
}
