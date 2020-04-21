<?php

namespace backend\controllers;

use common\helpers\FileHelper;
use common\helpers\ImageHelper;
use common\models\File;
use common\models\Image;
use modules\config\models\Config;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class FileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete-model-image', 'delete-image', 'delete-single-image', 'upload-image', 'sort-image', 'sort-file', 'delete-file', 'set-alt', 'process', 'import', 'editor-upload'],
                        'allow'   => true,
                        'roles'   => ['adminPanel'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'       => ['POST'],
                    'delete-image' => ['POST'],
                    'sort-image'   => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {

        $maxWidth = Config::getValue('maxWidth');
        $maxHeight = Config::getValue('maxHeight');
        if(!$maxWidth) $maxWidth = 2000;
        if(!$maxHeight) $maxHeight = 2000;
        return [
            'editor-upload' => [
                'class'            => 'vova07\imperavi\actions\UploadFileAction',
                'url'              => '/uploads/images/ed',
                'path'             => '@frontend/web/uploads/images/ed',
                'translit'         => true,
                'validatorOptions' => [
                    'maxWidth'  => $maxWidth,
                    'maxHeight' => $maxHeight
                ],
            ],
            ''
        ];
    }


    /**
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionSetAlt()
    {
        $class = Yii::$app->request->post('class');
        if (!$class) {
            $class = 'common\models\Image';
        }
        /* @var $model Image */
        $model = $class::findOne(['id' => Yii::$app->request->post('id')]);
        if ($model) {
            $model->alt = Yii::$app->request->post('value');
            $model->save();
            return true;
        }
        throw new NotFoundHttpException();
    }

    /**
     * Сортировка картинок
     * @param $id
     * @return bool
     * @throws MethodNotAllowedHttpException
     */
    public function actionSortImage($id)
    {
        $type = Yii::$app->request->get('type');
        if (!$type) {
            $type = 1;
        }
        if (Yii::$app->request->isAjax) {
            $sort = Yii::$app->request->post('sort');
            if ($sort['oldIndex'] > $sort['newIndex']) {
                $param = ['and', ['>=', 'sort', $sort['newIndex']], ['<', 'sort', $sort['oldIndex']], ['type' => $type]];
                $counter = 1;
            } else {
                $param = ['and', ['<=', 'sort', $sort['newIndex']], ['>', 'sort', $sort['oldIndex']], ['type' => $type]];
                $counter = -1;
            }
            Image::updateAllCounters(['sort' => $counter], [
                'and', ['class' => $sort['stack'][$sort['newIndex']]['class'], 'item_id' => $id], $param
            ]);
            Image::updateAll(['sort' => $sort['newIndex']], [
                'id' => $sort['stack'][$sort['newIndex']]['key']
            ]);
            return true;
        }
        throw new MethodNotAllowedHttpException();
    }

    /**
     * Сортировка файлов
     * @param $id
     * @return bool
     * @throws MethodNotAllowedHttpException
     */
    public function actionSortFile($id)
    {
        if (Yii::$app->request->isAjax) {
            $sort = Yii::$app->request->post('sort');
            if ($sort['oldIndex'] > $sort['newIndex']) {
                $param = ['and', ['>=', 'sort', $sort['newIndex']], ['<', 'sort', $sort['oldIndex']]];
                $counter = 1;
            } else {
                $param = ['and', ['<=', 'sort', $sort['newIndex']], ['>', 'sort', $sort['oldIndex']]];
                $counter = -1;
            }
            File::updateAllCounters(['sort' => $counter], [
                'and', ['class' => $sort['stack'][$sort['newIndex']]['class'], 'item_id' => $id], $param
            ]);
            File::updateAll(['sort' => $sort['newIndex']], [
                'id' => $sort['stack'][$sort['newIndex']]['key']
            ]);
            return true;
        }
        throw new MethodNotAllowedHttpException();
    }

    /**
     * @return bool
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteImage()
    {
        $post = Yii::$app->request->post();
        if (!empty($post['key'])) {
            $model = Image::findOne(['id' => $post['key']]);
            if ($model) {
                $model->delete();
                return true;
            }
        }
        throw new NotFoundHttpException();
    }

    public function actionDeleteSingleImage()
    {
        $post = Yii::$app->request->post();
        $field = Yii::$app->request->get('field');
        if (!$field) {
            $field = 'image';
        }
        if (!empty($post['key']) && !empty($post['class'])) {
            $class = $post['class'];
            $model = $class::findOne(['id' => intval($post['key'])]);
            if ($model) {
                $model->$field = '';
                if ($model->save()) {
                    return true;
                }
            }
        }
        throw new NotFoundHttpException();
    }

    /**
     * @return bool
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteFile()
    {
        $post = Yii::$app->request->post();
        if (!empty($post['key'])) {
            $model = File::findOne(['id' => $post['key']]);
            if ($model) {
                $model->delete();
                return true;
            }
        }
        throw new NotFoundHttpException();
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function actionUploadImage()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post('Image');
            if (!empty($post['item_id'])) {
                $model = new Image();
                $model->class = $post['class'];
                $model->item_id = $post['item_id'];
                if ($model->save()) {
                    $file = UploadedFile::getInstancesByName('images')[0];
                    $model->image = ImageHelper::uploadImage($model, $file, true);
                    if ($model->save()) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function actionUploadFile()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post('Image');
            if (!empty($post['item_id'])) {
                $model = new File();
                $model->class = $post['class'];
                $model->item_id = $post['item_id'];
                if ($model->save()) {
                    $file = UploadedFile::getInstancesByName('images')[0];
                    $model->file = FileHelper::uploadFile($model, $file, true);
                    if ($model->save()) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
