<?php
namespace modules\utils\assets;

use yii\web\AssetBundle;

class GalleryAsset extends AssetBundle
{
    public $sourcePath = '@modules/utils/assets';
    public $css = [
        'plugins/imageGallery/index.css',
    ];
    public $js = [
        'plugins/imageGallery/index.js',
    ];
}