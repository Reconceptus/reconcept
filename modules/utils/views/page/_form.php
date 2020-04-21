<?php

use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\utils\models\Page */
/* @var $form yii\widgets\ActiveForm */

$this->title = $model->isNewRecord ? 'Добавить страницу' : 'Изменить страницу: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
if (!$model->isNewRecord) {
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
}
$this->params['breadcrumbs'][] = $model->isNewRecord ? 'Добавить' : 'Изменить';

?>

<div class="page-form">
    <h1><?= $this->title ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
