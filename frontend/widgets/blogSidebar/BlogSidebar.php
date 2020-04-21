<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\blogSidebar;

use modules\blog\models\BlogCategory;
use yii\base\Widget;


class BlogSidebar extends Widget
{
    public $viewName = 'index';
    public $slug = null;

    public function run()
    {
        $models = \Yii::$app->cache->getOrSet('bloog_categories', function () {
            return BlogCategory::find()->where(['>', 'depth', 0])->andWhere(['status' => BlogCategory::STATUS_ACTIVE])->all();
        }, 60 * 60);
        $content = $this->render($this->viewName, [
            'models' => $models,
            'slug'   => $this->slug
        ]);
        return $content;
    }
}