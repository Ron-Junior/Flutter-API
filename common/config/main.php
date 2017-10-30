<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name' => 'Project name',
    'language' => 'pt-BR',
    'charset' => 'UTF-8',
    'timeZone' => 'America/Recife',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
