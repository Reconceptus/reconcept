<?php

use modules\config\models\Config;
use yii\helpers\Url;

$this->title = Config::getValue('mainPageTitle');
$this->registerMetaTag(['name' => 'description', 'content' => Config::getValue('mainPageDescription')]);
$this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->request->getHostInfo().'/'.Yii::$app->request->getPathInfo()]);
?>
<div id="main" class="main">

    <div class="main-content">

        <?= \frontend\widgets\mainTop\MainTop::widget() ?>

        <?= \frontend\widgets\mainPortfolio\MainPortfolio::widget() ?>

        <?= \frontend\widgets\mainText\MainText::widget() ?>

        <?= \frontend\widgets\mainServices\MainServices::widget() ?>

        <?= \frontend\widgets\mainBlog\MainBlog::widget() ?>

        <?= \frontend\widgets\mainReviews\MainReviews::widget() ?>

        <?= \frontend\widgets\mainLogos\MainLogos::widget() ?>

        <div class="page">
            <div class="content content--lg">
                <?= \frontend\widgets\forms\Forms::widget(['viewName' => 'consultation']) ?>
            </div>
        </div>
    </div>
</div>