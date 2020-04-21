<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 16:34
 */
/* @var \modules\portfolio\models\PortfolioReview[] $models */
?>
<div class="reviews">
    <div class="content content--lg">
        <div class="reviews--main">
            <h3 class="title">Отзывы клиентов</h3>
            <div class="content content--sm">
                <div class="reviews--slider">
                    <div class="owl-carousel owl-theme">
                        <?php foreach ($models as $model): ?>
                            <div class="item">
                                <div class="review--layout">
                                    <aside class="review--aside">
                                        <figure class="person">
                                            <img src="<?= $model->image ?>" alt="">
                                            <figcaption>
                                                <p><?= $model->fio ?></p>
                                                <p><?= $model->position ?></p>
                                            </figcaption>
                                        </figure>
                                    </aside>
                                    <div class="review--main">
                                        <div class="text-box">
                                            <blockquote>
                                                <p>
                                                    <?= mb_substr($model->text, 0, 500) ?>
                                                </p>
                                            </blockquote>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$('.owl-carousel').owlCarousel({
       loop:true,
       margin:10,
       nav:false,
        items:1
   });
JS;
$this->registerJs($js);
