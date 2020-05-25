<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 16:34
 */
/* @var \modules\services\models\ServiceCategory[] $models */
?>
<div class="home-services">
    <div class="content content--lg">
        <div class="service-list">
            <div class="content content--md">
                <h3 class="title">Услуги</h3>
                <div class="list">
                    <?php foreach ($models as $model): ?>
                        <?php $services = $model->services ?>
                        <?php if ($services): ?>
                            <section class="list-section">
                                <div class="item">
                                    <div class="heading"><?= $model->name ?></div>
                                </div>
                                <?php foreach ($services as $service): ?>
                                    <?php if ($service->status === \modules\services\models\Service::STATUS_ACTIVE): ?>
                                        <?php if ($service->to_footer): ?>
                                            <div class="item"> <a href="<?= $service->url ?>"><?= $service->name ?></a></div>
                                        <?php else: ?>
                                            <div class="item"><?= $service->name ?></div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </section>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>