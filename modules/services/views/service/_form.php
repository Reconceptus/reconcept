<?php

use common\helpers\ImageHelper;
use kartik\file\FileInput;
use modules\services\models\Service;
use modules\services\models\ServiceCategory;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\services\models\Service */
/* @var $form yii\widgets\ActiveForm */
$this->title = $model->isNewRecord ? 'Добавить услугу' : 'Изменить услугу: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Услуги', 'url' => ['index']];
if (!$model->isNewRecord) {
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['update', 'id' => $model->id]];
}
$this->params['breadcrumbs'][] = $model->isNewRecord ? 'Добавить' : 'Изменить';

?>

<div class="service-form">

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
        <div class="col-xs-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'category_id')->dropDownList(ServiceCategory::getList()) ?>

            <?= $form->field($model, 'text')->textarea()->widget(Widget::className(), [
                'settings' => [
                    'lang'                     => 'ru',
                    'minHeight'                => 200,
                    'imageUpload'              => Url::to(['/file/editor-upload']),
                    'imageUploadErrorCallback' => new JsExpression('function(json){ alert(json.error); }'),
                    'buttons'                  => [
                        'html', 'formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist', 'outdent',
                        'indent', 'image', 'file', 'link', 'alignment', 'horizontalrule'],
                    'plugins'                  => [
                        'counter', 'definedlinks', 'filemanager', 'fontcolor', 'fontfamily', 'fontsize', 'fullscreen',
                        'limiter', 'table', 'textdirection', 'textexpander', 'imagemanager', 'video'
                    ],
                ]]) ?>

            <?= $form->field($model, 'status')->dropDownList(Service::STATUS_LIST) ?>

            <?= $form->field($model, 'to_footer')->checkbox() ?>

            <?= $form->field($model, 'to_menu')->checkbox() ?>

            <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'seo_description')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
