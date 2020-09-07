<?php

use modules\position\models\PositionRequest;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\position\models\PositionRequest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="position-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'query')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'depth')->dropDownList(PositionRequest::DEPTH_LIST) ?>

    <?= $form->field($model, 'status')->dropDownList(PositionRequest::STATUS_LIST) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
