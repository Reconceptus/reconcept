<?php

namespace common\helpers;

use yii\log\Target;

class TelegramMessageTarget extends Target
{
    public $message = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

    public function export()
    {
        $message = implode("\n", array_map([$this, 'formatMessage'], $this->messages))."\n";
        Telegram::send($message);
    }

}
