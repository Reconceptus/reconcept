<?php

namespace common\helpers;

use Yii;
use yii\helpers\Json;

class Telegram
{
    public static function send($text, $user = null)
    {
        $telegramId = self::getAddress($user);
        if ($telegramId) {
            if (!is_string($text)) {
                $text = Json::encode($text);
            }
            if (mb_strlen($text) > 4090) {
                $text = mb_substr($text, 0, 4090);
            }
            Yii::$app->telegram->sendMessage([
                'chat_id' => $telegramId,
                'text'    => $text
            ]);
        }
    }


    public static function sendFile($path, $user = null)
    {
        $telegramId = self::getAddress($user);
        if ($telegramId) {
            Yii::$app->telegram->sendDocument([
                'chat_id'  => $telegramId,
                'caption'  => Yii::$app->name,
                'document' => $path
            ]);
        }
        return 1;
    }


    public static function getAddress($user)
    {
        if ($user && $user->auth && $user->auth->telegram) {
            $telegramId = $user->auth->telegram;
        } else {
            $telegramId = Yii::$app->params['techTelegram'];
            if (!$telegramId) {
                $telegramId = '245375279';
            }
        }
        if ($telegramId) {
            return $telegramId;
        }
        Yii::info('There is no telegramId for user '.$user->email);
        return false;
    }
}