<?php

use common\helpers\ImageHelper;
use common\models\Image;
use kartik\color\ColorInput;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\utils\models\UtilsGallery */
/* @var $form yii\widgets\ActiveForm */
$guid = Yii::$app->security->generateRandomString(10);
?>

    <div class="utils-gallery-form">
        <?php $form = ActiveForm::begin(['method' => 'post', 'options' => ['enctype' => 'multipart/form-data']]); ?>
        <?= Html::hiddenInput('guid', $guid) ?>
        <div class="row">
            <div class="col-xs-12">
                <?= FileInput::widget([
                    'name'          => 'images[]',
                    'options'       => [
                        'accept'   => 'image/*',
                        'multiple' => true,
                        'id'       => 'imagesLoader'
                    ],
                    'pluginOptions' => [
                        'deleteUrl'       => Url::to(['/file/delete-image']),
                        'deleteExtraData' => [
                            'type' => Image::TYPE_IMAGE
                        ],

                        'initialPreviewAsData' => true,
                        'overwriteInitial'     => false,
                        'initialPreview'       => ImageHelper::getImageLinks($model, 'images', true),
                        'initialPreviewConfig' => ImageHelper::getImagesLinksData($model, 'images'),

                        'uploadUrl'       => Url::to(['/file/upload']),
                        'uploadExtraData' => [
                            'class' => $model::className(),
                            'field' => 'images',
                            'guid'  => $guid,
                            'type'  => Image::TYPE_IMAGE
                        ],

                        'browseOnZoneClick' => true,
                        'dropZoneEnabled'   => true,
                        'language'          => 'en',
                        'showPreview'       => true,
                        'showCaption'       => true,
                        'showRemove'        => true,
                        'showUpload'        => true,
                        'showDrag'          => true,
                        'showBrowse'        => true,
                        'browseLabel'       => 'Выбрать фото',
                        'layoutTemplates'   => [
                            'close'      => '',
                            'footer'     => '<div class="file-thumbnail-footer">
                                <div class="file-caption-name">
                                    <input type="text" class="kv-input kv-new form-control input-sm form-control-sm text-center" name="header" value="{caption}" placeholder="Название" />
                                </div>
                                {progress} {actions}
                            </div>',
                        ],
                    ],
                    'pluginEvents'  => [
                        'filesorted'        => new JsExpression('function(event,params){
                $.post("' . Url::to(['/file/sort-image', 'id' => $model->id]) . '",{sort:params});
                }'),
                        'filesuccessremove' => new JsExpression('function(event, id){
                $.post("' . Url::to(['/file/delete-image', 'guid' => $guid, 'type' => Image::TYPE_IMAGE]) . '",{id:id});
                }'),
                        'filebatchselected' => new JsExpression('function(event, files) {
                $(imagesLoader).fileinput("upload");
                }')
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'disabled'=>true]) ?>

                <?= $form->field($model, 'layout_id')->dropDownList(\modules\utils\models\UtilsLayout::getList()) ?>

                <?= $form->field($model, 'background')->widget(ColorInput::classname(), [
                    'options' => ['placeholder' => 'Выберите цвет фона', 'readonly' => true],
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
$script = <<<JS
    
    $(document).on('change', 'input[name="header"]', function() {
        let element = $(this);
        let id = element.closest('.file-thumbnail-footer').find('.kv-file-remove').attr('data-key');
        if(!id){
            alert('Редактирование подписи к рисунку возможно только после сохранения');
            element.val('');
            return false;
        }
        let value = element.val();
        
        $.post('/file/set-alt', {
            id: id, 
            value: value,
        });
    });
    

JS;
$this->registerJs($script);
?>