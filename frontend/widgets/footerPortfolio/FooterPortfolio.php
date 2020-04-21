<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\footerPortfolio;

use modules\portfolio\models\Portfolio;
use yii\base\Widget;


class FooterPortfolio extends Widget
{
    public $viewName = 'index';

    public function run()
    {
        $models = Portfolio::find()->where(['to_footer'=>1])->andWhere(['status' => Portfolio::STATUS_ACTIVE])->orderBy(['sort'=>SORT_DESC])->all();
        $content = $this->render($this->viewName, [
            'models' => $models,
        ]);
        return $content;
    }
}