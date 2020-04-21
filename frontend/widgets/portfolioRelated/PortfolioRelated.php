<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\portfolioRelated;

use modules\portfolio\models\Portfolio;
use yii\base\Widget;
use yii\helpers\ArrayHelper;


class PortfolioRelated extends Widget
{
    public $viewName = 'index';
    /* @var $model Portfolio */
    public $model = null;
    public $limit = 4;

    public function run()
    {
        if ($this->model) {
            $tagIds = ArrayHelper::getColumn($this->model->hiddenTags, 'id');
            $models = Portfolio::find()->distinct()->joinWith('portfolioHiddenTags ht')->where(['in', 'ht.tag_id', $tagIds])
                ->andWhere(['status' => Portfolio::STATUS_ACTIVE])->andWhere(['!=', Portfolio::tableName() . '.id', $this->model->id])->limit($this->limit)->orderBy(['sort' => SORT_DESC])->all();
            $content = $this->render($this->viewName, [
                'models' => $models,
            ]);
            return $content;
        }
        return '';
    }
}