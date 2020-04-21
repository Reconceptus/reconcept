<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 16:34
 */
/* @var \common\models\Image[] $models */
?>
<div class="partners">
    <div class="content content--lg">
        <div class="partners--main">
            <div class="content content--md">
                <div class="partners--list">
                    <ul>
                        <?php foreach ($models as $model): ?>
                            <li>
                                <div class="partner">
                                    <figure class="figure">
                                        <img src="<?= $model->image ?>"
                                             alt="<?= $model->alt ?>">
                                    </figure>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
