<?php

namespace modules\blog\controllers;

use common\helpers\Html;
use common\helpers\ImageHelper;
use modules\blog\models\HashPost;
use modules\blog\models\Post;
use modules\blog\models\PostSearch;
use modules\blog\models\Tag;
use modules\utils\helpers\ContentHelper;
use modules\utils\helpers\GalleryHelper;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class'        => AccessControl::className(),
                'denyCallback' => function () {
                    if (Yii::$app->user->isGuest) {
                        return $this->redirect('/site/login');
                    } else {
                        throw new HttpException(403, 'У вас нет доступа для выбранного действия');
                    }
                },
                'rules'        => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'roles'   => [
                            'adminPanel',
                        ],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete', 'image-upload', 'view', 'main', 'letter'],
                        'allow'   => true,
                        'roles'   => [
                            'blog_post',
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return array|string|Response
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new Post();
        return $this->modify($model);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model || $model->status === Post::STATUS_DELETED) {
            throw new NotFoundHttpException();
        }
        return $this->modify($model);
    }

    /**
     * @param $model Post
     * @return array|string|Response
     * @throws \yii\base\Exception
     */
    public function modify($model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            $model->text = Html::makeTypo($model->text);
            // Добавляем автора и дату создания
            if ($model->isNewRecord && !$model->author_id) {
                $model->author_id = Yii::$app->user->id;
            }

            // Загружаем картинки
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = ImageHelper::uploadImage($model, $image, true);
            } elseif (!empty($model->oldAttributes['image'])) {
                $model->image = $model->oldAttributes['image'];
            }

            $imagePrev = UploadedFile::getInstance($model, 'image_preview');
            if ($imagePrev) {
                $model->image_preview = ImageHelper::uploadImage($model, $imagePrev, true);
            } elseif (!empty($model->oldAttributes['image_preview'])) {
                $model->image_preview = $model->oldAttributes['image_preview'];
            }

            if ($model->isNewRecord) {
                if ($model->validate()) {
                    $model->save();
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка при сохранении');
                }
            }
            // Обновляем теги
            $tags = $post['Post'] && $post['Post']['tags'] ? $post['Post']['tags'] : [];
            $model->updateTags($tags);
            if ($model->save()) {
                $model->findHashTags();
                GalleryHelper::processBlocks($model->text);
                Yii::$app->cache->delete('mainblog');
                Yii::$app->session->setFlash('success', 'Post created successfully');
            } else {
                Yii::$app->session->setFlash('danger', 'Error creating post');
            }
            return $this->redirect(Url::to(['update', 'id' => $model->id]));
        }
        $tags = ArrayHelper::map(Tag::find()->all(), 'id', 'name');

        return $this->render('_form', [
            'model' => $model,
            'tags'  => $tags
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Post::STATUS_DELETED;
        $model->slug = $model->slug . time();
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Post
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::find()->with('tags')->where(['id' => $id])->one()) !== null) {
            /* @var $model Post */
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionMain()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        if (!empty($post['id'])) {
            $model = $this->findModel($post['id']);
            if ($model) {
                if ($model->to_main) {
                    $model->to_main = 0;
                } else {
                    $model->to_main = 1;
                }
                if ($model->save()) {
                    Yii::$app->cache->delete('mainblog');
                    return ['status' => 'success', 'main' => $model->to_main];
                }
            }
        }
        return ['status' => 'fail'];
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionLetter()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        if (!empty($post['id'])) {
            $model = $this->findModel($post['id']);
            if ($model) {
                if ($model->to_letter) {
                    $model->to_letter = 0;
                } else {
                    $model->to_letter = 1;
                }
                if ($model->save()) {
                    return ['status' => 'success', 'main' => $model->to_letter];
                }
            }
        }
        return ['status' => 'fail'];
    }
}
