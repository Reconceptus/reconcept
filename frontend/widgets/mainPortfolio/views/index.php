<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 16:34
 */
/* @var \modules\portfolio\models\Portfolio[] $models */
?>
<div class="home-portfolio portfolio">
    <div class="content content--lg">
        <div class="portfolio-section">
            <div class="portfolio-grid">
                <ul>
                    <?php foreach ($models as $model): ?>
                        <li>
                            <a href="/<?= $model->slug ?>" class="item">
                                <figure class="item-img">
                                    <img src="<?= $model->horizontal_preview ?>"
                                         alt="<?= $model->name ?>">
                                </figure>
                                <section class="item-brief">
                                    <div class="item-arrow"></div>
                                    <div class="item-data">
                                        <h3 class="item-title"><?= $model->name ?></h3>
                                        <div class="item-subtitle"><?= $model->alt ?></div>
                                    </div>
                                </section>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="btn-box">
            <a href="/portfolio" class="btn btn-simple">Смотреть портфолио</a>
        </div>
    </div>
</div>