<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\mainBlog;

use common\models\User;
use modules\blog\models\BlogFavorite;
use modules\blog\models\Post;
use yii\base\Widget;

/* @var $models Post */
class MainBlog extends Widget
{
    public $viewName = 'index';

    public function run()
    {
        $models = \Yii::$app->cache->getOrSet('mainblog', function () {
            return Post::find()->where(['to_main' => 1])->andWhere(['status' => Post::STATUS_ACTIVE])->all();
        }, 60 * 60 * 24);
        if (!$models) {
            return '';
        }
        $user = \Yii::$app->cache->getOrSet('mainauthor', function () {
            return User::findOne(['id' => 1]);
        }, 60 * 60 * 24);
        $content = $this->render($this->viewName, [
            'models' => $models, 'user' => $user, 'favorites' => BlogFavorite::getFavoritesCookie()
        ]);

        return $content;
    }
}