<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\blog\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-8">

            <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'status')->dropDownList(\modules\blog\models\Comment::getStatusList()) ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success button-save']) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <div class="buttons-panel">
        <?php if (!$model->isNewRecord): ?>
            <?= Html::a('Удалить окончательно', Url::to(['remove', 'id' => $model->id]), [
                "data-method"  => "post",
                "data-confirm" => "Уверены что хотите ОКОНЧАТЕЛЬНО удалить комментарий?",
                "class"        => "btn btn-danger"
            ]) ?>
            <?= Html::a('Удалить', Url::to(['delete', 'id' => $model->id]), [
                "data-method"  => "post",
                "data-confirm" => "Уверены что хотите удалить комментарий?",
                "class"        => "btn btn-warning"
            ]) ?>
        <?php endif; ?>
    </div>
</div>
