<?php

use frontend\widgets\itemPreview\ItemPreview;
use modules\shop\models\Category;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/* @var $dataProvider ActiveDataProvider */
/* @var $category Category */
/* @var $favorites array */
/* @var $inCart array */
/* @var $sort string */
?>
<div id="main" class="main">
    <div class="page blog">
        <div class="blog-section">
            <div class="page-layout">
                <aside class="page-sidebar">
                    <div class="sidebar">
                        <div class="sidebar-main">
                            <div class="sidebar-header">
                                <h1 class="title"><?= $category->name ?></h1>
                                <div class="sidebar-modules">
                                    <div class="search">
                                        <button type="button" class="search-btn" id="showSearch">
                                            <i class="ico">
                                                <svg xmlns="http://www.w3.org/2000/svg">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         xlink:href="#icon-search"/>
                                                </svg>
                                            </i>
                                            Поиск
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
                <div class="page-main-part">
                    <div class="blog-grid">
                        <?= ListView::widget([
                            'dataProvider' => $dataProvider,
                            'options'      => [
                                'tag'   => 'ul',
                                'class' => '',
                            ],
                            'emptyText'    => 'Ничего не найдено',
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
                                return ItemPreview::widget(['model' => $model, 'favorites' => $favorites]);
                            },
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>