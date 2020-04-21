<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\mainReviews;

use frontend\modules\mainpage\models\Pages;
use modules\portfolio\models\PortfolioReview;
use yii\base\Widget;

/* @var $model Pages */
class MainReviews extends Widget
{
    public $viewName = 'index';

    public function run()
    {
        $models = PortfolioReview::find()->where(['to_main' => 1])->all();
        shuffle($models);
        if (!$models) {
            $content = '';
        } else {
            $content = $this->render($this->viewName, [
                'models' => $models,
            ]);
        }
        return $content;
    }
}