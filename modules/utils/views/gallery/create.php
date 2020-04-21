<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model modules\utils\models\UtilsGallery */

$this->title = 'Добавление галереи';
$this->params['breadcrumbs'][] = ['label' => 'Галереи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="utils-gallery-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
