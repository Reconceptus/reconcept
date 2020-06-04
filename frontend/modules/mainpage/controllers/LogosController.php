<?php

namespace frontend\modules\mainpage\controllers;

use common\helpers\ImageHelper;
use common\models\Image;
use frontend\modules\mainpage\models\Pages;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class LogosController extends Controller
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
                            'mainpage_logos',
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
                'url'              => '/uploads/images/logo', // Directory URL address, where files are stored.
                'path'             => '@images/logo', // Or absolute path to directory where files are stored.
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
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = Pages::findOne(['id' => 1]);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $images = UploadedFile::getInstancesByName('images');
            foreach ($images as $im) {
                $imgModel = new Image();
                $imgModel->class = $model->formName();
                $imgModel->item_id = $model->id;
                $imgModel->image = ImageHelper::uploadImage($imgModel, $im);
                $imgModel->thumb =  $imgModel->image;
                if ($imgModel->validate()) {
                    $imgModel->save();
                } else {
                    var_dump($imgModel->errors);
                    die;
                }
            }
        }
        return $this->render('index', ['model' => $model]);
    }
}
