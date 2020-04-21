<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model modules\utils\models\UtilsBlock */

$this->title = 'Изменение блока: ' . $model->name;

?>
<div class="utils-block-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
