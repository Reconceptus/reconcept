<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\mainpage\models\MainPageTopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Верхний блок главной страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-page-top-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <p>
        <?= Html::a('Добавить вариант', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'image',
                'format'    => 'html',
                'options'   => ['style' => 'width:150px'],
                'filter'    => false,
                'value'     => function ($model) {
                    return Html::img($model->image, ['width' => 150]);
                }
            ],
            [
                'attribute' => 'image_preview',
                'options'   => ['style' => 'width:150px'],
                'format'    => 'html',
                'filter'    => false,
                'value'     => function ($model) {
                    return Html::img($model->image_preview, ['width' => 150]);
                }
            ],
            'quote',
            'sign',

            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
