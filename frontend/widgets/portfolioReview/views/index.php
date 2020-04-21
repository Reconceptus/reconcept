<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 16:34
 */
/* @var $model \modules\portfolio\models\PortfolioReview */

?>

<div class="text-section project-review">
    <div class="content content--lg">
        <div class="text-section--main">
            <div class="text-box--wrap">
                <div class="content content--md">
                    <h3 class="title">Отзыв клиента</h3>
                    <div class="text-box--layout centered">
                        <aside class="text-box--aside">
                            <figure class="person">
                                <img src="<?= $model->image ?>" alt="<?= $model->fio ?>">
                                <figcaption>
                                    <p><?= $model->fio ?>,</p>
                                    <p><?= $model->position ?></p>
                                </figcaption>
                            </figure>
                        </aside>
                        <div class="text-box--main">
                            <div class="text-box">
                                <blockquote>
                                    <p>
                                        <?= $model->text ?>
                                    </p>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
