<?php

use modules\position\models\PositionRequest;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel modules\position\models\PositionRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запросы позиции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-request-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить отслеживание', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Сделать одиночный запрос', ['find'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'query',
            'domain',
            'last_result',
            [
                'attribute' => 'depth',
                'value'     => function ($model) {
                    return ArrayHelper::getValue(PositionRequest::DEPTH_LIST, $model->depth);
                }
            ],
            [
                'attribute' => 'status',
                'value'     => function ($model) {
                    return ArrayHelper::getValue(PositionRequest::STATUS_LIST, $model->status);
                },
                'filter'    => PositionRequest::STATUS_LIST
            ],
            ['class' => 'yii\grid\ActionColumn'],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{position}',
                'buttons'  => [
                    'position' => function ($url, $model) {
                        return 'Обновить';
                    },
                ]
            ],
        ],
    ]); ?>
</div>
