<?php

namespace common\helpers;

use Yii;
use yii\base\InvalidConfigException;
use yii\log\Target;

class TelegramTarget extends Target
{
    public $message = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (empty(Yii::$app->params['telegramUrl'])) {
            throw new InvalidConfigException('There is no telegramUrl config');
        }
    }

    public function export()
    {
        if (!empty(Yii::$app->params['telegramUrl'])) {
            $email = Yii::$app->params['techEmail'];
            $message = implode("\n", array_map([$this, 'formatMessage'], $this->messages)) . "\n";
            Telegram::send($message, $email);
        }
    }

}