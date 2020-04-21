<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.03.2019
 * Time: 14:00
 */

use common\helpers\ImageHelper;
use frontend\modules\mainpage\models\Pages;
use kartik\file\FileInput;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $model Pages */
?>
<div class="mainpage-pages-form">
    <?php $form = ActiveForm::begin(['method' => 'post', 'options' => ['enctype' => 'multipart/form-data']]); ?>
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
                'Image[class]' => 'Logos',
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

    <?= $form->field($model, 'text')->textarea()->widget(Widget::className(), [
        'settings' => [
            'lang'                     => 'ru',
            'minHeight'                => 200,
            'imageUpload'              => Url::to(['/blog/post/image-upload']),
            'imageUploadErrorCallback' => new JsExpression('function (response) { alert("При загрузке произошла ошибка"); }'),
            'buttons'                  => [
                'html', 'formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist', 'outdent',
                'indent', 'image', 'file', 'link', 'alignment', 'horizontalrule'],
            'plugins'                  => [
                'counter', 'definedlinks', 'filemanager', 'fontcolor', 'fontfamily', 'fontsize', 'fullscreen',
                'limiter', 'table', 'textdirection', 'textexpander', 'imagemanager', 'video'
            ],
        ]]) ?>


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