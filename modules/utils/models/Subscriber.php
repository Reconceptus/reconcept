<?php

namespace modules\utils\models;

use common\models\User;

/**
 * This is the model class for table "subscriber".
 *
 * @property int $id
 * @property int $user_id
 * @property string $email
 * @property int $status
 * @property User $user
 */
class Subscriber extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;
    const STATUS_LIST = [
        self::STATUS_DISABLED => 'Отключен',
        self::STATUS_ACTIVE   => 'Активен'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscriber';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'integer'],
            [['email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'user_id' => 'Пользователь',
            'email'   => 'Email',
            'status'  => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
