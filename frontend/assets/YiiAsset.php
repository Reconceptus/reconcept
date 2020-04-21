<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 12.03.2019
 * Time: 16:28
 */

namespace frontend\assets;


class YiiAsset extends \yii\web\YiiAsset
{
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $publishOptions = [
        'forceCopy' => true
    ];
}