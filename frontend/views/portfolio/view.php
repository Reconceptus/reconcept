<?php
/**
 * Created by PhpStorm.
 * User: adm
 * Date: 30.01.2019
 * Time: 09:39
 */

use common\models\Html;
use frontend\widgets\forms\Forms;
use frontend\widgets\portfolioRelated\PortfolioRelated;
use frontend\widgets\portfolioReview\PortfolioReview;
use frontend\widgets\share\Share;
use modules\config\models\Config;
use modules\portfolio\models\Portfolio;
use modules\utils\helpers\ContentHelper;

/* @var $model Portfolio */

$author = $model->author;
$this->title = $model->full_name;
?>
<div id="main" class="main">

    <div class="text-section slideDown" style="display: none;">
        <div class="content content--lg">
            <div class="text-section--main large">
                <div class="content">
                    <div class="page-title">
                        <h1 class="page-title--text"><?= Html::encode($this->title) ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Главное превью статьи -->

    <div class="project-main-preview full-width">
        <div class="figure">
            <img src="<?= $model->image ?>" alt="<?= $model->name ?>">
        </div>
    </div>


    <div class="text-section">
        <div class="content content--lg">
            <div class="text-section--main">
                <div class="text-box--wrap">
                    <div class="content content--md">
                        <div class="text-box--layout">
                            <?= Share::widget() ?>
                            <div class="text-box--main">
                                <div class="text-box">
                                    <?= Config::getValue('insert_galleries') ? ContentHelper::parseBlock(ContentHelper::parseGallery($model->content)) : $model->content ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($model->url): ?>
                            <div class="text-box--link">
                                <a target="_blank" href="<?= $model->url ?>" class="link">
                                    <?= $model->url ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= PortfolioReview::widget(['model' => $model->review]) ?>
    <?= PortfolioRelated::widget(['model' => $model]) ?>

    <div class="page">
        <div class="content content--lg">
            <?= Forms::widget(['viewName' => 'consultation']) ?>
        </div>
    </div>
</div>
