<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 16:34
 */
/* @var $models \modules\portfolio\models\Portfolio[] */

?>
<?php if ($models): ?>
    <div class="project-listing">
        <div class="content content--lg">
            <h3 class="title">Другие проекты</h3>
            <div class="listing">
                <ul>
                    <?php foreach ($models as $model): ?>
                        <li>
                            <a href="/portfolio/<?=$model->slug?>" class="item">
                                <figure class="item-img">
                                    <img src="<?=$model->vertical_preview?>"
                                         alt="<?=$model->name?>">
                                </figure>
                                <section class="item-brief">
                                    <div class="item-arrow"></div>
                                    <div class="item-data">
                                        <h3 class="item-title"><?=$model->name?></h3>
                                        <div class="item-subtitle"><?=$model->alt?></div>
                                    </div>
                                    <div class="item-btn">
<!--                                        <svg xmlns="http://www.w3.org/2000/svg" class="ico">-->
<!--                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"-->
<!--                                                 xlink:href="#icon-arrow-right"/>-->
<!--                                        </svg>-->
                                    </div>
                                </section>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>