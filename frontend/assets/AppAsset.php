<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//fonts.googleapis.com/css?family=Montserrat:100,300,400,500,600,700|Yeseva+One&amp;subset=cyrillic-ext',
        'css/owl.carousel.min.css',
        'css/fotorama.css',
        'css/styles.min.css',
        'css/site.css',
    ];
    public $js = [
        'js/jquery.validate.min.js',
        'js/jquery.cookie.js',
        'js/fotorama.js',
        'js/main.min.js',
        'js/script.js',
        'js/owl.carousel.min.js',
    ];
    public $depends = [
        'frontend\assets\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
