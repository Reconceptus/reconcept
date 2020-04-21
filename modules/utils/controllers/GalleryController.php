<?php

namespace modules\utils\controllers;

use common\helpers\ImageHelper;
use common\models\Image;
use modules\utils\models\UtilsGallery;
use modules\utils\models\UtilsGallerySearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * GalleryController implements the CRUD actions for UtilsGallery model.
 */
class GalleryController extends Controller
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
                            'utils_gallery',
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all UtilsGallery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UtilsGallerySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UtilsGallery model.
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
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new UtilsGallery();
        return $this->modify($model);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        return $this->modify($model);
    }

    /**
     * @param $model UtilsGallery
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function modify($model)
    {
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $images = UploadedFile::getInstancesByName('images');
            foreach ($images as $im) {
                $imgModel = new Image();
                $imgModel->class = $model->formName();
                $imgModel->item_id = $model->id;
                $imgModel->image = ImageHelper::uploadImage($imgModel, $im, true);
                $imgModel->thumb = ImageHelper::cropImage($imgModel->image);
                if ($imgModel->validate()) {
                    $imgModel->save();
                }else{
                    var_dump($imgModel->errors);die;
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the UtilsGallery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UtilsGallery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UtilsGallery::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
