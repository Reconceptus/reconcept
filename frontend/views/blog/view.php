<?php
/**
 * Created by PhpStorm.
 * User: adm
 * Date: 19.12.2018
 * Time: 13:37
 */

use common\helpers\DateTimeHelper;
use frontend\widgets\comments\Comments;
use frontend\widgets\forms\Forms;
use frontend\widgets\related\Related;
use frontend\widgets\share\Share;
use modules\blog\models\Post;
use modules\config\models\Config;
use modules\utils\helpers\ContentHelper;
use yii\helpers\Html;

/* @var $model Post */

$author = $model->author;
$this->title = $model->title;
$this->registerMetaTag(['name' => 'description', 'content' => $model->description]);
?>
<div id="main" class="main">

    <div class="text-section">
        <div class="content content--lg">
            <div class="text-section--main">
                <div class="content">
                    <div class="page-title">
                        <div class="article-info">
                            <time class="item-date"><?= DateTimeHelper::getDateRuFormat($model->created_at) ?></time>
                            <span class="item-num">Статья №<?= $model->id ?></span>
                            <?php if (Yii::$app->user->can('blog_post')): ?>
                                <span style="margin-left: 25px"><a target="_blank"
                                                                   href="<?= Yii::$app->params['back'] . '/blog/post/update?id=' . $model->id ?>">Редактировать</a></span>
                            <?php endif; ?>
                        </div>
                        <h1 class="page-title--text">
                            <?= $model->name ?>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="text-section--main">
                <div class="text-box--wrap md-size">
                    <div class="content content--md">
                        <div class="text-box--layout article-info">
                            <aside class="text-box--aside">
                                <figure class="person">
                                    <img src="<?= $author->getAvatar() ?>" alt="<?= $author->fio ?>">
                                    <figcaption>
                                        <p><?= $author->fio ?>,</p>
                                        <p><?= $author->position ?></p>
                                        <p><?= $author->phone ?></p>
                                    </figcaption>
                                </figure>
                            </aside>
                            <?php if ($model->image): ?>
                                <div class="text-box--main">
                                    <div class="article-preview">
                                        <figure class="img">
                                            <?= Html::img($model->image, ['alt' => $model->name]) ?>
                                        </figure>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="text-box--layout">
                            <?= Share::widget() ?>
                            <div class="text-box--main">
                                <div class="text-box">
                                    <?= ContentHelper::parseLink(Config::getValue('insert_galleries') ? ContentHelper::parseBlock(ContentHelper::parseGallery($model->text)) : $model->text) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= Comments::widget(['model' => $model]) ?>
    <?= Related::widget(['model' => $model]) ?>
    <div class="page">
        <div class="content content--lg">
            <?= Forms::widget(['viewName' => 'consultation']) ?>
        </div>
    </div>
    <?= Forms::widget(['viewName' => 'subscribe']) ?>
</div>
