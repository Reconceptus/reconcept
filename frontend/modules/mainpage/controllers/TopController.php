<?php

namespace frontend\modules\mainpage\controllers;

use common\helpers\ImageHelper;
use frontend\modules\mainpage\models\MainPageTop;
use frontend\modules\mainpage\models\MainPageTopSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * TopController implements the CRUD actions for MainPageTop model.
 */
class TopController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete', 'image-upload'],
                        'allow'   => true,
                        'roles'   => [
                            'mainpage_top',
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'image-upload' => [
                'class'            => 'vova07\imperavi\actions\UploadFileAction',
                'url'              => '/uploads/images/maintop', // Directory URL address, where files are stored.
                'path'             => '@images/maintop', // Or absolute path to directory where files are stored.
                'translit'         => true,
                'validatorOptions' => [
                    'maxWidth'  => 1800,
                    'maxHeight' => 1800
                ],
            ],
            ''
        ];
    }

    /**
     * Lists all MainPageTop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MainPageTopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new MainPageTop model.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MainPageTop();
        return $this->modify($model);
    }

    /**
     * Updates an existing MainPageTop model.
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
     * @param $model MainPageTop
     * @return string|\yii\web\Response
     */
    public function modify($model)
    {
        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = ImageHelper::uploadImage($model, $image);
            } elseif (!empty($model->oldAttributes['image'])) {
                $model->image = $model->oldAttributes['image'];
            }
            $image_preview = UploadedFile::getInstance($model, 'image_preview');
            if ($image_preview) {
                $model->image_preview = ImageHelper::uploadImage($model, $image_preview);
            } elseif (!empty($model->oldAttributes['image_preview'])) {
                $model->image_preview = $model->oldAttributes['image_preview'];
            }
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(false)[0]);
            }
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MainPageTop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @return bool
     */
    public function actionDeletePreview()
    {
        $post = Yii::$app->request->post();
        $model = $post['class']::findOne(['id' => $post['key']]);
        if ($model && $model->image_preview) {
            $file = Yii::getAlias('@webroot' . $model->image_preview);
            if (file_exists($file) && is_file($file)) {
                unlink($file);
            }
            $model->image_preview = '';
            if ($model->save()) {
                return true;
            }
        }
    }

    /**
     * Finds the MainPageTop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MainPageTop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MainPageTop::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
