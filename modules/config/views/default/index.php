<?php

use common\helpers\FileHelper;
use common\helpers\Html;
use common\helpers\ImageHelper;
use kartik\file\FileInput;
use modules\config\models\Config;
use modules\utils\helpers\GalleryHelper;
use modules\utils\models\Gallery;
use vova07\imperavi\Widget;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $parentConfig Config */
/* @var $models Config[] */

$this->title = $parentConfig->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['method' => 'post', 'options' => ['enctype' => 'multipart/form-data']]) ?>
    <?php foreach ($models as $model): ?>
        <?php if ($model->type === Config::TYPE_INPUT): ?>
            <div class="row">
                <div class="col-xs-7">
                    <?= $form->field($model, 'value')->textInput(['name' => $model->slug])->label($model->name) ?>
                </div>
            </div>
        <?php elseif ($model->type === Config::TYPE_INTEGER): ?>
            <div class="row">
                <div class="col-xs-7">
                    <?= $form->field($model, 'value')->textInput(['name' => $model->slug, 'type' => 'number'])->label($model->name) ?>
                </div>
            </div>
        <?php elseif ($model->type === Config::TYPE_NUMBER): ?>
            <div class="row">
                <div class="col-xs-7">
                    <?= $form->field($model, 'value')->textInput(['name' => $model->slug, 'type' => 'number', 'step' => '0.01'])->label($model->name) ?>
                </div>
            </div>
        <?php elseif ($model->type === Config::TYPE_CHECKBOX): ?>
            <div class="row">
                <div class="col-xs-7">
                    <div class="form-group">
                        <?= Html::Checkbox($model->slug, $model->value > 0, ['id' => '#checkbox'.$model->slug, 'class' => 'checkbox role-checkbox']) ?>
                        <?= Html::Label($model->name, '#checkbox'.$model->slug, ['class' => 'checkbox-label']); ?>
                    </div>
                </div>
            </div>
        <?php elseif ($model->type === Config::TYPE_SELECT): ?>
            <div class="row">
                <div class="col-xs-7">
                    <?= $form->field($model, 'value')->dropDownList($model->getVariants(), ['name' => $model->slug])->label($model->name) ?>
                </div>
            </div>
        <?php elseif ($model->type === Config::TYPE_TEXTAREA): ?>
            <div class="row">
                <div class="col-xs-7">
                    <p style="margin-bottom: 5px;font-weight: bold;"><?= $model->name ?></p>
                    <?= Widget::widget([
                        'name'     => $model->slug,
                        'value'    => $model->value,
                        'settings' => [
                            'formatting'               => ['p', 'blockquote', 'h3'],
                            'placeholder'              => 'Paste your text here',
                            'toolbarFixed'             => false,
                            'lang'                     => 'ru',
                            'linebreaks'               => true,
                            'pastePlainText'           => true,
                            'minHeight'                => 200,
                            'maxlength'                => '10',
                            'imageUpload'              => Url::to(['/file/editor-upload']),
                            'imageUploadErrorCallback' => new JsExpression('function (response) { alert("Upload error"); }'),
                            'buttons'                  => [
                                'html', 'formatting', 'unorderedlist', 'orderedlist', 'image', 'link'
                            ],
                            'plugins'                  => [
                                'imagemanager', 'video'
                            ],
                        ]
                    ]) ?>
                </div>
            </div>
        <?php elseif ($model->type === Config::TYPE_IMAGE): ?>
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-xs-3">
                    <p style="margin-bottom: 5px;font-weight: bold;"><?= $model->name ?></p>
                    <?= FileInput::widget([
                        'name'          => $model->slug,
                        'options'       => ['accept' => 'image/*'],
                        'value'         => $model->value,
                        'pluginOptions' => ImageHelper::getOptionsSingle($model, $model->value, 'value')
                    ]); ?>
                </div>
            </div>
        <?php elseif ($model->type === Config::TYPE_FILE): ?>
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-xs-3">
                    <p style="margin-bottom: 5px;font-weight: bold;"><?= $model->name ?></p>
                    <?= FileInput::widget([
                        'name'          => $model->slug,
                        'options'       => ['accept' => '*'],
                        'value'         => $model->value,
                        'pluginOptions' => FileHelper::getOptionsConfig($model, $model->value)
                    ]); ?>
                </div>
            </div>
        <?php elseif ($model->type === Config::TYPE_PURE_TEXTAREA): ?>
            <div class="row">
                <div class="col-xs-7">
                    <?= $form->field($model, 'value')->textarea(['name' => $model->slug])->label($model->name) ?>
                </div>
            </div>
        <?php
        elseif ($model->type === Config::TYPE_TEXTAREA_PARAGRAPH):
            $galleriesGuids = GalleryHelper::findBlocks($model->value);
            $guids = array_map(static function ($model) {
                return trim($model);
            }, $galleriesGuids);
            $galleries = Gallery::find()->distinct()->where(['in', 'guid', $guids])->all();
            ?>
            <div class="row">
                <div class="col-xs-7">
                    <p style="margin-bottom: 5px;font-weight: bold;"><?= $model->name ?></p>
                    <?php if ($galleries): ?>
                        <p>На данный момент тут используются галереи:</p>
                        <?php foreach ($galleries as $gallery): ?>
                            <p><?= \yii\helpers\Html::a($gallery->name, ['/utils/gallery/update', 'id' => $gallery->id], ['target' => '_blank']) ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?= Widget::widget([
                        'name'     => $model->slug,
                        'value'    => $model->value,
                        'settings' => [
                            'toolbarFixed'             => false,
                            'formatting'               => ['p', 'blockquote', 'h3'],
                            'maxlength'                => '10',
                            'lang'                     => 'en',
                            'minHeight'                => 200,
                            'imageUpload'              => Url::to(['/file/editor-upload']),
                            'imageUploadPath'          => Url::to(['/file/upload-gallery']),
                            'imageUploadErrorCallback' => new JsExpression('function(json){ alert(json.error); }'),
                            'buttons'                  => [
                                'html', 'formatting', 'unorderedlist', 'orderedlist', 'image', 'link'
                            ],
                            'plugins'                  => [
                                'imagemanager', 'video', 'imageGallery'
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
            <?php unset($galleries, $galleriesGuids, $guids) ?>
        <?php endif; ?>
    <?php endforeach; ?>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?php ActiveForm::end() ?>
</div>
