<?php

namespace frontend\modules\mainpage;

/**
 * mainpage module definition class
 */
class Mainpage extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\mainpage\controllers';
    public $defaultRoute = 'top';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
