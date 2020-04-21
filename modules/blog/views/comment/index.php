<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modules\blog\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комментарии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

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
//            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'text',
            [
                'attribute' => 'status',
                'value'     => function ($model) {
                    return $model->statusName;
                },
                'filter'    => \modules\blog\models\Comment::getStatusList(),
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{page}',
                'buttons'  => [
                    'page' => function ($url, $model) {
                        return Html::a('<span class="fa fa-eye" title="Смотреть статью"></span>', Yii::$app->params['front'] . '/blog/' . $model->post->slug);
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
