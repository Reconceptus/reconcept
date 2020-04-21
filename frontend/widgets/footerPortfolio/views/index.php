<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 16:34
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var \modules\portfolio\models\Portfolio[] $models */
?>
<section class="footer-section">
    <div class="footer-section--part">
        <header><h5 class="title">Портфолио</h5></header>
        <ul class="menu has-columns">
            <?php foreach ($models as $model): ?>
                <li><?= Html::a($model->name, Url::to('/portfolio/' . $model->slug)) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>