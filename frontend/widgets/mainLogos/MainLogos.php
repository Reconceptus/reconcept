<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\mainLogos;

use frontend\modules\mainpage\models\Pages;
use yii\base\Widget;

/* @var $model Pages */
class MainLogos extends Widget
{
    public $viewName = 'index';

    public function run()
    {
        $content = \Yii::$app->cache->getOrSet('mainlogos', function () {
            $model = Pages::findOne(['id' => 1]);
            if (!$model->images) {
                return '';
            }
            return $this->render($this->viewName, [
                'models' => $model->images,
            ]);
        }, 60 * 60 * 24);
        return $content;
    }
}