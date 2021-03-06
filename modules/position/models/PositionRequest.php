<?php

namespace modules\position\models;

use common\models\MActiveRecord;

/**
 * This is the model class for table "position_request".
 *
 * @property string $id
 * @property string $query
 * @property string $domain
 * @property int $depth
 * @property int $status
 * @property int $last_result
 * @property string $created_at
 * @property string $updated_at
 */
class PositionRequest extends MActiveRecord
{
    public const DEPTH_LIST = [
        100 => 100,
        200 => 200,
        300 => 300
    ];

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLED = 0;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE   => 'Активен',
        self::STATUS_DISABLED => 'Отключен'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'position_request';
    }

    public function afterSave($insert, $changedAttributes)
    {
        if($insert){
            $this->getPosition();
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['depth', 'status', 'last_result'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['query', 'domain'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'query'       => 'Запрос',
            'domain'      => 'Домен',
            'depth'       => 'Глубина проверки',
            'last_result' => 'Последний результат',
            'status'      => 'Статус',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
        ];
    }

    public function getPosition()
    {
        $result = PositionLog::getPosition($this->query, $this->domain, 100, $this->id);
        $this->last_result = $result;
        $this->save();
        return $result;
    }
}
