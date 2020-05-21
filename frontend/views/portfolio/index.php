<?php
/**
 * Created by PhpStorm.
 * User: venodon
 * Date: 30.01.2019
 * Time: 10:11
 */

use modules\config\models\Config;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var \modules\portfolio\models\PortfolioTag[] $tags */
/* @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Config::getValue('portfolio_index_seo_title');
$this->registerMetaTag(['name' => 'description', 'content' => Config::getValue('portfolio_index_seo_description')]);
$this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->request->getHostInfo().'/'.Yii::$app->request->getPathInfo()]);
?>
<div id="main" class="main">
    <div class="portfolio">
        <div class="content content--lg">
            <div class="page-title">
                <h1 class="page-title--text"><?= Config::getValue('portfolio_index_title') ?></h1>
            </div>
            <div class="portfolio-section">
                <div class="portfolio-categories">
                    <ul>
                        <li><?= Html::a('Все проекты', Url::to('/portfolio')) ?></li>
                        <?php foreach ($tags as $tag): ?>
                            <li><?= Html::a($tag->name, Url::to(['/portfolio', 'tag' => $tag->name])) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
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
