<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\utils\models\Subscriber */
/* @var $form yii\widgets\ActiveForm */
$this->title = $model->isNewRecord ? 'Добавить подписчика' : 'Изменить подписчика: ' . $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
if (!$model->isNewRecord) {
    $this->params['breadcrumbs'][] = ['label' => $model->email, 'url' => ['view', 'id' => $model->id]];
}
$this->params['breadcrumbs'][] = $model->isNewRecord ? 'Добавить' : 'Изменить';
?>

<div class="subscriber-form">
    <h1><?= $this->title ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(\modules\utils\models\Subscriber::STATUS_LIST) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
