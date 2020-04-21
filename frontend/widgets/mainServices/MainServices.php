<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\mainServices;

use modules\services\models\Service;
use modules\services\models\ServiceCategory;
use yii\base\Widget;

/* @var $models Service */
class MainServices extends Widget
{
    public $viewName = 'index';

    public function run()
    {
        $content = \Yii::$app->cache->getOrSet('mainservices', function () {
            $models = ServiceCategory::find()->with('services')->all();
            if (!$models) {
                return '';
            }
            return $this->render($this->viewName, [
                'models' => $models,
            ]);
        }, 60 * 60 * 24);
        return $content;
    }
}