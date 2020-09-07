<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modules\feedback\models\SupportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История запросов';
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
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'query',
            'domain',
            'position',
            'created_at'
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
