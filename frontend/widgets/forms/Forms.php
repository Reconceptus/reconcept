<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\forms;

use modules\config\models\Config;
use yii\base\Widget;


class Forms extends Widget
{
    public $viewName = 'subscribe';

    public function run()
    {
        $policy = \Yii::$app->cache->getOrSet('policy', function () {
            return Config::getValue('license_text');
        }, 60 * 60 * 24);
        $content = $this->render($this->viewName, ['policy' => $policy]);
        return $content;
    }
}