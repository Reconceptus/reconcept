<?php

namespace modules\portfolio\controllers;

use common\helpers\Html;
use common\helpers\ImageHelper;
use modules\portfolio\models\HiddenTag;
use modules\portfolio\models\Portfolio;
use modules\portfolio\models\PortfolioReview;
use modules\portfolio\models\PortfolioSearch;
use modules\portfolio\models\PortfolioTag;
use modules\utils\helpers\ContentHelper;
use modules\utils\helpers\GalleryHelper;
use modules\utils\models\UtilsGallery;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * DefaultController implements the CRUD actions for Portfolio model.
 */
class DefaultController extends Controller
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
                        'actions' => ['create', 'update', 'delete', 'view', 'image-upload', 'main'],
                        'allow'   => true,
                        'roles'   => [
                            'portfolio_portfolio',
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all Portfolio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PortfolioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
        $dataProvider->pagination->pageSize = 100;

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionView($id)
    {
        $post = Yii::$app->request->post();
        $model = PortfolioReview::findOne(['portfolio_id' => $id]);
        if (!$model || $model->portfolio->status === Portfolio::STATUS_DELETED) {
            throw new NotFoundHttpException();
        }
        if ($model->load($post) && $model->validate()) {
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = ImageHelper::uploadImage($model, $image, true);
            } elseif (!empty($model->oldAttributes['image'])) {
                $model->image = $model->oldAttributes['image'];
            }
            $model->save();
            return $this->redirect('index');
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @return array|string|Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new Portfolio();
        return $this->modify($model);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model || $model->status === Portfolio::STATUS_DELETED) {
            throw new NotFoundHttpException();
        }
        return $this->modify($model);
    }

    /**
     * @param $model Portfolio
     * @return array|string|Response
     * @throws Exception
     */
    public function modify($model)
    {
        $isNew = $model->isNewRecord;
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->validate()) {
            Yii::$app->cache->delete('mainportfolio');
            if ($isNew) {
                $review = new PortfolioReview();
                if (!$model->author_id) {
                    $model->author_id = Yii::$app->user->id;
                }
            }
            $model->content = Html::makeTypo($model->content);
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = ImageHelper::uploadImage($model, $image, true);
            } elseif (!empty($model->oldAttributes['image'])) {
                $model->image = $model->oldAttributes['image'];
            }

            $verticalPrev = UploadedFile::getInstance($model, 'vertical_preview');
            if ($verticalPrev) {
                $model->vertical_preview = ImageHelper::uploadImage($model, $verticalPrev, true);
            } elseif (!empty($model->oldAttributes['vertical_preview'])) {
                $model->vertical_preview = $model->oldAttributes['vertical_preview'];
            }

            $horizontalPrev = UploadedFile::getInstance($model, 'horizontal_preview');
            if ($horizontalPrev) {
                $model->horizontal_preview = ImageHelper::uploadImage($model, $horizontalPrev, true);
            } elseif (!empty($model->oldAttributes['horizontal_preview'])) {
                $model->horizontal_preview = $model->oldAttributes['horizontal_preview'];
            }
            // Обновляем теги
            $model->save();
            $tags = $post['Portfolio'] && $post['Portfolio']['tags'] ? $post['Portfolio']['tags'] : [];
            $model->updateTags($tags);
            // скрытые теги
            $hidden = $post['Portfolio'] && $post['Portfolio']['hiddenTags'] ? $post['Portfolio']['hiddenTags'] : [];
            $model->updateHiddenTags($hidden);
            $model->save();
            GalleryHelper::processBlocks($model->content);
            if (isset($review)) {
                $review->portfolio_id = $model->id;
                $review->save();
            }
            return $this->redirect(['update', 'id' => $model->id]);
        }
        $tags = ArrayHelper::map(PortfolioTag::find()->all(), 'id', 'name');
        $hiddenTags = ArrayHelper::map(HiddenTag::find()->all(), 'id', 'name');
        $galleriesGuids = GalleryHelper::findBlocks($model->content);
        $guids = array_map(function ($model) {
            return trim($model);
        }, $galleriesGuids);
        $galleries = UtilsGallery::find()->distinct()->where(['in', 'code', $guids])->all();
        return $this->render('update', [
            'model'      => $model,
            'tags'       => $tags,
            'hiddenTags' => $hiddenTags,
            'galleries'  => $galleries
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Portfolio::STATUS_DELETED;
        $model->slug = $model->slug . time();
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Portfolio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Portfolio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Portfolio::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['main', 'review-main'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
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
                    return ['status' => 'success', 'main' => $model->to_main];
                }
            }
        }
        return ['status' => 'fail'];
    }
}
