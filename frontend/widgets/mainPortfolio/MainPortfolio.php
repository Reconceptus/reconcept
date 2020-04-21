<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\mainPortfolio;

use frontend\modules\mainpage\models\Pages;
use modules\portfolio\models\Portfolio;
use yii\base\Widget;

/* @var $model Pages */
class MainPortfolio extends Widget
{
    public $viewName = 'index';

    public function run()
    {
        $content = \Yii::$app->cache->getOrSet('mainportfolio', function () {
            $models = Portfolio::find()->where(['to_main' => 1])->andWhere(['status' => Portfolio::STATUS_ACTIVE])->orderBy(['sort'=>SORT_DESC])->all();
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