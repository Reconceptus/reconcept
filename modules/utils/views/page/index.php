<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modules\utils\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статичные страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить страницу', ['create'], ['class' => 'btn btn-success']) ?>
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
                'options'   => ['style' => 'width:40px'],
            ],
            [
                'attribute' => 'name',
                'options'   => ['style' => 'width:100px; overflow:auto'],
            ],
            [
                'attribute' => 'text',
                'format'    => 'html',
                'value'     => function ($model) {
                    return mb_substr($model->text, 0, 250);
                },
            ],
            [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {delete}',
                    'buttons'  => [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                Yii::$app->params['front'] . '/site/' . $model->slug);
                        }
                    ],
                    'options'   => ['style' => 'width:40px'],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
