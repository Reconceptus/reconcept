<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modules\utils\models\SubscriberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подписчики';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscriber-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить подписчика', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'options'      => ['class' => 'table-responsive'],
        'tableOptions' => ['class' => 'table table-condensed table-striped'],
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'filter'    => false,
            ],
            [
                'attribute' => 'user_id',
                'label'     => 'Зарегистрированный пользователь',
                'filter'    => false,
                'value'     => function ($model) {
                    return $model->user ? $model->user->fio : '';
                }
            ],
            [
                'attribute' => 'email',
            ],
            [
                'attribute' => 'status',
                'filter'    => \modules\utils\models\Subscriber::STATUS_LIST,
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{update}',
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{remove}',
                'buttons'  => [
                    'remove' => function ($url, $model) {
                        return Html::a('<span class="fa fa-times" title="Отключить"></span>', \yii\helpers\Url::to(['remove', 'id' => $model->id]));
                    },
                ]
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
