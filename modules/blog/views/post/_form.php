<?php

use common\helpers\ImageHelper;
use common\models\User;
use kartik\file\FileInput;
use kartik\select2\Select2;
use modules\blog\models\BlogCategory;
use modules\blog\models\Post;
use vova07\imperavi\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $tags array */
/* @var $this yii\web\View */
/* @var $model modules\blog\models\Post */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="post-form">

    <?php $form = ActiveForm::begin([
        'method' => 'post', 'options' => ['enctype' => 'multipart/form-data']
    ]); ?>
    <div class="row">
        <div class="col-xs-3 admin-image">
            <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                'options'       => ['accept' => 'image/*'],
                'value'         => $model->image,
                'pluginOptions' => ImageHelper::getOptionsSingle($model, $model->image, 'image', true)
            ]); ?>
        </div>
        <div class="col-xs-3 admin-image">
            <?= $form->field($model, 'image_preview')->widget(FileInput::classname(), [
                'options'       => ['accept' => 'image/*'],
                'value'         => $model->image_preview,
                'pluginOptions' => ImageHelper::getOptionsSingle($model, $model->image_preview, 'image_preview', true)
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'category_id')->dropDownList(BlogCategory::getList()) ?>

            <?= $form->field($model, 'author_id')->dropDownList(User::getAuthors(), ['prompt' => '']) ?>

            <?= $form->field($model, 'status')->dropDownList(Post::STATUS_LIST) ?>

            <?= $form->field($model, 'sort')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'tags')->widget(Select2::classname(), [
                'data'          => $tags,
                'value'         => ArrayHelper::map($model->tags, 'name', 'name'),
                'language'      => 'ru',
                'options'       => ['placeholder' => 'Теги', 'multiple' => true],
                'pluginOptions' => [
                    'allowClear'         => true,
                    'tags'               => true,
                    'tokenSeparators'    => [';'],
                    'maximumInputLength' => 255
                ],
            ]); ?>
            <?= $form->field($model, 'intro')->textarea(['maxlength' => true, 'rows' => 4]) ?>

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

            <?= $form->field($model, 'to_main')->checkbox() ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success button-save']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="buttons-panel">
        <?= Html::a('Отмена', Url::to('/blog/post'), ['class' => 'btn btn-danger']) ?>
        <?php if (!$model->isNewRecord): ?>
            <?= Html::a('На сайте', Url::to(Yii::$app->params['front'] . '/blog/' . $model->slug), ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
        <?php endif; ?>
    </div>
</div>
