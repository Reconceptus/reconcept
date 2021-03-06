<?php

use modules\blog\models\BlogCategory;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modules\blog\models\BlogCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'options'      => ['class' => 'table-responsive'],
        'tableOptions' => ['class' => 'table table-condensed table-striped'],
        'rowOptions'   => function ($model, $key, $index, $grid) {
            return [
                'onclick' => 'window.location = "' . Url::to(['update', 'id' => $model->id]) . '"',
            ];
        },
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options'   => ['style' => 'width:50px'],
                'filter'    => false
            ],
            [
                'headerOptions' => ['width' => 120],
                'attribute'     => 'image',
                'format'        => 'image',
                'filter'        => false
            ],
            'name',
            'description',
            [
                'attribute'     => 'status',
                'headerOptions' => ['width' => 180],
                'value'         => function ($model) {
                    return BlogCategory::STATUS_LIST[$model->status];
                },
                'filter'        => BlogCategory::STATUS_LIST,
            ],
            [
                'attribute' => 'depth',
                'options'   => ['style' => 'width:50px'],
                'filter'    => false
            ],

            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'buttons'  => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            Yii::$app->params['front'] . '/' . $model->slug);
                    }
                ]
            ],
        ],
        'layout'       => '{items}{pager}'
    ]); ?>
    <?php Pjax::end(); ?>
</div>
