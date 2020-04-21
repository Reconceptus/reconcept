<?php

namespace modules\services;

/**
 * services module definition class
 */
class Services extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'modules\services\controllers';
    public $defaultRoute = 'service';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
