<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 16:34
 */
/* @var \frontend\modules\mainpage\models\MainPageTop $model */
/* @var bool $isDesktop */
?>
<div class="home-banner">
    <div class="content content--lg">
        <div class="banner-container">
            <figure class="banner"
                    style="background-image:  url(<?= $isDesktop ? $model->image : $model->image_preview ?>)">
                <figcaption>
                    <h1 class="title"><?= $model->quote ?></h1>
                    <div class="auth"><?= $model->sign ?></div>
                </figcaption>
                <div class="logo" style="display: none">
                    <svg xmlns="http://www.w3.org/2000/svg">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-reconcept"/>
                    </svg>
                </div>
            </figure>
        </div>
    </div>
</div>
