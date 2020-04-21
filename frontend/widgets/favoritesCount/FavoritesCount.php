<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\favoritesCount;

use modules\blog\models\BlogFavorite;
use yii\base\Widget;


class FavoritesCount extends Widget
{
    public $viewName = 'index';

    public function run()
    {
        $count = null;
        $favorites = BlogFavorite::getFavoritesCookie();
        $content = $this->render($this->viewName, [
            'count' => count($favorites),
        ]);
        return $content;
    }
}