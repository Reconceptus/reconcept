<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 16:34
 */

use frontend\widgets\forms\Forms;
use modules\blog\models\BlogCategory;

/* @var string $slug */
/* @var BlogCategory $models */
?>
<aside class="page-sidebar">
    <div class="sidebar">
        <div class="sidebar-main">
            <div class="sidebar-header">
                <h1 class="title"><?= $slug === 'favorite' ? 'Мне нравится' : 'Блог' ?></h1>
                <div class="sidebar-modules">
                    <div class="search">
                        <button type="button" class="search-btn" id="showSearch">
                            <i class="ico">
                                <svg xmlns="http://www.w3.org/2000/svg">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-search"/>
                                </svg>
                            </i>
                            Поиск
                        </button>
                    </div>
                    <?= Forms::widget(['viewName' => 'blog_sidebar']) ?>
                </div>
            </div>
            <?= $this->render('_blog_sidebar_categories', ['models' => $models, 'slug' => $slug]) ?>
        </div>
    </div>
</aside>
