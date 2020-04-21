<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model modules\utils\models\UtilsBlock */

$this->title = 'Добавление блока';
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="utils-block-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
