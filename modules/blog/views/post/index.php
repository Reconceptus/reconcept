<?php

use modules\blog\models\Post;
use modules\config\models\Config;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel modules\blog\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посты';
$this->params['breadcrumbs'][] = $this->title;
$values = [50, 100, 500, 2000];
$current = $dataProvider->getPagination()->getPageSize();

$columns = [
    [
        'attribute' => 'id',
        'options'   => ['style' => 'width:50px'],
        'filter'    => false
    ],
    [
        'headerOptions' => ['width' => 120],
        'options'       => ['style' => 'width:120px'],
        'attribute'     => 'image_preview',
        'format'        => 'html',
        'value'         => function ($model) {
            return Html::img($model->image_preview, ['class' => 'admin-grid-image']);
        },
        'filter'        => false
    ],
    [
        'attribute' => 'name',
    ],
    [
        'attribute'     => 'tags',
        'headerOptions' => ['width' => 300],
        'options'       => ['style' => 'max-width:300px'],
        'value'         => function ($model) {
            $tags = ArrayHelper::getColumn($model->tags, 'name');
            return implode(', ', $tags);
        },
        'filter'        => false,
    ],
    [
        'attribute'     => 'status',
        'headerOptions' => ['width' => 100],
        'value'         => function ($model) {
            return $model->status === Post::STATUS_ACTIVE ? 'Активен' : 'Отключен';
        },
        'filter'        => [0 => 'Отключен', 1 => 'Активен'],
    ],
    'views',
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
                    Yii::$app->params['front'] . '/blog/' . $model->slug);
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
                return '<a title="Выводить на главную" class="js-post-main fa fa-' . $class . '" href="" data-id="' . $model->id . '"></a>';
            },
        ]
    ],
];
if (Config::getValue('blog_post_to_letter')) {
    $columns[] = [
        'class'    => 'yii\grid\ActionColumn',
        'template' => '{letter}',
        'buttons'  => [
            'letter' => function ($url, $model) {
                $class = $model->to_letter ? "color-green" : "";
                return '<a title="Добавлять в письма" class="js-post-letter fa fa-envelope ' . $class . '" href="" data-id="' . $model->id . '"></a>';
            },
        ]
    ];
}
?>
    <div class="post-index">

        <h1><?= Html::encode($this->title) ?></h1>
        <p>
            <?= Html::a('Добавить пост', ['create'], ['class' => 'btn btn-success']) ?>
            <span class="col-xs-3">
                <select class="form-control" onchange="location = this.value;">
                    <?php foreach ($values as $value): ?>
                        <option value="<?= Html::encode(Url::current(['per-page' => $value, 'page' => null])) ?>"
                                <?php if ($current == $value): ?>selected="selected"<?php endif; ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </span>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'options'      => ['class' => 'table-responsive'],
            'tableOptions' => ['class' => 'table table-condensed table-striped'],
            'columns'      => $columns,
            'layout'       => '{items}{pager}'
        ]); ?>

    </div>
<?php
$js = <<<JS
$(document).on('click', '.js-post-main', function(e) {
    e.preventDefault();
    e.stopPropagation();
    let button = $(this);
    let id = button.data('id');
    $.post('/blog/post/main', {id:id}).done(function(data) {
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

$(document).on('click', '.js-post-letter', function(e) {
    e.preventDefault();
    e.stopPropagation();
    let button = $(this);
    let id = button.data('id');
    $.post('/blog/post/letter', {id:id}).done(function(data) {
        if(data.status === 'success'){
            if(data.main){
                button.addClass('color-green');
            }else{
                button.removeClass('color-green');
            }
        }
    });
});
JS;
$this->registerJs($js);