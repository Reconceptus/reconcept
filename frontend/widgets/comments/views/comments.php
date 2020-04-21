<?php
/**
 * Created by PhpStorm.
 * User: suhov.a.s
 * Date: 26.07.2018
 * Time: 10:37
 */

use modules\blog\models\Comment;
use yii\helpers\Html;

/* @var $comments Comment[] */
/* @var $level_start */
/* @var $is_admin bool */

$menu = null;
$level = $level_start;
$is_admin = Yii::$app->user->can('blog_comment');

foreach ($comments as $key => $comment) {
    switch ($comment->depth) {
        case ($comment->depth == $level):
            $menu .= Html::endTag('li') . PHP_EOL;
            break;
        case $comment->depth > $level:
            $class_ul = $level == $level_start ? 'main-menu' : 'sub-menu';
            $menu .= Html::beginTag('ul', ['class' => $class_ul]);
            break;
        case $comment->depth < $level:
            $menu .= Html::endTag('li') . PHP_EOL;
            for ($i = $level - $comment->depth; $i; $i--) {
                $menu .= Html::endTag('ul') . PHP_EOL;
                $menu .= Html::endTag('li') . PHP_EOL;
            }
            break;
    };
    if (isset($comments[$key + 1]) && $comments[$key + 1]->depth > $comment->depth) {
        $menu .= Html::beginTag('li', ['class' => 'parent']);
    } else {
        $menu .= Html::beginTag('li');
    }
    $menu .= $this->render('_comment', ['comment' => $comment]);

    $level = $comment->depth;
}

for ($i = $level; $i > $level_start; $i--) {
    $menu .= Html::endTag('li') . PHP_EOL;
    $menu .= Html::endTag('ul') . PHP_EOL;
}

echo $menu;