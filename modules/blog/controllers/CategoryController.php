<?php

namespace modules\blog\controllers;

use common\helpers\ImageHelper;
use modules\blog\models\BlogCategory;
use modules\blog\models\BlogCategorySearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
                        'actions' => [],
                        'allow'   => true,
                        'roles'   => [
                            'blog_category',
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all BlogCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlogCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BlogCategory model.
     * @param integer $id
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
     * Creates a new BlogCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BlogCategory();
        return $this->modify($model);
    }

    /**
     * Updates an existing BlogCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        return $this->modify($model);
    }

    /**
     * @param $model BlogCategory
     * @return string|\yii\web\Response
     */
    public function modify($model)
    {
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            if ($model->isNewRecord) {
                if (!empty($post['parent'])) {
                    $parent = BlogCategory::findOne(['id' => $post['parent']]);
                    if ($parent && $model->parent !== $parent && $parent !== $model) {
                        $model->appendTo($parent);
                    }
                } else {
                    $parent = BlogCategory::findOne(['depth' => 0]);
                    if (!$parent) {
                        $model->makeRoot();
                    } else {
                        if ($model->parent !== $parent && $parent !== $model) {
                            $model->appendTo($parent);
                        }
                    }
                }
            }
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = ImageHelper::uploadImage($model, $image);
            } elseif (!empty($model->oldAttributes['image'])) {
                $model->image = $model->oldAttributes['image'];
            }
            $model->save();
            return $this->redirect('index');
        }
        $categories = BlogCategory::getList();
        if (!$model->isNewRecord) {
            unset($categories[$model->id]);
        }
        return $this->render('_form', [
            'model'      => $model,
            'categories' => $categories
        ]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = BlogCategory::STATUS_DELETED;
        $model->slug = $model->slug . time();
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlogCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlogCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
