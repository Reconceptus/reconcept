<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'app-backend',
    'name'                => 'Reconcept',
    'sourceLanguage'      => 'ru',
    'language'            => 'ru',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap'           => ['log'],
    'defaultRoute'        => 'blog/post',
    'modules'             => [
        'blog'  => [
            'class' => 'modules\blog\Blog',
        ],
        'users' => [
            'class' => 'modules\users\Users',
        ],
        'config' => [
            'class' => 'modules\config\Config',
        ],
        'shop' => [
            'class' => 'modules\shop\Shop',
        ],
        'portfolio' => [
            'class' => 'modules\portfolio\Portfolio',
        ],
        'utils' => [
            'class' => 'modules\utils\Utils',
        ],
        'services' => [
            'class' => 'modules\services\Services',
        ],
        'mainpage' => [
            'class' => 'frontend\modules\mainpage\Mainpage',
        ],
        'feedback' => [
            'class' => 'modules\feedback\Module',
        ],
    ],
    'components'          => [
        'view'         => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@backend/views'
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-blue',
                ],
            ],
        ],
        'authManager'  => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request'      => [
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => $params['cookieValidationKey'],
        ],
        'user'         => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity', 'httpOnly' => true],
        ],
        'session'      => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced',
            'cookieParams' =>[
                'httpOnly' => true,
                'domain' => $params['cookieDomain'],
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
            ],
        ],
    ],
    'params'              => $params,
];
