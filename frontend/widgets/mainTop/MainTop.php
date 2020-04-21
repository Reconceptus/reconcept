<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\mainTop;

use frontend\modules\mainpage\models\MainPageTop;
use frontend\modules\mainpage\models\Pages;
use yii\base\Widget;

/* @var $model Pages */
class MainTop extends Widget
{
    public $viewName = 'index';

    public function run()
    {
        $isDesktop = !\Yii::$app->mobileDetect->isMobile();
        $content = \Yii::$app->cache->getOrSet($isDesktop ? 'maintop' : 'maintop_m', function () use ($isDesktop) {
            $models = MainPageTop::find()->all();
            if (!$models) {
                return '';
            }
            return $this->render($this->viewName, [
                'model'     => $models[array_rand($models)],
                'isDesktop' => $isDesktop
            ]);
        }, 120);
        return $content;
    }
}