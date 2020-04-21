<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 26.07.2018
 * Time: 17:13
 */

namespace frontend\controllers;

use common\models\User;
use modules\shop\models\Cart;
use modules\shop\models\Category;
use modules\shop\models\Item;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ShopController extends Controller
{
    /**
     * @return string
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionIndex()
    {
        $get = Yii::$app->request->get();

        // Определяем категорию
        $category = isset($get['category']) ? $get['category'] : '';
        if (!$category) {
            $category = Category::find()->where(['status' => Category::STATUS_ACTIVE])->one();
        } else {
            $category = Category::find()->where(['slug' => $category])->andWhere(['status' => Category::STATUS_ACTIVE])->one();
        }
        if (!$category) {
            throw new NotFoundHttpException();
        }

        $query = Item::getFilteredQuery($category, $get);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize'        => 24,
                'defaultPageSize' => 24,
            ],
            'sort'       => ['defaultOrder' => ['sort' => SORT_DESC, 'id' => SORT_DESC]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'category'     => $category,
            'favorites'    => User::getFavoriteIds(),
            'inCart'       => Cart::getInCart(),
            'sort'         => isset($get['sort']) ? $get['sort'] : '',
        ]);
    }

    public function actionView()
    {
        $model = Item::find()->where(['slug' => Yii::$app->request->get('slug'), 'status' => Item::STATUS_ACTIVE])->one();
        if (!$model) {
            throw new NotFoundHttpException('Товар не найден');
        }
        return $this->render('view', [
            'model'     => $model,
            'favorites' => User::getFavoriteIds(),
            'inCart'    => Cart::getInCart()
        ]);
    }

    public function actionHistory()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $arr = Yii::$app->request->get('arr');
        $models = Item::find()->where(['in', 'id', $arr])->andWhere(['status' => Item::STATUS_ACTIVE])->all();
        $html = $this->renderPartial('_history', ['models' => array_reverse($models)]);
        return ['status' => 'success', 'html' => $html];
    }
}