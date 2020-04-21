<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\mainText;

use frontend\modules\mainpage\models\Pages;
use yii\base\Widget;

/* @var $model Pages */
class MainText extends Widget
{
    public $viewName = 'index';

    public function run()
    {
        $content = \Yii::$app->cache->getOrSet('maintext', function () {
            $model = Pages::findOne(['id' => 1]);
            if (!$model->text) {
                return '';
            }
            return $this->render($this->viewName, [
                'model' => $model,
            ]);
        }, 60 * 60 * 24);
        return $content;
    }
}