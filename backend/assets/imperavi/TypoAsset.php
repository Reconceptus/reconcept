<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 04.02.2019
 * Time: 17:17
 */

namespace backend\assets\imperavi;


use yii\web\AssetBundle;

class TypoAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/assets/imperavi';

    /**
     * @inheritdoc
     */
    public $js = [
        'typo.js',
    ];
}