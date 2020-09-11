<?php

use common\helpers\ImageHelper;
use kartik\file\FileInput;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\YiiAsset;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \modules\portfolio\models\PortfolioReview */

$this->title = 'Отзыв для портфолио ' . $model->portfolio->name;

YiiAsset::register($this);
?>
<div class="portfolio-view">
    <h1><?= $this->title ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-3 admin-image">
            <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                'options'       => ['accept' => 'image/*'],
                'value'         => $model->image,
                'pluginOptions' => ImageHelper::getOptionsSingle($model, $model->image)
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-9">
            <?= $form->field($model, 'portfolio_id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'text')->textarea()->widget(Widget::className(), [
                'settings' => [
                    'lang'                     => 'ru',
                    'minHeight'                => 200,
                    'imageUpload'              => Url::to(['/file/editor-upload']),
                    'imageUploadErrorCallback' => new JsExpression('function(json){ alert(json.error); }'),
                    'buttons'                  => [
                        'html', 'formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist', 'outdent',
                        'indent', 'link', 'alignment', 'horizontalrule'],
                    'plugins'                  => [
                        'counter', 'definedlinks', 'filemanager', 'fontcolor', 'fontfamily', 'fontsize', 'fullscreen',
                        'limiter', 'table', 'textdirection', 'textexpander', 'imagemanager', 'specialchars'
                    ],
                ]]) ?>

            <?= $form->field($model, 'to_main')->checkbox() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
