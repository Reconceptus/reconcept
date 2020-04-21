<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 18.02.2019
 * Time: 13:54
 */
/* @var $model \modules\services\models\Service*/

use yii\helpers\Url; ?>
<a href="<?= Url::to('/services/' . $model->slug) ?>" class="item">
    <figure class="item-img">
        <img src="<?= $model->image ?>"
             alt="<?= $model->name ?>">
    </figure>
    <section class="item-brief">
        <div class="item-data">
            <h3 class="item-title"><?= $model->name ?></h3>
        </div>
        <div class="item-btn">
<!--            <svg xmlns="http://www.w3.org/2000/svg" class="ico">-->
<!--                <use xmlns:xlink="http://www.w3.org/1999/xlink"-->
<!--                     xlink:href="#icon-arrow-right"/>-->
<!--            </svg>-->
        </div>
    </section>
</a>

