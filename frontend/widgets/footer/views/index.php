<?php

use frontend\widgets\footerPortfolio\FooterPortfolio;
use modules\blog\models\BlogCategory;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var BlogCategory[] $models */
/* @var $contacts array */
/* @var $services array */

?>
<footer class="footer" id="footer">
    <div class="content content--lg">
        <div class="footer-main">
            <section class="footer-section">
                <div class="footer-section--part">
                    <header><h5 class="title">Услуги</h5></header>
                    <ul class="menu has-columns">
                        <?php foreach ($services as $service): ?>
                            <li><?= Html::a($service['name'], Url::to('/services/' . $service['slug'])) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a name="services"></a>
            </section>
            <?= FooterPortfolio::widget() ?>
            <section class="footer-section">
                <div class="footer-section--part">
                    <header><h5 class="title">Студия</h5></header>
                    <ul class="menu">
                        <li><a href="/about">О нас</a></li>
                        <li><a href="/blog">Блог</a></li>
                        <li><a href="/portfolio">Портфолио</a></li>
                        <li><a href="/contacts">Контакты</a></li>
                        <li><a href="/site/policy">Политика конфиденциальности</a></li>
                    </ul>
                </div>
                <div class="footer-section--part">
                    <header><h5 class="title">Страницы</h5></header>
                    <ul class="menu">
                        <?php foreach ($contacts as $contact): ?>
                            <li><?= Html::a($contact['name'], Url::to($contact['value']), ['target' => '_blank']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        </div>
        <div class="footer-copyright">
            &copy; 2010-<?= date('Y') ?> <?= Html::encode(Yii::$app->name) ?>
        </div>
    </div>
</footer>