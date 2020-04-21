<?php

use common\helpers\ImageHelper;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\mainpage\models\MainPageTop */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="main-page-top-form">

    <?php $form = ActiveForm::begin([
        'method' => 'post', 'options' => ['enctype' => 'multipart/form-data']
    ]); ?>
    <div class="row">
        <div class="col-xs-3 admin-image">
            <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                'options'       => ['accept' => 'image/*'],
                'value'         => $model->image,
                'pluginOptions' => ImageHelper::getOptionsSingle($model, $model->image)
            ]); ?>
        </div>
        <div class="col-xs-3 admin-image">
            <?= $form->field($model, 'image_preview')->widget(FileInput::classname(), [
                'options'       => ['accept' => 'image/*'],
                'value'         => $model->image_preview,
                'pluginOptions' => ImageHelper::getOptionsSingle($model, $model->image_preview, 'image_preview')
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-8">
            <?= $form->field($model, 'quote')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'sign')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
