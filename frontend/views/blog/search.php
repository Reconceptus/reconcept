<?php
/**
 * Created by PhpStorm.
 * User: venodon
 * Date: 01.02.2019
 * Time: 11:09
 */

use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var string $q */
/* @var ActiveDataProvider $dataProvider */
$this->title = 'Поиск';
$this->registerMetaTag(['name' => 'description', 'content' => 'Поиск по блогу']);
$this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);
?>
<div id="main" class="main">

    <div class="page blog">
        <div class="content content--lg">
            <div class="blog-section">
                <div class="page-layout">
                    <aside class="page-sidebar sidebar">
                        <h1 class="title">Поиск статей</h1>
                        <div class="search">
                            <button type="button" class="search-btn" id="showSearch">
                                <i class="ico">
                                    <svg xmlns="http://www.w3.org/2000/svg">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-search"/>
                                    </svg>
                                </i>
                                Поиск
                            </button>
                        </div>
                        <?= \frontend\widgets\blogSidebar\BlogSidebar::widget() ?>
                    </aside>


                    <div class="page-main-part">
                        <div class="blog-grid">
                            <?php if (!$q): ?>
                                <h2>Задан пустой поисковой запрос</h2>
                            <?php else: ?>
                                <?= ListView::widget([
                                    'dataProvider' => $dataProvider,
                                    'options'      => [
                                        'tag'   => 'ul',
                                        'class' => '',
                                    ],
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
                                    'itemView'     => function ($model, $key, $index, $widget) {
                                        return $this->render('_list', ['model' => $model]);
                                    },
                                ]);
                                ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?= \frontend\components\CustomPager::widget([
                    'pagination'         => $dataProvider->getPagination(),
                    'linkOptions'        => [],
                    'activePageCssClass' => 'current',
                    'nextPageLabel'      => '>',
                    'prevPageLabel'      => '<',
                    'prevPageCssClass'   => 'prev',
                    'nextPageCssClass'   => 'next',
                ]) ?>
            </div>

            <?= \frontend\widgets\forms\Forms::widget(['viewName' => 'consultation']) ?>
        </div>
    </div>
</div>
