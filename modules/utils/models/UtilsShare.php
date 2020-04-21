<?php

namespace modules\utils\models;

/**
 * This is the model class for table "utils_share".
 *
 * @property int $id
 * @property string $url
 * @property int $vk
 * @property int $ok
 * @property int $fb
 * @property int $tw
 * @property int $ig
 */
class UtilsShare extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'utils_share';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vk', 'ok', 'fb', 'tw', 'ig'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['url'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'  => 'ID',
            'url' => 'Url',
            'vk'  => 'Вконтакте',
            'ok'  => 'Одноклассники',
            'fb'  => 'Facebook',
            'tw'  => 'Twitter',
            'ig'  => 'Instagram',
        ];
    }

    public static function addShare(string $url, string $social)
    {
        $model = self::findOne(['url' => $url]);
        if (!$model) {
            $model = new self(['url' => $url]);
        }
        $model->$social++;
        if ($model->save()) {
            return $model->$social;
        }
    }
}
