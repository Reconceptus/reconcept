<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modules\services\models\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Услуги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить услугу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options'      => ['class' => 'table-responsive'],
        'tableOptions' => ['class' => 'table table-condensed table-striped'],
        'filterModel'  => $searchModel,
        'rowOptions'   => function ($model, $key, $index, $grid) {
            return [
                'onclick' => 'window.location = "' . Url::to(['update', 'id' => $model->id]) . '"',
            ];
        },
        'columns'      => [
            [
                'attribute' => 'id',
                'options'   => ['style' => 'width:40px'],
            ],
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'category_id',
                'value'     => function ($model) {
                    return $model->category ? $model->category->name : '';
                },
                'filter'    => \modules\services\models\ServiceCategory::getList()
            ],
            [
                'attribute' => 'text',
                'format'    => 'html',
                'value'     => function ($model) {
                    return mb_substr($model->text, 0, 60);
                },
            ],
            [
                'attribute'     => 'status',
                'headerOptions' => ['width' => 100],
                'value'         => function ($model) {
                    return $model->status === \modules\services\models\Service::STATUS_ACTIVE ? 'Активен' : 'Отключен';
                },
                'filter'        => [0 => 'Отключен', 1 => 'Активен'],
            ],
            'views',
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'buttons'  => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            Yii::$app->params['front'] . '/services/' . $model->slug);
                    }
                ],
                'options'  => ['style' => 'width:60px'],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
