<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 18.02.2019
 * Time: 13:44
 */

use yii\widgets\ListView; ?>
<div id="main" class="main">
    <div class="portfolio">
        <div class="content content--lg">
            <div class="page-title">
                <h1 class="page-title--text"><?= $this->title ?></h1>
            </div>
            <div class="portfolio-section">
                <div class="portfolio-grid">
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
                    <ul>
                        <li>

                        </li>
                    </ul>
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
