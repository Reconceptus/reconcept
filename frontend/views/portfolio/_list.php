<?php
/**
 * Created by PhpStorm.
 * User: venodon
 * Date: 30.01.2019
 * Time: 10:18
 */

use modules\portfolio\models\Portfolio;
use yii\helpers\Url;

/* @var $model Portfolio */
?>
<a href="<?= Url::to('/portfolio/' . $model->slug) ?>" class="item">
    <figure class="item-img">
        <img src="<?= $model->horizontal_preview ?>"
             alt="<?= $model->full_name ?>">
    </figure>
    <section class="item-brief">
        <div class="item-data">
            <h3 class="item-title"><?= $model->name ?></h3>
            <div class="item-subtitle"><?= $model->alt ?></div>
        </div>
        <div class="item-btn">
<!--            <svg xmlns="http://www.w3.org/2000/svg" class="ico">-->
<!--                <use xmlns:xlink="http://www.w3.org/1999/xlink"-->
<!--                     xlink:href="#icon-arrow-right"/>-->
<!--            </svg>-->
        </div>
    </section>
</a>
