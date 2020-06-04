<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name' => '智能审核管理系统',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
//    'bootstrap' =>  ['log', 'preload'],
    'modules' => [
        'rbac' => [
            'class' => 'dektrium\rbac\Module',
            'enableFlashMessages' => false,
            'admins' => ['admin'],
        ],
        'external' => [
            'class' => 'backend\modules\external\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'class' => 'backend\components\User',
            'identityClass' => 'common\models\Administrator',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => false],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => 'backend\components\RUrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/rbac/views' => '@app/views/rbac',
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => require(__DIR__ . '/' . (YII_ENV === 'prod' ? 'assets-prod.php' : 'assets-dev.php'))
        ],
        'preload'=>[
            'class'=>'common\components\AutomaticServerPush',
        ],
    ],
    'params' => $params,
];
