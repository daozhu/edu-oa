<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language'  =>'zh-CN',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=backend',
            'username' => 'hrjt',
            'password' => '*(dAo_2008',
            'charset' => 'utf8',
            'tablePrefix' => 'hrjt_'
        ],
    ],
];
