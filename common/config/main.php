<?php
return [
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log', 'queue', 'session'],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'formatter' => [
            'class' => 'common\components\Formatter',
            'nullDisplay' => '--',
            'defaultTimeZone' => 'Asia/Shanghai',
            'dateFormat' => 'yy/M/d',
            'datetimeFormat' => 'yy/M/d HH:mm',
            // 'numberFormatterOptions'=>[
            //     NumberFormatter::MIN_FRACTION_DIGITS => 2,
            //     NumberFormatter::MAX_FRACTION_DIGITS => 3,
            // ],
        ],
        'config' => [
            'class'         => 'abhimanyu\config\components\Config', // Class (Required)
            'db'            => 'db',                                 // Database Connection ID (Optional)
            'tableName'     => '{{%config}}',                        // Table Name (Optioanl)
            'cacheId'       => 'cache',                              // Cache Id. Defaults to NULL (Optional)
            'cacheKey'      => 'config.cache',                       // Key identifying the cache value (Required only if cacheId is set)
            'cacheDuration' => 10                                   // Cache Expiration time in seconds. 0 means never expire. Defaults to 0 (Optional)
        ],
//        'redis' => [
//            'class' => 'yii\redis\Connection',
//            'hostname' => '127.0.0.1',
//            'port' => 6379,
//            'database' => 13,
//        ],
        'session' => [
            'class' => 'yii\web\Session',
            'timeout' => 24 * 3600,
        ],
        'queue' => [
            'class' => 'yii\queue\db\Queue',
            'as log' => 'yii\queue\LogBehavior',
            // Other driver options
            'db' => 'db', // DB connection component or its config
            'tableName' => '{{%queue}}', // Table name
            'channel' => 'default', // Queue channel key
            'mutex' => 'yii\mutex\MysqlMutex', // Mutex used to sync queries
        ],
        'log' => [
            'traceLevel' => 3,
            'targets' => [
                'app' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logVars' => ['_GET', '_POST', '_SESSION'],
                    'maxLogFiles' => 30,
                    'except' => [
                        'api',
                        'debug',
                        'yii\base\UserException',
                        'yii\db\*',
                        'yii\web\HttpException:401',
                    ],
                ],
                'api' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace'],
                    'maxLogFiles' => 30,
                    'categories' => ['api', 'yii\base\UserException'],
                    'logVars' => ['_GET', '_POST', '_SESSION'],
                    'logFile' => '@runtime/logs/api.log',
                ],
                'debug' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace'],
                    'maxLogFiles' => 30,
                    'categories' => ['debug'],
                    'logVars' => ['_GET', '_POST', '_SESSION'],
                    'logFile' => '@runtime/logs/debug.log',
                ],
                'payment' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace'],
                    'maxLogFiles' => 30,
                    'categories' => ['payment'],
                    'logVars' => ['_GET', '_POST', '_SESSION'],
                    'logFile' => '@runtime/logs/payment.log',
                ],
                // 'email'=>[
                //     'class' => 'yii\log\EmailTarget',
                //     'mailer' => 'mailer',
                //     'levels' => ['error'],
                //     'except'=> [
                //         'yii\base\UserException',
                //         'yii\web\HttpException:404',
                //         'yii\web\HttpException:400',
                //         'yii\web\HttpException:403',
                //         'common\base\*',
                //         'yii\web\HttpException:401',
                //     ],
                //     'logVars'=>['_GET', '_POST', '_SESSION'],
                //     'message' => [
                //         'from' => ['luzirq@qq.com'],
                //         'to' => ['luzirq@qq.com'],
                //         'subject' => '【PAP】错误日志邮件通知',
                //     ],
                // ],
                'db' => [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET', '_POST', '_SESSION', '_COOKIE'],
                    'except' => [
                        'yii\base\UserException',
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:400',
                        'yii\web\HttpException:403',
                        'common\base\*',
                        'yii\web\HttpException:401',
                    ],
                ],
                'xfsd' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'maxLogFiles' => 30,
                    'categories' => ['xfsd'],
                    'logVars' => ['_GET', '_POST', '_SESSION'],
                    'logFile' => '@runtime/logs/xfsd.log',
                ],
            ],
        ],
    ],
];
