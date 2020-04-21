<?php

namespace modules\blog;

/**
 * blog module definition class
 */
class Blog extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'modules\blog\controllers';
    public $defaultRoute = 'post';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
