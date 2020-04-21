<?php

use common\helpers\ImageHelper;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\utils\models\UtilsGallery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="utils-gallery-form">
    <?php $form = ActiveForm::begin(['method' => 'post', 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'layout_id')->dropDownList(\modules\utils\models\UtilsLayout::getList()) ?>

    <?= FileInput::widget([
        'name'          => 'images[]',
        'options'       => [
            'accept'   => 'image/*',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'deleteUrl'       => Url::to(['/file/delete-image']),
            'deleteExtraData' => [

            ],

            'initialPreviewAsData' => true,
            'overwriteInitial'     => false,
            'initialPreview'       => ImageHelper::getImageLinks($model),
            'initialPreviewConfig' => ImageHelper::getImagesLinksData($model),

            'uploadUrl'       => Url::to(['/file/upload-image']),
            'uploadExtraData' => [
                'Image[class]'   => $model->formName(),
                'Image[item_id]' => $model->id,
            ],

            'browseOnZoneClick' => true,
            'dropZoneEnabled'   => true,
            'language'          => 'ru',
            'showPreview'       => true,
            'showCaption'       => false,
            'showRemove'        => false,
            'showUpload'        => false,
            'showDrag'          => true,
            'showBrowse'        => false,
            'browseLabel'       => 'Выбрать фото',
            'layoutTemplates'   => [
                'actionZoom' => '',
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
            'filesorted' => new \yii\web\JsExpression('function(event,params){
                        $.post("' . Url::to(["/file/sort-image", "id" => $model->id]) . '",{sort:params});
                        }')
        ]
    ]); ?>

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