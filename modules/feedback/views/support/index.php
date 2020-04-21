<?php

use modules\feedback\models\Support;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modules\feedback\models\SupportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обращения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="support-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'options'      => ['class' => 'table-responsive'],
        'tableOptions' => ['class' => 'table table-condensed table-striped'],
        'rowOptions'   => function ($model) {
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
            'name',
            'email:email',
            'phone',
            'contact',
            [
                'attribute' => 'status',
                'filter'    => Support::STATUS_LIST,
                'value'     => function ($model) {
                    return Support::STATUS_LIST[$model->status];
                }
            ],
            'created_at',
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{download}',
                'buttons'  => [
                    'download' => function ($url, $model) {
                        return $model->file ? Html::a('<span class="fa fa-download" title="Загрузить файл"></span>', Yii::$app->params['front'] . $model->file) : '';
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
