<?php

namespace frontend\controllers;

use common\helpers\Telegram;
use frontend\widgets\comments\Comments;
use modules\blog\models\BlogCategory;
use modules\blog\models\BlogFavorite;
use modules\blog\models\Comment;
use modules\blog\models\Post;
use modules\blog\models\Tag;
use modules\config\models\Config;
use modules\portfolio\models\Portfolio;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Blog controller
 */
class BlogController extends Controller
{

    /**
     * Displays post list.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $slug = \Yii::$app->request->get('slug');
        $query = Post::find()->with('tags')->joinWith('category')->where([Post::tableName().'.status' => Post::STATUS_ACTIVE]);
        if ($slug) {
            $query->andWhere([BlogCategory::tableName().'.slug' => $slug]);
        }
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 40,
            ],
            'sort'       => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ],
        ]);
        $tags = \Yii::$app->cache->getOrSet('blog_index_tags', function () {
            return Tag::find()->all();
        }, 60 * 60);
        return $this->render('index', [
            'tags'         => $tags,
            'dataProvider' => $dataProvider,
            'slug'         => $slug,
            'favorites'    => BlogFavorite::getFavoritesCookie()
        ]);
    }

    /**
     * Поиск в статье
     * @return string|array
     */
    public function actionSearch()
    {
        // Проверка на существование
        if (\Yii::$app->request->isAjax && $q = \Yii::$app->request->post('q')) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $result = Post::find()->where(['status' => Post::STATUS_ACTIVE])
                ->andWhere(['or', ['like', 'name', $q], ['like', 'intro', $q], ['like', 'text', $q]])->exists();
            if (!$result) {
                return ['status' => 'success', 'result' => 0];
            }
            return $this->redirect(['/blog/search', 'q' => $q]);
        }
        $q = \Yii::$app->request->get('q');
        if (!$q) {
            $query = Post::find()->with('tags')->with('category')
                ->where(['status' => Post::STATUS_ACTIVE]);
        } else {
            $query = Post::find()->with('tags')->with('category')
                ->where(['status' => Post::STATUS_ACTIVE])
                ->andWhere(['or', ['like', 'name', $q], ['like', 'intro', $q], ['like', 'text', $q]]);
        }
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort'       => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ],
        ]);
        $tags = \Yii::$app->cache->getOrSet('blog_index_tags', function () {
            return Tag::find()->all();
        }, 60 * 60);

        return $this->render('index', [
            'tags'         => $tags,
            'dataProvider' => $dataProvider,
            'q'            => $q,
            'favorites'    => BlogFavorite::getFavoritesCookie()
        ]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        $query = Post::find()->where(['slug' => \Yii::$app->request->get('slug')]);
        if (!Yii::$app->user->can('admin')) {
            $query->andWhere(['status' => Post::STATUS_ACTIVE]);
        }
        $model = $query->one();
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $model->views = ++$model->views;
        $model->save();
        return $this->render('view', ['model' => $model]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFavorites()
    {
        $favorites = BlogFavorite::getFavoritesCookie();
        $query = Post::find()->active()->where(['in', 'id', $favorites]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $tags = \Yii::$app->cache->getOrSet('blog_index_tags', function () {
            return Tag::find()->all();
        }, 60 * 60);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'slug'         => 'favorite',
            'tags'         => $tags,
            'favorites'    => BlogFavorite::getFavoritesCookie()
        ]);
    }

    /**
     * @return array
     */
    public function actionAddComment()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        if ($post) {
            $name = Html::encode($post['name']);
            $text = Html::encode($post['comment']);
            $email = Html::encode($post['mail']);
            $accept = !empty($post['approve']) ? 1 : 0;
            if ($accept) {
                $parent = Comment::findOne(['id' => (int) ($post['comment_id'])]);
                if ($parent) {
                    if ($newComment = Comment::add($parent, $name, $text, $email)) {
                        $mail = Yii::$app->mailer->compose('new-comment', ['model' => $newComment]);
                        $mail->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name]);
                        if ($parent->depth > 0) {
                            $mail->setTo($parent->email);
                            $mail->setSubject('Новый комментарий на сайте '.Yii::$app->request->getHostInfo());
                            $mail->send();
                        }
                        Telegram::send('Новый комментарий на сайте '.Yii::$app->request->getHostInfo().': '.Yii::$app->params['front'].'/blog/'.$newComment->post->slug);
                        return [
                            'status' => 'success',
                            'html'   => Config::getValue('pre_moderate_comments') ? '' : Comments::renderComment($newComment)
                        ];
                    }
                }
            }
        }
        return ['status' => 'fail', 'message' => 'Ошибка при добавлении комментария'];
    }

    /**
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionAddFavorite()
    {
        $addCounter = 0;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $get = \Yii::$app->request->get();
        $fav = false;
        $message = '';
        $title = '';
        if (!\Yii::$app->user->isGuest) {
            $model = BlogFavorite::find()->where([
                'post_id' => intval($get['id']), 'user_id' => \Yii::$app->user->id
            ])->one();
            if (!$model && isset($get['fav']) && $get['fav'] === 'true') {
                $model = new BlogFavorite();
                $model->post_id = intval($get['id']);
                $model->user_id = \Yii::$app->user->id;
                if ($model->save()) {
                    $addCounter = 1;
                    $fav = true;
                }
            } else {
                if ($model && isset($get['fav']) && $get['fav'] === 'true') {
                    $fav = true;
                } else {
                    if ($model && isset($get['fav']) && $get['fav'] === 'false') {
                        $model->delete();
                        $addCounter = -1;
                    }
                }
            }
        } else {
            $title = ' Вы не авторизованы';
            $message = 'После авторизации Вам будет доступна функция сохранения понравившихся постов. <p><a href="/site/login" style="color: #2196f3">Войти</a></p><p><a href="/site/signup" style="color: #2196f3">Зарегистрироваться</a></p>';
        }
        return ['fav' => $fav, 'counter' => $addCounter, 'title' => $title, 'message' => $message];
    }
}
