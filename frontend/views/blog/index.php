<?php

use frontend\components\CustomPager;
use frontend\widgets\blogSidebar\BlogSidebar;
use frontend\widgets\forms\Forms;
use frontend\widgets\postPreview\PostPreview;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ListView;

/* @var $slug string|null */
/* @var $favorites array */
/* @var $this View */
$this->title = 'Блог компании Reconcept';
$this->registerMetaTag(['name' => 'description', 'content' => 'Блог компании Reconcept']);
$this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->request->getHostInfo().'/'.Yii::$app->request->getPathInfo()]);
?>
<div id="main" class="main">

    <div class="page blog">
        <div class="content content--lg">
            <div class="blog-section">
                <div class="page-layout">
                    <?= BlogSidebar::widget(['slug' => $slug ?? null]) ?>
                    <div class="page-main-part">
                        <div class="blog-grid">
                            <?= ListView::widget([
                                'dataProvider' => $dataProvider,
                                'options'      => [
                                    'tag'   => 'ul',
                                    'class' => '',
                                ],
                                'emptyText'    => isset($slug) && $slug === 'favorite' ? 'Ваша подборка пуста. Отмечайте понравившиеся статьи нажатием на флажок и формируйте собственную подборку полезных статей от ReConcept' : 'Ничего не найдено',
                                'pager'        => [
                                    'nextPageLabel'      => '',
                                    'prevPageLabel'      => '',
                                    'maxButtonCount'     => '10',
                                    'activePageCssClass' => 'current',
                                    'linkOptions'        => [
                                        'class' => 'pager-el',
                                    ],
                                    'options'            => [
                                        'class' => 'pager'
                                    ],
                                ],
                                'itemOptions'  => [
                                    'tag'   => 'li',
                                    'class' => ''
                                ],
                                'layout'       => "{items}",
                                'itemView'     => function ($model) use ($favorites) {
                                    return PostPreview::widget(['model' => $model, 'favorites' => $favorites]);
                                },
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <?= CustomPager::widget([
                    'pagination'         => $dataProvider->getPagination(),
                    'linkOptions'        => [],
                    'activePageCssClass' => 'current',
                    'nextPageLabel'      => '>',
                    'prevPageLabel'      => '<',
                    'prevPageCssClass'   => 'prev',
                    'nextPageCssClass'   => 'next',
                ]) ?>
            </div>

            <?= Forms::widget(['viewName' => 'consultation']) ?>
        </div>
    </div>
</div>
