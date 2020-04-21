<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model modules\utils\models\UtilsLayout */

$this->title = 'Добавление шаблона';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="utils-layout-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
