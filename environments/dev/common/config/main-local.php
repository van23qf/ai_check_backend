<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],
        'cache' => [
            //'class' => 'yii\caching\FileCache',
            'class' => 'yii\redis\Cache',
            'keyPrefix' => $_COOKIE['pap_domain'].':CACHE:',
        ],
        'session' => [
            'name' => $_SERVER['HTTP_HOST'],
            'class' => 'yii\redis\Session',
            'keyPrefix' =>  $_COOKIE['pap_domain'].':SESSION:',
        ],
    ],
];
