<?php
/**
 * Created by PhpStorm.
 * User: venodon
 * Date: 24.01.2019
 * Time: 12:36
 */
/* @var \modules\blog\models\Post[] $models */
/* @var $slug string */
/* @var $favorites array */
?>
<div class="article-listing">
    <div class="content content--lg">
        <h3 class="title">РАЗВЕРНУТЬ СТАТЬИ ПО ТЕМЕ</h3>
        <div class="listing">
            <ul>
                <?php foreach ($models as $model): ?>
                    <li><?= \frontend\widgets\postPreview\PostPreview::widget(['model' => $model, 'favorites' => $favorites]) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>