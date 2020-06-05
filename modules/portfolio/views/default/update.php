<?php

use modules\utils\models\UtilsGallery;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $tags array */
/* @var $hiddenTags array */
/* @var $model modules\portfolio\models\Portfolio */
/* @var $galleries UtilsGallery[] */

$this->title = $model->isNewRecord ? 'Добавить портфолио' : 'Изменить портфолио: ' . $model->name;
?>
<div class="portfolio-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'      => $model,
        'tags'       => $tags,
        'hiddenTags' => $hiddenTags,
        'galleries'  => $galleries
    ]) ?>

</div>
