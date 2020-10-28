<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'app-frontend',
    'name'                => 'ReConcept',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'sourceLanguage'      => 'ru',
    'language'            => 'ru',
    'controllerNamespace' => 'frontend\controllers',
    'components'          => [
        'mobileDetect' => [
            'class' => '\skeeks\yii2\mobiledetect\MobileDetect'
        ],
        'request'      => [
            'cookieValidationKey' => $params['cookieValidationKey'],
            'csrfParam'           => '_csrf-site',
        ],
        'authManager'  => [
            'class' => 'yii\rbac\DbManager',
        ],
        'user'         => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity', 'httpOnly' => true],
        ],
        'session'      => [
            // this is the name of the session cookie used for login on the frontend
            'name'         => 'advanced',
            'cookieParams' => [
                'httpOnly' => true,
                'domain'   => $params['cookieDomain'],
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                'sitemap'                                                  => 'sitemap/index',
                'contacts'                                                 => 'site/contacts',
                'login'                                                    => 'site/login',
                'signup'                                                   => 'site/signup',
                'site/auth'                                                => 'site/auth',
                'site/error'                                               => 'site/error',
                'site/logout'                                              => 'site/logout',
                'site/reset-password'                                      => 'site/reset-password',
                'site/request-password-reset'                              => 'site/request-password-reset',
                'shop'                                                     => 'shop/index',
                'shop/cart'                                                => 'shop/cart',
                'shop/<category:[a-zA-Z0-9\_\-]+>'                         => 'shop/index',
                'shop/<category:[a-zA-Z0-9\_\-]+>/<slug:[a-zA-Z0-9\_\-]+>' => 'shop/view',
                'blog'                                                     => 'blog',
                'blog/add-favorite'                                        => 'blog/add-favorite',
                'blog/hash/<hash:[a-zA-Zа-яА-Я0-9\_\-]+>'                  => 'blog/hash',
                'blog/add-new-js-ajax-comment'                             => 'blog/add-comment',
                'blog/search'                                              => 'blog/search',
                'blog/index'                                               => 'blog/index',
                'blog/category/<slug:[a-zA-Z0-9\_\-]+>'                    => 'blog/index',
                'blog/favorites'                                           => 'blog/favorites',
                'blog/<slug:[a-zA-Z0-9\_\-]+>'                             => 'blog/view',
                'site/<slug:[a-zA-Z0-9\_\-]+>'                             => 'site/page',
                'services/<slug:[a-zA-Z0-9\_\-]+>'                         => 'services/view',
                'portfolio/index'                                          => 'portfolio/index',
                'portfolio'                                                => 'portfolio/index',
                'portfolio/<slug:[a-zA-Z0-9\_\-]+>'                        => 'portfolio/view',
                '<slug:[a-zA-Z0-9\_\-]+>'                                  => 'portfolio/view',
//                '<controller:[a-zA-Z0-9\_\-]+>/<action:[a-zA-Z0-9\_\-]+>' => '<controller>/<action>',
//                '<controller:[a-zA-Z0-9\_\-]+>'                           => '<controller>',
            ],
        ],
    ],
    'params'              => $params,
];
