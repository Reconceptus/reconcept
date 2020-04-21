<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel modules\portfolio\models\PortfolioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Портфолио';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="portfolio-index">

        <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a('Добавить портфолио', ['create'], ['class' => 'btn btn-success']) ?>
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
                    'attribute'     => 'horizontal_preview',
                    'format'        => 'html',
                    'value'         => function ($model) {
                        return Html::img($model->horizontal_preview, ['class' => 'admin-grid-image']);
                    },
                    'filter'        => false
                ],
                'full_name',
                [
                    'attribute' => 'tags',
                    'value'     => function ($model) {
                        $tags = \yii\helpers\ArrayHelper::getColumn($model->tags, 'name');
                        return implode(', ', $tags);
                    },
                    'filter'    => false,
                ],
                [
                    'attribute' => 'hiddenTags',
                    'value'     => function ($model) {
                        $tags = \yii\helpers\ArrayHelper::getColumn($model->hiddenTags, 'name');
                        return implode(', ', $tags);
                    },
                    'filter'    => false,
                ],
                'url:url',
                [
                    'attribute'     => 'status',
                    'headerOptions' => ['width' => 180],
                    'value'         => function ($model) {
                        return $model->status === \modules\portfolio\models\Portfolio::STATUS_ACTIVE ? 'Активен' : 'Отключен';
                    },
                    'filter'        => \modules\portfolio\models\Portfolio::STATUS_LIST,
                ],
                'views',
                'sort',
                //'horizontal_preview',
                //'content:ntext',
                //'to_main',
                //'to_footer',
                //'seo_title',
                //'seo_description',
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{update}',
                ],
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons'  => [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                Yii::$app->params['front'] . '/portfolio/' . $model->slug);
                        }
                    ]
                ],
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{review}',
                    'buttons'  => [
                        'review' => function ($url, $model) {
                            $class = $model->review->text ? 'color-green' : '';
                            return Html::a('<span class="fa fa-handshake-o ' . $class . '"></span>',
                                Url::to(['/portfolio/default/view', 'id' => $model->id]));
                        },
                    ]
                ],
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                ],
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{main}',
                    'buttons'  => [
                        'main' => function ($url, $model) {
                            $class = $model->to_main ? "check color-green" : "times";
                            return '<a class="js-portfolio-main fa fa-' . $class . '" href="" data-id="' . $model->id . '"></a>';
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>
<?php
$js = <<<JS
 $(document).on('click', '.js-portfolio-main', function(e) {
     e.preventDefault();
     e.stopPropagation();
        let button = $(this);
        let id = button.data('id');
        $.post('/portfolio/default/main', {id:id}).done(function(data) {
          if(data.status === 'success'){
              if(data.main){
                  button.removeClass('fa-times');
                  button.addClass('fa-check color-green');
              }else{
                  button.removeClass('fa-check color-green');
                  button.addClass('fa-times');
              }
          }
        });
    });
JS;
$this->registerJs($js);