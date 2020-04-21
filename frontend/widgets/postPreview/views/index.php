<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 16:34
 */

use common\helpers\DateTimeHelper;
use common\models\Html;
use modules\blog\models\Post;
use yii\helpers\Url;

/* @var Post $model */
/* @var array $favorites */
?>
<div class="item">
    <figure class="item-img">
        <?= Html::a('', Url::to('/blog/' . $model->slug), ['class' => 'item-link']) ?>
        <?= Html::img($model->image_preview, ['alt' => $model->name]) ?>
        <button type="button" class="favorite  <?= in_array($model->id, $favorites) ? 'liked' : '' ?>">
            <span data-id="<?= $model->id ?>" class="js-favor icon"></span>
        </button>
    </figure>
    <section class="item-brief">
        <div class="item-data">
            <span class="item-tag"><?= $model->categoryName ?></span>
            <time class="item-date"><?=DateTimeHelper::getDateRuFormat($model->created_at) ?></time>
        </div>
        <h3 class="item-title">
            <?= Html::a($model->name, Url::to('/blog/' . $model->slug)) ?>
        </h3>
        <div class="item-info">
            <span class="views">
                <i class="ico">
                    <svg xmlns="http://www.w3.org/2000/svg">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-eye"/>
                    </svg>
                </i>
                <?= $model->views ?>
            </span>
            <span class="comments">
                <i class="ico">
                    <svg xmlns="http://www.w3.org/2000/svg">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                             xlink:href="#icon-comment"/>
                    </svg>
                </i>
                <?= count($model->comments) - 1 ?>
            </span>
        </div>
    </section>
</div>