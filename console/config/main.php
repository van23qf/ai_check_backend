<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            // 'migrationPath' => null,
            'migrationNamespaces' => [
                // 'console\migrations',
                //'yii\queue\db\migrations',
            ],
        ],
    ],
    'modules' => [

    ],
    'components' => [],
    'params' => $params,
];
