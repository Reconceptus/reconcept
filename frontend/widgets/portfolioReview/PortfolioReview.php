<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\portfolioReview;

use yii\base\Widget;


class PortfolioReview extends Widget
{
    public $viewName = 'index';
    /* @var $model \modules\portfolio\models\PortfolioReview */
    public $model = null;

    public function run()
    {
        if ($this->model && $this->model->text) {
            $content = $this->render($this->viewName, [
                'model' => $this->model,
            ]);
            return $content;
        }
        return '';
    }
}