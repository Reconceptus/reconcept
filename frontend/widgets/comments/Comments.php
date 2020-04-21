<?php
/**
 * Created by PhpStorm.
 * User: suhov.a.s
 * Date: 26.07.2018
 * Time: 10:35
 */

namespace frontend\widgets\comments;

use modules\blog\models\Comment;
use yii\base\Widget;

class Comments extends Widget
{
    public $viewName = 'index';
    public $model;

    public function run()
    {
        $countComments = Comment::find()->where(['post_id' => $this->model->id])->andWhere(['!=', 'status', Comment::STATUS_WAIT])->count();
        $rootComment = $this->model->rootComment;
        $level_start = $rootComment->depth;
        $comments = $rootComment->getDescendants()->all();
        $content = $this->render($this->viewName, [
            'rootComment'   => $rootComment,
            'comments'      => $comments,
            'level_start'   => $level_start,
            'model'         => $this->model,
            'countComments' => $countComments
        ]);
        return $content;
    }

    public static function renderComment($comment)
    {
        return (new Comments)->render('_comment', ['comment' => $comment]);
    }
}