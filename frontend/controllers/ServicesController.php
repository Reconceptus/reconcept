<?php

namespace frontend\controllers;

use modules\services\models\Service;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Blog controller
 */
class ServicesController extends Controller
{

    /**
     * Displays post list.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Service::find()->active();
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 40,
            ],
            'sort'       => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        $model = Service::findOne(['slug' => \Yii::$app->request->get('slug')]);
        if (!$model || $model->status != Service::STATUS_ACTIVE) {
            throw new NotFoundHttpException();
        }
        $model->views++;
        $model->save();
        return $this->render('view', ['model' => $model]);
    }
}
