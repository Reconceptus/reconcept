<?php

use common\helpers\ImageHelper;
use common\models\User;
use kartik\file\FileInput;
use kartik\select2\Select2;
use modules\portfolio\models\Portfolio;
use modules\utils\models\UtilsGallery;
use vova07\imperavi\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\portfolio\models\Portfolio */
/* @var $form yii\widgets\ActiveForm */
/* @var $tags array */
/* @var $hiddenTags array */
/* @var $galleries UtilsGallery[] */
?>

<div class="portfolio-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-3 admin-image">
            <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                'options'       => ['accept' => 'image/*'],
                'value'         => $model->image,
                'pluginOptions' => ImageHelper::getOptionsSingle($model, $model->image)
            ]); ?>
        </div>
        <div class="col-xs-3 admin-image">
            <?= $form->field($model, 'horizontal_preview')->widget(FileInput::classname(), [
                'options'       => ['accept' => 'image/*'],
                'value'         => $model->horizontal_preview,
                'pluginOptions' => ImageHelper::getOptionsSingle($model, $model->horizontal_preview,
                    'horizontal_preview')
            ]); ?>
        </div>
        <div class="col-xs-3 admin-image">
            <?= $form->field($model, 'vertical_preview')->widget(FileInput::classname(), [
                'options'       => ['accept' => 'image/*'],
                'value'         => $model->vertical_preview,
                'pluginOptions' => ImageHelper::getOptionsSingle($model, $model->vertical_preview, 'vertical_preview')
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-9">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'tags')->widget(Select2::classname(), [
                'data'          => $tags,
                'value'         => ArrayHelper::map($model->tags, 'name', 'name'),
                'language'      => 'ru',
                'options'       => ['placeholder' => 'Теги', 'multiple' => true],
                'pluginOptions' => [
                    'allowClear'         => true,
                    'tags'               => true,
                    'tokenSeparators'    => [',', ' '],
                    'maximumInputLength' => 255
                ],
            ]); ?>

            <?= $form->field($model, 'hiddenTags')->widget(Select2::classname(), [
                'data'          => $hiddenTags,
                'value'         => ArrayHelper::map($model->hiddenTags, 'name', 'name'),
                'language'      => 'ru',
                'options'       => ['placeholder' => 'Скрытые теги', 'multiple' => true],
                'pluginOptions' => [
                    'allowClear'         => true,
                    'tags'               => true,
                    'tokenSeparators'    => [',', ' '],
                    'maximumInputLength' => 255
                ],
            ]); ?>

            <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'author_id')->dropDownList(User::getAuthors()) ?>

            <?= $form->field($model, 'content')->textarea()->widget(Widget::className(), [
                'settings' => [
                    'lang'                     => 'ru',
                    'minHeight'                => 200,
                    'imageUpload'              => Url::to(['/file/editor-upload']),
                    'imageUploadPath'          => Url::to(['/file/upload-gallery']),
                    'imageUploadErrorCallback' => new JsExpression('function(json){ alert(json.error); }'),
                    'buttons'                  => [
                        'html', 'formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist', 'outdent',
                        'indent', 'image', 'file', 'link', 'alignment', 'horizontalrule'
                    ],
                    'plugins'                  => [
                        'counter', 'definedlinks', 'filemanager', 'fontcolor', 'fontfamily', 'fontsize', 'fullscreen',
                        'limiter', 'table', 'textdirection', 'textexpander', 'imagemanager', 'video', 'imageGallery'
                    ],
                ]
            ]) ?>
            <?php if($galleries):?>
                <h3>На данный момент тут используются галереи:</h3>
                <?php foreach ($galleries as $gallery): ?>
                    <p><?= \common\models\Html::a($gallery->name, ['/utils/gallery/update', 'id' => $gallery->id]) ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <?= $form->field($model, 'status')->dropDownList(Portfolio::STATUS_LIST) ?>

            <?= $form->field($model, 'to_main')->checkbox() ?>

            <?= $form->field($model, 'to_footer')->checkbox() ?>

            <?= $form->field($model, 'sort')->textInput(['type' => 'number', 'step' => 1]) ?>

            <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'seo_description')->textInput(['maxlength' => true]) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success button-save']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <div class="buttons-panel">
        <?= Html::a('Отмена', Url::to('/portfolio'), ['class' => 'btn btn-danger']) ?>
        <?php if (!$model->isNewRecord): ?>
            <?= Html::a('Отзыв', Url::to(['/portfolio/default/view', 'id' => $model->id]),
                ['class' => 'btn btn-warning', 'target' => '_blank']) ?>
            <?= Html::a('На сайте', Url::to(Yii::$app->params['front'] . '/portfolio/' . $model->slug),
                ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
        <?php endif; ?>
    </div>
</div>
