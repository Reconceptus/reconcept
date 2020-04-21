<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'app-console',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases'             => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap'       => [
        'fixture' => [
            'class'     => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
        'migrate' => [
            'class'         => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                Yii::getAlias('@console') . '/migrations',
                Yii::getAlias('@modules') . '/users/migrations',
                Yii::getAlias('@modules') . '/config/migrations',
                Yii::getAlias('@modules') . '/blog/migrations',
                Yii::getAlias('@modules') . '/shop/migrations',
                Yii::getAlias('@modules') . '/portfolio/migrations',
                Yii::getAlias('@modules') . '/utils/migrations',
                Yii::getAlias('@modules') . '/services/migrations',
                Yii::getAlias('@modules') . '/feedback/migrations',
                Yii::getAlias('@frontend') . '/migrations',
            ],
        ],
    ],
    'components'          => [
        'log'         => [
            'targets' => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'params'              => $params,
];
