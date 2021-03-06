<?php

use modules\utils\models\UtilsGallery;
use modules\utils\models\UtilsLayout;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modules\utils\models\UtilsGallerySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Галереи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="utils-gallery-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать галерею', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'options'      => ['class' => 'table-responsive'],
        'tableOptions' => ['class' => 'table table-condensed table-striped'],
        'columns'      => [

            [
                'attribute' => 'id',
                'options'   => ['style' => 'width:50px'],
                'filter'    => false
            ],
            [
                'headerOptions' => ['width' => 120],
                'options'       => ['style' => 'width:120px'],
                'attribute'     => 'images',
                'format'        => 'html',
                'value'         =>
                    static function ($model) {
                        /* @var $model UtilsGallery */
                        return Html::img($model->preview, ['class' => 'admin-grid-image']);
                    },
                'filter'        => false
            ],
            'code',
            'name',
            [
                'attribute' => 'layout_id',
                'value'     =>
                    static function ($model) {
                        /* @var $model UtilsGallery */
                        return $model->layout ? $model->layout->name : '';
                    },
                'filter'    =>ArrayHelper::map(UtilsLayout::find()->all(), 'id','name')
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
