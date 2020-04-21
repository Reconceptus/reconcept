<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model modules\utils\models\UtilsLayout */

$this->title = 'Изменить шаблоны: ' . $model->id;

?>
<div class="utils-layout-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
