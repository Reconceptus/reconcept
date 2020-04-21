<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 16:34
 */
/* @var \modules\blog\models\Post[] $models */
/* @var array $favorites */
/* @var \common\models\User $user */
?>
<div class="main-articles">
    <div class="blog">
        <div class="content content--lg">
            <div class="blog-section">
                <div class="page-layout">
                    <aside class="page-sidebar">
                        <div class="sidebar">
                            <div class="sidebar-main">
                                <div class="sidebar-header">
                                    <div>
                                        <h3 class="title">Блог <br>Сергея Веснина</h3>
                                        <div class="text">Еженедельно свежие статьи на интересующие вас темы, читайте
                                            в
                                            <a href="/blog" target="_blank">блоге</a>, подписывайтесь на
                                            рассылку.
                                        </div>
                                    </div>
                                    <div class="img">
                                        <img src="<?= $user->getAvatar() ?>" alt="<?= $user->fio ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                    <div class="page-main-part">
                        <div class="blog-grid">
                            <ul>
                                <?php foreach ($models as $model): ?>
                                    <li><?= \frontend\widgets\postPreview\PostPreview::widget(['model' => $model, 'favorites' => $favorites]) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>