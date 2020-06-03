<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 18.02.2019
 * Time: 13:43
 */

use modules\config\models\Config;
use modules\utils\helpers\ContentHelper;
use yii\helpers\Url;

/* @var $model \modules\services\models\Service */
$this->title = $model->seo_title ?? $model->name;
$this->registerMetaTag(['name' => 'description', 'content' => $model->seo_description]);
$this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->request->getHostInfo() . '/' . Yii::$app->request->getPathInfo()]);
?>
<div id="main" class="main">

    <div class="text-section">
        <div class="content content--lg">
            <div class="text-section--main">
                <div class="content">
                    <div class="page-title">
                        <h1 class="page-title--text"><?= $model->name ?></h1>
                    </div>
                </div>
            </div>
            <div class="text-section--main">
                <div class="text-box--wrap md-size">
                    <div class="content content--md">
                        <div class="text-box--layout">
                            <?= \frontend\widgets\share\Share::widget() ?>
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
    <?= \frontend\widgets\forms\Forms::widget(['viewName' => 'consultation']) ?>
</div>
