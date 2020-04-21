<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\related;

use modules\blog\models\BlogFavorite;
use modules\blog\models\Post;
use yii\base\Widget;


class Related extends Widget
{
    /* @var $model Post */
    public $viewName = 'index';
    public $model;
    public $limit = 4;

    public function run()
    {
        $models = \Yii::$app->cache->getOrSet('related_post_category_' . $this->model->category_id,
            function () {
                return self::getRelated($this->model, $this->limit);
            }, 60 * 60);
        $content = $this->render($this->viewName, [
            'models'    => $models,
            'favorites' => BlogFavorite::getFavoritesCookie()
        ]);
        return $content;
    }

    public static function getRelated($model, $limit)
    {
        $query = Post::find()->select('id')->active();
        if ($model) {
            $query->andWhere(['category_id' => $model->category_id]);
        }
        $articles = $query->indexBy('id')->column();
        if ($limit > count($articles)) {
            $limit = count($articles);
        }
        $ids = array_rand($articles, $limit);
        $models = Post::find()->where(['in', 'id', $ids])->all();
        return $models;
    }
}