<?php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components'    => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            //'class' => 'yii\redis\Cache',
            'keyPrefix' => 'CONSOLE:CACHE:',
        ],
        'session' => [
            'name' => 'CONSOLE',
            'class' => 'yii\redis\Session',
            'keyPrefix' =>  'CONSOLE:SESSION:',
        ],
    ],
];
