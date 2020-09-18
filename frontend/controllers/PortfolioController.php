<?php

namespace frontend\controllers;

use modules\portfolio\models\Portfolio;
use modules\portfolio\models\PortfolioTag;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Blog controller
 */
class PortfolioController extends Controller
{

    /**
     * Displays post list.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Portfolio::find()->joinWith('tags t')->joinWith('review')->where(['status' => Portfolio::STATUS_ACTIVE]);
        if ($tag = \Yii::$app->request->get('tag')) {
            $query->andWhere(['t.name' => $tag]);
        }
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 40,
            ],
            'sort'       => [
                'defaultOrder' => [
                    'sort' => SORT_DESC
                ]
            ],
        ]);
        $tags = PortfolioTag::find()->alias('t')->joinWith('portfolio p')->where(['p.status' => Portfolio::STATUS_ACTIVE])->orderBy(['t.sort'=>SORT_DESC])->all();
        return $this->render('index', ['tags' => $tags, 'dataProvider' => $dataProvider]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        $slug = \Yii::$app->request->get('slug');
        $url = \Yii::$app->request->getUrl();
        $pos = mb_strpos($url, '/portfolio');
        if ($pos === false) {
            return $this->redirect('/portfolio/' . $slug);
        }
        $query = Portfolio::find()->where(['slug' => $slug]);
        if (!Yii::$app->user->can('admin')) {
            $query->andWhere(['status' => Portfolio::STATUS_ACTIVE]);
        }
        $model = $query->one();
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $model->views = ++$model->views;
        $model->save();
        return $this->render('view', ['model' => $model]);
    }
}
